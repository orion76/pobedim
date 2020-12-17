<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

if (!isUserLoggedOn()){
    echo htm_redirect1('/');
    exit;
}


$ax = val_rq('ax');
$ivp = val_rq('ivp');
$iv = val_rq('iv', $ivp);
$u = current_iu();
$bx = val_rq('bx');
$sx = val_rq('sx');
$kv = val_rq('kv');

if (empty($iv)) {
    $iv = 0;
}


if ($ax === 'create0') {
    $nv = val_rq('nv');
    $ivp = val_rq('ivp');
    $kv = val_rq('kv');
    
    $r = new db(DB_POBEDIM, 'select * from w2_veche_i6(:u, :ivp,:nv, :kv, null)'
            , [':u' => $u, ':ivp' => $ivp, ':nv' => $nv, ':py' => val_rq('py'), ':kv' => $kv ]
    );

    $iv = $r->row('VECHE');

    echo htm_redirect1("/derjava/veche_edit.php?iv=$iv");
    exit;
}


if ($ax === 'create') {
    $nv = val_rq('nv');

    $ta = 0;
    $ta1 = val_rq('ta1'); //территория
    $ta2 = val_rq('ta2'); //территория
    $ta3 = val_rq('ta3'); //территория
    if ($ta3 > 0) {
        $ta = $ta3;
    } else if ($ta2 > 0) {
        $ta = $ta2;
    } else {
        $ta = $ta1;
    }

    $r = new db(DB_POBEDIM, 'select * from w2_veche_i6(:u, :iv,:nv, :kv, null)'
            , [':u' => $u, ':iv' => $ta, ':nv' => $nv, ':py' => val_rq('py'), ':kv' => $kv ]
    );

    $iv = $r->row('VECHE');

    echo htm_redirect1("/derjava/veche_edit.php?iv=$iv");
    exit;
}


if ($kv == '6' && empty($ivp)){
    echo htm_redirect1('/derjava/v6.php');
    exit;
} 

/*
if ($kv == '8' && empty($ivp)){
    echo htm_redirect1('/derjava/v8_create.php');
    exit;
} 
*/

/*
if ($kv == '9' && empty($ivp)){
    echo htm_redirect1('/derjava/v9.php');
    exit;
} 
*/

if ($kv == '1')
    $ht = new html('/derjava/v1_create.html');
else
    if ($kv == '3')
    $ht = new html('/derjava/v3_create.html');
    else
        $ht = new html('/derjava/veche_create.html');

$menu= menu_user($ht, '');

$vv[0] = select_veche4($ht,$iv, 3, !empty($ivp));

$data0[0] = ['NAME_VECHE'=> iif($kv =='6', 'Референдум: '. $vv[0]['NAME_VECHE'] ,' ')
                ,'NAME_VECHE_DISABLED' => iif($kv =='6',' readonly="readonly" ',' ')
            ]; 
if ($kv == '1' || $kv == '3' ) $data0[0]['NAME_VECHE']=$sx;

$ht->data('kind_veche', rows_kind_veche($kv,'!6',''));
$ht->data('tree_veche', $vv);
$ht->data('data0', $data0);


echo $ht->build('', true);




