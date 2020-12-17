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

$r2 = new db(DB_POBEDIM, 'select * from w2_veche where veche=:v',[':v'=>$sx]);
$r3 = new db(DB_POBEDIM, 'select * from w2_poll where id_poll=:p',[':p'=>$sx]);
    

$aa=directoryToArray("$dir0/u",1);
foreach ($aa as $f){ 
    if (strpos($f,'.user')!==false) @unlink($dir0.'/u'.$f);
    if (strpos($f,'.tmp')!==false) @unlink($dir0.'/u'.$f);
    if (strpos($f,'/index.php')!==false) @unlink($dir0.'/u'.$f);
    if (strpos($f,'/x.css')!==false) @unlink($dir0.'/u'.$f);
    if (strpos($f,'/thumb64.png')!==false) @unlink($dir0.'/u'.$f);
    
    if (strpos($f,'_0.png')!==false) {
        $s = str_replace('/thumb','', $f);
        $d = dirname($dir0.'/w/u_files'.$s); mkdir($d,0777,true);
        rename($dir0.'/u'.$f , $dir0.'/w/u_files'.$s);
        }
    if (strpos($f,'/thumb')!==false) @rmdir(dirname($dir0.'/u'.$f));
    $k = strpos($f,'/',2); $f = substr($f,0,$k);
    @rmdir($dir0.'/u'.$f);
}

$u = current_iu();

unset($r1);
if ( filter_var($sx, FILTER_SANITIZE_NUMBER_INT) ) {
    $row['U'] = $sx;
    fill_row_user($row);
    $r1[0]=$row;
} else {
    
$c = val_rq('c',1);
 set_time_limit(120);
 $r1 = new db(DB_USER, "select first $c distinct U from W0_USER_SX_L3 ( :sx ) where U > 1000 order by u desc"
            , [':u' => $u , ':sx'=>$sx ]
        ,   function($row) {
    
     
               $fu= fill_row_user($row);
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

$ht->data('data1', $r1);
$ht->data('data2', $r2);
$ht->data('data3', $r3);

echo $ht->build('',true);
