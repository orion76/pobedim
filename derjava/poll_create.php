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
    $ht = new html('/derjava/poll_create.html');
} else
switch ($kp){
    case '4':
        $ht = new html('/derjava/slovar_create.html');
        break;
    case '8':
        $ht = new html('/derjava/poll8_create.html');
        break;
    case '9':
        $ht = new html('/derjava/poll9_create.html');
        break;
    
    case '70':
        $ht = new html('/derjava/p70_create.html');
        break;
    case '71':
        $ht = new html('/derjava/p71_create.html');
        break;    
    
    default :
        $ht = new html('/derjava/p00_create.html');    
        break;
}


$menu= menu_user($ht, '');

$r0 = new db(DB_POBEDIM,'select * from w2_veche v where v.veche=:iv',[':iv'=>$iv]);
$data0 = $r0->rows;
$data0[0]['VECHE'] = $iv;
$data0[0]['ID_KIND_POLL'] = $kp;


$ht->data('data0', $data0);
echo $ht->build('', true);


