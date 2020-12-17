<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/user_compile.html');
$menu= menu_user($ht, '');
 
$ht->data('data0', $data0 );
 

echo $ht->build('',true);

 


