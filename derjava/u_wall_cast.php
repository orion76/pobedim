<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$dir_wall = $dir0 .'/w';

$ax = val_rq('ax', 0);
$do_show_all_new = val_rq('all_new', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/u_wall_cast.html');
$menu= menu_user($ht, 'ACTIVE17');
 
$data0[0] =[];
$ht->data('data0', $data0 );

$r1 = new db(DB_POBEDIM, 'select * from W2_WALL_MSG_CAST_L4(:u, 10)'
        , [':u'=>$u]
         , function ($row,$p,$lp){
            $w = $row['ID_WALL'];
          
            $sx = substr( $row['BLOB_MSG_WALL_PARENT'] ,0, 500 );
            $i = strrpos($sx, ' '); $sx = substr( $sx ,0, $i );
            $row['BLOB_MSG_WALL_PARENT_BRIEF'] = $sx;
            
            $dir = $lp['dir']."/".$w;
            if ( !file_exists( $dir)) { mkdir( $dir ,0777,true); }
            $nf = $dir ."/index.php";
if (!file_exists($nf)){
                $m = $row['ID_MSG_WALL_PARENT'];
                $v = $row['VECHE'];
                $p = $row['ID_POLL'];
                $wu = $row['U'];

    if ($v != null) 
    {
      $s = "/derjava/veche.php?w=$w&iv=$v&m=$m#m$m";
    } else 
    if ($p != null) 
    {
      $s = "/derjava/poll.php?w=$w&qq=$p&m=$m#m$m";
    }
    else              
      $s = "/derjava/user_contact.php?$wu&w=$w&m=$m#m$m";
    
                $row['HREF'] = $s;
                
 $sx = '"0;url=https://pobedim.su'.$s.'"';
 
                file_put_contents($nf, <<<TXT
<?php
echo '
<!DOCTYPE html>
<html><head><meta http-equiv="refresh" content=
TXT
. $sx . 
<<<TXT
></head><body></body>
</html>';
TXT
                        );
            }
          return $row;
        }
        ,['dir'=>$dir_wall]
        );

$ht->data('data1', $r1->rows );



$r2 = new db(DB_POBEDIM, <<<TXTSQL

   SELECT PU.ID_POLL, p.NAME_POLL, max(PAA.TS_INSERTED), count(1) as CNT
    FROM U2_POLL PU
      inner join w2_poll p on p.id_poll=pu.id_poll and p.SORTING_POLL is distinct from -1
      INNER JOIN W2_POLL_ANSWERS PAA ON PAA.ID_POLL = PU.ID_POLL AND
                                        PAA.TS_INSERTED > PU.TS_READ_POLL
    WHERE PU.U = :u
    GROUP BY 1,2
    order by 3 desc

TXTSQL
        , [':u'=>$u]
         , function ($row,$p,$lp){ return $row;}
         ,[]
         );
$ht->data('data2', $r2->rows );
/*
*/



echo $ht->build('',true);

