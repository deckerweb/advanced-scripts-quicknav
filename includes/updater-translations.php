<?php
/** Translate the canonical updater phrases through the host textdomain. */
defined( 'ABSPATH' ) || exit;
/**
 * Translate one engine message through the existing host catalog.
 * @param string $message English updater source message.
 * @return string Host translation or unchanged source.
 */
return static function ( string $message ): string {
 switch ( $message ) {
  case 'Private mode must be boolean.': return __( 'Private mode must be boolean.', 'advanced-scripts-quicknav' );
  case 'Invalid authentication provider.': return __( 'Invalid authentication provider.', 'advanced-scripts-quicknav' );
  case 'The plugin must be installed in a stable slug directory.': return __( 'The plugin must be installed in a stable slug directory.', 'advanced-scripts-quicknav' );
  case 'Invalid GitHub repository URL.': return __( 'Invalid GitHub repository URL.', 'advanced-scripts-quicknav' );
  case 'The private update could not be authorized. Check the repository credentials and refresh updates.': return __( 'The private update could not be authorized. Check the repository credentials and refresh updates.', 'advanced-scripts-quicknav' );
  case 'Could not create the update download file.': return __( 'Could not create the update download file.', 'advanced-scripts-quicknav' );
  case 'The private update download failed. Check credentials and try again.': return __( 'The private update download failed. Check credentials and try again.', 'advanced-scripts-quicknav' );
  case 'Could not access the update filesystem.': return __( 'Could not access the update filesystem.', 'advanced-scripts-quicknav' );
  case 'GitHub release does not contain the plugin main file.': return __( 'GitHub release does not contain the plugin main file.', 'advanced-scripts-quicknav' );
  case 'Could not prepare the GitHub release package.': return __( 'Could not prepare the GitHub release package.', 'advanced-scripts-quicknav' );
  case 'See the release on GitHub.': return __( 'See the release on GitHub.', 'advanced-scripts-quicknav' );
  case 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.': return __( 'Git Updater is active. If a deckerweb installation or update fails, temporarily deactivate Git Updater and retry. Reactivate it afterwards if other plugins or themes need it. Only leave it disabled when those extensions have another reliable update provider.', 'advanced-scripts-quicknav' );
  case 'Manage installed plugins': return __( 'Manage installed plugins', 'advanced-scripts-quicknav' );
  case 'Dismiss this notice': return __( 'Dismiss this notice', 'advanced-scripts-quicknav' );
  case 'Permission denied.': return __( 'Permission denied.', 'advanced-scripts-quicknav' );
  case 'Public catalog callbacks require both providers and cannot be used in private mode.': return __( 'Public catalog callbacks require both providers and cannot be used in private mode.', 'advanced-scripts-quicknav' );
  case 'The approved release could not be verified.': return __( 'The approved release could not be verified.', 'advanced-scripts-quicknav' );
  case 'The approved release changed. Refresh updates first.': return __( 'The approved release changed. Refresh updates first.', 'advanced-scripts-quicknav' );
  default: return $message;
 }
};
