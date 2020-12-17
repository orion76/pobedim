<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$ts = val_rq('ts', 0);
$w = val_rq('w', 0);
$mx = val_rq('mx', 0);
$u = current_iu();

//if(isUserLoggedOn()){
    $ht = new html('/derjava/wall.html');
    $menu= menu_user($ht, '');
    
    include $dir0.'/derjava/wall_fn.php';
    
    //$r8 = getWall4($data0,$u,'0','0','0','',$w,$ts,0,$mx);

    $rx='/derjava/wall.php?w='.$w;
//$w=val_rq('w');
$msg_last=$ts; // пост по которому измерять время
$m=val_rq('m');   // пост который нужно отобразить как единственный
  //mx -  пост, который отображать не надо
$sx= val_rq('sxw');
     
$r8 = getWall5($data0,$u, '0', '0', '0' , $rx
            ,$w ,$msg_last ,$m ,$mx ,$rx ,$sx );
    
    
    $ht->data('data8', $r8->rows);
    $ht->data('data0', $data0 );
    $r = $ht->build('',true);
    echo $r;
//}
  
