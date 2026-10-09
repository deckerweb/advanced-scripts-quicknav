# Advanced Scripts QuickNav

![Advanced Scripts QuickNav](assets-github/github-banner-de.png)

**Deine Scripts. Einen Klick entfernt.** Greife direkt aus der WordPress-Toolbar auf persönliche Favoriten, Statuslisten und Scripts im Ordnerbaum zu. Ein schlankes Add-on für Advanced Scripts.

**Version:** 1.2.1-rc1 · **WordPress:** 6.7+ · **PHP:** 8.0+ · **GPL-2.0-or-later**

[English](README.md) · [Anleitung und vollständige FAQ](docs/wiki/Deutsch.md) · [GitHub](https://github.com/deckerweb/advanced-scripts-quicknav)

[Plugin-ZIP herunterladen](https://github.com/deckerweb/advanced-scripts-quicknav/releases/latest/download/advanced-scripts-quicknav.zip) · [Releases](https://github.com/deckerweb/advanced-scripts-quicknav/releases)

## Inhaltsverzeichnis

- [Auf einen Blick](#section-0)
- [Installation](#section-1)
- [Updates und Library](#section-2)
- [Konfiguration](#section-3)
- [Häufige Fragen](#section-4)
- [Changelog](#section-5)
- [Über das Plugin](#section-6)

<a id="section-0"></a>

## Auf einen Blick

- Persönliche Favoriten je Benutzer und Website.
- Script-Links im Ordnerbaum und Script hier anlegen.
- Eigener Aktivstatus plus Hinweise auf deaktivierte Elternordner.
- Typ, Ordnerpfad, Ausführungsort und Hook als Zusatzinfos.
- Visuelle Favoritenkarten, Snippet-Statistik, Anzeigeoptionen, Safe-Mode-Hinweise, SCRIPT_DEBUG und optionale Entwicklerlinks.
- Bestehende Ressourcenlinks und Unterstützung für DevKit Pro, System Dashboard, Variable Inspector und Debug Log Manager bleiben enthalten.

<a id="section-1"></a>

## Installation

1. Lade das Release-ZIP hoch: Plugins → Plugin hinzufügen → Plugin hochladen.
2. Aktiviere Advanced Scripts und QuickNav.
3. Öffne Einstellungen → Advanced Scripts QuickNav und wähle Favoriten.
4. Speichere deine Einstellungen und öffne Scripts in der Toolbar.

Deaktiviere ein bisher verwendetes eigenständiges QuickNav-Snippet vor der Plugin-Aktivierung.

<a id="section-2"></a>

## Updates und Library

Der deckerweb GitHub Updater V2 zeigt öffentliche stabile Releases im regulären WordPress-Updatesystem an. Für die Erstinstallation das Release-ZIP hochladen. Die deckerweb Library ergänzt den deckerweb-Tab im Plugin-Installer und eigene gemeinsame Einstellungen. Sie ist unabhängig von Snippets finden.

Header und Footer der Einstellungsseite zeigen Icon, Version, sprachabhängige Dokumentation, den als HTML formatierten lokalen Changelog, Plugin-Website und © 2025–2026 David Decker – DECKERWEB.

<a id="section-3"></a>

## Konfiguration

Konstanten haben Vorrang vor persönlichen Anzeigeoptionen. Benutzer benötigen manage_options und die konfigurierte QuickNav-Berechtigung. Der Ordnerbaum enthält maximal acht Ebenen und standardmäßig 40 Script-/Ordnereinträge insgesamt. Favoriten und jede Statusliste sind ebenfalls auf je 40 Einträge begrenzt. Alle anzeigen öffnet Advanced Scripts.

Beispiele, bei Bedarf einzeln verwenden:

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

ASQN_ICON akzeptiert blue oder remix; ohne Konstante erscheint das QuickNav-Symbol. ASQN_DISABLE_LIBRARY steuert nur Ressourcenlinks. Die deckerweb Library hat eigene Einstellungen.

<a id="section-4"></a>

## Häufige Fragen

### Für wen ist QuickNav gedacht?

Für Administratoren, die häufig mit Advanced Scripts arbeiten und aus der Backend- oder Frontend-Toolbar direkt auf Scripts zugreifen möchten.

### Wo wähle ich Favoriten aus?

Öffne Einstellungen → Advanced Scripts QuickNav. Sieh klickbare Favoritenkarten und die Snippet-Statistik, filtere nach Titel oder Ordner, wähle Scripts und speichere deine Einstellungen.

### Was bedeutet Durch Ordner deaktiviert?

Mindestens ein übergeordneter Ordner ist inaktiv. Advanced Scripts überspringt diesen Zweig auch dann, wenn das Script selbst aktiv ist.

### Bedeutet Aktiv, dass ein Script gerade ausgeführt wird?

Nein. Gemeint ist der gespeicherte Script-Status. Ordnerstatus, Ausführungsort, Bedingungen, Hooks und PHP-Safe-Mode können die Ausführung beeinflussen.

### Wie erkennt QuickNav den Safe Mode?

Eine definierte Konstante AS_SAFE_MODE hat Vorrang, auch mit false. Andernfalls liest QuickNav die Option advanced-scripts-safemode. Es verändert den Safe Mode nicht.

### Wie funktionieren Updates?

Das Plugin integriert deckerweb GitHub Updater V2 in das reguläre WordPress-Updatesystem. Er prüft öffentliche stabile GitHub-Releases und stellt das passende Plugin-ZIP bereit. Automatische Updates werden nicht eingeschaltet.

[Alle Fragen nach Themen](docs/wiki/Deutsch.md)

<a id="section-5"></a>

## Changelog


### 1.2.1-rc1 · 2026-10-01

- **Verbessert:** Änderungsverlauf in den Plugin-Details verwendet native Überschriften, Listen, fette Kategorie-Labels und klickbare Links statt rohem Markdown; zeigt je nach Admin-Sprache die deutsche oder englische Fassung.

- **Verbessert:** Eingebetteten Plugin-Katalog und übersetzte Update-Meldungen aktualisiert; persönliche Einstellungen bleiben erhalten.
- **Behoben:** Prüft Paketidentität und Kompatibilität auch bei WordPress-Sammelupdates.
- **Sonstiges:** Deinstallation bereinigt temporäre Update-Daten und erhält persönliche Einstellungen sowie Advanced-Scripts-Inhalte.


### 1.2.0 · 2026-10-01

- **Neu:** Persönliche Favoriten mit klickbaren Karten, direkter Auswahlvorschau und Filter nach Titel oder Ordner.
- **Neu:** Snippet-Statistik, Script-Links im Ordnerbaum und die Aktion Script hier hinzufügen.
- **Verbessert:** Gemeinsamer deckerweb GitHub Updater V2, deckerweb Library, Einstellungs-Header/-Footer und zugänglicher HTML-Changelog-Dialog.
- **Verbessert:** Code-Compass-Grafiken sowie abgestimmte deutsche/englische Dokumentation, FAQs und Übersetzungen.
- **Behoben:** Safe-Mode-Erkennung, Verarbeitung der Link-Filter, Menü-IDs, Sichtbarkeitsoptionen und der veraltete Toolbar-Callback, der in rc2 einen Fatal Error verursachte.

- **Sonstiges:** Benötigt PHP 8.0 oder neuer; persönliche Einstellungen bleiben erhalten.

### 1.2.0-rc3 · 2026-10-01 (Testkandidat)

- **Behoben:** Verbliebene admin_bar_menu-Registrierung der entfernten Methode remove_adminbar_nodes gelöscht; verhindert einen Fatal Error bei aktivierten Expertenlinks.

### 1.2.0-rc2 · 2026-10-01 (Testkandidat)

- **Neu:** Visuelle Favoritenkarten mit direkten Editor-Links und sofortiger Vorschau der aktuellen Auswahl.
- **Neu:** Kompakte Snippet-Statistik mit Gesamtzahl, Aktiv-/Inaktiv-Zahlen, Ordnern, gespeicherten Favoriten, durch Ordner blockierten aktiven Snippets und Typverteilung.
- **Verbessert:** Formatiert den lokalen Changelog-Dialog als maskiertes HTML mit Versions- und Datumsüberschriften, lesbaren Listen und dezenten Kategorie-Badges nach dem gemeinsamen deckerweb-Footer-Standard.
- **Verbessert:** Behält die gewählte Gestaltung Code Compass (Variante A) bei und aktualisiert die abgestimmten deutschen und englischen Dokumentationen und Übersetzungen.
- **Sonstiges:** Entfernt die Vollbild-Blockeditor-Option, zugehörige Hooks, CSS-Anpassungen und das Entfernen des WordPress-Logos.

### 1.2.0-rc1 · 2026-10-01 (Testkandidat)

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

### 1.1.0 · 2025-04-05

- **Neu:** Beschränkt QuickNav optional mit ASQN_ENABLED_USERS auf festgelegte Benutzer-IDs.
- **Verbessert:** Unterstützt Installation und Aktualisierung mit Git Updater.
- **Behoben:** Behebt eine PHP-Warnung im Frontend.

### 1.0.0 · 2025-03-24

- **Neu:** Erste öffentliche Version mit Statuslisten, Ordnerlinks, Safe-Mode-Hinweisen, Ressourcenlinks, Entwickleranbindungen und Konstanten.


<a id="section-6"></a>

## Über das Plugin

Ein unabhängiges Add-on von David Decker – DECKERWEB. Advanced Scripts wird von Clean Plugins entwickelt. QuickNav enthält keinen Premium-Quellcode von Advanced Scripts.

[Projekt unterstützen](https://ko-fi.com/deckerweb) · [Newsletter](https://eepurl.com/gbAUUn) · [Advanced Scripts kaufen (Affiliate-Link)](https://r.freemius.com/6334/142255/)

GPL v2 oder höher. Neue QuickNav-Grafiken: © 2026 David Decker – DECKERWEB. Vorhandene Drittanbieter-Icons behalten ihre Urheberzuordnung.

## Hilfe und Sicherheit

[Issues](https://github.com/deckerweb/advanced-scripts-quicknav/issues) · [Vertrauliche Sicherheitsmeldungen](SECURITY-de.md)

[Komponenten, Lizenzen und Daten](docs/DATA-de.md)
