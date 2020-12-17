<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914215122';
$a["iu_owner"] = '1000';
$a["title"] = 'Пенсии: Москва готовит социальную катастрофу';
$a["topics"] = 'пенсионнаяРеформа2018';
$a["subjects"] = 'ВассерманА';
$a["ranges"] = '';
$a["urls"] = 'ДеньТВ';
$a["text"] = ' [  Пенсии: Москва готовит социальную катастрофу ]( https://youtu.be/OAcrz-iFNzg )  
<youtube key="OAcrz-iFNzg">     

';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11084';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
