# Änderungsverlauf

## 1.2.1-rc1 · 2026-10-01

- **Verbessert:** Änderungsverlauf in den Plugin-Details verwendet native Überschriften, Listen, fette Kategorie-Labels und klickbare Links statt rohem Markdown; zeigt je nach Admin-Sprache die deutsche oder englische Fassung.

- **Verbessert:** Eingebetteten Plugin-Katalog und übersetzte Update-Meldungen aktualisiert; persönliche Einstellungen bleiben erhalten.
- **Behoben:** Prüft Paketidentität und Kompatibilität auch bei WordPress-Sammelupdates.
- **Sonstiges:** Deinstallation bereinigt temporäre Update-Daten und erhält persönliche Einstellungen sowie Advanced-Scripts-Inhalte.


## 1.2.0 · 2026-10-01

- **Neu:** Persönliche Favoriten mit klickbaren Karten, direkter Auswahlvorschau und Filter nach Titel oder Ordner.
- **Neu:** Snippet-Statistik, Script-Links im Ordnerbaum und die Aktion Script hier hinzufügen.
- **Verbessert:** Gemeinsamer deckerweb GitHub Updater V2, deckerweb Library, Einstellungs-Header/-Footer und zugänglicher HTML-Changelog-Dialog.
- **Verbessert:** Code-Compass-Grafiken sowie abgestimmte deutsche/englische Dokumentation, FAQs und Übersetzungen.
- **Behoben:** Safe-Mode-Erkennung, Verarbeitung der Link-Filter, Menü-IDs, Sichtbarkeitsoptionen und der veraltete Toolbar-Callback, der in rc2 einen Fatal Error verursachte.

- **Sonstiges:** Benötigt PHP 8.0 oder neuer; persönliche Einstellungen bleiben erhalten.

## 1.2.0-rc3 · 2026-10-01 (Testkandidat)

- **Behoben:** Verbliebene admin_bar_menu-Registrierung der entfernten Methode remove_adminbar_nodes gelöscht; verhindert einen Fatal Error bei aktivierten Expertenlinks.

## 1.2.0-rc2 · 2026-10-01 (Testkandidat)

- **Neu:** Visuelle Favoritenkarten mit direkten Editor-Links und sofortiger Vorschau der aktuellen Auswahl.
- **Neu:** Kompakte Snippet-Statistik mit Gesamtzahl, Aktiv-/Inaktiv-Zahlen, Ordnern, gespeicherten Favoriten, durch Ordner blockierten aktiven Snippets und Typverteilung.
- **Verbessert:** Formatiert den lokalen Changelog-Dialog als maskiertes HTML mit Versions- und Datumsüberschriften, lesbaren Listen und dezenten Kategorie-Badges nach dem gemeinsamen deckerweb-Footer-Standard.
- **Verbessert:** Behält die gewählte Gestaltung Code Compass (Variante A) bei und aktualisiert die abgestimmten deutschen und englischen Dokumentationen und Übersetzungen.
- **Sonstiges:** Entfernt die Vollbild-Blockeditor-Option, zugehörige Hooks, CSS-Anpassungen und das Entfernen des WordPress-Logos.

## 1.2.0-rc1 · 2026-10-01 (Testkandidat)

- **Neu:** Persönliche Favoriten getrennt nach Benutzer und Website, mit Filter nach Titel oder Ordner auf der Einstellungsseite.
- **Neu:** Direkte Script-Links im Ordnerbaum und die Aktion Script hier anlegen für jeden Ordner.
- **Neu:** Persönliche Einstellungen für Script-Zähler, Entwicklerlinks, Vollbild-Toolbar und begrenzte Menülisten.
- **Verbessert:** Zeigt Durch Ordner deaktiviert an, ohne den eigenen Aktivstatus umzudeuten; ergänzt Typ, Ordnerpfad, Ausführungsort und Hook.
- **Verbessert:** Integriert den gemeinsamen deckerweb GitHub Updater V2, sprachabhängige Update-Grafiken und die deckerweb Library 0.2.0.
- **Verbessert:** Ergänzt den deckerweb-Header und -Footer der Einstellungsseite sowie einen zugänglichen lokalen Changelog-Dialog.
- **Behoben:** Erkennt den über die Advanced-Scripts-Oberfläche aktivierten Safe Mode und berücksichtigt den Vorrang von AS_SAFE_MODE.
- **Behoben:** Übernimmt Link-Filter-Rückgaben, trennt die Sichtbarkeit von Bibliothek und Footer und berücksichtigt leere Sammlungen und eigene Admin-Farbschemata.
- **Sonstiges:** Benötigt WordPress 6.7 und PHP 8.0. Einstellungen und Favoriten bleiben bei Updates erhalten.
- **Sonstiges:** Enthält vorläufig Design A; drei Icon- und Banner-Alternativen werden separat zur Auswahl bereitgestellt.

## 1.1.0 · 2025-04-05

- **Neu:** Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
- **Verbessert:** Unterstützt Installation und Aktualisierung mit Git Updater.
- **Behoben:** Behebt eine PHP-Warnung im Frontend.

## 1.0.0 · 2025-03-24

- **Neu:** Erste öffentliche Version mit Statuslisten, Ordnerlinks, Safe-Mode-Hinweisen, Ressourcenlinks, Entwickleranbindungen und Konstanten.
