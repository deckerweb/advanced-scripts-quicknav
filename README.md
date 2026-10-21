# Advanced Scripts QuickNav

![Advanced Scripts QuickNav](assets-github/github-banner-en.png)

**Your scripts. One click away.** Reach personal favorites, status lists and scripts inside folders directly from the WordPress toolbar. A focused add-on for Advanced Scripts.

**Version:** 1.3.1 · **WordPress:** 6.7+ · **PHP:** 8.1+ · **GPL-2.0-or-later**

[Deutsch](README-de.md) · [Guide and complete FAQ](docs/wiki/English.md) · [GitHub](https://github.com/deckerweb/advanced-scripts-quicknav)

[Download plugin ZIP](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/advanced-scripts-quicknav.zip) · [Releases](https://github.com/deckerweb/advanced-scripts-quicknav/releases)

## Contents

- [At a glance](#section-0)
- [Installation](#section-1)
- [Updates and Library](#section-2)
- [Configuration](#section-3)
- [FAQ](#section-4)
- [Changelog](#section-5)
- [About](#section-6)

<a id="section-0"></a>

## At a glance

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

<a id="section-1"></a>

## Installation

1. Upload the release ZIP through Plugins → Add New → Upload Plugin.
2. Activate Advanced Scripts and QuickNav.
3. Open Settings → Advanced Scripts QuickNav and select favorites.
4. Save preferences and open Scripts in the toolbar.

Previously installed standalone snippets should be disabled before activating the plugin.

<a id="section-2"></a>

## Updates and Library

deckerweb GitHub Updater V2 offers public stable releases through regular WordPress updates. Install the release ZIP for the first installation. deckerweb Library adds a deckerweb tab in the plugin installer and shared settings. It is separate from Find Snippets.

The settings header/footer includes the icon, version, localized documentation, local changelog, plugin website and © 2025–2026 David Decker – DECKERWEB.

<a id="section-3"></a>

## Configuration

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

<a id="section-4"></a>


## Global script search

Combine **Script type** and **Script status** filters with each other and the query, or use filters without entering text. **Active** refers to saved status and can include scripts blocked by folders; **Active, blocked by folder** selects those cases. Use the star next to a result to save personal favorites immediately, up to 100 per user and site. The toolbar reflects changes on the next page load.


Under **Tools → Advanced Scripts**, QuickNav adds **Search all scripts** above the script list. It finds scripts by title and folder path across all folders. Multiple search words must all match; case and accents are ignored. Results show type, saved activation status and folder path. Click a result to open its editor in the corresponding folder.

**Esc** or **Clear search** restores the original list. **Arrow down** moves focus from the search field to the first result. Up to 50 matches are shown; refine the query to narrow larger result sets. Script source code is neither searched nor transmitted. Search uses your existing QuickNav permissions and does not modify Advanced Scripts files or settings.

## FAQ

### Who is QuickNav for?

Administrators who frequently use Advanced Scripts and want direct access from the backend and frontend toolbar.

### Where do I select favorites?

Open Settings → Advanced Scripts QuickNav. See clickable favorite cards and snippet statistics, filter by title or folder, select scripts and save your preferences.

### What does Disabled by folder mean?

The script has at least one inactive ancestor folder. Advanced Scripts skips that branch even if the script itself is active.

### Does Active mean currently running?

No. It is the saved script flag. Folder status, execution location, conditions, hooks and PHP Safe Mode can affect execution.

### How does Safe Mode detection work?

A defined AS_SAFE_MODE takes precedence, including false. Otherwise QuickNav reads the advanced-scripts-safemode option. It does not change Safe Mode.

### How do updates work?

The plugin integrates deckerweb GitHub Updater V2 with the regular WordPress update system. It checks public stable GitHub releases and provides the matching plugin ZIP. It does not enable automatic updates.

[Complete FAQ by topic](docs/wiki/English.md)

<a id="section-5"></a>

## Changelog

### 1.3.1 · 2026-10-21

- **Improved:** Updated deckerweb Library to 1.1.0 and deckerweb Updater to 2.2.0.
- **Fixed:** Made search and favorite input validation and sanitization consistent.
- **Misc:** Requires PHP 8.1 or newer; WordPress 6.7 remains the minimum. Existing settings and favorites are preserved.

### 1.3.0 · 2026-10-19

- **New:** Native WordPress Command Palette shortcuts for the script manager, new scripts, search, QuickNav settings and personal favorites.
- **New:** Global search by script title and folder path directly in Advanced Scripts, with type/status filters and keyboard navigation.
- **New:** Add or remove personal favorites directly from search results, without opening the settings page.
- **Improved:** Search blends into the dark Advanced Scripts interface and shows up to 50 results with a total match count.
- **Improved:** Updated deckerweb Library to 1.0.0; QuickNav remains activatable when Advanced Scripts is missing or inactive.
- **Improved:** More efficient search metadata processing and robust closing/documentation fallbacks for the changelog dialog.

#
- **Fixed:** The local changelog follows the loaded German admin translation even when the request locale differs.

## 1.2.1 · 2026-10-09

- **Improved:** Settings show a subtle Safe Mode notice identifying its effective source: the AS_SAFE_MODE constant or the Advanced Scripts setting.

- **Fixed:** QuickNav stays active and accessible without Advanced Scripts; dependency notices appear outside the header, and saving returns to the admin without losing preferences.

- **Improved:** Banners use the freely licensed Inter typeface; SVG text paths ensure consistent rendering.

- **Improved:** Plugin-details changelog uses native headings, lists, bold category labels and clickable links instead of raw Markdown; selects German or English release notes for the administrator.

- **Improved:** Updated the embedded plugin catalog and localized update messages; personal settings remain intact.
- **Fixed:** Enforces package identity and compatibility checks during WordPress bulk updates.
- **Misc:** Uninstall clears temporary update data while preserving personal preferences and Advanced Scripts contents.

### 1.2.0 · 2026-10-01

- **New:** Personal favorites with clickable cards, a live selection preview and filtering by title or folder.
- **New:** Snippet statistics, folder-tree script links and an Add script here shortcut.
- **Improved:** Shared deckerweb GitHub Updater V2, deckerweb Library, settings header/footer and an accessible HTML changelog dialog.
- **Improved:** Code Compass artwork and synchronized English/German documentation, FAQs and translations.
- **Fixed:** Safe Mode detection, link-filter handling, menu IDs, visibility controls and an obsolete toolbar callback.

- **Misc:** Requires PHP 8.0 or newer; personal preferences remain intact.

### 1.1.0 · 2025-04-05

- **New:** Optionally restrict QuickNav to defined user IDs with ASQN_ENABLED_USERS.
- **Improved:** Supports installation and updates with Git Updater.
- **Fixed:** Addresses a PHP warning on the frontend.

## About

An independent add-on by David Decker – DECKERWEB. Advanced Scripts is developed by Clean Plugins. QuickNav does not include Advanced Scripts premium source.

[Support the project](https://ko-fi.com/deckerweb) · [Newsletter](https://eepurl.com/gbAUUn) · [Buy Advanced Scripts (affiliate link)](https://r.freemius.com/6334/142255/)

GPL v2 or later. New QuickNav artwork: © 2026 David Decker – DECKERWEB. Existing third-party icons retain their attribution.

## Support and security

[Issues](https://github.com/deckerweb/advanced-scripts-quicknav/issues) · [Private security reporting](SECURITY.md)

[Component licenses and data](docs/DATA.md)

## WordPress Command Palette

Navigate through the native WordPress Command Palette: open the script manager, add a new script, focus global search, open QuickNav settings or jump directly to personal favorites. Commands respect QuickNav permissions and the current website; favorite commands use your configured entry limit. They appear wherever WordPress provides its native palette (in the block editor on older supported versions). If Advanced Scripts is unavailable, only QuickNav settings remain available. No Advanced Scripts files are modified.
