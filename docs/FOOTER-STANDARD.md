# Shared deckerweb footer standard

[Deutsch](FOOTER-STANDARD-de.md)

The user selected a formatted HTML changelog as the standard for all plugin footers. The persistent rule is also stored in Documents/Codex/AGENTS.md. Existing other plugins will adopt it when next edited.

Reference: `includes/deckerweb-changelog-v1.php`, `Deckerweb_Changelog_Renderer_V1`. The shared class guards against duplicate declarations across plugins; the QuickNav snippet embeds the same source.

Source remains the local translated changelog. Presentation uses version/date headings, categorized list entries and subtle New/Improved/Fixed/Misc badges (translated in German). No readme markers or raw text blocks appear in the modal. Source HTML is always escaped; only fixed structure and escaped inline code spans become HTML.

Modal: accessible title, visible close action, Escape, focus restoration, scrollable content, mobile layout and an ordinary changelog link as a no-JavaScript fallback.
