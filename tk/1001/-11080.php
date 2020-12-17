<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914171454';
$a["iu_owner"] = '1000';
$a["title"] = 'Кедми развенчал мифы о Сталине. Рассудительно, взвешенно, интеллигентно';
$a["topics"] = 'Сталин';
$a["subjects"] = 'КедмиЯков';
$a["ranges"] = '';
$a["urls"] = '';
$a["text"] = ' [ Кедми развенчал мифы о Сталине.  ]( https://youtu.be/OCLaEWSGeQk )  
<youtube key="OCLaEWSGeQk">     
 ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11080';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
