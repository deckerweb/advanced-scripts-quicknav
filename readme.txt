=== Advanced Scripts QuickNav ===
Contributors: deckerweb
Tags: advanced scripts, admin bar, toolbar, code snippets, favorites
Requires at least: 6.7
Requires PHP: 8.0
Stable tag: 1.2.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Your scripts. One click away.

== Description ==

- Personal favorites per user and website.
- Script links in the folder tree and Add script here.
- Own activation status plus inactive-ancestor hints.
- Type, folder path, execution location and hook details.
- Visual favorite cards, snippet statistics, display preferences, Safe Mode notices, SCRIPT_DEBUG and optional developer links.
- Existing resource links and integrations with DevKit Pro, System Dashboard, Variable Inspector and Debug Log Manager remain included.

== Installation ==

1. Upload the release ZIP through Plugins → Add New → Upload Plugin.
2. Activate Advanced Scripts and QuickNav.
3. Open Settings → Advanced Scripts QuickNav and select favorites.
4. Save preferences and open Scripts in the toolbar.

Alternatively, import ddw-advanced-scripts-quicknav.as.json into Advanced Scripts. Use one installation mode only. PHP Safe Mode can block the snippet in the admin; updater and deckerweb Library are plugin-only.

Source was reviewed against Advanced Scripts 2.6.2 and rc3 was successfully tested by the plugin author. The broader WordPress integration matrix remains deferred. PHP 7.4 is no longer supported because of the shared Library.

== Frequently Asked Questions ==

= Who is QuickNav for? =

Administrators who frequently use Advanced Scripts and want direct access from the backend and frontend toolbar.

= Where do I select favorites? =

Open Settings → Advanced Scripts QuickNav. See clickable favorite cards and snippet statistics, filter by title or folder, select scripts and save your preferences.

= What does Disabled by folder mean? =

The script has at least one inactive ancestor folder. Advanced Scripts skips that branch even if the script itself is active.

= Does Active mean currently running? =

No. It is the saved script flag. Folder status, execution location, conditions, hooks and PHP Safe Mode can affect execution.

= How does Safe Mode detection work? =

A defined AS_SAFE_MODE takes precedence, including false. Otherwise QuickNav reads the advanced-scripts-safemode option. It does not change Safe Mode.

= How do updates work? =

The plugin integrates deckerweb GitHub Updater V2 with the regular WordPress update system. It checks public stable GitHub releases and provides the matching plugin ZIP. It does not enable automatic updates.

= Can I install the snippet instead? =

Yes. Import the generated JSON into Advanced Scripts and enable it with the plugins_loaded hook. It contains navigation and personal preferences, but no plugin updater or deckerweb Library. Use either the plugin or the snippet.

== Changelog ==

= 1.2.0 · 2026-10-01 =

* New: Personal favorites with clickable cards, a live selection preview and filtering by title or folder.
* New: Snippet statistics, folder-tree script links and an Add script here shortcut.
* Improved: Modular metadata-only integration with Advanced Scripts, script details, folder-blocking hints and bounded menus.
* Improved: Shared deckerweb GitHub Updater V2, deckerweb Library, settings header/footer and an accessible HTML changelog dialog.
* Improved: Code Compass artwork and synchronized English/German documentation, FAQs and translations.
* Fixed: Safe Mode detection, link-filter handling, menu IDs, visibility controls and the obsolete toolbar callback that caused a fatal error in rc2.
* Misc: Requires WordPress 6.7+ and PHP 8.0+. Removes fullscreen block-editor adjustments. Plugin and standalone snippet are built from one source.
* Misc: Released after successful user testing of rc3 and focused release checks. The broader WordPress integration matrix remains deferred.

= 1.2.0-rc3 · 2026-10-01 (test candidate) =

* Fixed: Removed the remaining admin_bar_menu registration for the deleted remove_adminbar_nodes method, preventing a fatal error when expert links are enabled.
* Improved: Regression checks now reject invalid action callbacks.

= 1.2.0-rc2 · 2026-10-01 (test candidate) =

* New: Visual favorites cards with direct editor links and a live preview of the current selection.
* New: A compact snippet overview with totals, active/inactive counts, folders, saved favorites, active snippets blocked by folders and a type breakdown.
* Improved: Formats the local changelog dialog as escaped HTML with version/date headings, readable lists and subtle category badges, following the shared deckerweb footer standard.
* Improved: Keeps the selected Code Compass artwork (variant A) and updates synchronized English/German documentation and translations.
* Misc: Removes the fullscreen block-editor preference, hooks, CSS adjustments and WordPress-logo removal.
* Misc: Existing favorites remain unchanged. The broad WordPress integration matrix remains deferred.

= 1.2.0-rc1 · 2026-10-01 (test candidate) =

* New: Personal favorites stored separately for each user and website, with a filter by title or folder on the preferences page.
* New: Direct script links in the folder tree and an Add script here shortcut for each folder.
* New: Personal preferences for script counts, developer links, fullscreen toolbar and bounded menu lists.
* Improved: Shows Disabled by folder without changing the script’s own active/inactive status; includes type, folder path, location and hook details.
* Improved: Separates configuration, the Advanced Scripts adapter, navigation, rendering, personal settings and hook registration.
* Improved: Adds the shared deckerweb GitHub Updater V2, localized update artwork and embedded deckerweb Library 0.2.0.
* Improved: Adds the deckerweb settings header/footer and an accessible local changelog dialog.
* Fixed: Recognizes Safe Mode enabled through the Advanced Scripts interface, respecting AS_SAFE_MODE precedence.
* Fixed: Applies link-filter return values, separates library/footer visibility and handles empty collections and custom admin color schemes.
* Fixed: Uses shared permission checks for the toolbar and fullscreen integration; protects preferences with POST, capabilities and a nonce.
* Misc: Requires PHP 8.0+, preserves WordPress 6.7+, and provides synchronized English/German readmes, FAQs, Wiki sources and complete changelogs.
* Misc: Includes provisional design A; three icon/banner alternatives are supplied separately for selection.
* Misc: Generates the standalone Advanced Scripts JSON snippet from the same source. Updater and deckerweb Library are plugin-only.
* Misc: This is a local test candidate. Full WordPress integration validation and public release publication are pending.

= 1.1.0 · 2025-04-05 =

* New: Optionally restrict QuickNav to defined user IDs with ASQN_ENABLED_USERS.
* Improved: Supports installation and updates with Git Updater.
* Fixed: Addresses a PHP warning on the frontend.

= 1.0.0 · 2025-03-24 =

* New: Initial public release with status lists, folder links, Safe Mode notices, resource links, developer integrations and constants.

= 0.5.0 · 2025-03-23 =

* Misc: Internal test version.

= 0.0.0 · 2025-03-23 =

* Misc: Development started.

== Upgrade Notice ==

= 1.2.0 =
Requires PHP 8.0+. Adds favorites, statistics and deckerweb updates. Install either plugin or snippet.


= 1.2.0-rc3 =
Fixed: Prevents the invalid toolbar callback fatal error. Replace rc2 with rc3.


= 1.2.0-rc2 =
Test candidate. Now requires PHP 8.0+. Full integration validation follows later.
