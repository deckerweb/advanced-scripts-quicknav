=== Advanced Scripts QuickNav ===
Contributors: deckerweb
Tags: advanced scripts, admin bar, toolbar, code snippets, favorites
Requires at least: 6.7
Tested up to: 7.1.2
Requires PHP: 8.1
Stable tag: 1.3.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Deine Scripts. Einen Klick entfernt.

== Description ==

= Deine Scripts direkt in der Toolbar =

Erreiche Advanced Scripts überall dort, wo die WordPress-Toolbar verfügbar ist, und öffne Scripts direkt im Editor.

= Aktive Scripts, inaktive Scripts und Ordner im Blick =

Navigiere über getrennte Statuslisten und den Ordnerbaum schnell zum passenden Script.

= Deine Favoriten. Dein Schnellzugriff. =

Stelle häufig benötigte Scripts persönlich zusammen, öffne sie über die Toolbar und füge Favoriten direkt an Suchtreffern hinzu oder entferne sie dort.

= Alle Ordner durchsuchen. Gezielt finden. =

**Die Suche, die Advanced Scripts bisher fehlt.** Finde Scripts über alle Ordner hinweg und grenze die Treffer nach Typ und Aktivstatus ein. Weniger Zeit mit Suchen verbringen, schneller am richtigen Script weiterarbeiten. Probiere es gleich aus!

= Ergänzend dazu =

* **Die Script-Sammlung auf einen Blick:** Eine übersichtliche Statistik zeigt aktive und inaktive Snippets, Script-Typen und Ordnerblockaden.
* **So arbeiten, wie es zu dir passt:** Persönliche Anzeigeoptionen, Script-Zähler und begrenzte Menülisten gelten pro Benutzer und Website.

== Installation ==

1. Lade das Release-ZIP hoch: Plugins → Plugin hinzufügen → Plugin hochladen.
2. Aktiviere Advanced Scripts und QuickNav.
3. Öffne Einstellungen → Advanced Scripts QuickNav und wähle Favoriten.
4. Speichere deine Einstellungen und öffne Scripts in der Toolbar.

Deaktiviere ein bisher verwendetes eigenständiges QuickNav-Snippet vor der Plugin-Aktivierung.


== Globale Script-Suche ==

Die Filter **Script-Typ** und **Script-Status** lassen sich miteinander und mit dem Suchtext kombinieren; auch ohne Suchtext sind sie nutzbar. **Aktiv** bezieht sich auf den gespeicherten Status und kann durch Ordner blockierte Scripts enthalten. Der eigene Filter **Aktiv, durch Ordner blockiert** zeigt nur diese Fälle. Über den Stern am Treffer werden persönliche Favoriten sofort gespeichert, maximal 100 pro Benutzer und Website. Die Toolbar übernimmt die Änderung beim nächsten Seitenaufruf.


Unter **Werkzeuge → Advanced Scripts** ergänzt QuickNav oberhalb der Script-Liste das Feld **Alle Scripts durchsuchen**. Es findet Scripts anhand von Titel und Ordnerpfad in allen Ordnern. Mehrere Suchwörter werden gemeinsam berücksichtigt; Groß-/Kleinschreibung und Akzente werden ignoriert. Treffer zeigen Typ, gespeicherten Aktivstatus und Ordnerpfad. Ein Klick öffnet das Script direkt im zugehörigen Ordner.

**Esc** oder **Suche zurücksetzen** stellt die ursprüngliche Liste wieder her. Mit **Pfeil nach unten** geht der Fokus vom Suchfeld zum ersten Treffer. Angezeigt werden bis zu 50 Treffer; bei mehr Treffern lässt sich die Suche weiter eingrenzen. Script-Quellcode wird weder durchsucht noch übertragen. Die Suche verwendet deine bestehenden QuickNav-Berechtigungen und verändert keine Dateien oder Einstellungen von Advanced Scripts.

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

== WordPress-Befehlspalette ==

Nutze die native WordPress-Befehlspalette: Script-Verwaltung öffnen, neues Script anlegen, globale Suche fokussieren, QuickNav-Einstellungen aufrufen oder direkt zu persönlichen Favoriten springen. Die Befehle beachten die QuickNav-Berechtigungen und die aktuelle Website; Favoritenbefehle verwenden das eingestellte Eintragslimit. Sie erscheinen dort, wo WordPress seine native Palette bereitstellt (bei älteren unterstützten Versionen im Blockeditor). Fehlt Advanced Scripts, bleiben die QuickNav-Einstellungen erreichbar. Dateien von Advanced Scripts werden nicht verändert.

== Changelog ==

= 1.3.1 · 2026-10-21 =

* Verbessert: deckerweb Library auf 1.1.0 und deckerweb Updater auf 2.2.0 aktualisiert.
* Behoben: Validierung und Bereinigung der Such- und Favoriteneingaben vereinheitlicht.
* Sonstiges: Benötigt PHP 8.1 oder neuer; WordPress 6.7 bleibt die Mindestversion. Einstellungen und Favoriten bleiben erhalten.

= 1.3.0 · 2026-10-19 =

* Neu: Native WordPress-Befehlspalette mit Script-Verwaltung, neuem Script, Suche, QuickNav-Einstellungen und persönlichen Favoriten.
* Neu: Globale Suche nach Script-Titel und Ordnerpfad direkt in Advanced Scripts, mit Typ-/Statusfiltern und Tastaturbedienung.
* Neu: Persönliche Favoriten direkt an den Suchtreffern hinzufügen oder entfernen, ohne die Einstellungsseite zu öffnen.
* Verbessert: Die Suche fügt sich in die dunkle Advanced-Scripts-Oberfläche ein und zeigt bis zu 50 Treffer mit Gesamtzahl.
* Verbessert: deckerweb Library auf 1.0.0 aktualisiert; QuickNav bleibt bei fehlendem oder deaktiviertem Advanced Scripts aktivierbar.
* Verbessert: Effizientere Verarbeitung der Suchmetadaten und robustes Schließen beziehungsweise Dokumentations-Fallbacks im Changelog-Dialog.
* Behoben: Der lokale Änderungsverlauf folgt der geladenen deutschen Admin-Übersetzung auch bei abweichender Anfrage-Sprache.

= 1.2.1 · 2026-10-09 =

* Verbessert: Die Einstellungsseite zeigt einen dezenten Safe-Mode-Hinweis mit der wirksamen Quelle: AS_SAFE_MODE-Konstante oder Einstellung in Advanced Scripts.

* Behoben: QuickNav bleibt ohne Advanced Scripts aktiv und erreichbar; Abhängigkeitshinweise stehen außerhalb des Headers, und Speichern führt ohne Datenverlust zurück in den Admin.

* Verbessert: Banner verwenden die frei lizenzierte Schrift Inter; SVG-Schriftpfade sichern eine einheitliche Darstellung.

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

= 1.1.0 · 2025-04-05 =

* Neu: Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
* Verbessert: Unterstützt Installation und Aktualisierung mit Git Updater.
* Behoben: Behebt eine PHP-Warnung im Frontend.

== Upgrade Notice ==

= 1.2.0 =
Benötigt PHP 8.0+. Ergänzt Favoriten, Statistik und deckerweb-Updates. Entweder Plugin oder Snippet installieren.
