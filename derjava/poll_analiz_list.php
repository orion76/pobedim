<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);

$u = current_iu();
$iv = val_rq('iv', 0) + 0;
$qq = val_rq('p', 0) + 0;
$pa = val_rq('pa', 0) + 0;
$ku = val_rq('ku',-1);

$lon = val_rq('lon','user');


$err = '';

    $r7 = new db(DB_POBEDIM, 'select * from W2_POLL_ANALIZ_LIST_L2 (:u, :iv, :qq, :pa, :ku)'
            , [':u'=>$u, ':iv'=>$iv,  ':qq' => $qq, ':pa' => $pa, ':ku'=>$ku]
            ,function($row,$pp,$lp){ 
        
               if ($row['IS_CONFIRMED_U'] == '1')
               {
                   $row['CONFIRM_CHG'] = 'clear';
                   $row['CONFIRM_MINUS'] = (1*$row['CNT_CONFIRMED']-1);
                   $row['CONFIRM_PLUS'] = $row['CNT_CONFIRMED'];
               } else {
                   //$row['IS_CONFIRMED_U'] = null;
                   $row['CONFIRM_CHG'] = 'exposure_plus_1';
                   $row['CONFIRM_MINUS'] = $row['CNT_CONFIRMED'];
                   $row['CONFIRM_PLUS'] = (1*$row['CNT_CONFIRMED']+1);
               }
/*            
               $u=$pp[':u'];
               $pa=$pp[':pa'];
               $qq=$pp[':qq'];
               $ct=$row['ID_CERTIFICATE'];
*/
                if (!isset($row['NAME_ROLE_VECHE'])) $row['NAME_ROLE_VECHE'] = 'Гость';
        
                $row['NO'] = null;
                $row['YES'] = null;
                $row['CHECK'] = null;
                if ($row['ID_KIND_ANSWER'] == 2) $row['CHECK'] = 1;
                if ($row['ID_KIND_ANSWER'] == 1) $row['YES'] = 1;
                if ($row['ID_KIND_ANSWER'] == -1) $row['NO'] = 1;
                if ($row['U_ADMIN'] == $row['U_DELEGATE']) $row['HAS_DELEGATE']=null; else $row['HAS_DELEGATE']=1;
                return $row; 
            },['dir0'=>$dir0]
            );
            
    $r0 = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER where ID_ANSWER = :pa',[':pa'=>$pa]);



$ht = new html('/derjava/poll_analiz_list.html');
$menu= menu_user($ht, '');



$r = new db(DB_POBEDIM, <<<TXTSQL
        select * 
            from W2_USER_KIND_POLL_ANALIZ_L(:u,:v,:p)
            where coalesce(SORTING_KIND_USER,0) >= 0 and
                  NAME_KIND_USER <> '' 
TXTSQL
                ,[':u'=>$u,':v'=>$iv,':p'=>$qq]
                ,function($row,$pp,$lp){
                    $row['SELECTED'] = iif ($row['ID_KIND_USER']==$lp['ku'] , ' selected ', null);
                    return $row;
                }
                ,['ku'=>$ku] );
$ht->data('data20',$r->rows);




$data0 = $r0->rows;
$data0[0]['ID_POLL'] = $qq;
$data0[0]['VECHE'] = $iv;
$data0[0]['ID_ANSWER'] = $pa;
$data0[0]['CNT_ANSWER'] = $r7->length();

$ht->data('data0', $data0 );
$ht->data('data7', $r7->rows );

echo $ht->build('',true);

 