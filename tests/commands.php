<?php
/** Focused native command integration contracts. */
require __DIR__.'/fixture.php';
define('ASQN_PLUGIN_FILE', dirname(__DIR__).'/advanced-scripts-quicknav.php');
function wp_script_is($handle,$state){return !empty($GLOBALS['enqueued'][$handle]);}
function wp_enqueue_script($handle,...$args){$GLOBALS['enqueued'][$handle]=$args;}
function wp_localize_script($handle,$name,$data){$GLOBALS['localized'][$handle]=$data;}
require_quicknav(dirname(__DIR__));
require dirname(__DIR__).'/includes/class-asqn-commands.php';
$GLOBALS['manager']->items=items();
$commands=new ASQN_Commands();
expect(count($commands->items())===4,'Four base navigation commands');
$GLOBALS['meta'][ASQN_Config::key('favorites')]=[2,1,999,5];
$list=$commands->items();
expect(count($list)===6,'Only existing script favorites become commands');
expect(str_contains($list[2]['url'],'edit=0'),'New script uses empty editor');
expect(str_ends_with($list[3]['url'],'#asqn-search-query'),'Search targets focus anchor');
expect(!str_contains(json_encode($list),'PRIVATE CODE'),'No script source exposed');
$GLOBALS['blog']=2;expect(count($commands->items())===4,'Favorites scoped to current website');
$GLOBALS['blog']=1;
$commands->assets();expect(empty($GLOBALS['localized']),'No extra palette loaded');
$GLOBALS['enqueued']['wp-commands']=true;
$commands->assets();expect(count($GLOBALS['localized']['asqn-commands']['items'])===6,'Native palette receives commands');
$GLOBALS['permission']=false;expect($commands->items()===[],'Permission denied yields no commands');
echo "Command permissions, favorite scope, source isolation and conditional assets passed.\n";
