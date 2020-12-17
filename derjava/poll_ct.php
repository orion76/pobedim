<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);


$fu = current_fu();
$u = current_iu();

$iu = val_rq('u', $u);
$pa = val_rq('pa');
$qq = val_rq('qq');

$ht = new html('/derjava/poll_ct.html');
$menu= menu_user($ht, '');
 
$r0 = new db(DB_POBEDIM, <<<SQL
        select qq.*,  pa.* 
            from w2_poll_answer pa
                inner join w2_poll qq on qq.ID_POLL=:qq
            where id_answer=:pa
SQL
        , [':qq' => $qq, ':pa' => $pa]
        , function($row,$pa,$lp){
            $row['ID_POLL']=$lp['qq'];
            return $row;
        } 
        , ['qq'=>$qq , 'pa'=>$pa]
        );
        
$ovv = stripos( $r0->row('OPTIONS_POLL') ,'VV' ) !== false;
$data0 = $r0->rows;
$data0[0]['VV_OPTION'] = null;

if($ovv){
    $data0[0]['VV_OPTION'] = 1;
$r1 = new db(DB_POBEDIM,<<<SQL
        select ct.ID_CERTIFICATE
            ,pc.U_DELEGATE
            ,pc.U_ADMIN
            ,pc.ID_ANSWER
            ,pc.ID_KIND_ANSWER
            , uct.* 
            from W2_CERTIFICATE ct 
                inner join U2_CERTIFICATE uct on uct.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                                 uct.U = ct.U_ADMIN
                LEFT outer join W2_POLL_CERTIFICATES pc on pc.ID_POLL = :qq and
                                    pc.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                    pc.ID_KIND_ANSWER <> 0 and
                                    pc.ID_ANSWER = :pa
            where uct.U=:iu 
SQL
      ,[ ':iu'=>$iu , ':qq'=>$qq , ':pa'=>$pa]
      , function($row,$pa,$lp){
            $row['VV_OPTION'] = 1;
            $row['CHECKED_YES'] = ' ';
            $row['CHECKED_NO'] = ' ';
            $row['CHECKED'] = ' ';
           // if($row['ID_KIND_ANSWER'] != 0) $row['CHECKED'] = ' checked ';
            if($row['ID_KIND_ANSWER'] == 1) $row['CHECKED_YES'] = ' checked ';
            if($row['ID_KIND_ANSWER'] == -1) $row['CHECKED_NO'] = ' checked ';
        return $row;
      } 
      , ['qq'=>$qq , 'pa'=>$pa]
       );
} else {
        
$r1 = new db(DB_POBEDIM,<<<SQL
        select ct.ID_CERTIFICATE
            ,pc.U_DELEGATE
            ,pc.U_ADMIN
            ,pc.ID_ANSWER
            ,pc.ID_KIND_ANSWER
            , uct.* 
            from W2_CERTIFICATE ct 
                inner join U2_CERTIFICATE uct on uct.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                                 uct.U = ct.U_ADMIN
                LEFT outer join W2_POLL_CERTIFICATES pc on pc.ID_POLL = :qq and
                                    pc.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                    pc.ID_KIND_ANSWER <> 0
            where uct.U=:iu
SQL
      ,[ ':iu'=>$iu , ':qq'=>$qq ]
      , function($row,$pa,$lp){
            $row['VV_OPTION'] = null;
            if($lp['pa'] == $row['ID_ANSWER'] &&
                $row['ID_KIND_ANSWER'] != 0   ) {
                $row['CHECKED'] = ' checked ';
      } else {  $row['CHECKED'] = ' ';}
        return $row;
      } 
      , ['qq'=>$qq , 'pa'=>$pa]
       );
}


$ht->data('data1', $r1->rows );
$ht->data('data0', $data0 );
 

echo $ht->build('',true);

 
