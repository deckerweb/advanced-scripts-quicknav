<?php
/** Remove temporary component data while preserving user preferences and scripts. */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
$asqn_repository = 'https://github.com/deckerweb/advanced-scripts-quicknav';
delete_site_transient( 'ddw_ghru_' . substr( md5( $asqn_repository ), 0, 24 ) );
if ( version_compare( PHP_VERSION, '8.1', '>=' ) ) {
	$asqn_updater_class = require __DIR__ . '/includes/deckerweb-github-release-updater-v2.php';
	$asqn_updater_class::clear_repository_cache( $asqn_repository );
}
require_once __DIR__ . '/includes/deckerweb-plugin-library/lifecycle.php';
deckerweb_library_uninstall_v3( __DIR__ . '/advanced-scripts-quicknav.php' );
