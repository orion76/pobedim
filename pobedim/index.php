<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
 
require_once $dir0 . '/ut.php';


echo htm_redirect1('/derjava/home.php');

 