<?php
/**
 * Plugin Name: Advanced Scripts QuickNav
 * Plugin URI: https://github.com/deckerweb/advanced-scripts-quicknav
 * Description: Reach your Advanced Scripts through personal favorites, status lists and folders in the WordPress toolbar.
 * Version: 1.2.0
 * Author: David Decker – DECKERWEB
 * Author URI: https://deckerweb.de/
 * Text Domain: advanced-scripts-quicknav
 * Domain Path: /languages/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Update URI: https://github.com/deckerweb/advanced-scripts-quicknav
 * GitHub Plugin URI: https://github.com/deckerweb/advanced-scripts-quicknav
 * Primary Branch: main
 * Copyright: © 2022–2026 David Decker – DECKERWEB
 */
defined( 'ABSPATH' ) || exit;
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	add_action( 'admin_notices', static function() { echo '<div class="notice notice-error"><p>Advanced Scripts QuickNav requires PHP 8.0 or newer.</p></div>'; } );
	return;
}
// The generated snippet uses the same class guard; never register the toolbar twice.
if ( class_exists( 'DDW_Advanced_Scripts_QuickNav', false ) ) { return; }
define( 'ASQN_PLUGIN_FILE', __FILE__ );
foreach ( [ 'config', 'adapter', 'favorites', 'navigation', 'renderer', 'settings', 'plugin' ] as $component ) { require_once __DIR__ . '/includes/class-asqn-' . $component . '.php'; }
require_once __DIR__ . '/includes/deckerweb-changelog-v1.php';
require_once __DIR__ . '/includes/deckerweb-plugin-library/bootstrap.php';
deckerweb_library_register( __FILE__, [], __DIR__ . '/includes/deckerweb-plugin-library' );
new DDW_Advanced_Scripts_QuickNav();
