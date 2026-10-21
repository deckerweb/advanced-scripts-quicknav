<?php
/** Cross-folder metadata search and personal favorites, without upstream writes. */
defined( 'ABSPATH' ) || exit;

/** Own the search assets and permission-protected metadata endpoint. */
final class ASQN_Search {
	/** Register screen-specific assets and authenticated AJAX only. @return void */
	public function register(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'wp_ajax_asqn_search_scripts', [ $this, 'ajax' ] );
		add_action( 'wp_ajax_asqn_search_favorite', [ $this, 'favorite' ] );
	}

	/**
	 * Load local enhancement assets only on the supported upstream admin screen.
	 * @param string $hook WordPress admin screen hook suffix.
	 * @return void No output or assets on unrelated screens or without permission.
	 */
	public function assets( string $hook ): void {
		if ( 'tools_page_advanced-scripts' !== $hook || ! ASQN_Config::allowed() || ! ASQN_Advanced_Scripts_Adapter::available() || defined( 'ASQN_SNIPPET_MODE' ) ) { return; }
		$base = dirname( ASQN_PLUGIN_FILE );
		wp_enqueue_style( 'asqn-search', plugins_url( 'assets/search.css', ASQN_PLUGIN_FILE ), [], ASQN_Config::VERSION . '.' . filemtime( $base . '/assets/search.css' ) );
		wp_enqueue_script( 'asqn-search', plugins_url( 'assets/search.js', ASQN_PLUGIN_FILE ), [], ASQN_Config::VERSION . '.' . filemtime( $base . '/assets/search.js' ), true );
		wp_localize_script( 'asqn-search', 'ASQNSearch', [
			'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'asqn_search' ),
			'favoriteNonce' => wp_create_nonce( 'asqn_search_favorite' ),
			'typeLabel' => __( 'Script type', 'advanced-scripts-quicknav' ),
			'statusLabel' => __( 'Script status', 'advanced-scripts-quicknav' ),
			'types' => [ 'all' => __( 'All types', 'advanced-scripts-quicknav' ), 'php' => 'PHP', 'css' => 'CSS / SCSS', 'js' => 'JavaScript', 'html' => 'HTML', 'other' => __( 'Other types', 'advanced-scripts-quicknav' ) ],
			'states' => [ 'all' => __( 'All statuses', 'advanced-scripts-quicknav' ), 'active' => __( 'Active', 'advanced-scripts-quicknav' ), 'inactive' => __( 'Inactive', 'advanced-scripts-quicknav' ), 'blocked' => __( 'Active, blocked by folder', 'advanced-scripts-quicknav' ) ],
			'addFavorite' => __( 'Add to favorites', 'advanced-scripts-quicknav' ),
			'removeFavorite' => __( 'Remove from favorites', 'advanced-scripts-quicknav' ),
			'favoriteSaved' => __( 'Favorites updated.', 'advanced-scripts-quicknav' ),
			'favoriteError' => __( 'The favorite could not be saved. Please try again.', 'advanced-scripts-quicknav' ),
			'label' => __( 'Search all scripts', 'advanced-scripts-quicknav' ),
			'help' => __( 'Search titles and folder paths across all folders. Script contents are not searched.', 'advanced-scripts-quicknav' ),
			'clear' => __( 'Clear search', 'advanced-scripts-quicknav' ),
			'loading' => __( 'Searching…', 'advanced-scripts-quicknav' ),
			'empty' => __( 'No matching scripts.', 'advanced-scripts-quicknav' ),
			/* translators: %d: total matching scripts. */
			'count' => __( 'Matches: %d', 'advanced-scripts-quicknav' ),
			'limited' => __( 'Showing the first 50 matches. Refine your search for more precise results.', 'advanced-scripts-quicknav' ),
			'error' => __( 'Search is unavailable. Try again or reload this page. The original script list remains available.', 'advanced-scripts-quicknav' ),
		] );
	}

	/**
	 * Search fresh metadata without exposing snippet contents or modifying state.
	 * @return void Sends JSON and terminates the authenticated AJAX request.
	 */
	public function ajax(): void {
		if ( ! ASQN_Config::allowed() ) { wp_send_json_error( null, 403 ); }
		if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) && is_string( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) ) { wp_send_json_error( null, 405 ); }
		check_ajax_referer( 'asqn_search', 'nonce' );
		if ( ! ASQN_Advanced_Scripts_Adapter::available() ) { wp_send_json_error( null, 409 ); }
		$query = '';
		if ( isset( $_POST['query'] ) && ! is_string( $_POST['query'] ) ) { wp_send_json_error( null, 400 ); }
		$query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		if ( strlen( $query ) > 400 ) { wp_send_json_error( null, 400 ); }
		if ( ( isset( $_POST['type'] ) && ! is_string( $_POST['type'] ) ) || ( isset( $_POST['state'] ) && ! is_string( $_POST['state'] ) ) ) { wp_send_json_error( null, 400 ); }
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'all'; $state = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : 'all';
		if ( ! is_string( $type ) || ! in_array( $type, [ 'all', 'php', 'css', 'js', 'html', 'other' ], true ) || ! is_string( $state ) || ! in_array( $state, [ 'all', 'active', 'inactive', 'blocked' ], true ) ) { wp_send_json_error( null, 400 ); }
		wp_send_json_success( $this->find( $query, ( new ASQN_Advanced_Scripts_Adapter() )->scripts(), $type, $state ) );
	}

	/**
	 * Set a favorite for the authenticated user/site; never accept a target user ID.
	 * @return void Sends JSON after a nonce-protected metadata-only preference write.
	 */
	public function favorite(): void {
		if ( ! ASQN_Config::allowed() ) { wp_send_json_error( null, 403 ); }
		if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) && is_string( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) ) { wp_send_json_error( null, 405 ); }
		check_ajax_referer( 'asqn_search_favorite', 'nonce' );
		if ( ! ASQN_Advanced_Scripts_Adapter::available() ) { wp_send_json_error( null, 409 ); }
		if ( ! isset( $_POST['id'], $_POST['selected'] ) || ! is_string( $_POST['id'] ) || ! is_string( $_POST['selected'] ) || ! preg_match( '/\A[0-9]{1,18}\z/D', wp_unslash( $_POST['id'] ) ) || ! in_array( wp_unslash( $_POST['selected'] ), [ '0', '1' ], true ) ) { wp_send_json_error( null, 400 ); }
		$id = sanitize_text_field( wp_unslash( $_POST['id'] ) ); $selected = sanitize_text_field( wp_unslash( $_POST['selected'] ) );
		if ( ! is_string( $id ) || ! ctype_digit( $id ) || strlen( $id ) > 18 || (int) $id < 1 || ! in_array( $selected, [ '0', '1' ], true ) ) { wp_send_json_error( null, 400 ); }
		$scripts = ( new ASQN_Advanced_Scripts_Adapter() )->scripts(); $id = (int) $id;
		if ( ! isset( $scripts[$id] ) || 'folder' === $scripts[$id]['type'] ) { wp_send_json_error( null, 404 ); }
		$key = ASQN_Config::key( 'favorites' ); $user = get_current_user_id();
		// Use the previous metadata value to detect overlapping changes where supported.
		for ( $attempt = 0; $attempt < 3; $attempt++ ) {
			$old = get_user_meta( $user, $key, true );
			$ids = ASQN_Favorites::sanitize( is_array( $old ) ? $old : [], $scripts );
			$has = in_array( $id, $ids, true );
			if ( $has === ( '1' === $selected ) ) { wp_send_json_success( [ 'id' => $id, 'favorite' => $has ] ); }
			if ( '1' === $selected && count( $ids ) >= 100 ) { wp_send_json_error( [ 'message' => __( 'Please select no more than 100 favorites.', 'advanced-scripts-quicknav' ) ], 409 ); }
			$next = '1' === $selected ? array_merge( $ids, [ $id ] ) : array_values( array_diff( $ids, [ $id ] ) );
			$exists = metadata_exists( 'user', $user, $key );
			$saved = $exists ? update_user_meta( $user, $key, $next, $old ) : add_user_meta( $user, $key, $next, true );
			if ( $saved ) { wp_send_json_success( [ 'id' => $id, 'favorite' => '1' === $selected ] ); }
			wp_cache_delete( $user, 'user_meta' );
		}
		wp_send_json_error( null, 409 );
	}

	/**
	 * Resolve a stable type group without searching source code.
	 * @param string $type Native Advanced Scripts MIME/type identifier.
	 * @return string Group used by the type filter.
	 */
	private static function group( string $type ): string {
		$label = strtoupper( ASQN_Navigation_Builder::type( $type ) );
		if ( false !== strpos( $label, 'PHP' ) ) { return 'php'; }
		if ( false !== strpos( $label, 'CSS' ) || false !== strpos( $label, 'SASS' ) ) { return 'css'; }
		if ( 'JS' === $label || 'JAVASCRIPT' === $label ) { return 'js'; }
		if ( 'HTML' === $label ) { return 'html'; }
		return 'other';
	}

	/**
	 * Match words and filters, then project only the first fifty sorted matches.
	 * @param string $query Case/accent-insensitive whitespace-separated words.
	 * @param array $scripts Indexed metadata from the adapter, excluding source code.
	 * @param string $type Type group, or all.
	 * @param string $state Saved activation state, blocked, or all.
	 * @return array Total count and at most fifty source-free result records.
	 */
	public function find( string $query, array $scripts, string $type = 'all', string $state = 'all' ): array {
		$words = preg_split( '/\s+/u', strtolower( remove_accents( trim( $query ) ) ), -1, PREG_SPLIT_NO_EMPTY );
		if ( ! $words && 'all' === $type && 'all' === $state ) { return [ 'total' => 0, 'items' => [] ]; }
		$nav = new ASQN_Navigation_Builder( $scripts ); $matches = []; $paths = []; $blocks = [];
		foreach ( $scripts as $id => $item ) {
			if ( 'folder' === $item['type'] || ( 'all' !== $type && self::group( $item['type'] ) !== $type ) ) { continue; }
			if ( ( 'active' === $state && ! $item['status'] ) || ( 'inactive' === $state && $item['status'] ) ) { continue; }
			$parent = $item['parent'];
			$blocks[$parent] = $blocks[$parent] ?? $nav->blocked( $item );
			if ( 'blocked' === $state && ( ! $item['status'] || ! $blocks[$parent] ) ) { continue; }
			$paths[$parent] = $paths[$parent] ?? $nav->path( $item );
			$haystack = strtolower( remove_accents( $item['title'] . ' ' . $paths[$parent] ) );
			foreach ( $words ?: [] as $word ) { if ( false === strpos( $haystack, $word ) ) { continue 2; } }
			$matches[] = $id;
		}
		usort( $matches,
			/** Sort matching IDs by display title and stable ID. @param int $a First ID. @param int $b Second ID. @return int Sort order. */
			static function( int $a, int $b ) use ( $scripts ): int { return strcasecmp( $scripts[$a]['title'], $scripts[$b]['title'] ) ?: $a <=> $b; }
		);
		$favorites = array_fill_keys( ASQN_Favorites::ids( $scripts ), true ); $items = [];
		foreach ( array_slice( $matches, 0, 50 ) as $id ) {
			$item = $scripts[$id];
			$items[] = [ 'id' => $id, 'title' => $item['title'] ?: __( 'Untitled script', 'advanced-scripts-quicknav' ), 'path' => $paths[$item['parent']],
				'url' => ASQN_Advanced_Scripts_Adapter::url( $item['parent'], $item['term_id'] ), 'type' => ASQN_Navigation_Builder::type( $item['type'] ), 'favorite' => isset( $favorites[$id] ),
				'status' => ! $item['status'] ? __( 'Inactive', 'advanced-scripts-quicknav' ) : ( $blocks[$item['parent']] ? __( 'Active, blocked by folder', 'advanced-scripts-quicknav' ) : __( 'Active', 'advanced-scripts-quicknav' ) ) ];
		}
		return [ 'total' => count( $matches ), 'items' => $items ];
	}
}
