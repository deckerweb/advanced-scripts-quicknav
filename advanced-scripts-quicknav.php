<?php
/**
 * Plugin Name: Advanced Scripts QuickNav
 * Plugin URI: https://github.com/deckerweb/advanced-scripts-quicknav
 * Description: Reach your Advanced Scripts through personal favorites, status lists and folders in the WordPress toolbar.
 * Version: 1.3.2
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: David Decker – DECKERWEB
 * Author URI: https://github.com/deckerweb
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: advanced-scripts-quicknav
 * Domain Path: /languages/
 * Update URI: https://github.com/deckerweb/advanced-scripts-quicknav
 * GitHub Plugin URI: https://github.com/deckerweb/advanced-scripts-quicknav
 *
 * Copyright © 2025–2026 David Decker – DECKERWEB.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */
defined( 'ABSPATH' ) || exit;
if ( version_compare( PHP_VERSION, '8.1', '<' ) ) {
	add_action( 'admin_notices',
		/**
		 * Explain the unsupported PHP runtime without loading plugin components.
		 * @return void Outputs an escaped administrative notice.
		 */
		static function() { echo '<div class="notice notice-error"><p>' . esc_html__( 'Advanced Scripts QuickNav requires PHP 8.1 or newer.', 'advanced-scripts-quicknav' ) . '</p></div>'; } );
	return;
}
// The generated snippet uses the same class guard; never register the toolbar twice.
if ( class_exists( 'DDW_Advanced_Scripts_QuickNav', false ) ) { return; }
define( 'ASQN_PLUGIN_FILE', __FILE__ );
foreach ( [ 'config', 'adapter', 'favorites', 'navigation', 'renderer', 'settings', 'search', 'commands', 'plugin' ] as $asqn_component ) { require_once __DIR__ . '/includes/class-asqn-' . $asqn_component . '.php'; }
require_once __DIR__ . '/includes/deckerweb-changelog-v1.php';
require_once __DIR__ . '/includes/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register_v2( __FILE__, [], __DIR__ . '/includes/deckerweb-plugin-library' );
new DDW_Advanced_Scripts_QuickNav();
