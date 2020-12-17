<?php  
//session_start();
$a["subdir"]='/tk/1000/';
$a["tku"]='180916153903';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'

EOT;
$a["topics"]='революция ';
$a["subjects"]='ФурсовА';
$a["ranges"]='1917';
$a["urls"]='';
$a["text"]=<<<'EOT'
 [ Скрытая часть переворота 1917 года. Андрей Фурсов.... ]( https://youtu.be/pK8cAIsNbzc )  
<youtube key="pK8cAIsNbzc">  

EOT;
$a["publish"]='1';
$a["MAX_FILE_SIZE"]='3000000';
$a["tk"]='-11092';
 require_once( dirname(__DIR__). "/tku.php"); 
 if ($_GET["ax"]==="edit") {tku_edit($a, __FILE__);} else {tku_show($a, __FILE__);} 
