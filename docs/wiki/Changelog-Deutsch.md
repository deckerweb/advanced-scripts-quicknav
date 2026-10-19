# Änderungsverlauf

## 1.3.0 · 2026-10-19

- **Neu:** Native WordPress-Befehlspalette mit Script-Verwaltung, neuem Script, Suche, QuickNav-Einstellungen und persönlichen Favoriten.
- **Neu:** Globale Suche nach Script-Titel und Ordnerpfad direkt in Advanced Scripts, mit Typ-/Statusfiltern und Tastaturbedienung.
- **Neu:** Persönliche Favoriten direkt an den Suchtreffern hinzufügen oder entfernen, ohne die Einstellungsseite zu öffnen.
- **Verbessert:** Die Suche fügt sich in die dunkle Advanced-Scripts-Oberfläche ein und zeigt bis zu 50 Treffer mit Gesamtzahl.
- **Verbessert:** deckerweb Library auf 1.0.0 aktualisiert; QuickNav bleibt bei fehlendem oder deaktiviertem Advanced Scripts aktivierbar.
- **Verbessert:** Effizientere Verarbeitung der Suchmetadaten und robustes Schließen beziehungsweise Dokumentations-Fallbacks im Changelog-Dialog.
- **Behoben:** Der lokale Änderungsverlauf folgt der geladenen deutschen Admin-Übersetzung auch bei abweichender Anfrage-Sprache.

## 1.2.1 · 2026-10-09

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

## 1.1.0 · 2025-04-05

- **Neu:** Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
- **Verbessert:** Unterstützt Installation und Aktualisierung mit Git Updater.
- **Behoben:** Behebt eine PHP-Warnung im Frontend.

## 1.0.0 · 2025-03-24

- **Neu:** Erste öffentliche Version mit Statuslisten, Ordnerlinks, Safe-Mode-Hinweisen, Ressourcenlinks, Entwickleranbindungen und Konstanten.
