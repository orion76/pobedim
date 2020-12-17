<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/sys_stat.html');
$menu= menu_user($ht, '');
 
$r1 = new db(DB_USER,'SELECT US.DATE_SESSION, COUNT(1) AS CNT
  FROM  U0_SESSION US
  GROUP BY 1
  ORDER BY 1 DESC');

$data0[0]=[];

$ht->data('data1', $r1->rows );
$ht->data('data0', $data0 );
 

echo $ht->build('',true);

 


