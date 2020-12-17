<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$u = current_iu();


$ht = new html('/derjava/u_questions.html');
$menu= menu_user($ht, '');
 
$data0[0] = [];
$ht->data('data0', $data0 );


$r3 = new db(DB_USER,<<<SQL
    select u0.U_USER
        , sum( case when c1.TS > u0.TS_VIEW and c1.T_TEXT_CONVERSE = 4 then 1 ELSE 0 end ) as CNT4_NOT_VIEWED
        , sum( case when c1.TS > u0.TS_VIEW and c1.T_TEXT_CONVERSE = 3 then 1 ELSE 0 end ) as CNT3_NOT_VIEWED
        , sum( case when c1.T_TEXT_CONVERSE = 1 and c0.T_TEXT_CONVERSE = 4 then 1 ELSE 0 end ) as CNT1_SENT        
        , sum( case when c1.T_TEXT_CONVERSE = 1 and
              c0.T_TEXT_CONVERSE = 4 AND
              COALESCE( u1.TS_VIEW,c0.TS) > c0.TS
         then 1 ELSE 0 end ) as CNT1_READ
        
        , sum(1) as CNT_VIEWED
            from U2_converse c0
                inner join U2_CONVERSE c1 on c1.ID_CONVERSE = c0.ID_CONVERSE and
                                             c1.U <> c0.U
                inner join U0_USER u0 on u0.u = c0.U and
                                         u0.U_USER = c1.U
                left outer join U0_USER u1 on u1.u = c1.U and
                                         u1.U_USER = c0.U        
            where c0.u = :u
            group by 1
            order by 3 desc,2 desc
SQL
        ,[':u'=>$u]
        , function($row){ 
        if ($row['CNT1_SENT'] == 0)  $row['CNT1_SENT'] = null;
        if ($row['CNT1_READ'] == 0)  $row['CNT1_READ'] = null;
        if ($row['CNT4_NOT_VIEWED'] == 0)  $row['CNT4_NOT_VIEWED'] = null;
        if ($row['CNT3_NOT_VIEWED'] == 0)  $row['CNT3_NOT_VIEWED'] = null;
        $row['IMGSRC_U'] = tag_user_imgsrc0($row['U_USER']);
    return $row;
        }
        );

$ht->data('data3', $r3->rows);        

echo $ht->build('',true);

 


