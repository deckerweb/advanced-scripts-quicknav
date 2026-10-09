<?php
/** Focused Safe Mode source and precedence checks with WordPress doubles. */
require __DIR__ . '/fixture.php';
if ( ($argv[1] ?? '') === 'constant-on' ) { define('AS_SAFE_MODE',true); }
if ( ($argv[1] ?? '') === 'constant-off' ) { define('AS_SAFE_MODE',false); }
require_quicknav(dirname(__DIR__));
ob_start();(new ASQN_Settings())->safe_mode_notice();$html=ob_get_clean();
if ( ($argv[1] ?? '') === 'constant-off' ) { expect($html==='','False constant overrides enabled UI setting'); }
else { expect(str_contains($html,'notice-warning inline'),'Visible inline Safe Mode notice');expect(str_contains($html,($argv[1]??'')==='constant-on'?'AS_SAFE_MODE':'Enabled in the Advanced Scripts settings.'),'Correct effective source');expect(str_contains($html,'PHP snippets are paused'),'Effect explained'); }
echo "PASS Safe Mode source and precedence: ".($argv[1]??'settings')."\n";
