<?php
require __DIR__.'/fixture.php';
define('ASQN_PLUGIN_FILE',dirname(__DIR__).'/advanced-scripts-quicknav.php');
function plugin_basename($file){return 'advanced-scripts-quicknav/advanced-scripts-quicknav.php';}
function wp_parse_url($url){return parse_url($url);}
class Plugin_Upgrader { public bool $bulk=true; }
class WP_Error { public function __construct(public string $code,public string $message){} }
function is_wp_error($value){return $value instanceof WP_Error;}
require dirname(__DIR__).'/includes/class-asqn-github-updates.php';
$adapter=new Deckerweb\AdvancedScriptsQuickNav\GitHubUpdates();
$adapter->boot();
expect(Deckerweb\GitHubReleaseUpdater\V2\Updater::IMPLEMENTATION_VERSION==='2.2.0','Canonical shared updater loaded');
foreach($GLOBALS['actions']['admin_notices']??[] as $callbacks)foreach($callbacks as $entry)expect($entry[0]!==[$adapter,'compatibility_notice'],'Host-translations handshake succeeds');
$context=['plugin'=>'advanced-scripts-quicknav/advanced-scripts-quicknav.php'];
$error=$adapter->validate_source('/missing/','',new Plugin_Upgrader(),$context);
expect($error instanceof WP_Error&&$error->code==='asqn_update_source','Bulk updates still enforce package validation');
expect($adapter->validate_source('/missing/','',null,$context)==='/missing/','Unknown non-bulk context unchanged');
$context['plugin']='other/other.php';expect($adapter->validate_source('/missing/','',new Plugin_Upgrader(),$context)==='/missing/','Foreign packages unchanged');
require_quicknav(dirname(__DIR__));require_once dirname(__DIR__).'/includes/class-asqn-search.php';require_once dirname(__DIR__).'/includes/class-asqn-commands.php';
foreach(['ASQN_Config','ASQN_Advanced_Scripts_Adapter','ASQN_Favorites','ASQN_Navigation_Builder','ASQN_Admin_Bar_Renderer','ASQN_Settings','ASQN_Search','ASQN_Commands','Deckerweb\\AdvancedScriptsQuickNav\\GitHubUpdates'] as $name){
 $class=new ReflectionClass($name);expect((bool)$class->getDocComment(),'Class documentation: '.$name);
 foreach($class->getMethods() as $method){$doc=$method->getDocComment();expect($doc&&str_contains($doc,'@return'),'Return contract: '.$name.'::'.$method->getName());foreach($method->getParameters() as $param)expect(str_contains($doc,'$'.$param->getName()),'Parameter contract: '.$param->getName());}
}
echo "Component integration, bulk package guards and host documentation checks passed.\n";
