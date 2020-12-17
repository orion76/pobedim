<?php  
//session_start();
$a["subdir"]='/tk/1000/';
$a["tku"]='180916143317';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'
  Опасность городов будущего в России. Андрей Фурсов
EOT;
$a["topics"]='';
$a["subjects"]='ФурсовА';
$a["ranges"]='';
$a["urls"]='';
$a["text"]=<<<'EOT'

 [ Опасность городов будущего в России. Андрей Фурсов.... ]( https://youtu.be/l_gwmTA5ziY )  

<youtube key="l_gwmTA5ziY">     
                
EOT;
$a["publish"]='1';
$a["MAX_FILE_SIZE"]='3000000';
$a["tk"]='-11091';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
