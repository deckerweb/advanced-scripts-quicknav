<?php
/** Minimal WordPress doubles for focused QuickNav checks, not integration coverage. */
error_reporting(E_ALL);
set_error_handler(static function($level,$message,$file,$line){throw new RuntimeException("$message at $file:$line");});
define('ABSPATH',__DIR__.'/');define('EPXADVSC_VER','2.6.2');
$GLOBALS['blog']=1;$GLOBALS['permission']=true;$GLOBALS['locale']='de_DE';$GLOBALS['safe']=true;$GLOBALS['filters']=[];$GLOBALS['meta']=[];
function add_action($hook, $callback, $priority=10, $accepted_args=1){
 if (!is_callable($callback)) throw new RuntimeException('Invalid action callback on '.$hook.': '.(is_array($callback)?get_class($callback[0]).'::'.$callback[1]:'callback'));
 $GLOBALS['actions'][$hook][$priority][]=[$callback,$accepted_args];
} function add_filter(...$args){}
function apply_filters($tag,$value,...$args){return isset($GLOBALS['filters'][$tag]) ? $GLOBALS['filters'][$tag]($value,...$args) : $value;}
function get_current_blog_id(){return $GLOBALS['blog'];} function get_current_user_id(){return 7;}
function get_user_meta($id,$key,$single){return $GLOBALS['meta'][$key]??'';}function update_user_meta($id,$key,$value){$GLOBALS['meta'][$key]=$value;}
function get_option($key,$default=false){return $key==='advanced-scripts-safemode'?$GLOBALS['safe']:$default;}
function current_user_can($cap){return $GLOBALS['permission'];}function is_admin_bar_showing(){return true;}function is_admin(){return true;}function is_network_admin(){return false;}
function absint($x){return abs((int)$x);}function sanitize_key($x){return preg_replace('/[^a-z0-9_-]/','',strtolower($x));}
function esc_html($x){return htmlspecialchars((string)$x,ENT_QUOTES,'UTF-8');}function esc_attr($x){return esc_html($x);}function esc_url($x){return $x;}
function __($x,$domain=''){return $GLOBALS['translations'][$x]??$x;}function esc_html__($x,$d=''){return esc_html(__($x,$d));}function esc_attr__($x,$d=''){return esc_attr(__($x,$d));}function _x($x,$ctx,$d=''){return __($ctx."\x04".$x,$d);}function esc_html_x($x,$ctx,$d=''){return esc_html(_x($x,$ctx,$d));}
function determine_locale(){return $GLOBALS['locale'];}function admin_url($x=''){return 'https://example.test/wp-admin/'.$x;}
function add_query_arg($args,$url){return $url.'?'.http_build_query($args);}function plugins_url($path,$file){return 'file://'.dirname($file).'/'.$path;}
function wp_nonce_field(...$x){echo '<input type="hidden" name="_wpnonce" value="fixture">';}function checked($a,$b=true,$echo=true){$x=$a==$b?'checked="checked"':'';if($echo)echo $x;return $x;}
function submit_button($text){echo '<p><button class="button-primary" type="submit">'.esc_html($text).'</button></p>';}
function get_user_option($key){return 'custom-scheme';}function sanitize_hex_color($x){return $x;}
function wp_add_inline_style($handle,$css){$GLOBALS['inline'][$handle]=$css;}
class FakeBar {public array $nodes=[]; public function add_node($node){$this->nodes[$node['id']]=$node;}public function add_group($node){$this->add_node($node);}public function remove_node($id){unset($this->nodes[$id]);}}
class FixtureManager {public array $items=[];public function get_scripts(){return $this->items;}}
$GLOBALS['manager']=new FixtureManager();function cpas_scripts_manager(){return $GLOBALS['manager'];}
function require_quicknav($root){require_once $root.'/includes/deckerweb-changelog-v1.php';foreach(['config','adapter','favorites','navigation','renderer','settings'] as $part)require_once $root.'/includes/class-asqn-'.$part.'.php';}
function expect($value,$message){if(!$value)throw new RuntimeException($message);}
function items(){return [
['term_id'=>1,'parent'=>0,'title'=>'Shop','type'=>'folder','status'=>false],
['term_id'=>2,'parent'=>1,'title'=>'Checkout <script>','type'=>'application/x-httpd-php','location'=>'front','hook'=>'init','status'=>true,'code'=>'PRIVATE CODE'],
['term_id'=>3,'parent'=>0,'title'=>'Tracking','type'=>'text/javascript','location'=>'front','status'=>false],
['term_id'=>4,'parent'=>1,'title'=>'Styles','type'=>'folder','status'=>true],
['term_id'=>5,'parent'=>4,'title'=>'Checkout CSS','type'=>'text/css','status'=>true],
['term_id'=>6,'parent'=>0,'title'=>'Unbekannter Typ','type'=>'custom/future','status'=>true]
];}
