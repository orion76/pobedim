<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';


require_once ($dir0 . '/thumb.php');
require_once ($dir0 . '/ut2.php');
require_once ($dir0 . '/db2.php');
require_once ($dir0 . '/mailer.php');
//require_once $dir0 . '/pobedim/golosovanie/golosovanie_fn.php';


$ax = val_rq('ax');
$bx = val_rq('bx');

$ix = val_rq('ix',''); // info on header

$u = current_iu();
$fu = current_fu();

 
$ht = new html('/derjava/user_trustees.html');
$menu= menu_user($ht, '');

$cmenu = tag_ul(''
        .tag_li(tag_a('/derjava/user_an_ct.php?ax=', 'Волонтёр') )
        . tag_li(tag_a('/derjava/user_polls.php?ax=', 'Голосования'))
        .tag_li(tag_a('/derjava/user_files.php?ax=filefolder', 'Файлы') )
        );

$ht->subst('cmenu', $cmenu );

$r1 = new db(DB_POBEDIM, <<<SQL
select c0.ID_CERTIFICATE, c0.VECHE, u.u , v.NAME_VECHE, c0.ID_ROLE_VECHE
    from W2_VECHE_CERTIFICATES c0
        left outer join W2_USER u on u.ID_CERTIFICATE_U = c0.ID_CERTIFICATE
        inner join w2_veche v on v.veche=c0.veche
    where c0.U_DELEGATE = :u
    ORDER by c0.VECHE
SQL
        , [':u'=>$u]   );

$ht->data('data1', $r1->rows);

echo $ht->build('',true);