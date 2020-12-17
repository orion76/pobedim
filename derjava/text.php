<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';



$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();

$tk = val_rq('tk');
$iv = val_rq('iv');

$ht = new html('/derjava/text_page.html');
$menu= menu_user($ht, '');
 
$r0 = new db(DB_POBEDIM,'select tk.* from w2_talk tk where tk.ID_TALK = :tk',[':tk'=>$tk]
,function($row){
    
    
    $s  = '';
    $fs = '/tk/' . $row['U'].'/'.$row['ID_TALK'].'.php';
    $nf = $dir0 = dirname(__DIR__). $fs;
    if (file_exists($nf)){
        unset($a);
        $a['echo']=FALSE;
        require $nf;
        $s = tku_htm($a);
    } 
    $row['TEXT'] = $s;
   
    $row['CAN_EDIT'] = iif($row['U']== current_iu(),1,null);
    return $row;
}
        );

$r0->rows[0]['VECHE'] = $iv;
        
$ht->data('data0', $r0->rows );
 

echo $ht->build('',true);

