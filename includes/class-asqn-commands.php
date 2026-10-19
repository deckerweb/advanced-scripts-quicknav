<?php
/** Native WordPress Command Palette navigation for QuickNav and Advanced Scripts. */
defined( 'ABSPATH' ) || exit;

/** Register permission-scoped navigation commands without loading a second palette. */
final class ASQN_Commands {
	/** Attach late enough to detect the native palette's enqueue state. @return void */
	public function register(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ], 100 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'assets' ], 100 );
	}

	/** Load only alongside an existing native palette on website admin screens. @return void */
	public function assets(): void {
		if ( ! ASQN_Config::allowed() || is_network_admin() || ! wp_script_is( 'wp-commands', 'enqueued' ) || wp_script_is( 'asqn-commands', 'enqueued' ) ) { return; }
		wp_enqueue_script( 'asqn-commands', plugins_url( 'assets/commands.js', ASQN_PLUGIN_FILE ), [ 'wp-commands', 'wp-data', 'wp-dom-ready' ], ASQN_Config::VERSION . '.' . filemtime( dirname( ASQN_PLUGIN_FILE ) . '/assets/commands.js' ), true );
		wp_localize_script( 'asqn-commands', 'ASQNCommands', [ 'items' => $this->items() ] );
	}

	/**
	 * Build bounded navigation metadata for the current user and website.
	 * @return array Command definitions containing labels and local admin URLs only.
	 */
	public function items(): array {
		if ( ! ASQN_Config::allowed() ) { return []; }
		$items = [ [ 'name' => 'asqn/settings', 'label' => __( 'QuickNav: Open settings', 'advanced-scripts-quicknav' ), 'url' => admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ] ];
		if ( ! ASQN_Advanced_Scripts_Adapter::available() ) { return $items; }
		$items[] = [ 'name' => 'asqn/advanced-scripts', 'label' => __( 'Advanced Scripts: Open script manager', 'advanced-scripts-quicknav' ), 'url' => ASQN_Advanced_Scripts_Adapter::url() ];
		$items[] = [ 'name' => 'asqn/new-script', 'label' => __( 'Advanced Scripts: Add new script', 'advanced-scripts-quicknav' ), 'url' => ASQN_Advanced_Scripts_Adapter::url( 0, 0 ) ];
		$items[] = [ 'name' => 'asqn/search', 'label' => __( 'Advanced Scripts: Search all scripts', 'advanced-scripts-quicknav' ), 'url' => ASQN_Advanced_Scripts_Adapter::url() . '#asqn-search-query', 'focusSearch' => true ];
		$saved = get_user_meta( get_current_user_id(), ASQN_Config::key( 'favorites' ), true );
		if ( ! is_array( $saved ) || ! $saved ) { return $items; }
		$scripts = ( new ASQN_Advanced_Scripts_Adapter() )->scripts();
		foreach ( array_slice( ASQN_Favorites::sanitize( $saved, $scripts ), 0, ASQN_Config::limit() ) as $id ) {
			$item = $scripts[$id];
			$items[] = [ 'name' => 'asqn/favorite-' . $id,
				/* translators: %s: personal favorite script title. */
				'label' => sprintf( __( 'Advanced Scripts: Open favorite — %s', 'advanced-scripts-quicknav' ), $item['title'] ?: __( 'Untitled script', 'advanced-scripts-quicknav' ) ),
				'url' => ASQN_Advanced_Scripts_Adapter::url( $item['parent'], $id ) ];
		}
		return $items;
	}
}
