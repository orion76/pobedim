<?php

$dir = __DIR__;
require_once ( $dir.'/ut.php');


function db2_fb_sn(){
    $r = false;
    /*
      $fbu = $_SESSION['fbu'];
      if (isset($fbu)){
        $ufb = $fbu['id'];
        $_SESSION['nu'] = trim($fbu['first_name']).' '.trim( $fbu['last_name']);
        $r = db_get('update w2_session set u_fb=:ufb'
                . ', name_u=:nu ,id_user=:iu'
                . ' where ID_SESSION=:isn'
                      ,[':ufb'=>$ufb
                          ,':isn'=>val_sn('isn',null)
                          ,':iu'=> val_sn('iu',null)
                          ,'nu'=> val_sn('nu',null) ]
                ,2);
      }
    */
    return $r;  
}
