<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914191038';
$a["iu_owner"] = '1000';
$a["title"] = ' про мировую обстановку, социализм и Собянина';
$a["topics"] = '';
$a["subjects"] = 'ВассерманА';
$a["ranges"] = '';
$a["urls"] = '';
$a["text"] = ' [ Разведопрос: Анатолий Вассерман про мировую обстан... ]( https://youtu.be/nO1PVHoZYEY )  <youtube key="nO1PVHoZYEY">     
</youtube>       ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11083';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
