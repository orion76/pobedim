<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

require_once ($dir0 . '/thumb.php');

$ax = val_rq('ax');
$bx = val_rq('bx');

$u = current_iu();
$url_files_u = "/w/u_files/$u";
$dir_files_u = $dir0 . $url_files_u;


if ($bx === 'uploadfile') {
    php_uploadfile($dir_files_u.'/');
}


$ix = val_rq('ix',''); // info on header



$ht = new html('/derjava/user_files.html');
$menu= menu_user($ht, '');


$data1=[];


    $af = directoryToArray($dir_files_u, false);
    $s = '';
    foreach ($af as $nf) {
        array_push($data1, [ 'NAME_FILE'=>$nf , 'HREF_FILE'=>"/u/$u/files/$nf"
                ,'IMG_FILE' => himg_thumb("$url_files_u/$nf", 64)
                ]);
    }

     
$ht->data('data1', $data1);

echo $ht->build('',true);