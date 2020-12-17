<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/sys_about.html');
$menu= menu_user($ht, '');


$r1 = new db(DB_POBEDIM, <<<SQL
        select m.* 
          from w2_wall_msg m
           where m.ID_WALL=-5820
           order by sorting_msg_wall 
SQL
        ,[]
        , function($row){
    
            $r = veche_get_imagehref( $row['BLOB_MSG_WALL'] ); 
                if (!empty($r['imgsrc'])){
                    if (empty($r['onclick'])) { $imgstyle = null; } else { $imgstyle= 'cursor: pointer'; }
                    $row['IMGSRC_MSG_WALL'] = trim($r['imgsrc']);
                    $row['IMG_CLICK']=$r['onclick'];
                    $row['IMG_STYLE']=$imgstyle;
                    $row['TITLE']=$r['title'];
                    $row['KEY_YOUTUBE']=$r['key_youtube'];
                    $row['T_YOUTUBE']=$r['t'];
                    $row['IMG']=0;
                    $row['IFRAME']=null;
                }
            return $row;
        });


        
$ht->data('data1', $r1->rows );


   if ( checkAddressPort('192.168.1.67_',5443) )
   {$data0[0]['OPENMEETINGS_ON'] = 1;  $data0[0]['OPENMEETINGS_OFF'] = null;}
   else 
   {$data0[0]['OPENMEETINGS_ON'] = null;  $data0[0]['OPENMEETINGS_OFF'] = 1;}


$ht->data('data0', $data0 );
 

echo $ht->build('',true);

