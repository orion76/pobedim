<?php  
//session_start();
$a["subdir"] = '/tk/1001/';
$a["tku"] = '180913185259';
$a["iu_owner"] = '1001';
$a["title"] = 'День Конституции РФ  ПОЛНАЯ ВЕРСИЯ';
$a["topics"] = 'Конституция1993';
$a["subjects"] = '';
$a["ranges"] = 'РФ';
$a["urls"] = '';
$a["text"] = ' [ День Конституции РФ  ПОЛНАЯ ВЕРСИЯ... ]( https://www.youtube.com/watch?v=hBN9f3EopJI ) 
 <youtube key="hBN9f3EopJI">     

                ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11077';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
