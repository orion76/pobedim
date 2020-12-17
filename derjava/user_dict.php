<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$fu = current_fu();

$u = current_iu();
$iu = val_rq('iu', $u);


$ht = new html('/derjava/user_dict.html');
$menu= menu_user($ht, '');
 
$r0= new db(DB_POBEDIM,'select * from w2_user where u = :iu',[':iu'=>$iu]);
$data0=$r0->rows;
        
$r1 = new db(DB_POBEDIM,'select * from w2_user_dictionary_l(:iu)',[':iu'=>$iu]);


$ht->data('data0', $data0 );
$ht->data('data1', $r1->rows );

echo $ht->build('',true);


exit;




