<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/download_run.html');
$menu= menu_user($ht, '');
 

if (!isUserLoggedOn()) {

    $s = 'Требуется авторизация.';
} else {

    $r = new db(DB_POBEDIM, 'insert into W2_DISTRIBUTING (u) values (:u)', [':u' => $u]);

    $nf = dirname(__DIR__, 1) . '/pobedim_web.zip';
    $tf = filemtime($nf);

    if ($tf === false) {
        $s = 'Архив временно не доступен, повторите попытку через несколько минут или сообщиет об этом по адресу admin@pobedim.su';
    } else {

        $s = date("d.m.Y H:i:s.", $tf);
        $szf = round(filesize($nf) / 1024 / 1024);
        $s = tag_a('/pobedim_web.zip', "Архив сайта от $s, размер $szf MB");
    }
}


$data0[0]['TEXT'] = $s;
$ht->data('data0', $data0 );



echo $ht->build('',true);


exit;




