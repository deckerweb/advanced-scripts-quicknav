# Entwicklung

[English](DEVELOPMENT.md)

PHP 8.0+ und Python 3 genügen für den Paketbau. JavaScript-Bundler und Composer sind nicht erforderlich.

```sh
php tests/smoke.php
python3 tools/build.py
```

`dist/advanced-scripts-quicknav.zip` ist das Plugin-Archiv mit stabilem Verzeichnisnamen. Das versionierte ZIP enthält denselben Inhalt. Die erzeugte `.as.json` ist ein Advanced-Scripts-Import, kein WordPress-Plugin-ZIP. Inaktiv importieren und anschließend mit `plugins_loaded`, Priorität 20 aktivieren. Die erzeugte `.php` ist die lesbare Ein-Datei-Fassung.

Im Plugin-Betrieb werden die Scripts erst nach dem Laden aller aktiven Plugins gelesen. Der Adapter verwendet `get_scripts()` und reicht ausschließlich Metadaten weiter. Die Navigation erhält die Reihenfolge des Hauptplugins und erzeugt daraus begrenzte Ansichten. Sie ruft keine Ausführungsmethoden von Advanced Scripts auf. Favoriten gehören zum aktuellen Benutzer und zur aktuellen Website-ID.

Die gemeinsame Library wählt bei `plugins_loaded` eine Laufzeit mit der höchsten Version. Sie wird nur vom eigentlichen Plugin registriert. Der Updater startet bei `init` und erhält pluginspezifische Paket-, Versions- und Anforderungsprüfungen. Beide Komponenten werden nicht in das eigenständige Snippet eingebaut.

Der GitHub-Workflow baut Artefakte, veröffentlicht aber kein Release. Testkandidaten gehören in Entwürfe oder Vorabversionen. Eine stabile Version folgt nach Freigabe durch den Autor und gezielten Release-Prüfungen; die zurückgestellte Integrationsmatrix dokumentieren. Bei Versionsänderungen alle Sprachfassungen und Versionsangaben prüfen. Der Updater überspringt Entwürfe und Vorabversionen.

`tests/smoke.php` verwendet WordPress-Testdoubles und prüft Navigation, Ordnerstatus, Metadatenumfang, Berechtigungen und Menügrenzen. Der Test belegt keine WordPress-Laufzeitkompatibilität. Durchgeführte Prüfungen und Grenzen stehen in VALIDATION-de.md.

SVG-Quellen und PNG-Exporte liegen in `assets-github/`. Deutsche und englische Banner sind getrennte Dateien. Historische Grafikpfade in `assets/` verwenden die gewählte Gestaltung Code Compass (A).

Übersetzungen werden als PO-, MO- und PHP-Kataloge mitgeliefert. Beide deutschen Sprachvarianten müssen zum POT passen. Fehlende Übersetzungen dürfen nicht als geprüfte Kompatibilität ausgegeben werden.
