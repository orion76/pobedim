<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax');
$iv = val_rq('iv', 0);
$u = current_iu();
$bx = val_rq('bx');
$qq = val_rq('qq');
if (empty($qq)) $qq = val_rq('p');


if (empty($iv)) {
    $iv = 0;
}


//$r0 = new db(DB_POBEDIM,'select w.ID_WALL, p.* from w2_poll p left outer join w2_wall w on w.id_poll=p.id_poll  where p.id_poll =:qq',[':qq'=>$qq] );
$r0 = new db(DB_POBEDIM,'select p.* from w2_poll p where p.id_poll =:qq',[':qq'=>$qq] );
$data0 = $r0->rows;

if ($r0->row('ID_KIND_POLL') == '4'){
    $ht = new html('/derjava/slovar_edit.html');
}
else {
    $ht = new html('/derjava/poll_edit.html');
}
$menu= menu_user($ht, '');

$nd = "/data.poll/$qq";
$data0[0]['IMG_POLL'] = "$nd/$qq.jpg"; 

/*
if (!file_exists( $dir0 . "$nd/$qq.jpg" )) 
    {$data0[0]['STYLE_IMG_POLL'] = 'max-height:200px;';}
    else{$data0[0]['STYLE_IMG_POLL'] = 'max-height:200px;';}
*/

$data0[0]['CAN_DELETE_POLL'] = 1;

if($r0->row('END_POLL')!= ''){
    $data0[0]['DEP_CHECKED'] = ' checked="checked" ';
    $data0[0]['EP_CHECKED'] = ' ';
} else {
    $data0[0]['DEP_CHECKED'] = ' ';
    $data0[0]['EP_CHECKED'] = ' checked="checked" ';
}
        

$data0[0]['AA_CHECKED'] = iif(strpos($r0->row('OPTIONS_POLL'),'AA')===false,'',' checked="checked" ');
$data0[0]['VV_CHECKED'] = iif(strpos($r0->row('OPTIONS_POLL'),'VV')===false,'',' checked="checked" ');
$data0[0]['PP_CHECKED'] = iif(strpos($r0->row('OPTIONS_POLL'),'PP')===false,'',' checked="checked" ');
$data0[0]['WW_CHECKED'] = iif(strpos($r0->row('OPTIONS_POLL'),'WW')===false,'',' checked="checked" ');


$r1 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_poll =:qq and SORTING_ANSWER >-1 order by sorting_answer'
        ,[':qq'=>$qq]
        ,function($row){
               $qq = $row['ID_POLL'];
               $pa = $row['ID_ANSWER'];
               $src = "/data.poll/$qq/$pa.jpg";
               $row['IMGSRC_ANSWER'] = $src;
            //    return tag_tr( script_edit_golosovanie_answer($qq,$pa,$row['NAME_ANSWER'],0) );
            return $row;
            }
        );
$ht->data('data1', $r1->rows);


$ht->data('data0', $data0);
echo $ht->build('', true);
exit();


