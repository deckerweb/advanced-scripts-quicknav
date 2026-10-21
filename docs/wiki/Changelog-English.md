# Changelog

## 1.3.1 · 2026-10-21

- **Improved:** Updated deckerweb Library to 1.1.0 and deckerweb Updater to 2.2.0.
- **Fixed:** Made search and favorite input validation and sanitization consistent.
- **Misc:** Requires PHP 8.1 or newer; WordPress 6.7 remains the minimum. Existing settings and favorites are preserved.

## 1.3.0 · 2026-10-19

- **New:** Native WordPress Command Palette shortcuts for the script manager, new scripts, search, QuickNav settings and personal favorites.
- **New:** Global search by script title and folder path directly in Advanced Scripts, with type/status filters and keyboard navigation.
- **New:** Add or remove personal favorites directly from search results, without opening the settings page.
- **Improved:** Search blends into the dark Advanced Scripts interface and shows up to 50 results with a total match count.
- **Improved:** Updated deckerweb Library to 1.0.0; QuickNav remains activatable when Advanced Scripts is missing or inactive.
- **Improved:** More efficient search metadata processing and robust closing/documentation fallbacks for the changelog dialog.
- **Fixed:** The local changelog follows the loaded German admin translation even when the request locale differs.

## 1.2.1 · 2026-10-09

- **Improved:** Plugin-details changelog uses native headings, lists, bold category labels and clickable links instead of raw Markdown; selects German or English release notes for the administrator.

- **Improved:** Updated the embedded plugin catalog and localized update messages; personal settings remain intact.
- **Fixed:** Enforces package identity and compatibility checks during WordPress bulk updates.
- **Misc:** Uninstall clears temporary update data while preserving personal preferences and Advanced Scripts contents.


## 1.2.0 · 2026-10-01

- **New:** Personal favorites with clickable cards, a live selection preview and filtering by title or folder.
- **New:** Snippet statistics, folder-tree script links and an Add script here shortcut.
- **Improved:** Shared deckerweb GitHub Updater V2, deckerweb Library, settings header/footer and an accessible HTML changelog dialog.
- **Improved:** Code Compass artwork and synchronized English/German documentation, FAQs and translations.
- **Fixed:** Safe Mode detection, link-filter handling, menu IDs, visibility controls and an obsolete toolbar callback.

- **Misc:** Requires PHP 8.0 or newer; personal preferences remain intact.

## 1.1.0 · 2025-04-05

- **New:** Optionally restrict QuickNav to defined user IDs with ASQN_ENABLED_USERS.
- **Improved:** Supports installation and updates with Git Updater.
- **Fixed:** Addresses a PHP warning on the frontend.

## 1.0.0 · 2025-03-24

- **New:** Initial public release with status lists, folder links, Safe Mode notices, resource links, developer integrations and constants.
