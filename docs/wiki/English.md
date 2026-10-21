# Advanced Scripts QuickNav · English

[Deutsch](https://github.com/deckerweb/advanced-scripts-quicknav/wiki/Deutsch) · [README](../../README.md)

## Feature highlights

### Your scripts, right in the toolbar

Reach Advanced Scripts wherever the WordPress toolbar is available and open scripts directly in the editor.

### Active scripts, inactive scripts and folders at a glance

Find the right script through separate status lists and the folder tree.

### Your favorites. Your shortcuts.

Keep frequently used scripts in your personal favorites, open them from the toolbar and add or remove favorites directly in search results.

### Search every folder. Find exactly what you need.

**The search Advanced Scripts has been missing.** Find scripts across all folders and narrow results by type and activation status. Spend less time searching and get back to the right script faster. Give it a try!

### Also included

- **Your script collection at a glance:** A compact statistics overview shows active and inactive snippets, script types and folder blocks.
- **Work the way you prefer:** Personal display options, script counters and menu entry limits apply per user and website.

## Quick start

1. Upload the release ZIP through Plugins → Add New → Upload Plugin.
2. Activate Advanced Scripts and QuickNav.
3. Open Settings → Advanced Scripts QuickNav and select favorites.
4. Save preferences and open Scripts in the toolbar.

Previously installed standalone snippets should be disabled before activating the plugin.

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


## Global script search

Combine **Script type** and **Script status** filters with each other and the query, or use filters without entering text. **Active** refers to saved status and can include scripts blocked by folders; **Active, blocked by folder** selects those cases. Use the star next to a result to save personal favorites immediately, up to 100 per user and site. The toolbar reflects changes on the next page load.


Under **Tools → Advanced Scripts**, QuickNav adds **Search all scripts** above the script list. It finds scripts by title and folder path across all folders. Multiple search words must all match; case and accents are ignored. Results show type, saved activation status and folder path. Click a result to open its editor in the corresponding folder.

**Esc** or **Clear search** restores the original list. **Arrow down** moves focus from the search field to the first result. Up to 50 matches are shown; refine the query to narrow larger result sets. Script source code is neither searched nor transmitted. Search uses your existing QuickNav permissions and does not modify Advanced Scripts files or settings.

## FAQ by topic

### Getting started

#### Who is QuickNav for?

Administrators who frequently use Advanced Scripts and want direct access from the backend and frontend toolbar.

#### Does QuickNav replace Advanced Scripts?

No. Advanced Scripts remains responsible for code, execution, conditions, imports and exports. QuickNav provides navigation.

#### What are the requirements?

WordPress 6.7+, PHP 8.1+ and an active Advanced Scripts installation.

#### Where do I select favorites?

Open Settings → Advanced Scripts QuickNav. See clickable favorite cards and snippet statistics, filter by title or folder, select scripts and save your preferences.

### Daily navigation

#### What does Disabled by folder mean?

The script has at least one inactive ancestor folder. Advanced Scripts skips that branch even if the script itself is active.

#### Can I create a script inside a folder?

Yes. Add script here opens the native Advanced Scripts editor with the selected parent folder. Nothing is created until you save in that editor.

#### Why are some entries omitted?

The default limit is 40 entries per status list and favorites list, and 40 script/folder entries across the folder tree. Choose 10–200 in preferences. View all opens Advanced Scripts. Tree depth is limited to eight levels.

#### Can I search the toolbar?

Toolbar search is not available. The preferences page filters the favorites selection by title or folder.

#### What is the deckerweb Library?

An embedded catalog under Plugins → Add New → deckerweb, with shared settings. It is separate from the Find Snippets resource links. Multiple compatible deckerweb plugins share one runtime.

#### Does QuickNav send my scripts elsewhere?

QuickNav navigation and favorites do not send script data anywhere. The updater contacts GitHub for release metadata. Library actions can contact approved GitHub downloads; its optional online catalog has separate settings.

#### What happens if a favorite is deleted?

It is excluded from the displayed selection automatically. Saving preferences removes obsolete IDs. Scripts with the same title remain distinct because favorites use term IDs.

#### Can I switch scripts on and off in QuickNav?

No. Links open the native editor. Activation, deletion and execution stay with Advanced Scripts.

#### What do the statistics count?

All snippets excludes folders. Active and inactive use saved status. Active snippets blocked by an inactive ancestor folder are an additional subset of active snippets. Favorites counts the saved selection, independent of toolbar limits. Type totals include active and inactive snippets.

#### How do favorite cards work?

Cards open the native editor directly. Selecting or clearing a checkbox updates the card preview immediately; Save my preferences applies it to the toolbar. Without JavaScript, saved favorite cards remain usable.

### Preferences and data

#### Are favorites shared?

No. Favorites and display preferences belong to your account on the current website. Other users and multisite subsites have separate selections.

#### Where are my preferences stored?

In user metadata keyed by the current site ID. Deactivation keeps them. Uninstall removes temporary update/Library data while preserving personal preferences and Advanced Scripts contents.

#### Are translations included?

German informal and formal catalogs are bundled. Plugin mode uses the WordPress user/site language.

### Technical questions

#### Does Active mean currently running?

No. It is the saved script flag. Folder status, execution location, conditions, hooks and PHP Safe Mode can affect execution.

#### How does Safe Mode detection work?

A defined AS_SAFE_MODE takes precedence, including false. Otherwise QuickNav reads the advanced-scripts-safemode option. It does not change Safe Mode.

#### Does Safe Mode stop every frontend script?

No. In the supplied Advanced Scripts 2.6.2 source, PHP Safe Mode protects the admin and login contexts; it is not a general off switch for frontend scripts.

#### Do existing constants still work?

Yes. ASQN_VIEW_CAPABILITY, ASQN_ENABLED_USERS, ASQN_NAME_IN_ADMINBAR, ASQN_COUNTER, ASQN_ICON, ASQN_DISABLE_LIBRARY, ASQN_DISABLE_FOOTER and ASQN_EXPERT_MODE remain supported. Display constants override personal preferences.

#### Which permissions are required?

The current user needs manage_options and the QuickNav capability (activate_plugins by default). ASQN_ENABLED_USERS can restrict visibility further. Lowering the QuickNav capability does not grant access to Advanced Scripts.

#### How do updates work?

The plugin integrates deckerweb GitHub Updater V2 with the regular WordPress update system. It checks public stable GitHub releases and provides the matching plugin ZIP. It does not enable automatic updates.

#### What happens to the snippet in Safe Mode?

Advanced Scripts can prevent the QuickNav PHP snippet from running in the admin. It then cannot show its own Safe Mode notice. Use the plugin installation for reliable admin diagnostics.

#### What happened to fullscreen toolbar settings?

QuickNav no longer adjusts the fullscreen block editor, adds fullscreen CSS or removes the WordPress logo. Old ASQN_FULLSCREEN definitions and stored fullscreen preferences no longer affect QuickNav.


## Updates and Library

deckerweb GitHub Updater V2 offers public stable releases through regular WordPress updates. Install the release ZIP for the first installation. deckerweb Library adds a deckerweb tab in the plugin installer and shared settings. It is separate from Find Snippets.

The settings header/footer includes the icon, version, localized documentation, local changelog, plugin website and © 2025–2026 David Decker – DECKERWEB.

## Changelog

[Complete changelog](../CHANGELOG.md)
WordPress 6.7+, PHP 8.0+ and an active Advanced Scripts installation.

## WordPress Command Palette

Navigate through the native WordPress Command Palette: open the script manager, add a new script, focus global search, open QuickNav settings or jump directly to personal favorites. Commands respect QuickNav permissions and the current website; favorite commands use your configured entry limit. They appear wherever WordPress provides its native palette (in the block editor on older supported versions). If Advanced Scripts is unavailable, only QuickNav settings remain available. No Advanced Scripts files are modified.
