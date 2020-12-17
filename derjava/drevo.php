<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

require_once $dir0.'/derzhava/derzhava_fn.php';

$ax = val_rq('ax');
$bx = val_rq('bx');
$iv = val_rq('iv');
$sy = val_rq('sy'); // id_speciality

$u = current_iu();


$ht = new html('/derjava/drevo.html');
$menu= menu_user($ht, '');
$menu['RX']='';

$data0['VECHE'] = $iv;
$ht->data('data0', $data0);

$kv = 1;
$ht->data('kind_veche', rows_kind_veche($kv));


$r1 = new db(DB_POBEDIM, <<<TXT
            select sy.ID_SPECIALITY, sy.NAME_SPECIALITY, count(1) as CNT_SY
                from W2_SPECIALITY sy 
                  inner join W2_USER_SPECIALITIES syu on syu.ID_SPECIALITY=sy.ID_SPECIALITY
                where sy.ID_SPECIALITY > 1
                group by 1,2
                order by sy.NAME_SPECIALITY
            
TXT
            ,[] 
        );
        
$ht->data('data1', $r1->rows );        


$r2 = new db(DB_POBEDIM, <<<TXT
            select first 300 distinct   u.U
                from w2_user u 
                inner join W2_USER_SPECIALITIES syu on syu.U=u.U and  syu.ID_SPECIALITY=:sy
                where u.U not in (1128)  and u.U > 1000
                order by u.U desc
            
TXT
            ,[':sy' => $sy ] 
        , function($row){
            $html = null;
            $row['SRC_IMG_U'] = user_imgsrc( $row['U'] );
            return $row; 
        }
    );

$ht->data('data2', $r2->rows );        
echo $ht->build('', true);



 