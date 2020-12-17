<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$ct = val_rq('ct');
$fu = current_fu();
$u = current_iu();


if (empty($ct)) {
    $r = new db(DB_POBEDIM, 'select ID_CERTIFICATE_U from w2_user where u=:u',[':u'=>$u]);
    $ct = $r->row('ID_CERTIFICATE_U');
}

$ht = new html('/derjava/ck.html');
$menu= menu_user($ht, '');

$r0 = new db(DB_POBEDIM, 'select * from V2_CERTIFICATE where ID_CERTIFICATE=:ic',[':ic'=>$ct]);
$data0 = $r0->rows;
$data0[0]['CT']=$ct;

$r1 = new db(DB_POBEDIM, <<<SQL
select c.*
     , p.NAME_POLL
     , pa.NAME_ANSWER
    FROM W2_POLL_CERTIFICATES c
        inner join W2_POLL p on p.ID_POLL = c.ID_POLL and p.SORTING_POLL is distinct from -1
        inner join W2_POLL_ANSWER pa on pa.ID_ANSWER = c.ID_ANSWER 
    where c.ID_CERTIFICATE = :ct and
          c.ID_KIND_ANSWER <> 0
    order by c.TS_SYS desc
SQL
        ,[':ct'=>$ct]
        ,function($row){
                $row['NO'] = null;
                $row['YES'] = null;
                $row['CHECK'] = null;
                if ($row['ID_KIND_ANSWER'] == 2) $row['CHECK'] = 1;
                if ($row['ID_KIND_ANSWER'] == 1) $row['YES'] = 1;
                if ($row['ID_KIND_ANSWER'] == -1) $row['NO'] = 1;
            return $row;
        }
        );


$ht->data('data0', $data0 );
$ht->data('data1', $r1->rows );
 

echo $ht->build('',true);

 