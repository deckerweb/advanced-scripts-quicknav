<?php
/** Hook registration; adapters are resolved only after active plugins have loaded. */
defined( 'ABSPATH' ) || exit;
final class DDW_Advanced_Scripts_QuickNav {
	private ?ASQN_Admin_Bar_Renderer $renderer = null;
	public function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'menu' ], 999 );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
		add_filter( 'debug_information', [ $this, 'diagnostics' ], 9 );
		( new ASQN_Settings() )->register();
		if ( ! defined( 'ASQN_SNIPPET_MODE' ) ) {
			add_action( 'init', [ $this, 'updates' ] );
			add_action( 'init', [ $this, 'translations' ], 0 );
			add_filter( 'plugin_action_links_' . plugin_basename( ASQN_PLUGIN_FILE ), [ $this, 'links' ] );
			add_filter( 'plugin_row_meta', [ $this, 'meta' ], 10, 2 );
		}
	}
	private function renderer(): ASQN_Admin_Bar_Renderer {
		if ( null === $this->renderer ) { $this->renderer = new ASQN_Admin_Bar_Renderer( new ASQN_Navigation_Builder( ( new ASQN_Advanced_Scripts_Adapter() )->scripts() ) ); }
		return $this->renderer;
	}
	public function translations(): void { load_plugin_textdomain( 'advanced-scripts-quicknav', false, dirname( plugin_basename( ASQN_PLUGIN_FILE ) ) . '/languages' ); }
	public function menu( $bar ): void { $this->renderer()->add_admin_bar_menu( $bar ); }
	public function assets(): void {
		if ( ! ASQN_Config::visible() ) { return; }
		$this->renderer()->enqueue_admin_bar_styles();
		if ( defined( 'ASQN_SNIPPET_MODE' ) ) { wp_add_inline_style( 'admin-bar', ASQN_SNIPPET_CSS ); }
		else { wp_enqueue_style( 'asqn-toolbar', plugins_url( 'assets/toolbar.css', ASQN_PLUGIN_FILE ), [ 'admin-bar' ], ASQN_Config::VERSION ); }
	}
	public function diagnostics( array $info ): array { return $this->renderer()->site_health_debug_info( $info ); }
	public function updates(): void {
		require_once dirname( ASQN_PLUGIN_FILE ) . '/includes/class-asqn-github-updates.php';
		( new \Deckerweb\AdvancedScriptsQuickNav\GitHubUpdates() )->boot();
	}
	public function links( array $links ): array {
		if ( ASQN_Config::allowed() ) { array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ) . '">' . esc_html__( 'Preferences', 'advanced-scripts-quicknav' ) . '</a>' ); }
		return $links;
	}
	public function meta( array $links, string $file ): array {
		if ( $file !== plugin_basename( ASQN_PLUGIN_FILE ) ) { return $links; }
		$links[] = '<a href="' . esc_url( ASQN_Settings::document_url() ) . '">' . esc_html__( 'Documentation', 'advanced-scripts-quicknav' ) . '</a>';
		$links[] = '<a href="https://ko-fi.com/deckerweb" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support the project', 'advanced-scripts-quicknav' ) . '</a>';
		$links[] = '<a href="https://eepurl.com/gbAUUn" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Newsletter', 'advanced-scripts-quicknav' ) . '</a>';
		return apply_filters( 'ddw/admin_extras/pluginrow_meta', $links );
	}
}
