=== Advanced Scripts QuickNav ===
Contributors: deckerweb
Tags: advanced scripts, admin bar, toolbar, code snippets, favorites
Requires at least: 6.7
Requires PHP: 8.0
Stable tag: 1.2.0
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

Alternativ: Importiere ddw-advanced-scripts-quicknav.as.json in Advanced Scripts. Verwende nur eine Installationsart. Der PHP-Safe-Mode kann das Snippet im Admin blockieren; Updater und deckerweb Library sind nur im Plugin enthalten.

Der Code wurde mit Advanced Scripts 2.6.2 abgeglichen und rc3 vom Plugin-Autor erfolgreich getestet. Die umfassende WordPress-Integrationsmatrix bleibt zurückgestellt. PHP 7.4 wird wegen der gemeinsamen Library nicht mehr unterstützt.

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

= Kann ich stattdessen das Snippet installieren? =

Ja. Importiere das erzeugte JSON in Advanced Scripts und aktiviere es mit dem Hook plugins_loaded. Es enthält Navigation und persönliche Einstellungen, aber keinen Plugin-Updater und keine deckerweb Library. Verwende entweder Plugin oder Snippet.

== Changelog ==

= 1.2.0 · 2026-10-01 =

* Neu: Persönliche Favoriten mit klickbaren Karten, direkter Auswahlvorschau und Filter nach Titel oder Ordner.
* Neu: Snippet-Statistik, Script-Links im Ordnerbaum und die Aktion Script hier hinzufügen.
* Verbessert: Modulare Metadaten-Anbindung an Advanced Scripts, Script-Details, Hinweise auf Ordnersperren und begrenzte Menüs.
* Verbessert: Gemeinsamer deckerweb GitHub Updater V2, deckerweb Library, Einstellungs-Header/-Footer und zugänglicher HTML-Changelog-Dialog.
* Verbessert: Code-Compass-Grafiken sowie abgestimmte deutsche/englische Dokumentation, FAQs und Übersetzungen.
* Behoben: Safe-Mode-Erkennung, Verarbeitung der Link-Filter, Menü-IDs, Sichtbarkeitsoptionen und der veraltete Toolbar-Callback, der in rc2 einen Fatal Error verursachte.
* Sonstiges: Benötigt WordPress 6.7+ und PHP 8.0+. Entfernt Vollbild-Blockeditor-Anpassungen. Plugin und eigenständiges Snippet entstehen aus derselben Quelle.
* Sonstiges: Veröffentlicht nach erfolgreichem Benutzertest von rc3 und gezielten Release-Prüfungen. Die umfassende WordPress-Integrationsmatrix bleibt zurückgestellt.

= 1.2.0-rc3 · 2026-10-01 (Testkandidat) =

* Behoben: Verbliebene admin_bar_menu-Registrierung der entfernten Methode remove_adminbar_nodes gelöscht; verhindert einen Fatal Error bei aktivierten Expertenlinks.
* Verbessert: Regressionstests erkennen jetzt ungültige Action-Callbacks.

= 1.2.0-rc2 · 2026-10-01 (Testkandidat) =

* Neu: Visuelle Favoritenkarten mit direkten Editor-Links und sofortiger Vorschau der aktuellen Auswahl.
* Neu: Kompakte Snippet-Statistik mit Gesamtzahl, Aktiv-/Inaktiv-Zahlen, Ordnern, gespeicherten Favoriten, durch Ordner blockierten aktiven Snippets und Typverteilung.
* Verbessert: Formatiert den lokalen Changelog-Dialog als maskiertes HTML mit Versions- und Datumsüberschriften, lesbaren Listen und dezenten Kategorie-Badges nach dem gemeinsamen deckerweb-Footer-Standard.
* Verbessert: Behält die gewählte Gestaltung Code Compass (Variante A) bei und aktualisiert die abgestimmten deutschen und englischen Dokumentationen und Übersetzungen.
* Sonstiges: Entfernt die Vollbild-Blockeditor-Option, zugehörige Hooks, CSS-Anpassungen und das Entfernen des WordPress-Logos.
* Sonstiges: Vorhandene Favoriten bleiben erhalten. Die umfassende WordPress-Integrationsmatrix bleibt auf später verschoben.

= 1.2.0-rc1 · 2026-10-01 (Testkandidat) =

* Neu: Persönliche Favoriten getrennt nach Benutzer und Website, mit Filter nach Titel oder Ordner auf der Einstellungsseite.
* Neu: Direkte Script-Links im Ordnerbaum und die Aktion Script hier anlegen für jeden Ordner.
* Neu: Persönliche Einstellungen für Script-Zähler, Entwicklerlinks, Vollbild-Toolbar und begrenzte Menülisten.
* Verbessert: Zeigt Durch Ordner deaktiviert an, ohne den eigenen Aktivstatus umzudeuten; ergänzt Typ, Ordnerpfad, Ausführungsort und Hook.
* Verbessert: Trennt Konfiguration, Advanced-Scripts-Adapter, Navigation, Darstellung, persönliche Einstellungen und Hook-Registrierung.
* Verbessert: Integriert den gemeinsamen deckerweb GitHub Updater V2, sprachabhängige Update-Grafiken und die deckerweb Library 0.2.0.
* Verbessert: Ergänzt den deckerweb-Header und -Footer der Einstellungsseite sowie einen zugänglichen lokalen Changelog-Dialog.
* Behoben: Erkennt den über die Advanced-Scripts-Oberfläche aktivierten Safe Mode und berücksichtigt den Vorrang von AS_SAFE_MODE.
* Behoben: Übernimmt Link-Filter-Rückgaben, trennt die Sichtbarkeit von Bibliothek und Footer und berücksichtigt leere Sammlungen und eigene Admin-Farbschemata.
* Behoben: Verwendet gemeinsame Berechtigungsprüfungen für Toolbar und Vollbildintegration; schützt Einstellungen durch POST, Berechtigungen und Nonce.
* Sonstiges: Benötigt PHP 8.0+, behält WordPress 6.7+ bei und liefert abgestimmte deutsche und englische Readmes, FAQ, Wiki-Quellen und vollständige Changelogs.
* Sonstiges: Enthält vorläufig Design A; drei Icon- und Banner-Alternativen werden separat zur Auswahl bereitgestellt.
* Sonstiges: Erzeugt das eigenständige Advanced-Scripts-JSON-Snippet aus derselben Quelle. Updater und deckerweb Library sind der Plugin-Installation vorbehalten.
* Sonstiges: Dies ist ein lokaler Testkandidat. Die umfassende WordPress-Integrationsprüfung und die öffentliche Veröffentlichung stehen noch aus.

= 1.1.0 · 2025-04-05 =

* Neu: Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
* Verbessert: Unterstützt Installation und Aktualisierung mit Git Updater.
* Behoben: Behebt eine PHP-Warnung im Frontend.

= 1.0.0 · 2025-03-24 =

* Neu: Erste öffentliche Version mit Statuslisten, Ordnerlinks, Safe-Mode-Hinweisen, Ressourcenlinks, Entwickleranbindungen und Konstanten.

= 0.5.0 · 2025-03-23 =

* Sonstiges: Interne Testversion.

= 0.0.0 · 2025-03-23 =

* Sonstiges: Entwicklungsbeginn.

== Upgrade Notice ==

= 1.2.0 =
Benötigt PHP 8.0+. Ergänzt Favoriten, Statistik und deckerweb-Updates. Entweder Plugin oder Snippet installieren.


= 1.2.0-rc3 =
Behoben: Verhindert den Fatal Error durch den ungültigen Toolbar-Callback. rc2 durch rc3 ersetzen.


= 1.2.0-rc2 =
Testkandidat. Benötigt jetzt PHP 8.0+. Umfassende Integrationsprüfung folgt später.
