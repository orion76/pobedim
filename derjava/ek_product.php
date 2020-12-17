<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/ek_product.html');
$menu= menu_user($ht, '');
$data0[0]=[];
 
$ht->data('data0', $data0 );
 
$r1 = new db(DB_POBEDIM, 'select lr.*, lru.COST_LABOR from W2_LABOR lr left outer join u2_labor lru on lru.id_labor=lr.id_labor and lru.u=:u ',[':u'=>$u]);
$ht->data('data1', $r1->rows );

echo $ht->build('',true);

 