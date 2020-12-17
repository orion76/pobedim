<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$w = val_rq('w', 0)*1+0;
$m = val_rq('m',null);

if ($w == 0 && count($_GET)==1) {
    $get = array_keys($_GET);
    $w = $get[0];
}



$u = current_iu();

if ($w == '0'){
   $ht = new html('/derjava/walls_page.html');
   
    unset($r1);
    $r1 = new db(DB_POBEDIM, <<<SQLTEXT

   select W.ID_WALL , COUNT(M.ID_MSG_WALL) CNT_WALL , MAX( M.TS_MSG_WALL ) TS_WALL 
         ,W.VECHE, W.ID_POLL, W.U   
      from w2_wall w
         INNER JOIN W2_WALL_MSG M ON M.ID_WALL = W.ID_WALL
         left outer join w2_veche v on v.veche = W.VECHE
         left outer join w2_poll p on p.ID_POLL = W.ID_POLL
         left outer join w2_user u on u.u = W.U   
      where 
          v.sorting_veche is distinct from -1
          and
          p.sorting_poll is distinct from -1
          and
          u.is_deleted is distinct from 1
              
      GROUP BY 1,W.VECHE, W.ID_POLL, W.U
      HAVING COUNT(M.ID_MSG_WALL) > 0
      order by  3 DESC, 2 DESC
   
SQLTEXT
        ,[]
        , function ($row){
           $v = $row['VECHE'];
           $img1 = null;
           $href = null;
           if ( $v != null) { $img1 = "/data.veche/$v/thumb64.jpg"; $href = "/derjava/veche.php?$v"; }
           
           $p = $row['ID_POLL'];
           if ( $p != null) { $img1 = "/data.poll/$p/thumb64.jpg"; $href= "/derjava/poll.php?$p"; }
           
           $u = $row['U'];
           if ($p == null & $v == null) { $img1 = tag_user_imgsrc ($u); $href= "/derjava/user_contact.php?$u"; }
           
           $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
           
           if (file_exists($dir0.$img1)) $row['IMGSRC_WALL']= $img1; else $row['IMGSRC_WALL']= null;
           $row['IMGA_WALL']=$href;
           return $row;
        }
        );
    $ht->data('data1', $r1->rows);   
} else {
    $ht = new html('/derjava/wall_page.html');    
    $ht->data('data1', null);   
}

$menu= menu_user($ht, 'ACTIVE0');
include $dir0.'/derjava/wall_fn.php';



$data0[0]['WALL'] = $w;


$r8 = getWall5($data0,$u, '0','0','0','',$w,null,$m,null, "/derjava/wall.php?w=".$w ,val_rq('sxw') );



$ht->data('data8', $r8->rows);
$ht->data('data0', $data0 );
$ht->data('menu1', $menu );
echo $ht->build('',true);
