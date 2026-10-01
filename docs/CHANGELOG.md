# Changelog

[Deutsch](CHANGELOG-de.md)

## 1.2.0 · 2026-10-01

- **New:** Personal favorites with clickable cards, a live selection preview and filtering by title or folder.
- **New:** Snippet statistics, folder-tree script links and an Add script here shortcut.
- **Improved:** Modular metadata-only integration with Advanced Scripts, script details, folder-blocking hints and bounded menus.
- **Improved:** Shared deckerweb GitHub Updater V2, deckerweb Library, settings header/footer and an accessible HTML changelog dialog.
- **Improved:** Code Compass artwork and synchronized English/German documentation, FAQs and translations.
- **Fixed:** Safe Mode detection, link-filter handling, menu IDs, visibility controls and the obsolete toolbar callback that caused a fatal error in rc2.
- **Misc:** Requires WordPress 6.7+ and PHP 8.0+. Removes fullscreen block-editor adjustments. Plugin and standalone snippet are built from one source.
- **Misc:** Released after successful user testing of rc3 and focused release checks. The broader WordPress integration matrix remains deferred.

## 1.2.0-rc3 · 2026-10-01 (test candidate)

- **Fixed:** Removed the remaining admin_bar_menu registration for the deleted remove_adminbar_nodes method, preventing a fatal error when expert links are enabled.
- **Improved:** Regression checks now reject invalid action callbacks.

## 1.2.0-rc2 · 2026-10-01 (test candidate)

- **New:** Visual favorites cards with direct editor links and a live preview of the current selection.
- **New:** A compact snippet overview with totals, active/inactive counts, folders, saved favorites, active snippets blocked by folders and a type breakdown.
- **Improved:** Formats the local changelog dialog as escaped HTML with version/date headings, readable lists and subtle category badges, following the shared deckerweb footer standard.
- **Improved:** Keeps the selected Code Compass artwork (variant A) and updates synchronized English/German documentation and translations.
- **Misc:** Removes the fullscreen block-editor preference, hooks, CSS adjustments and WordPress-logo removal.
- **Misc:** Existing favorites remain unchanged. The broad WordPress integration matrix remains deferred.

## 1.2.0-rc1 · 2026-10-01 (test candidate)

- **New:** Personal favorites stored separately for each user and website, with a filter by title or folder on the preferences page.
- **New:** Direct script links in the folder tree and an Add script here shortcut for each folder.
- **New:** Personal preferences for script counts, developer links, fullscreen toolbar and bounded menu lists.
- **Improved:** Shows Disabled by folder without changing the script’s own active/inactive status; includes type, folder path, location and hook details.
- **Improved:** Separates configuration, the Advanced Scripts adapter, navigation, rendering, personal settings and hook registration.
- **Improved:** Adds the shared deckerweb GitHub Updater V2, localized update artwork and embedded deckerweb Library 0.2.0.
- **Improved:** Adds the deckerweb settings header/footer and an accessible local changelog dialog.
- **Fixed:** Recognizes Safe Mode enabled through the Advanced Scripts interface, respecting AS_SAFE_MODE precedence.
- **Fixed:** Applies link-filter return values, separates library/footer visibility and handles empty collections and custom admin color schemes.
- **Fixed:** Uses shared permission checks for the toolbar and fullscreen integration; protects preferences with POST, capabilities and a nonce.
- **Misc:** Requires PHP 8.0+, preserves WordPress 6.7+, and provides synchronized English/German readmes, FAQs, Wiki sources and complete changelogs.
- **Misc:** Includes provisional design A; three icon/banner alternatives are supplied separately for selection.
- **Misc:** Generates the standalone Advanced Scripts JSON snippet from the same source. Updater and deckerweb Library are plugin-only.
- **Misc:** This is a local test candidate. Full WordPress integration validation and public release publication are pending.

## 1.1.0 · 2025-04-05

- **New:** Optionally restrict QuickNav to defined user IDs with ASQN_ENABLED_USERS.
- **Improved:** Supports installation and updates with Git Updater.
- **Fixed:** Addresses a PHP warning on the frontend.

## 1.0.0 · 2025-03-24

- **New:** Initial public release with status lists, folder links, Safe Mode notices, resource links, developer integrations and constants.

## 0.5.0 · 2025-03-23

- **Misc:** Internal test version.

## 0.0.0 · 2025-03-23

- **Misc:** Development started.

