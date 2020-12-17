<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$u = current_iu();


$ht = new html('/derjava/trustnet.html');
$menu= menu_user($ht, '');
 
$data0[0] = [];
$ht->data('data0', $data0 );

$r1 = new db(DB_POBEDIM,'select * from w2_coordinators_L3(1,5)',[]
        ,function($row){
            return $row;
           } 
        );
$_SESSION['TN'] = $r1->rows;
$ht->data('data1', $r1->rows );

echo $ht->build('',true);

 


