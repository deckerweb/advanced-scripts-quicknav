# Advanced Scripts QuickNav · English

[Deutsch](https://github.com/deckerweb/advanced-scripts-quicknav/wiki/Deutsch) · [README](../../README.md)

## Quick start

1. Upload the release ZIP through Plugins → Add New → Upload Plugin.
2. Activate Advanced Scripts and QuickNav.
3. Open Settings → Advanced Scripts QuickNav and select favorites.
4. Save preferences and open Scripts in the toolbar.

Alternatively, import ddw-advanced-scripts-quicknav.as.json into Advanced Scripts. Use one installation mode only. PHP Safe Mode can block the snippet in the admin; updater and deckerweb Library are plugin-only.

Source was reviewed against Advanced Scripts 2.6.2 and rc3 was successfully tested by the plugin author. The broader WordPress integration matrix remains deferred. PHP 7.4 is no longer supported because of the shared Library.

## Display and configuration

Constants override personal display preferences. Users need manage_options and the configured QuickNav capability. The folder tree allows eight levels and defaults to 40 script/folder entries overall. Favorites and each status list also default to 40 entries. View all opens Advanced Scripts.

Examples; apply individually as needed:

```php
define( 'ASQN_VIEW_CAPABILITY', 'activate_plugins' );
define( 'ASQN_ENABLED_USERS', [ 1, 500 ] );
define( 'ASQN_NAME_IN_ADMINBAR', 'Scripts' );
define( 'ASQN_COUNTER', 'yes' );
define( 'ASQN_ICON', 'remix' );
define( 'ASQN_DISABLE_LIBRARY', 'yes' );
define( 'ASQN_DISABLE_FOOTER', 'yes' );
define( 'ASQN_EXPERT_MODE', false );
define( 'ASQN_MENU_LIMIT', 40 );
```

ASQN_ICON accepts blue or remix; without a constant the QuickNav symbol is used. ASQN_DISABLE_LIBRARY controls resource links only. deckerweb Library has its own settings.

## FAQ

### Who is QuickNav for?

Administrators who frequently use Advanced Scripts and want direct access from the backend and frontend toolbar.

### Does QuickNav replace Advanced Scripts?

No. Advanced Scripts remains responsible for code, execution, conditions, imports and exports. QuickNav provides navigation.

### What are the requirements?

WordPress 6.7+, PHP 8.0+ and an active Advanced Scripts installation. The source was reviewed against Advanced Scripts 2.6.2; rc3 was successfully user-tested. Broader runtime compatibility checks remain deferred.

### Where do I select favorites?

Open Settings → Advanced Scripts QuickNav. See clickable favorite cards and snippet statistics, filter by title or folder, select scripts and save your preferences.

### Are favorites shared?

No. Favorites and display preferences belong to your account on the current website. Other users and multisite subsites have separate selections.

### What does Disabled by folder mean?

The script has at least one inactive ancestor folder. Advanced Scripts skips that branch even if the script itself is active.

### Does Active mean currently running?

No. It is the saved script flag. Folder status, execution location, conditions, hooks and PHP Safe Mode can affect execution.

### How does Safe Mode detection work?

A defined AS_SAFE_MODE takes precedence, including false. Otherwise QuickNav reads the advanced-scripts-safemode option. It does not change Safe Mode.

### Does Safe Mode stop every frontend script?

No. In the supplied Advanced Scripts 2.6.2 source, PHP Safe Mode protects the admin and login contexts; it is not a general off switch for frontend scripts.

### Can I create a script inside a folder?

Yes. Add script here opens the native Advanced Scripts editor with the selected parent folder. Nothing is created until you save in that editor.

### Why are some entries omitted?

The default limit is 40 entries per status list and favorites list, and 40 script/folder entries across the folder tree. Choose 10–200 in preferences. View all opens Advanced Scripts. Tree depth is limited to eight levels.

### Can I search the toolbar?

Not in this release. The preferences page filters the favorites selection by title or folder. A keyboard-accessible toolbar search is planned separately.

### Do existing constants still work?

Yes. ASQN_VIEW_CAPABILITY, ASQN_ENABLED_USERS, ASQN_NAME_IN_ADMINBAR, ASQN_COUNTER, ASQN_ICON, ASQN_DISABLE_LIBRARY, ASQN_DISABLE_FOOTER and ASQN_EXPERT_MODE remain supported. Display constants override personal preferences.

### Which permissions are required?

The current user needs manage_options and the QuickNav capability (activate_plugins by default). ASQN_ENABLED_USERS can restrict visibility further. Lowering the QuickNav capability does not grant access to Advanced Scripts.

### How do updates work?

The plugin integrates deckerweb GitHub Updater V2 with the regular WordPress update system. It checks public stable GitHub releases and provides the matching plugin ZIP. It does not enable automatic updates.

### What is the deckerweb Library?

An embedded catalog under Plugins → Add New → deckerweb, with shared settings. It is separate from the Find Snippets resource links. Multiple compatible deckerweb plugins share one runtime.

### Does QuickNav send my scripts elsewhere?

QuickNav navigation and favorites do not send script data anywhere. The updater contacts GitHub for release metadata. Library actions can contact approved GitHub downloads; its optional online catalog has separate settings.

### Can I install the snippet instead?

Yes. Import the generated JSON into Advanced Scripts and enable it with the plugins_loaded hook. It contains navigation and personal preferences, but no plugin updater or deckerweb Library. Use either the plugin or the snippet.

### What happens to the snippet in Safe Mode?

Advanced Scripts can prevent the QuickNav PHP snippet from running in the admin. It then cannot show its own Safe Mode notice. Use the plugin installation for reliable admin diagnostics.

### What happens if a favorite is deleted?

It is excluded from the displayed selection automatically. Saving preferences removes obsolete IDs. Scripts with the same title remain distinct because favorites use term IDs.

### Can I switch scripts on and off in QuickNav?

No. Links open the native editor. Activation, deletion and execution stay with Advanced Scripts.

### Where are my preferences stored?

In user metadata keyed by the current site ID. Deactivation keeps them. There is no automatic uninstall cleanup in this version.

### Are translations included?

German informal and formal catalogs are bundled. Plugin mode uses the WordPress user/site language. The standalone snippet includes its German catalog.

### What do the statistics count?

All snippets excludes folders. Active and inactive use saved status. Active snippets blocked by an inactive ancestor folder are an additional subset of active snippets. Favorites counts the saved selection, independent of toolbar limits. Type totals include active and inactive snippets.

### How do favorite cards work?

Cards open the native editor directly. Selecting or clearing a checkbox updates the card preview immediately; Save my preferences applies it to the toolbar. Without JavaScript, saved favorite cards remain usable.

### What happened to fullscreen toolbar settings?

QuickNav no longer adjusts the fullscreen block editor, adds fullscreen CSS or removes the WordPress logo. Old ASQN_FULLSCREEN definitions and stored fullscreen preferences no longer affect QuickNav.

### Is this ready for production?

Version 1.2.0 is based on successfully user-tested rc3. Syntax and focused release checks passed; the broader WordPress integration matrix remains deferred.

## Updates and Library

deckerweb GitHub Updater V2 offers public stable releases through regular WordPress updates. Install the release ZIP for the first installation. deckerweb Library adds a deckerweb tab in the plugin installer and shared settings. It is separate from Find Snippets.

The settings header/footer includes the icon, version, localized documentation, local changelog, plugin website and © 2022–2026 David Decker – DECKERWEB.

## Changelog

[Complete changelog](../CHANGELOG.md)
