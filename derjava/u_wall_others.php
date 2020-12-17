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


$ht = new html('/derjava/u_wall_others.html');
$menu= menu_user($ht, 'ACTIVE16');
 
$data0[0] =[];
$ht->data('data0', $data0 );

$r1 = new db(DB_POBEDIM, 'select * from W2_WALL_MSG_OTHERS_L4(:u)'
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

echo $ht->build('',true);

