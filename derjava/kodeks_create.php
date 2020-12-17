<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax');
$iv = val_rq('iv', 0);
$u = current_iu();
$bx = val_rq('bx');
$kv = val_rq('kv');
$kp = val_rq('kp',1);

if (empty($iv)) {
    $iv = 0;
}

$ht = new html('/derjava/kodeks_create.html');
$menu= menu_user($ht, '');

$data0['VECHE'] = $iv;
$ht->data('data0', $data0);


//select_veche4($ht,$iv, 3);


echo $ht->build('', true);


