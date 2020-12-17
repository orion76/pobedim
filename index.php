<?php

/*
  mkb   845
  tchk  315
 */


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once ( $dir0.'/ut.php');


if (!file_exists('c:/tmp/u')) mkdir('c:/tmp/u');

$srv = strtolower( $_SERVER['SERVER_NAME'] );
if (isset($_SERVER['QUERY_STRING'])) $q = $_SERVER['QUERY_STRING']; else $q = '';


if ($srv === 'localhost') {
 
//echo htm_redirect1('/tt_test.php');
// echo htm_redirect1('/tk/ltk.php');
//   echo htm_redirect1('/pobedim/index.php');
// echo htm_redirect1('/pobedim/zakonsssr/index.php');
//   echo htm_redirect1('/derjava/home.php');
// echo htm_redirect1('/pobedim/progulki/progulki.php');
//   $_SESSION[SN_HTML_PATH] = 'pobedim/';   // path from base
   if (strpos($q,'7ya')) {
       include $dir0.  '/7ya/index.php';
   } 
   else
       include $dir0.  '/derjava/home.php';
   
   exit;
}
 

$k = strpos($srv, 'open'); // openmeetings
if ($k !== false) {
    echo htm_redirect1('https://pobedim.su:5443/');
    exit;
}
 
if ($srv === 'держава.сайт' || $srv === 'ДЕРЖАВА.САЙТ' || $srv === 'xn--80aafgfg3e.xn--80aswg') {
    echo htm_redirect1('http://держава.сайт/derjava/home.php');
    exit;
}

if ($srv === 'счастливаясемья.рф' || $srv === 'СЧАСТЛИВАЯСЕМЬЯ.РФ' || $srv === 'xn--80aafnmrl7abeg1d4dube.xn--p1ai' || strpos($q,'7ya')) {
    //echo htm_redirect1('/7ya/index.php');
    include $dir0.  '/7ya/index.php';
    exit;
}


if ( ($srv === 'pobedim.su') || (strpos($srv, 'pobedim.su') > 0)
        || (strpos($srv, 'держава.сайт') > 0) 
        || (strpos($srv, 'xn--80aafgfg3e.xn--80aswg') > 0)
        ) {
    include $dir0.  '/derjava/index.php';
    
   // if ($_SERVER['HTTPS'] === 'on') {
        //$_SESSION[SN_HTML_PATH] = 'pobedim/';
        //include 'pobedim/index.php';
        //include '/derzhava/about.php';
     //   echo htm_redirect1('https://pobedim.su/derjava/home.php');
   // } //else {
        // echo htm_redirect1('https://pobedim.su/pobedim/index.php');
        // echo htm_redirect1('http://derzhava.su/derzhava/poisk_golosovania.php?c=150');
    //}
    exit;
}

if ($srv === 'derzhava.su') {
    echo htm_redirect1('http://derzhava.su/derjava/home.php');
    exit;
}

 


$k = strpos($srv, '.kotabl.ru');


if ($k > 3) {
    echo htm_redirect1('/pg404.php');
    exit;
}

include ("kotabl.php");

