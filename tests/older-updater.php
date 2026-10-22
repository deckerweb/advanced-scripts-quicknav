<?php
namespace Deckerweb\GitHubReleaseUpdater\V2 {
 /** Synthetic legacy engine exposes no host-translation contract. */
 final class Updater {}
}
namespace {
 require __DIR__.'/fixture.php';define('ASQN_PLUGIN_FILE',dirname(__DIR__).'/advanced-scripts-quicknav.php');
 require dirname(__DIR__).'/includes/class-asqn-github-updates.php';
 function plugin_basename($file){return 'advanced-scripts-quicknav/advanced-scripts-quicknav.php';}
 function wp_parse_url($url){return parse_url($url);}
 $adapter=new \Deckerweb\AdvancedScriptsQuickNav\GitHubUpdates();$adapter->boot();
 expect(\Deckerweb\GitHubReleaseUpdater\V2\Runtime_2_2_1\Updater::IMPLEMENTATION_VERSION==='2.2.1','Pinned updater loads beside a legacy engine');
 foreach($GLOBALS['actions']['admin_notices']??[] as $callbacks)foreach($callbacks as $entry)expect($entry[0]!==[$adapter,'compatibility_notice'],'Pinned engine does not pause because of a legacy copy');
 require_quicknav(dirname(__DIR__));$GLOBALS['manager']->items=items();$bar=new FakeBar();(new ASQN_Admin_Bar_Renderer(new ASQN_Navigation_Builder((new ASQN_Advanced_Scripts_Adapter())->scripts())))->add_admin_bar_menu($bar);
 expect(isset($bar->nodes['asqn-active']),'Toolbar remains usable beside an older engine');
 echo "Older updater coexistence check passed.\n";
}
