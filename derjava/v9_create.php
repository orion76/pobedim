<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';


$ax = val_rq('ax', 0);
$bx = val_rq('bx');
$pa = val_rq('pa');


$fu = current_fu();
$u = current_iu();

$ivp = val_rq('ivp', 0);
if (empty($iv)) {
    $iv = 0;
}

if ($ax === 'create') {
    $nv = val_rq('nv');

    $r = new db(DB_POBEDIM, 'select * from w2_veche_i6(:u, :iv ,:nv, 9, null)'
            , [ ':u' => $u, ':nv' => $nv, ':iv'=>$ivp ]
    );

    $iv = $r->row('VECHE');

    echo htm_redirect1("/derjava/veche_edit.php?iv=$iv");
    exit;
}


$ht = new html('/derjava/v9_create.html');
$menu= menu_user($ht, '');


$r0 = new db(DB_POBEDIM, 'select v.* from w2_veche v where v.veche=:ivp'
        ,[':ivp'=>$ivp]);



$ht->data('data0', $r0->rows );
echo $ht->build('',true);

