<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914172421';
$a["iu_owner"] = '1000';
$a["title"] = 'Яков Кедми Лучшее 2018  Часть 2';
$a["topics"] = '';
$a["subjects"] = 'КедмиЯков';
$a["ranges"] = '';
$a["urls"] = '';
$a["text"] = ' [ Яков Кедми Лучшее 2018  Часть 2... ]( https://youtu.be/mDmzPuEt1rc ) 
 <youtube key="mDmzPuEt1rc">     
       ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11081';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
