# deckerweb standards for Advanced Scripts QuickNav

- Keep English and German README.md/readme.txt editions, FAQ and Wiki sources synchronized.
- Categorize every changelog entry as New:, Improved:, Fixed:, Misc:. Translate German prefixes to Neu:, Verbessert:, Behoben:, Sonstiges: across all bundled history and dialogs.
- Use the shared deckerweb GitHub Updater V2 and embedded deckerweb Library; keep host-specific adaptation outside shared components.
- Keep the settings header/footer, localized documentation link, local changelog dialog and copyright © 2022–2026 David Decker – DECKERWEB.
- Preserve existing ASQN_* constants, public filters and legacy status-view IDs where practical. Distinguish a script's own active flag from ancestor blocking and actual execution.
- Never bundle the Advanced Scripts premium source. Read through the adapter; do not implement a second execution engine.
- Build the plugin ZIP and the standalone Advanced Scripts JSON snippet from one source using tools/build.py. Plugin root slug: advanced-scripts-quicknav.
- 1.2.0 is the author-approved stable release based on successfully tested rc3. The user explicitly deferred the broad WordPress integration matrix; focused syntax, packaging and new-function checks are still expected.
- Artwork A (Code Compass) was selected by the user and is included.

- Footer changelogs use the shared deckerweb HTML renderer: version/date headings, categorized list items and subtle badges; escaped source only, no raw readme or pre blocks. See includes/deckerweb-changelog-v1.php.
- QuickNav has no fullscreen block-editor adjustments, preference or WordPress-logo removal as of rc2.
