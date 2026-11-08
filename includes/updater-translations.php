<?php
/** Copy into the host adapter and replace the advanced-scripts-quicknav textdomain with its literal domain. */
defined( 'ABSPATH' ) || exit;
/**
 * Resolve an updater source string in the host textdomain.
 * @param string $message English updater message.
 * @return string Translated host message, or original source text.
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
        case 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.':
            return __( 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.', 'advanced-scripts-quicknav' );
        case 'Manage installed plugins':
            return __( 'Manage installed plugins', 'advanced-scripts-quicknav' );
        case 'Dismiss this notice':
            return __( 'Dismiss this notice', 'advanced-scripts-quicknav' );
        case 'Permission denied.':
            return __( 'Permission denied.', 'advanced-scripts-quicknav' );
        case 'Public catalog callbacks require both providers and cannot be used in private mode.':
            return __( 'Public catalog callbacks require both providers and cannot be used in private mode.', 'advanced-scripts-quicknav' );
        case 'The approved release could not be verified.':
            return __( 'The approved release could not be verified.', 'advanced-scripts-quicknav' );
        case 'The approved release changed. Refresh updates first.':
            return __( 'The approved release changed. Refresh updates first.', 'advanced-scripts-quicknav' );
        case 'Catalog diagnostics require a public catalog provider.':
            return __( 'Catalog diagnostics require a public catalog provider.', 'advanced-scripts-quicknav' );
        case 'Catalog approval':
            return __( 'Catalog approval', 'advanced-scripts-quicknav' );
        case 'Package download':
            return __( 'Package download', 'advanced-scripts-quicknav' );
        case 'Checksum verification':
            return __( 'Checksum verification', 'advanced-scripts-quicknav' );
        case 'Archive verification':
            return __( 'Archive verification', 'advanced-scripts-quicknav' );
        case 'The update server could not be reached. Try again later.':
            return __( 'The update server could not be reached. Try again later.', 'advanced-scripts-quicknav' );
        case 'The update request timed out. Try again later.':
            return __( 'The update request timed out. Try again later.', 'advanced-scripts-quicknav' );
        case 'The update server address could not be resolved.':
            return __( 'The update server address could not be resolved.', 'advanced-scripts-quicknav' );
        case 'The secure connection to the update server failed.':
            return __( 'The secure connection to the update server failed.', 'advanced-scripts-quicknav' );
        case 'The update server returned an unsuccessful HTTP response.':
            return __( 'The update server returned an unsuccessful HTTP response.', 'advanced-scripts-quicknav' );
        case 'The update provider did not return valid update data.':
            return __( 'The update provider did not return valid update data.', 'advanced-scripts-quicknav' );
        case 'The package does not match the approved checksum. Installation was stopped.':
            return __( 'The package does not match the approved checksum. Installation was stopped.', 'advanced-scripts-quicknav' );
        case 'The archive or plugin identity could not be verified. Installation was stopped.':
            return __( 'The archive or plugin identity could not be verified. Installation was stopped.', 'advanced-scripts-quicknav' );
        case 'Update stopped at %1$s: %2$s':
            /* translators: 1: update stage, 2: fixed explanation. */
            return __( 'Update stopped at %1$s: %2$s', 'advanced-scripts-quicknav' );
        case 'HTTP status: %d.':
            /* translators: %d: HTTP error status. */
            return __( 'HTTP status: %d.', 'advanced-scripts-quicknav' );
        case 'Invalid private download limits.':
            return __( 'Invalid private download limits.', 'advanced-scripts-quicknav' );
        case 'Invalid package cleanup provider.':
            return __( 'Invalid package cleanup provider.', 'advanced-scripts-quicknav' );
        case 'Safe package cleanup is unavailable for this update provider.':
            return __( 'Safe package cleanup is unavailable for this update provider.', 'advanced-scripts-quicknav' );
        case 'The update package identity, version or requirements could not be verified.':
            return __( 'The update package identity, version or requirements could not be verified.', 'advanced-scripts-quicknav' );
        default:
            return $message;
    }
};
