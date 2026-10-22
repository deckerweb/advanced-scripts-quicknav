<?php
/**
 * Integrate the shared deckerweb updater with plugin-scoped safeguards.
 *
 * @package AdvancedScriptsQuickNav
 */

namespace Deckerweb\AdvancedScriptsQuickNav;

defined( 'ABSPATH' ) || exit;

/** Configure shared update artwork and isolate plugin-specific safeguards. */
final class GitHubUpdates {
	/** Public release repository; never taken from user input. */
	private const REPOSITORY = 'https://github.com/deckerweb/advanced-scripts-quicknav';

	/** Register after WordPress initializes translations. @return void */
	public function register(): void {
		add_action( 'init', array( $this, 'boot' ) );
	}

	/** Load the shared versioned class once, including beside other deckerweb plugins. @return void */
	public function boot(): void {
		// Always select this host's pinned implementation, regardless of legacy load order.
		$updater_class = require dirname( ASQN_PLUGIN_FILE ) . '/includes/deckerweb-github-release-updater-v2.php';
		if ( ! is_string( $updater_class ) || ! class_exists( $updater_class, false ) || ! defined( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' ) || ! constant( $updater_class . '::SUPPORTS_HOST_TRANSLATIONS' ) ) {
			add_action( 'admin_notices', array( $this, 'compatibility_notice' ) );
			return;
		}
		try {
			$updater = new $updater_class(
				ASQN_PLUGIN_FILE,
				self::REPOSITORY,
				'Advanced Scripts QuickNav',
				__( 'Reach your scripts through favorites, status lists and folders in the WordPress toolbar.', 'advanced-scripts-quicknav' ),
				$this->artwork(),
				array( 'translate' => require dirname( ASQN_PLUGIN_FILE ) . '/includes/updater-translations.php' )
			);
		} catch ( \InvalidArgumentException $error ) {
			// An unsupported install directory must not break navigation.
			return;
		}
		$updater->register();
		require_once dirname( ASQN_PLUGIN_FILE ) . '/includes/class-asqn-release-notes.php';
		add_filter( 'plugins_api', array( $this, 'format_information' ), 30, 3 );
		add_filter( 'http_request_args', array( $this, 'request_limits' ), 20, 2 );
		add_filter( 'upgrader_source_selection', array( $this, 'validate_source' ), 30, 4 );
	}
	/**
	 * Explain the paused update integration without exposing internal errors.
	 *
	 * @return void No return value.
	 */
	public function compatibility_notice(): void {
		if ( ! current_user_can( 'update_plugins' ) ) { return; }
		echo '<div class="notice notice-warning"><p>' . esc_html__( 'QuickNav updates are paused because another plugin loaded an older deckerweb Updater. Update that plugin to restore QuickNav updates.', 'advanced-scripts-quicknav' ) . '</p></div>';
	}
	/**
	 * Format only this plugin’s release notes in native plugin details.
	 *
	 * @param mixed $response Existing plugins API response.
	 * @param string $action Requested plugins API operation.
	 * @param mixed $args Request arguments.
	 * @return mixed The existing response, with this plugin’s changelog formatted when applicable.
	 */
	public function format_information( $response, string $action, $args ) {
		if ( 'plugin_information' !== $action || ! is_object( $args ) || ( $args->slug ?? '' ) !== 'advanced-scripts-quicknav' || ! is_object( $response ) || ( $response->homepage ?? '' ) !== self::REPOSITORY || ! is_string( $response->sections['changelog'] ?? null ) ) {
			return $response;
		}
		$heading = is_string( $response->version ?? null ) ? '<h3>' . esc_html( $response->version ) . '</h3>' : '';
		$response->sections['changelog'] = $heading . ReleaseNotes::render( $response->sections['changelog'], determine_locale() );
		return $response;
	}

	/**
	 * Provide bundled artwork matching the current administrator's language.
	 *
	 * @return array<string,array<string,string>> WordPress icon and banner maps.
	 */
	public function artwork(): array {
		$language = 1 === preg_match( '/^de(?:_|$)/i', determine_locale() ) ? 'de-' : '';
		return array(
			'icons'   => array(
				'svg' => plugins_url( 'assets-github/icon.svg', ASQN_PLUGIN_FILE ),
				'1x'  => plugins_url( 'assets-github/icon-128x128.png', ASQN_PLUGIN_FILE ),
				'2x'  => plugins_url( 'assets-github/icon-256x256.png', ASQN_PLUGIN_FILE ),
			),
			'banners' => array(
				'low'  => plugins_url( 'assets-github/banner-' . $language . '772x250.png', ASQN_PLUGIN_FILE ),
				'high' => plugins_url( 'assets-github/banner-' . $language . '1544x500.png', ASQN_PLUGIN_FILE ),
			),
		);
	}

	/**
	 * Bound only this repository's metadata requests; do not change package downloads.
	 *
	 * @param array  $args WordPress HTTP arguments.
	 * @param string $url Requested URL.
	 * @return array
	 */
	public function request_limits( array $args, string $url ): array {
		if ( 'https://api.github.com/repos/deckerweb/advanced-scripts-quicknav/releases/latest' === $url ) {
			$args['limit_response_size'] = 512 * 1024;
			$args['timeout']             = 6;
			$args['redirection']         = 0;
			$args['sslverify']           = true;
			$args['reject_unsafe_urls']  = true;
		}
		return $args;
	}

	/**
	 * Validate the actual candidate before WordPress removes the installed plugin.
	 * Requirements in installed headers cannot describe a future release reliably.
	 *
	 * @param mixed $source Normalized directory or WP_Error from the shared updater.
	 * @param mixed $remote_source Unused extraction root.
	 * @param mixed $upgrader Unused WordPress upgrader instance.
	 * @param array $hook_extra Upgrade context.
	 * @return mixed Original source or localized WP_Error.
	 */
	public function validate_source( $source, $remote_source, $upgrader, array $hook_extra ) {
		if ( ( $hook_extra['plugin'] ?? '' ) !== plugin_basename( ASQN_PLUGIN_FILE ) || ( isset( $hook_extra['type'] ) && 'plugin' !== $hook_extra['type'] ) || ( isset( $hook_extra['action'] ) && 'update' !== $hook_extra['action'] ) || ( ! isset( $hook_extra['type'], $hook_extra['action'] ) && ! ( $upgrader instanceof \Plugin_Upgrader && true === $upgrader->bulk ) ) ) {
			return $source;
		}
		if ( is_wp_error( $source ) ) {
			$messages = array(
				'ddw_ghru_filesystem' => __( 'The update filesystem is unavailable. Please try again.', 'advanced-scripts-quicknav' ),
				'ddw_ghru_archive'    => __( 'The GitHub package does not contain the Advanced Scripts QuickNav plugin file.', 'advanced-scripts-quicknav' ),
				'ddw_ghru_rename'     => __( 'The GitHub package could not be prepared. The installed version has been kept.', 'advanced-scripts-quicknav' ),
			);
			$code     = $source->get_error_code();
			return isset( $messages[ $code ] ) ? new \WP_Error( $code, $messages[ $code ], $source->get_error_data( $code ) ) : $source;
		}
		global $wp_filesystem;
		if ( ! is_string( $source ) || ! $wp_filesystem ) {
			return new \WP_Error( 'asqn_update_source', __( 'The update package could not be checked.', 'advanced-scripts-quicknav' ) );
		}
		$main = trailingslashit( $source ) . basename( ASQN_PLUGIN_FILE );
		if ( ! $wp_filesystem->is_file( $main ) || $wp_filesystem->size( $main ) > 1024 * 1024 ) {
			return new \WP_Error( 'asqn_update_source', __( 'The update package could not be checked.', 'advanced-scripts-quicknav' ) );
		}
		$text = $wp_filesystem->get_contents( $main );
		if ( ! is_string( $text ) ) {
			return new \WP_Error( 'asqn_update_source', __( 'The update package could not be checked.', 'advanced-scripts-quicknav' ) );
		}
		$headers = array();
		foreach ( array( 'Plugin Name', 'Version', 'Update URI', 'Requires PHP', 'Requires at least' ) as $header ) {
			$headers[ $header ] = preg_match( '/^[ \t\/*#@]*' . preg_quote( $header, '/' ) . ':(.*)$/mi', str_replace( "\r", "\n", substr( $text, 0, 8192 ) ), $match ) ? trim( $match[1] ) : '';
		}
		if ( 'Advanced Scripts QuickNav' !== $headers['Plugin Name'] || self::REPOSITORY !== $headers['Update URI'] || ! preg_match( '/^\d+\.\d+\.\d+$/D', $headers['Version'] ) || version_compare( $headers['Version'], \ASQN_Config::VERSION, '<=' ) ) {
			return new \WP_Error( 'asqn_update_identity', __( 'The package identity or version does not match a newer Advanced Scripts QuickNav release.', 'advanced-scripts-quicknav' ) );
		}
		$current  = get_site_transient( 'update_plugins' );
		$expected = is_object( $current ) ? ( $current->response[ plugin_basename( ASQN_PLUGIN_FILE ) ]->new_version ?? '' ) : '';
		if ( ! is_string( $expected ) || '' === $expected || $headers['Version'] !== $expected ) {
			return new \WP_Error( 'asqn_update_version', __( 'The package version differs from the offered update. Please check for updates again.', 'advanced-scripts-quicknav' ) );
		}
		foreach ( array( 'Requires PHP', 'Requires at least' ) as $header ) {
			if ( ! preg_match( '/^\d+\.\d+(?:\.\d+)?$/D', $headers[ $header ] ) ) {
				return new \WP_Error( 'asqn_update_requirements', __( 'The update package has missing or invalid WordPress/PHP requirements.', 'advanced-scripts-quicknav' ) );
			}
		}
		if ( ! is_php_version_compatible( $headers['Requires PHP'] ) || ! is_wp_version_compatible( $headers['Requires at least'] ) ) {
			return new \WP_Error( 'asqn_update_compatibility', __( 'This release requires a newer WordPress or PHP version. The installed version has been kept.', 'advanced-scripts-quicknav' ) );
		}
		return $source;
	}
}
