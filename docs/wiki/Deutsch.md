# Advanced Scripts QuickNav · Deutsch

[English](https://github.com/deckerweb/advanced-scripts-quicknav/wiki/English) · [README](../../README-de.md)

## Schnellstart

1. Lade das Release-ZIP hoch: Plugins → Plugin hinzufügen → Plugin hochladen.
2. Aktiviere Advanced Scripts und QuickNav.
3. Öffne Einstellungen → Advanced Scripts QuickNav und wähle Favoriten.
4. Speichere deine Einstellungen und öffne Scripts in der Toolbar.

Alternativ: Importiere ddw-advanced-scripts-quicknav.as.json in Advanced Scripts. Verwende nur eine Installationsart. Der PHP-Safe-Mode kann das Snippet im Admin blockieren; Updater und deckerweb Library sind nur im Plugin enthalten.

Der Code wurde mit Advanced Scripts 2.6.2 abgeglichen und rc3 vom Plugin-Autor erfolgreich getestet. Die umfassende WordPress-Integrationsmatrix bleibt zurückgestellt. PHP 7.4 wird wegen der gemeinsamen Library nicht mehr unterstützt.

## Anzeige und Konfiguration

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

## FAQ

### Für wen ist QuickNav gedacht?

Für Administratoren, die häufig mit Advanced Scripts arbeiten und aus der Backend- oder Frontend-Toolbar direkt auf Scripts zugreifen möchten.

### Ersetzt QuickNav Advanced Scripts?

Nein. Advanced Scripts verwaltet weiterhin Code, Ausführung, Bedingungen, Import und Export. QuickNav ergänzt die Navigation.

### Welche Voraussetzungen gelten?

WordPress 6.7+, PHP 8.0+ und eine aktive Advanced-Scripts-Installation. Der Quellcode wurde mit der bereitgestellten Version 2.6.2 abgeglichen; die Laufzeitkompatibilität wurde noch nicht umfassend geprüft.

### Wo wähle ich Favoriten aus?

Öffne Einstellungen → Advanced Scripts QuickNav. Sieh klickbare Favoritenkarten und die Snippet-Statistik, filtere nach Titel oder Ordner, wähle Scripts und speichere deine Einstellungen.

### Werden Favoriten geteilt?

Nein. Favoriten und Anzeigeoptionen gehören zu deinem Benutzerkonto auf der aktuellen Website. Andere Benutzer und Multisite-Unterwebsites haben eigene Auswahlen.

### Was bedeutet Durch Ordner deaktiviert?

Mindestens ein übergeordneter Ordner ist inaktiv. Advanced Scripts überspringt diesen Zweig auch dann, wenn das Script selbst aktiv ist.

### Bedeutet Aktiv, dass ein Script gerade ausgeführt wird?

Nein. Gemeint ist der gespeicherte Script-Status. Ordnerstatus, Ausführungsort, Bedingungen, Hooks und PHP-Safe-Mode können die Ausführung beeinflussen.

### Wie erkennt QuickNav den Safe Mode?

Eine definierte Konstante AS_SAFE_MODE hat Vorrang, auch mit false. Andernfalls liest QuickNav die Option advanced-scripts-safemode. Es verändert den Safe Mode nicht.

### Stoppt Safe Mode alle Frontend-Scripts?

Nein. Im bereitgestellten Quellcode von Advanced Scripts 2.6.2 schützt der PHP-Safe-Mode Admin und Login; er ist kein allgemeiner Ausschalter für Frontend-Scripts.

### Kann ich ein Script direkt in einem Ordner anlegen?

Ja. Script hier anlegen öffnet den Advanced-Scripts-Editor mit dem gewählten übergeordneten Ordner. Erst das Speichern im Editor legt das Script an.

### Warum fehlen manche Einträge?

Standardmäßig erscheinen bis zu 40 Einträge je Status- und Favoritenliste sowie insgesamt 40 Script- und Ordnereinträge im Ordnerbaum. Wähle 10–200 in den Einstellungen. Alle anzeigen öffnet Advanced Scripts. Der Baum ist auf acht Ebenen begrenzt.

### Kann ich die Toolbar durchsuchen?

In dieser Version noch nicht. Auf der Einstellungsseite lässt sich die Favoritenauswahl nach Titel oder Ordner filtern. Eine tastaturbedienbare Toolbar-Suche ist separat geplant.

### Funktionieren vorhandene Konstanten weiterhin?

Ja. ASQN_VIEW_CAPABILITY, ASQN_ENABLED_USERS, ASQN_NAME_IN_ADMINBAR, ASQN_COUNTER, ASQN_ICON, ASQN_DISABLE_LIBRARY, ASQN_DISABLE_FOOTER und ASQN_EXPERT_MODE bleiben unterstützt. Anzeige-Konstanten haben Vorrang vor persönlichen Einstellungen.

### Welche Berechtigungen werden benötigt?

Der Benutzer benötigt manage_options und die QuickNav-Berechtigung, standardmäßig activate_plugins. ASQN_ENABLED_USERS kann die Sichtbarkeit weiter beschränken. Eine niedrigere QuickNav-Berechtigung gewährt keinen zusätzlichen Zugriff auf Advanced Scripts.

### Wie funktionieren Updates?

Das Plugin integriert deckerweb GitHub Updater V2 in das reguläre WordPress-Updatesystem. Er prüft öffentliche stabile GitHub-Releases und stellt das passende Plugin-ZIP bereit. Automatische Updates werden nicht eingeschaltet.

### Was ist die deckerweb Library?

Ein eingebetteter Katalog unter Plugins → Plugin hinzufügen → deckerweb mit gemeinsamen Einstellungen. Er ist unabhängig von den Ressourcenlinks unter Snippets finden. Mehrere kompatible deckerweb-Plugins teilen sich eine Laufzeit.

### Sendet QuickNav meine Scripts an andere Dienste?

QuickNav-Navigation und Favoriten senden keine Script-Daten an andere Dienste. Der Updater ruft Release-Metadaten von GitHub ab. Library-Aktionen können freigegebene GitHub-Downloads abrufen; ihr optionaler Online-Katalog hat eigene Einstellungen.

### Kann ich stattdessen das Snippet installieren?

Ja. Importiere das erzeugte JSON in Advanced Scripts und aktiviere es mit dem Hook plugins_loaded. Es enthält Navigation und persönliche Einstellungen, aber keinen Plugin-Updater und keine deckerweb Library. Verwende entweder Plugin oder Snippet.

### Was passiert mit dem Snippet im Safe Mode?

Advanced Scripts kann die Ausführung des QuickNav-PHP-Snippets im Admin verhindern. Dann kann es auch keinen eigenen Safe-Mode-Hinweis anzeigen. Für zuverlässige Admin-Diagnose empfiehlt sich die Plugin-Installation.

### Was passiert mit gelöschten Favoriten?

Sie verschwinden automatisch aus der angezeigten Auswahl. Beim Speichern werden veraltete IDs entfernt. Gleichnamige Scripts bleiben durch ihre Term-IDs unterscheidbar.

### Kann ich Scripts in QuickNav ein- und ausschalten?

Nein. Die Links öffnen den nativen Editor. Aktivierung, Löschung und Ausführung bleiben bei Advanced Scripts.

### Wo werden meine Einstellungen gespeichert?

In Benutzermetadaten mit der ID der aktuellen Website. Eine Deaktivierung erhält sie. Diese Version enthält keine automatische Bereinigung bei Deinstallation.

### Sind Übersetzungen enthalten?

Deutsche informelle und formelle Kataloge werden mitgeliefert. Das Plugin verwendet die WordPress-Benutzer- oder Websitesprache. Das eigenständige Snippet enthält seinen deutschen Katalog.

### Was zählt die Statistik?

Alle Snippets zählt ohne Ordner. Aktiv und inaktiv beziehen sich auf den gespeicherten Status. Durch einen inaktiven Elternordner blockierte aktive Snippets sind eine zusätzliche Teilmenge der aktiven Snippets. Favoriten zählt die gespeicherte Auswahl unabhängig vom Toolbar-Limit. Typzahlen enthalten aktive und inaktive Snippets.

### Wie funktionieren die Favoritenkarten?

Die Karten öffnen direkt den nativen Editor. Beim An- und Abwählen aktualisiert sich die Kartenvorschau sofort; Meine Einstellungen speichern übernimmt die Auswahl in die Toolbar. Ohne JavaScript bleiben gespeicherte Favoritenkarten nutzbar.

### Was wurde aus der Vollbild-Toolbar-Option?

QuickNav passt den Vollbild-Blockeditor nicht mehr an, ergänzt kein Vollbild-CSS und entfernt das WordPress-Logo nicht mehr. Alte ASQN_FULLSCREEN-Definitionen und gespeicherte Vollbildoptionen haben keine Wirkung mehr auf QuickNav.

### Ist diese Version für den Produktiveinsatz fertig?

Version 1.2.0 basiert auf der erfolgreich getesteten rc3. Syntax und gezielte Release-Prüfungen sind bestanden; die umfassende WordPress-Integrationsmatrix folgt wie vereinbart später.

## Updates und Library

Der deckerweb GitHub Updater V2 zeigt öffentliche stabile Releases im regulären WordPress-Updatesystem an. Für die Erstinstallation das Release-ZIP hochladen. Die deckerweb Library ergänzt den deckerweb-Tab im Plugin-Installer und eigene gemeinsame Einstellungen. Sie ist unabhängig von Snippets finden.

Header und Footer der Einstellungsseite zeigen Icon, Version, sprachabhängige Dokumentation, den als HTML formatierten lokalen Changelog, Plugin-Website und © 2022–2026 David Decker – DECKERWEB.

## Changelog

[Vollständiger Änderungsverlauf](../CHANGELOG-de.md)
