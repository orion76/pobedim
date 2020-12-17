<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180911201719';
$a["iu_owner"] = '1000';
$a["title"] = 'Шпаргалка по синтаксису markdown';
$a["topics"] = '';
$a["subjects"] = '';
$a["ranges"] = 'markdown';
$a["urls"] = '';
$a["text"] = ' [ Шпаргалка по синтаксису markdown (маркдаун) со всеми самыми популярными тегами ]( http://ilfire.ru/kompyutery/shpargalka-po-sintaksisu-markdown-markdaun-so-vsemi-samymi-populyarnymi-tegami/ )  ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11067';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
