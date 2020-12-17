<?php  
//session_start();
$a["subdir"] = '/tk/1000/';
$a["tku"] = '180914165033';
$a["iu_owner"] = '1000';
$a["title"] = 'Преступное правительство ';
$a["topics"] = '';
$a["subjects"] = 'ВассерманА';
$a["ranges"] = '';
$a["urls"] = '';
$a["text"] = ' [ Преступное правительство ... ]( https://youtu.be/53AKHw7O3lY )
<youtube key="53AKHw7O3lY">     
                ';
$a["publish"] = '1';
$a["MAX_FILE_SIZE"] = '3000000';
$a["tk"] = '-11079';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
