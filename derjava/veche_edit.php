<?php

//   РЕДАКТИРОВАТЬ ГРУППУ МОЖЕТ ТОЛЬКО АДМИНИСТРАТОР
 
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';


$ax = val_rq('ax','edit');
$iv = val_rq('iv', 0);
if (empty($iv)) {
    $iv = 0;
}
$u = current_iu();
$bx = val_rq('bx');


 

$r0 = new db(DB_POBEDIM, <<<TXT
        select v.* 
               ,vk.NAME_KIND_VECHE
               ,vc.ID_ROLE_VECHE
               ,p70.ID_POLL as ID_POLL_70
            from w2_veche v
              inner join w2_user u on u.u =:u
              left outer join w2_veche_certificates vc on vc.veche=v.veche and vc.id_certificate= u.id_certificate_u
              left outer join w2_veche_kind vk on vk.id_kind_veche = v.id_kind_veche
              left outer join w2_poll p70 on p70.veche=v.veche and p70.id_kind_poll=70
           where v.veche=:iv
TXT
        , [':iv' => $iv, ':u' => $u]
        , function($row,$pp){
            if ( $row['CAN_GUEST_EDIT_WALL'] != 0 ) $row['CAN_GUEST_EDIT_WALL']=' checked '; else $row['CAN_GUEST_EDIT_WALL']=null;
            if ( $row['CAN_MEMBER_EDIT_WALL'] != 0 ) $row['CAN_MEMBER_EDIT_WALL']=' checked '; else $row['CAN_MEMBER_EDIT_WALL']=null;
            if ( $row['CAN_GUEST_COMMENT_WALL'] != 0 ) $row['CAN_GUEST_COMMENT_WALL']=' checked '; else $row['CAN_GUEST_COMMENT_WALL']=null;
            $r = new db(DB_USER, 'select * from w0_veche v where v.veche=:v',[':v'=>$pp[':iv']] );
            $row['TEXT_MSG_MEMBER_VECHE'] = $r->row('TEXT_MSG_MEMBER_VECHE');
            $row['rows70'] = new db(DB_POBEDIM, 'select j.*, 0 as WAGE_JOB_VECHE from V2_VECHE_JOB j where j.VECHE = :iv' , [':iv' => $pp[':iv']], function($row){return $row;} );
            return $row;
        }
        );
$rv = $r0->row('ID_ROLE_VECHE');

if ($ax === 'add_web_veche') {
//    if ( $r0->row ('ID_ROLE_VECHE') === "1" ){
    $r = new db(DB_POBEDIM, 'insert into  w2_veche_web2 (veche, URL_WEB_VECHE, TEXT_WEB_VECHE, u_admin) values (:iv, :u1,:t,:u)'
            , [':iv' => $iv, ':u1' => val_rq('uwv__'), ':t' => val_rq('twv') , ':u'=>$u ]);
//    }
}



$can_set_result = 1;
$ht = new html('/derjava/veche_edit.html');
$menu= menu_user($ht, '');

$data0 = $r0->rows;


$imgsrc = "/data.veche/$iv/$iv.jpg";
$szf = filesize_($imgsrc);
$data0[0]['IMG_VECHE'] = $imgsrc ."?$szf";
if ($szf === false) {
    $data0[0]['STYLE_IMG_VECHE'] = 'height:0px;';
} else {
    $data0[0]['STYLE_IMG_VECHE'] = '';// 'height:auto;';
}


$r2 = new db(DB_POBEDIM, 'select * from W2_VECHE_WEB2 where VECHE = :iv'
            , [':iv' => $iv]
    );

$ht->data('data2', $r2->rows);
$ht->data('data0', $data0);

// --- является ли эта группа комитетом?  заполняем data99
include $dir0 . '/derjava/veche_include99.php';


echo $ht->build('',true);

