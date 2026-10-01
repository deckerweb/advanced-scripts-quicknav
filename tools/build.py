#!/usr/bin/env python3
"""Build an installable plugin and a standalone Advanced Scripts import from one source."""
import argparse,base64,hashlib,json,re,shutil,subprocess,zipfile
from pathlib import Path
ROOT=Path(__file__).resolve().parents[1]
VERSION=re.search(r'\* Version: (.+)',(ROOT/'advanced-scripts-quicknav.php').read_text())[1].strip()
def build(destination):
 destination.mkdir(parents=True,exist_ok=True)
 components=['config','adapter','favorites','navigation','renderer','settings','plugin']
 def fragment(path):
  text=path.read_text();return re.sub(r'^<\?php\s*','',text)
 code="<?php\n// Generated from QuickNav source. Install either plugin or snippet.\nif (!defined('ABSPATH') || version_compare(PHP_VERSION, '8.0', '<')) { return; }\nif (!class_exists('DDW_Advanced_Scripts_QuickNav', false)) {\ndefine('ASQN_SNIPPET_MODE', true);\n"
 for name,file in [('CSS','assets/settings.css'),('JS','assets/settings.js'),('CHANGELOG_EN','docs/changelog.txt'),('CHANGELOG_DE','docs/changelog-de.txt')]:
  encoded=base64.b64encode((ROOT/file).read_bytes()).decode();code+=f"define('ASQN_SNIPPET_{name}', base64_decode('{encoded}'));\n"
 # Static translated catalogs are data, not runtime code evaluated from input.
 for locale in ['de_DE','de_DE_formal']:
  text=fragment(ROOT/f'languages/advanced-scripts-quicknav-{locale}.l10n.php')
  var='asqn_snippet_formal' if locale.endswith('formal') else 'asqn_snippet_german'
  code+=text.replace('return [',f'${var} = [',1)+'\n'
 code+="""
$asqn_snippet_translate = static function($translation, $text, $domain) use ($asqn_snippet_german, $asqn_snippet_formal) {
 if ($domain !== 'advanced-scripts-quicknav' || strpos(determine_locale(), 'de') !== 0) { return $translation; }
 $catalog = strpos(determine_locale(), 'formal') !== false ? $asqn_snippet_formal : $asqn_snippet_german;
 return $catalog['messages'][$text] ?? $translation;
};
add_filter('gettext', $asqn_snippet_translate, 10, 3);
add_filter('gettext_with_context', static function($translation, $text, $context, $domain) use ($asqn_snippet_translate) { return $asqn_snippet_translate($translation, $context . "\\x04" . $text, $domain); }, 10, 4);
"""
 code+=fragment(ROOT/'includes/deckerweb-changelog-v1.php')+'\n'
 code+='\n'.join(fragment(ROOT/f'includes/class-asqn-{name}.php') for name in components)+'\nnew DDW_Advanced_Scripts_QuickNav();\n}\n'
 php=destination/'ddw-advanced-scripts-quicknav.php';php.write_text(code)
 subprocess.run(['php','-l',str(php)],check=True)
 script={'generator':'Advanced Scripts','version':'2.6.2','date':'2026-10-01 00:00:00','scripts':[{'title':'DDW Advanced Scripts QuickNav','description':f'QuickNav {VERSION}; personal favorites and folder navigation. Do not enable alongside the plugin.','type':'application/x-httpd-php','code':base64.b64encode(code.encode()).decode(),'location':'all','hook':'plugins_loaded','priority':20,'status':False,'order':0}]}
 (destination/'ddw-advanced-scripts-quicknav.as.json').write_text(json.dumps(script,ensure_ascii=False,indent=2)+'\n')
 # Stable slug is essential for WordPress upgrades and shared updater matching.
 archive=destination/f'advanced-scripts-quicknav-{VERSION}.zip'
 excluded={'.git','.github','tools','tests','dist','node_modules','__pycache__'}
 with zipfile.ZipFile(archive,'w',zipfile.ZIP_DEFLATED) as z:
  for file in sorted(ROOT.rglob('*')):
   if not file.is_file():continue
   rel=file.relative_to(ROOT)
   if set(rel.parts)&excluded or file.name.startswith('.') or file.suffix=='.pyc':continue
   z.write(file,Path('advanced-scripts-quicknav')/rel)
 shutil.copyfile(archive,destination/'advanced-scripts-quicknav.zip')
 (destination/'SHA256SUMS.txt').write_text('\n'.join(hashlib.sha256(file.read_bytes()).hexdigest()+'  '+file.name for file in sorted(destination.iterdir()) if file.is_file() and file.name!='SHA256SUMS.txt')+'\n')
 print('Built',VERSION,'in',destination)
if __name__=='__main__':
 parser=argparse.ArgumentParser();parser.add_argument('--output',type=Path,default=ROOT/'dist');args=parser.parse_args();build(args.output.resolve())
