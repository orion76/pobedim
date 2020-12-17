<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

require_once ($dir0 . '/thumb.php');

$ax = val_rq('ax');
$bx = val_rq('bx');
$sx = val_rq('sx');
$lu = val_rq('lu',0);

$ix = val_rq('ix',''); // info on header

$u = current_iu();

$ht = new html('/derjava/user_search.html');
$menu= menu_user($ht, '');

$data0[0]['CNT_SX'] = null;

$r2 = null;
$r3 = null;
$r1 = [];
if ($lu == 6 || $lu == 7 ) {
    $r1 = new db(DB_POBEDIM,'select u from u2_poll where id_POLL=:p and IS_LIKE=:like',[':p'=>$sx, ':like'=>iif($lu == 7,1,-1)]
            ,  function($row) { $fu= fill_row_user($row);user_geo_get($row,$fu);  return $row;     }     );
}
else    
if ($lu == 5 ) {
    $r1 = new db(DB_POBEDIM,'select m.u from u2_wall_msg m where m.id_msg_wall=:m and m.IS_LIKED=1',[':m'=>$sx]
            ,  function($row) { $fu= fill_row_user($row);user_geo_get($row,$fu);   return $row;    }      );
}
else if ($lu >= 1 && $lu <=4) {
    foreach ( $_SESSION['TN'] as $row){
        if ($row['U']== $sx){
            
            $sx = $row['LIST_LINKS'.$lu];
            
            $lx = explode(',', $sx);
            foreach ($lx as $u)
            if (!empty($u))
            {
                $row['U'] = $u;
                $fu=fill_row_user($row);
                user_geo_get($row,$fu);
                array_push($r1,$row);
            }
            break;
        }
    }
} else {


$r2 = new db(DB_POBEDIM, 'select * from w2_veche where veche=:v',[':v'=>$sx]);
$r3 = new db(DB_POBEDIM, 'select * from w2_poll where id_poll=:p',[':p'=>$sx]);
    
unset($r1);
if ( filter_var($sx, FILTER_SANITIZE_NUMBER_INT) ) {
    $row['U'] = $sx;
    $fu= fill_row_user($row);
    user_geo_get($row,$fu);
    $r1[0]=$row;
} else {
    
 $c = val_rq('c',100);
 $data0[0]['CNT_SX'] = $c;
 set_time_limit(120);
 $r1 = new db(DB_USER, "select first $c distinct U from W0_USER_SX_L3 ( :sx ) ORDER BY TS_LOGIN DESC "
            , [':u' => $u , ':sx'=>$sx ]
        ,   function($row) {
               $fu= fill_row_user($row);
               user_geo_get($row,$fu);
    /*
               $cf = false; 
               if (strpos($row['NAME_U'], 'user') !== false) $row['NAME_U'] = '';
               if (stripos($row['NAME_U'], 'pobedim') !== false) $row['NAME_U'] = '';
               
               if ( empty($row['NAME_U'] )) { $cf = get_f_user($row['U'],'iu'); set_f_user($cf); } 
               $row['IMGSRC_U']= tag_user_imgsrc0($row['U']);
     */ 
                return $row;   
        }        
     );
}

}

$ht->data('data0', $data0);
$ht->data('data1', $r1);
$ht->data('data2', $r2);
$ht->data('data3', $r3);

echo $ht->build('',true);
