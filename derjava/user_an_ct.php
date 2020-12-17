<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

require_once ($dir0 . '/thumb.php');

$ax = val_rq('ax');
$bx = val_rq('bx');

$ix = val_rq('ix',''); // info on header

$u = current_iu();

$ht = new html('/derjava/user_an_ct.html');
$menu= menu_user($ht, '');

if ($ax === 'update'){
    $ct = val_rq('ct');
    $d0 = val_rq('d0');
    $d0 = strtotime($d0); if ($d0 <=0 ){ $d0 = null; } else { $d0= date("d.m.Y",$d0);}
    
    $r = new db(DB_POBEDIM,'select ID_CERTIFICATE from U2_CERTIFICATE_u1(:ct, :u,:n1,:n2,:n3,:d0,:cc,:pb,:el)'
            ,[  ':ct'=>$ct
                ,':u'=>$u
                ,':n1'=> val_rq('n1')
                ,':n2'=> val_rq('n2')
                ,':n3'=> val_rq('n3')
                ,':d0'=> $d0
                ,':cc'=> val_rq('cc')
                ,':pb'=> val_rq('bp')
                ,':el'=> val_rq('el')]);
    $ax='';
}

$cmenu = tag_ul(''
                //.tag_li(tag_a('/derjava/user_trustees.php?ax=trust', 'Голоса'))
                . tag_li(tag_a('/derjava/user_polls.php?ax=', 'Голосования'))
                .tag_li(tag_a('/derjava/user_files.php?ax=filefolder', 'Файлы') )
                );

$ht->subst('cmenu', $cmenu );    

//   сертификаты пользователя
$r1 = new db(DB_POBEDIM
        , <<<SQL
        select  ct.id_certificate, u.u, uct.* 
            from w2_certificate ct 
                left outer join u2_certificate uct on uct.id_certificate=ct.id_certificate and
                        uct.u = ct.u_admin
                left outer join w2_user u on u.id_certificate_u = ct.id_certificate
        where ct.u_admin=:u
SQL
        
        , [':u' => $u]
    );

     
$ht->data('data1', $r1->rows);

echo $ht->build('',true);