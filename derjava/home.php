<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';


$ht = new html('/derjava/home_page.html');
$menu= menu_user($ht, 'ACTIVE6');

$ht->part['head'][0]['TITLE'] =  'Народовластие, система самоуправления народа | Держава, pobedim.su';
$ht->part['head'][0]['TITLE_OG'] = 'Народовластие, система самоуправления народа (МСУ,ТОС,ТСЖ,.., народное законодатедьство) | Держава';
$ht->part['head'][0]['IMG_OG'] = 'https://img.youtube.com/vi/CbYpWRo4Jy0/hqdefault.jpg'; //f_host($src) ;



$ht->data('data0', [] );
$ht->data('menu1', $menu );
echo $ht->build('',true);




