<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$w = val_rq('w');
$u = current_iu();

$ht = new html('/derjava/wall_users_cast.html');
$menu= menu_user($ht, '');
 
$r1 = new db(DB_POBEDIM, ' SELECT DISTINCT uw.u FROM U2_WALL uw where uw.ID_WALL=:w and COALESCE(uw.DO_GET_MSG,1) = 1'
        ,[':w'=>$w]
        ,function($row){
            return $row;
        } );


$data0[0]['ID_WALL'] = $w;
$ht->data('data0', $data0 );
$ht->data('data1', $r1->rows );
 
unset($r1);

echo $ht->build('',true);

 


