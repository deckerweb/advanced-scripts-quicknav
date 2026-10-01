<?php
/** WordPress-native personal settings, with deckerweb header and local changelog. */
defined( 'ABSPATH' ) || exit;
final class ASQN_Settings {
	public function register(): void {
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_action( 'admin_post_asqn_save_preferences', [ $this, 'save' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
	}
	public function menu(): void {
		if ( ASQN_Config::allowed() ) { add_options_page( 'Advanced Scripts QuickNav', 'Advanced Scripts QuickNav', 'manage_options', 'advanced-scripts-quicknav', [ $this, 'page' ] ); }
	}
	public function assets( string $hook ): void {
		if ( 'settings_page_advanced-scripts-quicknav' !== $hook ) { return; }
		if ( ! defined( 'ASQN_SNIPPET_MODE' ) ) {
			wp_enqueue_style( 'asqn-settings', plugins_url( 'assets/settings.css', ASQN_PLUGIN_FILE ), [], ASQN_Config::VERSION );
			wp_enqueue_script( 'asqn-settings', plugins_url( 'assets/settings.js', ASQN_PLUGIN_FILE ), [], ASQN_Config::VERSION, true );
		} else {
			wp_add_inline_style( 'common', ASQN_SNIPPET_CSS );
		}
	}
	public function save(): void {
		if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! ASQN_Config::allowed() ) { wp_die( esc_html__( 'Permission denied.', 'advanced-scripts-quicknav' ), '', [ 'response' => 403 ] ); }
		check_admin_referer( 'asqn_preferences' );
		$scripts = ( new ASQN_Advanced_Scripts_Adapter() )->scripts();
		if ( ! ASQN_Advanced_Scripts_Adapter::available() ) { wp_die( esc_html__( 'Activate Advanced Scripts first.', 'advanced-scripts-quicknav' ) ); }
		$ids = isset( $_POST['favorites'] ) && is_array( $_POST['favorites'] ) ? wp_unslash( $_POST['favorites'] ) : [];
		if ( count( array_unique( array_filter( $ids, 'is_scalar' ), SORT_REGULAR ) ) > 100 ) { wp_die( esc_html__( 'Please select no more than 100 favorites.', 'advanced-scripts-quicknav' ) ); }
		update_user_meta( get_current_user_id(), ASQN_Config::key( 'favorites' ), ASQN_Favorites::sanitize( $ids, $scripts ) );
		$limit = isset( $_POST['limit'] ) && is_scalar( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 40;
		update_user_meta( get_current_user_id(), ASQN_Config::key( 'preferences' ), [ 'counter' => ! empty( $_POST['counter'] ), 'expert' => ! empty( $_POST['expert'] ), 'limit' => max( 10, min( 200, $limit ) ) ] );
		wp_safe_redirect( admin_url( 'options-general.php?page=advanced-scripts-quicknav&saved=1' ) ); exit;
	}
	public static function german(): bool { return 0 === strpos( determine_locale(), 'de' ); }
	public static function document_url(): string {
		return 'https://github.com/deckerweb/advanced-scripts-quicknav/blob/main/docs/wiki/' . ( self::german() ? 'Deutsch.md' : 'English.md' );
	}
	public function page(): void {
		if ( ! ASQN_Config::allowed() ) { return; }
		$nav = new ASQN_Navigation_Builder( ( new ASQN_Advanced_Scripts_Adapter() )->scripts() );
		$scripts = $nav->scripts(); $favorites = ASQN_Favorites::ids( $scripts ); $prefs = ASQN_Config::preferences();
		echo '<div class="wrap asqn-settings"><header class="asqn-page-heading">';
		if ( ! defined( 'ASQN_SNIPPET_MODE' ) ) { echo '<img src="' . esc_url( plugins_url( 'assets-github/icon.svg', ASQN_PLUGIN_FILE ) ) . '" alt="" width="56" height="56">'; }
		echo '<div><h1>Advanced Scripts QuickNav</h1><p>' . esc_html__( 'Your scripts. One click away.', 'advanced-scripts-quicknav' ) . '</p></div></header>';
		if ( isset( $_GET['saved'] ) ) { echo '<div class="notice notice-success inline"><p>' . esc_html__( 'Your preferences have been saved.', 'advanced-scripts-quicknav' ) . '</p></div>'; }
		if ( ! ASQN_Advanced_Scripts_Adapter::available() ) {
			echo '<div class="notice notice-warning inline"><p>' . esc_html__( 'Activate Advanced Scripts first. Your saved preferences are kept.', 'advanced-scripts-quicknav' ) . '</p></div>';
			$this->footer(); echo '</div>'; return;
		}
		echo '<p>' . esc_html__( 'These preferences apply only to your account on this website. Constants in wp-config.php take precedence over display preferences.', 'advanced-scripts-quicknav' ) . '</p>';
		$this->statistics( $nav, count( $favorites ) );
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="asqn_save_preferences">';
		wp_nonce_field( 'asqn_preferences' );
		echo '<section class="asqn-card"><h2>' . esc_html__( 'Display preferences', 'advanced-scripts-quicknav' ) . '</h2>';
		foreach ( [ 'counter' => [ 'ASQN_COUNTER', __( 'Show script count in the toolbar', 'advanced-scripts-quicknav' ), ASQN_Config::counter() ], 'expert' => [ 'ASQN_EXPERT_MODE', __( 'Show developer links', 'advanced-scripts-quicknav' ), ASQN_Config::expert() ] ] as $key => $field ) {
			$locked = defined( $field[0] );
			echo '<label class="asqn-option"><input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( $field[2], true, false ) . ( $locked ? ' disabled' : '' ) . '> ' . esc_html( $field[1] ) . ( $locked ? ' <code>' . esc_html( $field[0] ) . '</code>' : '' ) . '</label>';
			if ( $locked && $prefs[$key] ) { echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="1">'; }
		}
		echo '<p><label for="asqn-limit">' . esc_html__( 'Menu entry limit', 'advanced-scripts-quicknav' ) . '</label> <input id="asqn-limit" type="number" min="10" max="200" name="limit" value="' . esc_attr( ASQN_Config::limit() ) . '"' . ( defined( 'ASQN_MENU_LIMIT' ) ? ' disabled' : '' ) . '></p>';
		if ( defined( 'ASQN_MENU_LIMIT' ) ) { echo '<input type="hidden" name="limit" value="' . esc_attr( $prefs['limit'] ) . '"><code>ASQN_MENU_LIMIT</code>'; }
		echo '<p class="description">' . esc_html__( 'Applies to each status list, favorites, and the folder tree. Additional entries remain accessible in Advanced Scripts.', 'advanced-scripts-quicknav' ) . '</p></section>';
		echo '<section class="asqn-card"><h2>' . esc_html__( 'Personal favorites', 'advanced-scripts-quicknav' ) . '</h2><p>' . esc_html__( 'Select up to 100 scripts. Deleted scripts disappear from the list automatically. The limit above controls how many favorites appear in the toolbar.', 'advanced-scripts-quicknav' ) . '</p>';
		$this->favorites_preview( $nav, $favorites );
		echo '<h3>' . esc_html__( 'Choose favorites', 'advanced-scripts-quicknav' ) . '</h3>';
		echo '<label class="asqn-filter-label" for="asqn-favorite-filter">' . esc_html__( 'Filter by title or folder', 'advanced-scripts-quicknav' ) . '</label> <input type="search" id="asqn-favorite-filter" autocomplete="off"><p id="asqn-filter-status" role="status" aria-live="polite"></p><div class="asqn-favorite-list">';
		$count = 0;
		foreach ( $scripts as $id => $item ) {
			if ( 'folder' === $item['type'] ) { continue; } $count++;
			$title = $item['title'] ?: __( 'Untitled script', 'advanced-scripts-quicknav' );
			echo '<label class="asqn-favorite" data-asqn-search="' . esc_attr( $title . ' ' . $nav->path( $item ) ) . '" data-asqn-id="' . esc_attr( $id ) . '"><input type="checkbox" name="favorites[]" value="' . esc_attr( $id ) . '" ' . checked( in_array( $id, $favorites, true ), true, false ) . '><span><strong>' . esc_html( $title ) . '</strong><small>' . esc_html( $nav->description( $item ) ) . '</small></span></label>';
		}
		if ( ! $count ) { echo '<p>' . esc_html__( 'No scripts yet. Create your first script in Advanced Scripts.', 'advanced-scripts-quicknav' ) . '</p>'; }
		echo '</div></section>'; submit_button( __( 'Save my preferences', 'advanced-scripts-quicknav' ) ); echo '</form>';
		$this->footer();
		if ( defined( 'ASQN_SNIPPET_MODE' ) ) { echo '<script>' . ASQN_SNIPPET_JS . '</script>'; }
		echo '</div>';
	}
	private function statistics( ASQN_Navigation_Builder $nav, int $favorites ): void {
		$stats = $nav->statistics();
		$metrics = [ 'total' => __( 'All snippets', 'advanced-scripts-quicknav' ), 'active' => __( 'Active', 'advanced-scripts-quicknav' ), 'inactive' => __( 'Inactive', 'advanced-scripts-quicknav' ), 'folders' => __( 'Folders', 'advanced-scripts-quicknav' ), 'blocked' => __( 'Active, blocked by folder', 'advanced-scripts-quicknav' ), 'favorites' => __( 'Saved favorites', 'advanced-scripts-quicknav' ) ];
		$stats['favorites'] = $favorites;
		echo '<section class="asqn-card asqn-overview" aria-labelledby="asqn-overview-title"><h2 id="asqn-overview-title">' . esc_html__( 'Your snippet overview', 'advanced-scripts-quicknav' ) . '</h2><dl class="asqn-stats">';
		foreach ( $metrics as $key => $label ) {
			echo '<div class="asqn-stat asqn-stat-' . esc_attr( $key ) . '"><dt>' . esc_html( $label ) . '</dt><dd>' . esc_html( $stats[$key] ) . '</dd></div>';
		}
		echo '</dl><div class="asqn-status-bar" aria-hidden="true"><span style="width:' . esc_attr( $stats['total'] ? round( 100 * $stats['active'] / $stats['total'], 2 ) : 0 ) . '%"></span></div><p class="description">' . esc_html__( 'Active and inactive refer to saved snippet status. Folder blocks are counted within active snippets; conditions and execution location can also affect execution.', 'advanced-scripts-quicknav' ) . '</p>';
		if ( $stats['types'] ) {
			echo '<div class="asqn-type-summary" aria-label="' . esc_attr__( 'Snippet types', 'advanced-scripts-quicknav' ) . '">';
			foreach ( $stats['types'] as $type => $count ) { echo '<span>' . esc_html( $type ) . ' <strong>' . esc_html( $count ) . '</strong></span>'; }
			echo '</div>';
		}
		echo '</section>';
	}
	private function favorites_preview( ASQN_Navigation_Builder $nav, array $favorites ): void {
		$scripts = $nav->scripts();
		echo '<div class="asqn-selected-heading"><h3>' . esc_html__( 'Your favorites', 'advanced-scripts-quicknav' ) . '</h3><span id="asqn-selected-count" aria-live="polite">' . esc_html( count( $favorites ) ) . '</span></div><p class="description">' . esc_html__( 'Open a favorite directly from its card. This preview follows your selection; save to update the toolbar.', 'advanced-scripts-quicknav' ) . '</p><div class="asqn-selected-grid" id="asqn-selected-grid">';
		// Server-render every safe link once. JavaScript only changes card visibility.
		foreach ( $scripts as $id => $item ) {
			if ( 'folder' === $item['type'] ) { continue; }
			$title = $item['title'] ?: __( 'Untitled script', 'advanced-scripts-quicknav' );
			echo '<a class="asqn-selected-card" data-asqn-favorite-card="' . esc_attr( $id ) . '" href="' . esc_url( ASQN_Advanced_Scripts_Adapter::url( $item['parent'], $id ) ) . '"' . ( in_array( $id, $favorites, true ) ? '' : ' hidden' ) . '><span class="asqn-selected-icon" aria-hidden="true">★</span><span><strong>' . esc_html( $title ) . '</strong><small>' . esc_html( $nav->description( $item ) ) . '</small></span><span aria-hidden="true">↗</span></a>';
		}
		echo '</div><p id="asqn-selected-empty" class="asqn-empty"' . ( $favorites ? ' hidden' : '' ) . '>' . esc_html__( 'No favorites selected yet. Choose snippets below to build your quick-access list.', 'advanced-scripts-quicknav' ) . '</p>';
	}
	private function footer(): void {
		$changelog = defined( 'ASQN_SNIPPET_MODE' ) ? '' : plugins_url( self::german() ? 'docs/changelog-de.txt' : 'docs/changelog.txt', ASQN_PLUGIN_FILE );
		echo '<footer class="asqn-footer" aria-label="' . esc_attr__( 'Plugin information', 'advanced-scripts-quicknav' ) . '"><div><strong>Advanced Scripts QuickNav</strong> <span>' . esc_html__( 'Version', 'advanced-scripts-quicknav' ) . ' ' . esc_html( ASQN_Config::VERSION ) . '</span> · <a href="' . esc_url( $changelog ?: self::document_url() ) . '" data-asqn-changelog>' . esc_html__( 'Changelog', 'advanced-scripts-quicknav' ) . '</a> · <a href="' . esc_url( self::document_url() ) . '">' . esc_html__( 'Documentation', 'advanced-scripts-quicknav' ) . '</a><p>' . esc_html__( 'Your scripts. One click away.', 'advanced-scripts-quicknav' ) . '</p></div><div><span>© 2022–2026 <a href="https://github.com/deckerweb" target="_blank" rel="noopener noreferrer">David Decker – DECKERWEB</a></span><a href="https://github.com/deckerweb/advanced-scripts-quicknav" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Plugin website', 'advanced-scripts-quicknav' ) . '</a></div></footer>';
		if ( defined( 'ASQN_SNIPPET_MODE' ) ) { $content = self::german() ? ASQN_SNIPPET_CHANGELOG_DE : ASQN_SNIPPET_CHANGELOG_EN; }
		else { $file = dirname( ASQN_PLUGIN_FILE ) . '/docs/' . ( self::german() ? 'changelog-de.txt' : 'changelog.txt' ); $content = is_readable( $file ) ? file_get_contents( $file ) : ''; }
		echo '<dialog id="asqn-changelog" aria-labelledby="asqn-changelog-title"><header><h2 id="asqn-changelog-title">Advanced Scripts QuickNav · ' . esc_html__( 'Changelog', 'advanced-scripts-quicknav' ) . '</h2><button class="button" type="button" data-asqn-close autofocus>' . esc_html__( 'Close', 'advanced-scripts-quicknav' ) . '</button></header><div class="ddw-changelog-content" tabindex="0">' . Deckerweb_Changelog_Renderer_V1::render( $content ) . '</div></dialog>';
	}
}
