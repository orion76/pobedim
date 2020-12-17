<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$fu = current_fu();

if (count($_GET) >= 1) {
    $get = array_keys($_GET);
    $u = $get[0];
} else {
    $u = current_iu();    
}


$ht = new html('/derjava/u_veche.html');
$menu= menu_user($ht, '');
 

$r0 = new db(DB_POBEDIM, 'select * from W2_USER_S3(0,:u)', [':u'=>$u]);

$data0 = $r0->rows;

$ht->data('data0', $data0 );
 
//--- список групп пользователя
$r1 = new db(DB_POBEDIM, <<<SQL
SELECT vc0.ID_ROLE_VECHE, vc0.U_DELEGATE, vc0.U_ADMIN 
     , v.NAME_VECHE   , v.VECHE
     , vc1.CNT0, vc1.CNT3, vc1.CNT9 , vc1.DD0
  from w2_user u
    inner join W2_VECHE_CERTIFICATES vc0 on vc0.ID_CERTIFICATE = u.ID_CERTIFICATE_U and
                                            vc0.ID_ROLE_VECHE is DISTINCT from 10
    inner join W2_VECHE v on v.VECHE = vc0.VECHE and v.SORTING_VECHE is distinct from -1 and v.VECHE > 1
    LEFT outer join (select vcx.VECHE
                        , sum(case when vcx.ID_ROLE_VECHE is null then 1 else 0 end ) as CNT0
                        , max(case when vcx.ID_ROLE_VECHE is null then round( current_timestamp - vcx.TS_SYS) else 0 end ) as DD0
                        , sum(case when vcx.ID_ROLE_VECHE is not null and
                                        vcx.ID_ROLE_VECHE <= 4
                                   then 1 else 0 end ) as CNT3
                        , sum(case when vcx.ID_ROLE_VECHE is not null and
                                        vcx.ID_ROLE_VECHE > 4
                                   then 1 else 0 end ) as CNT9
                            from W2_VECHE_CERTIFICATES vcx
                            where vcx.ID_ROLE_VECHE is DISTINCT from 10
                            group by 1) vc1 on vc1.VECHE = vc0.VECHE
  where u.u = :u
  order by vc0.ID_ROLE_VECHE, vc1.CNT0 desc       
SQL
        ,[':u'=>$u]
        , function($row){
            if ( $row['ID_ROLE_VECHE'] == null ) $row['ID_ROLE_VECHE'] = '?';
            if ($row['CNT0'] == 0) {$row['CNT0'] = '';}
            if ($row['DD0'] == 0) {$row['DD0'] = '';}
            if ($row['CNT3'] == 0) {$row['CNT3'] = '';}
            if ($row['CNT9'] == 0) {$row['CNT9'] = '';}
            return $row;
        });

$ht->data('data1', $r1->rows );
echo $ht->build('',true);

 