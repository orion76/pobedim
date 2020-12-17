<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$dir_wall = $dir0 .'/w';

$ax = val_rq('ax', 0);
$ku = val_rq('ku', -1);
$u = current_iu();


$ht = new html('/derjava/u_wall_chat.html');
$menu= menu_user($ht, 'ACTIVE16');
 

$r = new db(DB_POBEDIM, <<<TXTSQL
                
        select * 
            from W2_USER_KIND_CHAT_L(:u)
            where coalesce(SORTING_KIND_USER,0) >= 0 and
                  NAME_KIND_USER <> '' 
TXTSQL
                ,[':u'=>$u]
                ,function($row,$pp,$lp){
                    $row['SELECTED'] = iif ($row['ID_KIND_USER']==$lp['ku'] , ' selected ', null);
                    return $row;
                }
                ,['ku'=>$ku] );
$ht->data('data20',$r->rows);



$data0[0]['CHECKED1'] = iif( val_rq('on:liked')=='true' ,' checked ',null);
$data0[0]['CHECKED2'] = iif( val_rq('on:marked')=='true' ,' checked ',null);
$data0[0]['CHECKED3'] = iif( val_rq('on:trashed')=='true' ,' checked ',null);
$data0[0]['CHECKED4'] = iif( val_rq('on:casted')=='true' ,' checked ',null);
$data0[0]['CHECKED5'] = iif( val_rq('on:attached')=='true' ,' checked ',null);
$data0[0]['CHECKED6'] = iif( val_rq('on:pub')=='true' ,' checked ',null);

$ht->data('data0', $data0 );

function ck_($v){ if (empty($v)) return 0; else return 1; }

$r1 = new db(DB_POBEDIM, 'select * from W2_WALL_MSG_CHAT_L2(:u,:ku,:ck1,:ck2,:ck3,:ck4,:ck5,:ck6)'
        , [':u'=>$u , ':ku'=>$ku
            , ':ck1'=>ck_(va0($data0, 'CHECKED1'))
            , ':ck2'=>ck_(va0($data0, 'CHECKED2'))
            , ':ck3'=>ck_(va0($data0, 'CHECKED3'))
            , ':ck4'=>ck_(va0($data0, 'CHECKED4'))
            , ':ck5'=>ck_(va0($data0, 'CHECKED5'))
            , ':ck6'=>ck_(va0($data0, 'CHECKED6'))
            ]
         , function ($row,$p,$lp){
            $w = $row['ID_WALL'];
          
            $sx = substr( $row['BLOB_MSG_WALL'] ,0, 500 );
            $i = strrpos($sx, ' '); $sx = substr( $sx ,0, $i );
            $row['BLOB_MSG_WALL_BRIEF'] = $sx;
            
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

