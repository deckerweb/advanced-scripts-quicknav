=== Advanced Scripts QuickNav ===
Contributors: deckerweb
Tags: advanced scripts, admin bar, toolbar, code snippets, favorites
Requires at least: 6.7
Tested up to: 7.1.2
Requires PHP: 8.0
Stable tag: 1.2.1-rc1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Deine Scripts. Einen Klick entfernt.

== Description ==

- Persönliche Favoriten je Benutzer und Website.
- Script-Links im Ordnerbaum und Script hier anlegen.
- Eigener Aktivstatus plus Hinweise auf deaktivierte Elternordner.
- Typ, Ordnerpfad, Ausführungsort und Hook als Zusatzinfos.
- Visuelle Favoritenkarten, Snippet-Statistik, Anzeigeoptionen, Safe-Mode-Hinweise, SCRIPT_DEBUG und optionale Entwicklerlinks.
- Bestehende Ressourcenlinks und Unterstützung für DevKit Pro, System Dashboard, Variable Inspector und Debug Log Manager bleiben enthalten.

== Installation ==

1. Lade das Release-ZIP hoch: Plugins → Plugin hinzufügen → Plugin hochladen.
2. Aktiviere Advanced Scripts und QuickNav.
3. Öffne Einstellungen → Advanced Scripts QuickNav und wähle Favoriten.
4. Speichere deine Einstellungen und öffne Scripts in der Toolbar.

Deaktiviere ein bisher verwendetes eigenständiges QuickNav-Snippet vor der Plugin-Aktivierung.

== Frequently Asked Questions ==

= Für wen ist QuickNav gedacht? =

Für Administratoren, die häufig mit Advanced Scripts arbeiten und aus der Backend- oder Frontend-Toolbar direkt auf Scripts zugreifen möchten.

= Wo wähle ich Favoriten aus? =

Öffne Einstellungen → Advanced Scripts QuickNav. Sieh klickbare Favoritenkarten und die Snippet-Statistik, filtere nach Titel oder Ordner, wähle Scripts und speichere deine Einstellungen.

= Was bedeutet Durch Ordner deaktiviert? =

Mindestens ein übergeordneter Ordner ist inaktiv. Advanced Scripts überspringt diesen Zweig auch dann, wenn das Script selbst aktiv ist.

= Bedeutet Aktiv, dass ein Script gerade ausgeführt wird? =

Nein. Gemeint ist der gespeicherte Script-Status. Ordnerstatus, Ausführungsort, Bedingungen, Hooks und PHP-Safe-Mode können die Ausführung beeinflussen.

= Wie erkennt QuickNav den Safe Mode? =

Eine definierte Konstante AS_SAFE_MODE hat Vorrang, auch mit false. Andernfalls liest QuickNav die Option advanced-scripts-safemode. Es verändert den Safe Mode nicht.

= Wie funktionieren Updates? =

Das Plugin integriert deckerweb GitHub Updater V2 in das reguläre WordPress-Updatesystem. Er prüft öffentliche stabile GitHub-Releases und stellt das passende Plugin-ZIP bereit. Automatische Updates werden nicht eingeschaltet.

Alle Fragen nach Themen: https://github.com/deckerweb/advanced-scripts-quicknav/wiki/Deutsch

== Changelog ==

= 1.2.1-rc1 · 2026-10-01 =

* Verbessert: Änderungsverlauf in den Plugin-Details verwendet native Überschriften, Listen, fette Kategorie-Labels und klickbare Links statt rohem Markdown; zeigt je nach Admin-Sprache die deutsche oder englische Fassung.

* Verbessert: Eingebetteten Plugin-Katalog und übersetzte Update-Meldungen aktualisiert; persönliche Einstellungen bleiben erhalten.
* Behoben: Prüft Paketidentität und Kompatibilität auch bei WordPress-Sammelupdates.
* Sonstiges: Deinstallation bereinigt temporäre Update-Daten und erhält persönliche Einstellungen sowie Advanced-Scripts-Inhalte.


= 1.2.0 · 2026-10-01 =

* Neu: Persönliche Favoriten mit klickbaren Karten, direkter Auswahlvorschau und Filter nach Titel oder Ordner.
* Neu: Snippet-Statistik, Script-Links im Ordnerbaum und die Aktion Script hier hinzufügen.
* Verbessert: Gemeinsamer deckerweb GitHub Updater V2, deckerweb Library, Einstellungs-Header/-Footer und zugänglicher HTML-Changelog-Dialog.
* Verbessert: Code-Compass-Grafiken sowie abgestimmte deutsche/englische Dokumentation, FAQs und Übersetzungen.
* Behoben: Safe-Mode-Erkennung, Verarbeitung der Link-Filter, Menü-IDs, Sichtbarkeitsoptionen und der veraltete Toolbar-Callback, der in rc2 einen Fatal Error verursachte.

* Sonstiges: Benötigt PHP 8.0 oder neuer; persönliche Einstellungen bleiben erhalten.

= 1.2.0-rc3 · 2026-10-01 (Testkandidat) =

* Behoben: Verbliebene admin_bar_menu-Registrierung der entfernten Methode remove_adminbar_nodes gelöscht; verhindert einen Fatal Error bei aktivierten Expertenlinks.

= 1.2.0-rc2 · 2026-10-01 (Testkandidat) =

* Neu: Visuelle Favoritenkarten mit direkten Editor-Links und sofortiger Vorschau der aktuellen Auswahl.
* Neu: Kompakte Snippet-Statistik mit Gesamtzahl, Aktiv-/Inaktiv-Zahlen, Ordnern, gespeicherten Favoriten, durch Ordner blockierten aktiven Snippets und Typverteilung.
* Verbessert: Formatiert den lokalen Changelog-Dialog als maskiertes HTML mit Versions- und Datumsüberschriften, lesbaren Listen und dezenten Kategorie-Badges nach dem gemeinsamen deckerweb-Footer-Standard.
* Verbessert: Behält die gewählte Gestaltung Code Compass (Variante A) bei und aktualisiert die abgestimmten deutschen und englischen Dokumentationen und Übersetzungen.
* Sonstiges: Entfernt die Vollbild-Blockeditor-Option, zugehörige Hooks, CSS-Anpassungen und das Entfernen des WordPress-Logos.

= 1.2.0-rc1 · 2026-10-01 (Testkandidat) =

* Neu: Persönliche Favoriten getrennt nach Benutzer und Website, mit Filter nach Titel oder Ordner auf der Einstellungsseite.
* Neu: Direkte Script-Links im Ordnerbaum und die Aktion Script hier anlegen für jeden Ordner.
* Neu: Persönliche Einstellungen für Script-Zähler, Entwicklerlinks, Vollbild-Toolbar und begrenzte Menülisten.
* Verbessert: Zeigt Durch Ordner deaktiviert an, ohne den eigenen Aktivstatus umzudeuten; ergänzt Typ, Ordnerpfad, Ausführungsort und Hook.
* Verbessert: Integriert den gemeinsamen deckerweb GitHub Updater V2, sprachabhängige Update-Grafiken und die deckerweb Library 0.2.0.
* Verbessert: Ergänzt den deckerweb-Header und -Footer der Einstellungsseite sowie einen zugänglichen lokalen Changelog-Dialog.
* Behoben: Erkennt den über die Advanced-Scripts-Oberfläche aktivierten Safe Mode und berücksichtigt den Vorrang von AS_SAFE_MODE.
* Behoben: Übernimmt Link-Filter-Rückgaben, trennt die Sichtbarkeit von Bibliothek und Footer und berücksichtigt leere Sammlungen und eigene Admin-Farbschemata.
* Sonstiges: Benötigt WordPress 6.7 und PHP 8.0. Einstellungen und Favoriten bleiben bei Updates erhalten.
* Sonstiges: Enthält vorläufig Design A; drei Icon- und Banner-Alternativen werden separat zur Auswahl bereitgestellt.

= 1.1.0 · 2025-04-05 =

* Neu: Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
* Verbessert: Unterstützt Installation und Aktualisierung mit Git Updater.
* Behoben: Behebt eine PHP-Warnung im Frontend.

= 1.0.0 · 2025-03-24 =

* Neu: Erste öffentliche Version mit Statuslisten, Ordnerlinks, Safe-Mode-Hinweisen, Ressourcenlinks, Entwickleranbindungen und Konstanten.

== Upgrade Notice ==

= 1.2.0 =
Benötigt PHP 8.0+. Ergänzt Favoriten, Statistik und deckerweb-Updates. Entweder Plugin oder Snippet installieren.

= 1.2.0-rc3 =
Behoben: Verhindert den Fatal Error durch den ungültigen Toolbar-Callback. rc2 durch rc3 ersetzen.

= 1.2.0-rc2 =
Testkandidat. Benötigt jetzt PHP 8.0+.
