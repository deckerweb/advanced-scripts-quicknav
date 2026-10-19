<?php
/** Loaded UI catalogs must select the same local changelog even with mixed locale filters. */
require __DIR__.'/fixture.php';
function get_translations_for_domain($domain){return (object)['headers'=>$GLOBALS['catalog_headers']??[]];}
require_quicknav(dirname(__DIR__));define('ASQN_PLUGIN_FILE',dirname(__DIR__).'/advanced-scripts-quicknav.php');
foreach([
 ['en_US',['Language'=>'de_DE'],true],
 ['en_US',['language'=>'de_DE_formal'],true],
 ['en_US',['language'=>'de_CH'],true],
 ['de_DE',['Language'=>'en_US'],false],
 ['de_DE',[],true],
 ['en_US',[],false],
]as[$locale,$headers,$german]){
 $GLOBALS['locale']=$locale;$GLOBALS['catalog_headers']=$headers;
 expect(ASQN_Settings::german()===$german,'Interface/catalog language precedence');
 ob_start();(new ASQN_Settings())->page();$html=ob_get_clean();
 expect(str_contains($html,$german?'Globale Suche nach Script-Titel':'Global search by script title'),'Localized changelog body');
 expect(str_contains($html,$german?'>Neu</span>':'>New</span>'),'Localized category badge');
}
echo "PASS de_DE, formal, Swiss, English and conflicting request/catalog changelog selection.\n";
