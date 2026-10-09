<?php
namespace Deckerweb\GitHubReleaseUpdater\V2 {
 /** Synthetic legacy engine exposes no host-translation contract. */
 final class Updater {}
}
namespace {
 require __DIR__.'/fixture.php';define('ASQN_PLUGIN_FILE',dirname(__DIR__).'/advanced-scripts-quicknav.php');
 require dirname(__DIR__).'/includes/class-asqn-github-updates.php';
 $adapter=new \Deckerweb\AdvancedScriptsQuickNav\GitHubUpdates();$adapter->boot();
 expect(isset($GLOBALS['actions']['admin_notices']),'Older engine pauses only the update integration with a valid notice callback');
 require_quicknav(dirname(__DIR__));$GLOBALS['manager']->items=items();$bar=new FakeBar();(new ASQN_Admin_Bar_Renderer(new ASQN_Navigation_Builder((new ASQN_Advanced_Scripts_Adapter())->scripts())))->add_admin_bar_menu($bar);
 expect(isset($bar->nodes['asqn-active']),'Toolbar remains usable beside an older engine');
 echo "Older updater coexistence check passed.\n";
}
