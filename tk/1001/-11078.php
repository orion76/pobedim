<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914164721';
$a["iu_owner"] = '1000';
$a["title"] = 'Шокирующая правда о Сталине. Фурсов А.И.';
$a["topics"] = 'Сталин';
$a["subjects"] = 'ФурсовАИ';
$a["ranges"] = '';
$a["urls"] = '';
$a["text"] = ' [ Шокирующая правда о Сталине. Фурсов А.И.... ]( https://youtu.be/cdeBsrIXmm8 ) 
 <youtube key="cdeBsrIXmm8">     

';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11078';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
