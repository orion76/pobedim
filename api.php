<?php

 require_once 'ut.php';
 
 echo 'err:service is closed';  exit;
 
 
 $q = val_rq('db_pobedim');
 if (strpos($q,'select') !== 0) {  echo 'err:support select only';  exit; }
 
 $r = new db(DB_POBEDIM,$q);
 
 $ds = json_encode($r->rows); 
 echo $ds;