<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';

$ax = val_rq('ax', 0);
$rx = val_rq('rx');if (empty($rx)) { $rx = val_sn('rx'); }

if ($ax=='fb') $ax=108;
if ($ax=='vk') $ax=109;
if ($ax=='ok') $ax=110;

if (!file_exists('c:/tmp/u')) mkdir('c:/tmp/u',0777);

$nf = 'c:/tmp/u/' . str_replace(':', '-', $_SERVER['REMOTE_ADDR']) . '.ini';
if ( $ax == 108 || $ax == 109 || $ax == 110) {
    file_put_contents($nf, ini_from_array(['rx' => $rx, 'base_url' => f_host()]));
}

switch ($ax) {
    case 108:
        require_once( f_root() . '/auth/auth.php');
        // создаём промежуточный файл и переходим на Facebook для подтверждения
        echo htm_redirect_login_fb_($_GET['rx']);
        exit;
    case 109:
        require_once( f_root() . '/auth/auth.php');
        // создаём промежуточный файл и переходим на vKontakte для подтверждения
        echo htm_redirect_login_vk_($_GET['rx']);
        exit;

    case 110:
        require_once( f_root() . '/auth/auth.php');
        echo htm_redirect_login_ok_($_GET['rx']);
        exit;
        
}