<?php
/** Search projection and AJAX access checks; fixture data contains a secret code field. */
require __DIR__.'/fixture.php';
function remove_accents($text){return strtr($text,['ä'=>'a','Ä'=>'A','é'=>'e']);}
function sanitize_text_field($text){return trim(strip_tags($text));}function wp_unslash($text){return stripslashes($text);}
function wp_send_json_error($data=null,$status=200){throw new RuntimeException('error:'.$status);}
function wp_send_json_success($data){$GLOBALS['response']=$data;throw new RuntimeException('success');}
function check_ajax_referer($action,$key){if(($GLOBALS['_POST'][$key]??'')!=='good')throw new RuntimeException('nonce:403');}
require_quicknav(dirname(__DIR__));require dirname(__DIR__).'/includes/class-asqn-search.php';
$search=new ASQN_Search();$GLOBALS['manager']->items=items();$scripts=(new ASQN_Advanced_Scripts_Adapter())->scripts();
$result=$search->find('CHECKOUT shop',$scripts);expect($result['total']===2,'Cross-folder title AND path search');
expect($search->find('Shop Styles',$scripts)['items'][0]['id']===5,'Nested folder match');
expect($search->find('Tracking',$scripts)['items'][0]['status']==='Inactive','Saved inactive status');
expect($result['items'][0]['status']==='Active, blocked by folder','Folder block distinguished');
expect(str_contains($result['items'][0]['url'],'parent=1')&&str_contains($result['items'][0]['url'],'edit=2'),'Direct editor link includes source folder');
expect(!str_contains(json_encode($result),'PRIVATE CODE')&&!str_contains(json_encode($result),'content'),'Code excluded');
expect($search->find('PRIVATE CODE',$scripts)['total']===0,'Code is not searchable');
expect($search->find(' ', $scripts)['total']===0,'Empty query');
$accent=$scripts;$accent[3]['title']='Änderung Café';expect($search->find('anderung cafe',$accent)['total']===1,'Case and accent search');
$many=[];for($i=1;$i<=65;$i++){$many[$i]=$scripts[3];$many[$i]['term_id']=$i;$many[$i]['title']='Match';}
$bounded=$search->find('match',$many);expect($bounded['total']===65&&count($bounded['items'])===50,'Capped results with full count');expect($bounded['items'][0]['id']===1,'Stable duplicate title order');
$_SERVER['REQUEST_METHOD']='POST';
$_POST=['query'=>'Checkout','nonce'=>'good'];
foreach(['denied','nonce','invalid','ok'] as $case){$GLOBALS['permission']=$case!=='denied';$_POST['nonce']=$case==='nonce'?'bad':'good';$_POST['query']=$case==='invalid'?['bad']:'Checkout';try{$search->ajax();throw new LogicException('No response');}catch(RuntimeException $e){expect($e->getMessage()===['denied'=>'error:403','nonce'=>'nonce:403','invalid'=>'error:400','ok'=>'success'][$case],'AJAX case '.$case);}}
expect($search->find('', $scripts, 'php')['total']===1,'Type-only query');
expect($search->find('', $scripts, 'all', 'inactive')['total']===1,'Inactive-only query');
expect($search->find('', $scripts, 'css', 'blocked')['total']===1,'Combined CSS and folder block filters');
expect($search->find('checkout', $scripts, 'js')['total']===0,'Combined words and type exclude nonmatching');
function metadata_exists($kind,$user,$key){return array_key_exists($key,$GLOBALS['meta']);}
function add_user_meta($user,$key,$value,$unique){if(array_key_exists($key,$GLOBALS['meta']))return false;$GLOBALS['meta'][$key]=$value;return 1;}
function wp_cache_delete($key,$group){}
function favorite_request($id,$selected,$expected='success'){
 $_POST=['id'=>$id,'selected'=>$selected,'nonce'=>'good'];
 try{(new ASQN_Search())->favorite();throw new LogicException('No JSON');}catch(RuntimeException $e){expect($e->getMessage()===$expected,'Favorite response '.json_encode($id).' '.$expected);}
}
favorite_request('2','1');expect(ASQN_Favorites::ids($scripts)===[2],'Adds personal favorite');
expect($search->find('Checkout',$scripts)['items'][0]['favorite']===true,'Search exposes saved state');
favorite_request('2','1');expect(ASQN_Favorites::ids($scripts)===[2],'Idempotent favorite set');
favorite_request('3','1');favorite_request('2','0');expect(ASQN_Favorites::ids($scripts)===[3],'Removal preserves other favorites');
favorite_request('1','1','error:404');favorite_request('999','1','error:404');favorite_request(['bad'],'1','error:400');
favorite_request('2','yes','error:400');
favorite_request('<2>','1','error:400');favorite_request('2x','1','error:400');favorite_request('2', ['1'], 'error:400');
$GLOBALS['blog']=2;expect(ASQN_Favorites::ids($scripts)===[],'Favorites site isolation');$GLOBALS['blog']=1;
$GLOBALS['permission']=false;favorite_request('2','1','error:403');$GLOBALS['permission']=true;
echo "PASS global search, paths, source exclusion, statuses, limits, permissions, nonce and malformed queries.\n";
