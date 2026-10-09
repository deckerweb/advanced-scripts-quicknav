<?php
/** Hook registration; adapters are resolved only after active plugins have loaded. */
defined( 'ABSPATH' ) || exit;
/** Register the host lifecycle and lazily resolve navigation metadata. */
final class DDW_Advanced_Scripts_QuickNav {
	private ?ASQN_Admin_Bar_Renderer $renderer = null;

	/**
	 * Initialize the component and its request-local dependencies.
	 *
	 * @return void No return value.
	 */
	public function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'menu' ], 999 );
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
		add_filter( 'debug_information', [ $this, 'diagnostics' ], 9 );
		( new ASQN_Settings() )->register();
		$this->allow_paused_activation();
		add_action( 'activate_plugin', [ $this, 'allow_paused_activation' ], -50, 0 );
		add_action( 'plugins_loaded', [ $this, 'allow_paused_activation' ], PHP_INT_MAX );
		add_action( 'init', [ $this, 'allow_paused_activation' ], PHP_INT_MAX );
		if ( ! defined( 'ASQN_SNIPPET_MODE' ) ) {
			add_action( 'init', [ $this, 'updates' ] );
			add_action( 'init', [ $this, 'translations' ], 0 );
			add_filter( 'plugin_action_links_' . plugin_basename( ASQN_PLUGIN_FILE ), [ $this, 'links' ] );
			add_filter( 'plugin_row_meta', [ $this, 'meta' ], 10, 2 );
		}
	}


	/**
	 * Keep native host activation available while its navigation dependency is absent.
	 *
	 * WordPress still validates this host's PHP and WordPress requirements. The
	 * shared Library guard remains unchanged for every other plugin.
	 *
	 * @return void Adapts only registered Library activation guards for this host.
	 */
	public function allow_paused_activation(): void {
		if ( defined( 'ASQN_SNIPPET_MODE' ) ) { return; }
		global $wp_filter;
		if ( empty( $wp_filter['activate_plugin'] ) ) { return; }
		foreach ( $wp_filter['activate_plugin']->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $callback ) {
				$handler = $callback['function'];
				if ( ! is_array( $handler ) || ! is_object( $handler[0] ) || 'activation_guard' !== $handler[1] || 0 !== strpos( get_class( $handler[0] ), 'Deckerweb\\PluginLibrary\\' ) ) { continue; }
				remove_action( 'activate_plugin', $handler, $priority );
				add_action( 'activate_plugin',
					/**
					 * Preserve the shared guard for all targets except this pausable host.
					 * @param string $file Plugin basename being activated.
					 * @param bool $network Whether this activation is network-wide.
					 * @return void No return value.
					 */
					static function( string $file, bool $network ) use ( $handler ): void {
						if ( plugin_basename( ASQN_PLUGIN_FILE ) !== $file ) { call_user_func( $handler, $file, $network ); }
					}, $priority, 2
				);
			}
		}
	}

	/**
	 * Lazily construct request-local metadata navigation.
	 *
	 * @return ASQN_Admin_Bar_Renderer Request-local toolbar renderer.
	 */
	private function renderer(): ASQN_Admin_Bar_Renderer {
		if ( null === $this->renderer ) { $this->renderer = new ASQN_Admin_Bar_Renderer( new ASQN_Navigation_Builder( ( new ASQN_Advanced_Scripts_Adapter() )->scripts() ) ); }
		return $this->renderer;
	}

	/**
	 * Load the existing host textdomain.
	 *
	 * @return void No return value.
	 */
	public function translations(): void { load_plugin_textdomain( 'advanced-scripts-quicknav', false, dirname( plugin_basename( ASQN_PLUGIN_FILE ) ) . '/languages' ); }

	/**
	 * Register or populate the appropriate administrative navigation.
	 *
	 * @param WP_Admin_Bar $bar WordPress admin-bar object to populate.
	 * @return void No return value.
	 */
	public function menu( $bar ): void { $this->renderer()->add_admin_bar_menu( $bar ); }

	/**
	 * Enqueue local assets only in the relevant administrative context.
	 *
	 * @return void No return value.
	 */
	public function assets(): void {
		if ( ! ASQN_Config::visible() ) { return; }
		$this->renderer()->enqueue_admin_bar_styles();
		if ( defined( 'ASQN_SNIPPET_MODE' ) ) { wp_add_inline_style( 'admin-bar', ASQN_SNIPPET_CSS ); }
		else { wp_enqueue_style( 'asqn-toolbar', plugins_url( 'assets/toolbar.css', ASQN_PLUGIN_FILE ), [ 'admin-bar' ], ASQN_Config::VERSION ); }
	}

	/**
	 * Extend Site Health with non-sensitive configuration information.
	 *
	 * @param array $info Existing Site Health data.
	 * @return array Existing Site Health data plus QuickNav diagnostics.
	 */
	public function diagnostics( array $info ): array { return $this->renderer()->site_health_debug_info( $info ); }

	/**
	 * Load and register the host’s pinned GitHub update adapter.
	 *
	 * @return void No return value.
	 */
	public function updates(): void {
		require_once dirname( ASQN_PLUGIN_FILE ) . '/includes/class-asqn-github-updates.php';
		( new \Deckerweb\AdvancedScriptsQuickNav\GitHubUpdates() )->boot();
	}

	/**
	 * Prepend the permitted settings action link.
	 *
	 * @param array $links Existing plugin-row links to preserve.
	 * @return array Existing links with the permitted settings shortcut prepended.
	 */
	public function links( array $links ): array {
		if ( ASQN_Config::allowed() ) { array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=advanced-scripts-quicknav' ) ) . '">' . esc_html__( 'Settings', 'advanced-scripts-quicknav' ) . '</a>' ); }
		return $links;
	}

	/**
	 * Extend the plugin-row information links.
	 *
	 * @param array $links Existing plugin-row links to preserve.
	 * @param string $file Installed plugin basename.
	 * @return array Filtered plugin-row information links.
	 */
	public function meta( array $links, string $file ): array {
		if ( $file !== plugin_basename( ASQN_PLUGIN_FILE ) ) { return $links; }
		$links[] = '<a href="' . esc_url( ASQN_Settings::document_url() ) . '">' . esc_html__( 'Documentation', 'advanced-scripts-quicknav' ) . '</a>';
		$links[] = '<a href="https://ko-fi.com/deckerweb" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support the project', 'advanced-scripts-quicknav' ) . '</a>';
		$links[] = '<a href="https://eepurl.com/gbAUUn" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Newsletter', 'advanced-scripts-quicknav' ) . '</a>';
		/**
		 * Filter plugin-row metadata links. Return the replacement link array.
		 *
		 * @since 1.0.0
		 * @param array $links Existing link definitions.
		 */
		return apply_filters( 'ddw/admin_extras/pluginrow_meta', $links );
	}
}
