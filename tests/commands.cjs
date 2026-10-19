'use strict';
const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const source = fs.readFileSync(__dirname + '/../assets/commands.js', 'utf8');
const registered = [];
let closed = 0, focused = 0, toggled = 0, navigated;
const location = { href: 'https://example.test/wp-admin/tools.php?page=advanced-scripts', origin: 'https://example.test', pathname: '/wp-admin/tools.php', search: '?page=advanced-scripts', assign: url => navigated = url };
const window = { location, ASQNCommands: { items: [
 {name:'asqn/settings',label:'Settings',url:'/wp-admin/options-general.php?page=advanced-scripts-quicknav'},
 {name:'asqn/search',label:'Search',url:'/wp-admin/tools.php?page=advanced-scripts#asqn-search-query',focusSearch:true},
 {name:'asqn/foreign',label:'Foreign',url:'https://foreign.test/'}
]}, wp: { commands:{store:'commands'}, data:{dispatch:()=>({registerCommand:item=>registered.push(item)})}, domReady:cb=>cb() } };
const document = {getElementById:id => id==='asqn-search-query' ? {focus:()=>focused++} : {classList:{contains:()=>true},querySelector:()=>({click:()=>toggled++})}};
vm.runInNewContext(source,{window,document,URL,URLSearchParams});
assert.equal(registered.length,2);
registered[1].callback({close:()=>closed++});
assert.equal(closed,1);assert.equal(focused,1);assert.equal(toggled,1);assert.equal(navigated,undefined);
registered[0].callback();assert.match(navigated,/options-general/);
vm.runInNewContext(source,{window:{},document,URL,URLSearchParams});
console.log('Command registration, native close, in-place search and local URL guards passed.');
