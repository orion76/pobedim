<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/kodeks.html');
$menu= menu_user($ht, '');



function fc($row) {
            $r = new db(DB_POBEDIM, 'select count(distinct ID_POLL) as CNT from U2_POLL where ID_STATUTE=:is'
                    ,[':is'=>$row['ID_STATUTE']]);
            $row['CNT_POLL']=$r->row('CNT');
            if ($row['CNT_POLL'] == 0){
                $row['rows3'] = null;
            } else {
                $r = new db( DB_POBEDIM, 'select * from w2_STATUTE_ANSWER_L2( :is , null )' ,[':is'=>$row['ID_STATUTE']]);
                $row['rows3'] = $r->rows;                
            }
            return $row;
};

$r1 = new db(DB_POBEDIM, 'select * from W2_STATUTE where ID_STATUTE_PARENT is null order by SORTING_STATUTE'
        ,[]
        , function($row){
            $r2 = new db( DB_POBEDIM, 'select * from W2_STATUTE where ID_STATUTE_PARENT=:isp  order by SORTING_STATUTE'
                    ,[':isp'=>$row['ID_STATUTE']]
                    , 'fc' 
                    );
            $row['rows2'] = $r2->rows;
            // if (count($row['rows2']) == 0) return null;
            return fc($row);
        }
        
        );
$ht->data('data1', $r1->rows );

$ht->data('data0', [] );
 




echo $ht->build('',true);


exit;




