# Gemeinsamer deckerweb-Footer-Standard

[English](FOOTER-STANDARD.md)

Der Benutzer hat den formatierten HTML-Änderungsverlauf als Standard für alle Plugin-Footer festgelegt. Die dauerhafte Vorgabe steht zusätzlich in Documents/Codex/AGENTS.md. Bereits bestehende andere Plugins werden damit bei ihrer nächsten Bearbeitung aktualisiert.

Referenz: `includes/deckerweb-changelog-v1.php`, Klasse `Deckerweb_Changelog_Renderer_V1`. Die gemeinsame Klasse ist gegen doppelte Deklarationen beim gleichzeitigen Einsatz mehrerer Plugins geschützt. Im QuickNav-Snippet wird sie aus derselben Quelle eingebettet.

Die Datenquelle bleibt der lokale, übersetzte Changelog. Die Darstellung enthält Versionsüberschrift, Datum, kategorisierte Listeneinträge und dezente Badges (Neu, Verbessert, Behoben, Sonstiges beziehungsweise New, Improved, Fixed, Misc). Readme-Marker und rohe Textblöcke erscheinen nicht im Modal. Quell-HTML wird immer maskiert; nur feste Struktur und maskierte Inline-Code-Spannen werden als HTML ausgegeben.

Modal: zugänglicher Titel, sichtbare Schließen-Schaltfläche, Escape, Fokusrückgabe, scrollbarer Inhalt, mobile Darstellung und gewöhnlicher Changelog-Link als Fallback ohne JavaScript.
