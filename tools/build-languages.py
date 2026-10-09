from pathlib import Path
import gettext,re,json,struct
p=Path(__file__).resolve().parents[1];lang=p/'languages'
old={}
with (lang/'advanced-scripts-quicknav-de_DE.mo').open('rb') as f:old=gettext.GNUTranslations(f)._catalog
with (lang/'advanced-scripts-quicknav-de_DE_formal.mo').open('rb') as f:old_formal=gettext.GNUTranslations(f)._catalog
strings=set()
for file in [p/'advanced-scripts-quicknav.php', *p.glob('includes/class-asqn-*.php'), p/'includes/updater-translations.php']:
 s=file.read_text()
 for m in re.finditer(r"(?:__|esc_html__|esc_attr__)\(\s*'((?:\\.|[^'\\])*)'\s*,\s*'advanced-scripts-quicknav'",s):strings.add(m[1].replace("\\'","'"))
 for m in re.finditer(r"(?:_x|esc_html_x)\(\s*'((?:\\.|[^'\\])*)'\s*,\s*'((?:\\.|[^'\\])*)'\s*,\s*'advanced-scripts-quicknav'",s):strings.add(m[2]+'\x04'+m[1])
translations={
'Please select no more than 100 favorites.':'Bitte wähle höchstens 100 Favoriten aus.','The update filesystem is unavailable. Please try again.':'Das Dateisystem für Updates ist nicht verfügbar. Bitte versuche es erneut.',
'The GitHub package does not contain the Advanced Scripts QuickNav plugin file.':'Das GitHub-Paket enthält die Plugin-Datei von Advanced Scripts QuickNav nicht.',
'The GitHub package could not be prepared. The installed version has been kept.':'Das GitHub-Paket konnte nicht vorbereitet werden. Die installierte Version bleibt erhalten.',
'The update package could not be checked.':'Das Update-Paket konnte nicht geprüft werden.',
'The package identity or version does not match a newer Advanced Scripts QuickNav release.':'Die Paketidentität oder Version entspricht keinem neueren Advanced-Scripts-QuickNav-Release.',
'The package version differs from the offered update. Please check for updates again.':'Die Paketversion weicht vom angebotenen Update ab. Bitte prüfe erneut auf Updates.',
'The update package has missing or invalid WordPress/PHP requirements.':'Die WordPress- oder PHP-Voraussetzungen im Update-Paket fehlen oder sind ungültig.',
'This release requires a newer WordPress or PHP version. The installed version has been kept.':'Dieses Release benötigt eine neuere WordPress- oder PHP-Version. Die installierte Version bleibt erhalten.',
'SCSS partial':'SCSS-Teil','active':'aktiv','All snippets':'Alle Snippets','Active':'Aktiv','Inactive':'Inaktiv','Folders':'Ordner','Active, blocked by folder':'Aktiv, durch Ordner blockiert','Saved favorites':'Gespeicherte Favoriten','Your snippet overview':'Deine Snippet-Übersicht','Snippet types':'Snippet-Typen',
'Active and inactive refer to saved snippet status. Folder blocks are counted within active snippets; conditions and execution location can also affect execution.':'Aktiv und inaktiv beziehen sich auf den gespeicherten Snippet-Status. Ordnerblockaden zählen innerhalb der aktiven Snippets; auch Bedingungen und Ausführungsort können die Ausführung beeinflussen.',
'Choose favorites':'Favoriten auswählen','Your favorites':'Deine Favoriten',
'Open a favorite directly from its card. This preview follows your selection; save to update the toolbar.':'Öffne einen Favoriten direkt über seine Karte. Die Vorschau folgt deiner Auswahl; speichere sie zum Aktualisieren der Toolbar.',
'No favorites selected yet. Choose snippets below to build your quick-access list.':'Noch keine Favoriten ausgewählt. Wähle unten Snippets für deinen Schnellzugriff aus.',
'Folder':'Ordner','Code':'Code','administration':'Adminbereich','shortcode':'Shortcode','everywhere':'überall','front-end':'Frontend',
'Disabled by folder':'Durch Ordner deaktiviert','Untitled script':'Script ohne Titel','View all in Advanced Scripts…':'Alle in Advanced Scripts anzeigen…','★ Favorites':'★ Favoriten','Manage favorites…':'Favoriten verwalten…','Add script here…':'Script hier anlegen…',
'Permission denied.':'Keine Berechtigung.','Activate Advanced Scripts first.':'Aktiviere zuerst Advanced Scripts.','Your scripts. One click away.':'Deine Scripts. Einen Klick entfernt.','Your preferences have been saved.':'Deine Einstellungen wurden gespeichert.','Activate Advanced Scripts first. Your saved preferences are kept.':'Aktiviere zuerst Advanced Scripts. Deine gespeicherten Einstellungen bleiben erhalten.',
'These preferences apply only to your account on this website. Constants in wp-config.php take precedence over display preferences.':'Diese Einstellungen gelten nur für dein Konto auf dieser Website. Konstanten in wp-config.php haben Vorrang vor Anzeigeoptionen.',
'Display preferences':'Anzeigeoptionen','Show script count in the toolbar':'Script-Zähler in der Toolbar anzeigen','Show developer links':'Entwicklerlinks anzeigen','Show toolbar in the fullscreen block editor':'Toolbar im Vollbild-Blockeditor anzeigen','Menu entry limit':'Maximale Menüeinträge',
'Applies to each status list, favorites, and the folder tree. Additional entries remain accessible in Advanced Scripts.':'Gilt für jede Statusliste, die Favoriten und den Ordnerbaum. Weitere Einträge bleiben über Advanced Scripts erreichbar.',
'Personal favorites':'Persönliche Favoriten','Select up to 100 scripts. Deleted scripts disappear from the list automatically. The limit above controls how many favorites appear in the toolbar.':'Wähle bis zu 100 Scripts. Gelöschte Scripts verschwinden automatisch aus der Liste. Das obige Limit bestimmt, wie viele Favoriten in der Toolbar erscheinen.',
'Filter by title or folder':'Nach Titel oder Ordner filtern','No scripts yet. Create your first script in Advanced Scripts.':'Noch keine Scripts. Lege dein erstes Script in Advanced Scripts an.','Save my preferences':'Meine Einstellungen speichern',
'Plugin information':'Plugin-Informationen','Version':'Version','Changelog':'Änderungsverlauf','Documentation':'Dokumentation','Plugin website':'Plugin-Website','Close':'Schließen','QuickNav preferences':'QuickNav-Einstellungen','Plugin version':'Plugin-Version','Not active':'Nicht aktiv','Safe Mode':'Safe Mode','Enabled':'Aktiviert','Disabled':'Deaktiviert',
'Reach your scripts through favorites, status lists and folders in the WordPress toolbar.':'Erreiche deine Scripts über Favoriten, Statuslisten und Ordner in der WordPress-Toolbar.','Preferences':'Einstellungen','Support the project':'Projekt unterstützen','Newsletter':'Newsletter','CP Facebook Group (official)':'CP-Facebook-Gruppe (offiziell)'
}
translations.update({'Private mode must be boolean.': 'Der Private-Modus muss ein boolescher Wert sein.', 'Invalid authentication provider.': 'Ungültiger Authentifizierungsanbieter.', 'The plugin must be installed in a stable slug directory.': 'Das Plugin muss in einem Verzeichnis mit einem festen Slug installiert sein.', 'Invalid GitHub repository URL.': 'Ungültige GitHub-Repository-URL.', 'The private update could not be authorized. Check the repository credentials and refresh updates.': 'Das private Update konnte nicht autorisiert werden. Prüfe die Repository-Zugangsdaten und suche erneut nach Updates.', 'Could not create the update download file.': 'Die Datei für den Update-Download konnte nicht erstellt werden.', 'The private update download failed. Check credentials and try again.': 'Der Download des privaten Updates ist fehlgeschlagen. Prüfe die Zugangsdaten und versuche es erneut.', 'Could not access the update filesystem.': 'Auf das Dateisystem für das Update konnte nicht zugegriffen werden.', 'GitHub release does not contain the plugin main file.': 'Der GitHub-Release enthält nicht die Hauptdatei des Plugins.', 'Could not prepare the GitHub release package.': 'Das GitHub-Release-Paket konnte nicht vorbereitet werden.', 'See the release on GitHub.': 'Sieh dir den Release auf GitHub an.', 'Settings': 'Einstellungen', 'Advanced Scripts QuickNav requires PHP 8.0 or newer.': 'Advanced Scripts QuickNav benötigt PHP 8.0 oder neuer.', 'QuickNav updates are paused because another plugin loaded an older deckerweb Updater. Update that plugin to restore QuickNav updates.': 'QuickNav-Updates pausieren, weil ein anderes Plugin einen älteren deckerweb Updater geladen hat. Aktualisiere dieses Plugin, um QuickNav-Updates wieder zu ermöglichen.'})
formal_overrides={'Private mode must be boolean.': 'Der Private-Modus muss ein boolescher Wert sein.', 'Invalid authentication provider.': 'Ungültiger Authentifizierungsanbieter.', 'The plugin must be installed in a stable slug directory.': 'Das Plugin muss in einem Verzeichnis mit einem festen Slug installiert sein.', 'Invalid GitHub repository URL.': 'Ungültige GitHub-Repository-URL.', 'The private update could not be authorized. Check the repository credentials and refresh updates.': 'Das private Update konnte nicht autorisiert werden. Prüfen Sie die Repository-Zugangsdaten und suchen Sie erneut nach Updates.', 'Could not create the update download file.': 'Die Datei für den Update-Download konnte nicht erstellt werden.', 'The private update download failed. Check credentials and try again.': 'Der Download des privaten Updates ist fehlgeschlagen. Prüfen Sie die Zugangsdaten und versuchen Sie es erneut.', 'Could not access the update filesystem.': 'Auf das Dateisystem für das Update konnte nicht zugegriffen werden.', 'GitHub release does not contain the plugin main file.': 'Der GitHub-Release enthält nicht die Hauptdatei des Plugins.', 'Could not prepare the GitHub release package.': 'Das GitHub-Release-Paket konnte nicht vorbereitet werden.', 'See the release on GitHub.': 'Sehen Sie sich den Release auf GitHub an.', 'Settings': 'Einstellungen', 'Advanced Scripts QuickNav requires PHP 8.0 or newer.': 'Advanced Scripts QuickNav benötigt PHP 8.0 oder neuer.', 'QuickNav updates are paused because another plugin loaded an older deckerweb Updater. Update that plugin to restore QuickNav updates.': 'QuickNav-Updates pausieren, weil ein anderes Plugin einen älteren deckerweb Updater geladen hat. Aktualisieren Sie dieses Plugin, um QuickNav-Updates wieder zu ermöglichen.'}
translations.update({'Script count':'Script-Zähler','Developer links':'Entwicklerlinks','Resource links':'Ressourcenlinks','Footer links':'Footer-Links'})
translations.update({
'Advanced Scripts is installed but inactive or unavailable. Activate it to use QuickNav. QuickNav remains active and your saved preferences are kept.': 'Advanced Scripts ist installiert, aber nicht aktiv oder nicht verfügbar. Aktiviere es, um QuickNav zu nutzen. QuickNav bleibt aktiv und deine gespeicherten Einstellungen bleiben erhalten.',
'Advanced Scripts is not installed yet. Install and activate it to use QuickNav. QuickNav remains active and your saved preferences are kept.': 'Advanced Scripts ist noch nicht installiert. Installiere und aktiviere es, um QuickNav zu nutzen. QuickNav bleibt aktiv und deine gespeicherten Einstellungen bleiben erhalten.',
'Manage plugins': 'Plugins verwalten',
})
formal_overrides.update({
'Advanced Scripts is installed but inactive or unavailable. Activate it to use QuickNav. QuickNav remains active and your saved preferences are kept.': 'Advanced Scripts ist installiert, aber nicht aktiv oder nicht verfügbar. Aktivieren Sie es, um QuickNav zu nutzen. QuickNav bleibt aktiv und Ihre gespeicherten Einstellungen bleiben erhalten.',
'Advanced Scripts is not installed yet. Install and activate it to use QuickNav. QuickNav remains active and your saved preferences are kept.': 'Advanced Scripts ist noch nicht installiert. Installieren und aktivieren Sie es, um QuickNav zu nutzen. QuickNav bleibt aktiv und Ihre gespeicherten Einstellungen bleiben erhalten.',
})
translations.update({
'Advanced Scripts Safe Mode is active.': 'Der Safe Mode von Advanced Scripts ist aktiv.',
'Enabled by the AS_SAFE_MODE constant, usually set in wp-config.php. This constant takes precedence over the Advanced Scripts setting.': 'Aktiviert über die Konstante AS_SAFE_MODE, üblicherweise in der wp-config.php gesetzt. Diese Konstante hat Vorrang vor der Einstellung in Advanced Scripts.',
'Enabled in the Advanced Scripts settings.': 'Aktiviert in den Einstellungen von Advanced Scripts.',
'PHP snippets are paused by Advanced Scripts. QuickNav navigation and your preferences remain available.': 'PHP-Snippets werden von Advanced Scripts pausiert. Die QuickNav-Navigation und deine Einstellungen bleiben verfügbar.',
})
formal_overrides.update({'PHP snippets are paused by Advanced Scripts. QuickNav navigation and your preferences remain available.': 'PHP-Snippets werden von Advanced Scripts pausiert. Die QuickNav-Navigation und Ihre Einstellungen bleiben verfügbar.'})
result={k:translations.get(k,old.get(k,'')) for k in sorted(strings)}
missing=[k for k,v in result.items() if not v]
if missing:raise SystemExit('Missing translations: '+repr(missing))
def mo(messages):
 keys=sorted(messages); ids=[k.encode() for k in keys]; vals=[messages[k].encode() for k in keys]; n=len(keys); start=28+16*n; a=b'';bt=b'';offsets=[]
 for key in ids:offsets.append((len(key),start+len(a)));a+=key+b'\0'
 for val in vals:bt+=val+b'\0'
 vstart=start+len(a); vo=[];offs=0
 for val in vals:vo.append((len(val),vstart+offs));offs+=len(val)+1
 return struct.pack('<7I',0x950412de,0,n,28,28+8*n,0,0)+b''.join(struct.pack('<2I',*x) for x in offsets+vo)+a+bt
def phpq(s):return "'"+s.replace('\\','\\\\').replace("'","\\'")+"'"
for locale in ['de_DE','de_DE_formal']:
 catalog=result.copy()
 if locale.endswith('formal'):
  for k,v in catalog.items():
   v = old_formal.get(k, v) if k not in translations else v
   catalog[k]=v.replace('Deine','Ihre').replace('deine','Ihre').replace('deiner','Ihrer').replace('deinen','Ihren').replace('deinem','Ihrem').replace('dein','Ihr').replace('dein Konto','Ihr Konto').replace('Aktiviere zuerst','Aktivieren Sie zuerst').replace('Wähle bis zu','Wählen Sie bis zu').replace('Wähle unten','Wählen Sie unten').replace('Öffne einen','Öffnen Sie einen').replace('speichere sie','speichern Sie sie').replace('Lege dein erstes','Legen Sie Ihr erstes').replace(' in Advanced Scripts an.',' in Advanced Scripts an.').replace('Erreiche deine Scripts','Erreichen Sie Ihre Scripts')
 if locale.endswith('formal'):
  catalog.update({k:v for k,v in formal_overrides.items() if k in strings})
  for k,v in catalog.items():
   catalog[k]=v.replace('Bitte prüfe','Bitte prüfen Sie').replace('Bitte wähle','Bitte wählen Sie').replace('Aktiviere ','Aktivieren Sie ').replace('Speichere ','Speichern Sie ').replace('Öffne ','Öffnen Sie ')
 header=f'Project-Id-Version: Advanced Scripts QuickNav 1.2.1-rc1\nLanguage: {locale}\nMIME-Version: 1.0\nContent-Type: text/plain; charset=UTF-8\nContent-Transfer-Encoding: 8bit\nPlural-Forms: nplurals=2; plural=(n != 1);\nPO-Revision-Date: 2026-10-09 00:00+0200\nLast-Translator: David Decker – DECKERWEB\nLanguage-Team: German\n'
 catalog['']=header;stem=lang/f'advanced-scripts-quicknav-{locale}'
 stem.with_suffix('.mo').write_bytes(mo(catalog))
 lines=['msgid ""','msgstr '+json.dumps(header,ensure_ascii=False),'']
 for k in sorted(result):
  if '\x04' in k:
   ctx,key=k.split('\x04',1);lines+=['msgctxt '+json.dumps(ctx,ensure_ascii=False)]
  else:key=k
  lines+=['msgid '+json.dumps(key,ensure_ascii=False),'msgstr '+json.dumps(catalog[k],ensure_ascii=False),'']
 stem.with_suffix('.po').write_text('\n'.join(lines))
 entries=',\n'.join(phpq(k)+' => '+phpq(v) for k,v in catalog.items() if k)
 Path(str(stem)+'.l10n.php').write_text("<?php\nreturn ['project-id-version' => 'Advanced Scripts QuickNav 1.2.1-rc1', 'language' => "+phpq(locale)+", 'plural-forms' => 'nplurals=2; plural=(n != 1);', 'messages' => [\n"+entries+"\n]];\n")
# Current source template; old unused strings are dropped, contextual strings retained.
lines=['msgid ""','msgstr "Project-Id-Version: Advanced Scripts QuickNav 1.2.1-rc1\\nContent-Type: text/plain; charset=UTF-8\\n"','']
for k in sorted(strings):
 if '\x04' in k:ctx,key=k.split('\x04',1);lines+=['msgctxt '+json.dumps(ctx,ensure_ascii=False)]
 else:key=k
 lines+=['msgid '+json.dumps(key,ensure_ascii=False),'msgstr ""','']
(lang/'advanced-scripts-quicknav.pot').write_text('\n'.join(lines))
print(f'{len(strings)} source strings, German informal/formal complete')
