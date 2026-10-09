<?php
/** Translate updater messages through the QuickNav textdomain. */
defined( 'ABSPATH' ) || exit;
/**
 * Translate one engine phrase without loading a second textdomain.
 * @param string $message English engine message.
 * @return string Host-localized message, or the original for unknown phrases.
 */
return static function ( string $message ): string {
    // Literal calls let the host's normal translation extractor collect every source string.
    switch ( $message ) {
        case 'Private mode must be boolean.':
            return __( 'Private mode must be boolean.', 'advanced-scripts-quicknav' );
        case 'Invalid authentication provider.':
            return __( 'Invalid authentication provider.', 'advanced-scripts-quicknav' );
        case 'The plugin must be installed in a stable slug directory.':
            return __( 'The plugin must be installed in a stable slug directory.', 'advanced-scripts-quicknav' );
        case 'Invalid GitHub repository URL.':
            return __( 'Invalid GitHub repository URL.', 'advanced-scripts-quicknav' );
        case 'The private update could not be authorized. Check the repository credentials and refresh updates.':
            return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'advanced-scripts-quicknav' );
        case 'Could not create the update download file.':
            return __( 'Could not create the update download file.', 'advanced-scripts-quicknav' );
        case 'The private update download failed. Check credentials and try again.':
            return __( 'The private update download failed. Check credentials and try again.', 'advanced-scripts-quicknav' );
        case 'Could not access the update filesystem.':
            return __( 'Could not access the update filesystem.', 'advanced-scripts-quicknav' );
        case 'GitHub release does not contain the plugin main file.':
            return __( 'GitHub release does not contain the plugin main file.', 'advanced-scripts-quicknav' );
        case 'Could not prepare the GitHub release package.':
            return __( 'Could not prepare the GitHub release package.', 'advanced-scripts-quicknav' );
        case 'See the release on GitHub.':
            return __( 'See the release on GitHub.', 'advanced-scripts-quicknav' );
        default:
            return $message;
    }
};
