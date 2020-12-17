<?php  
//session_start();
$a["subdir"]='/tk/1000/';
$a["tku"]='180917084943';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'
АК-62. Власть провоцирует на гражданскую. 15.09.2018г.
EOT;
$a["topics"]='гражданская война; провокация; ';
$a["subjects"]='КозонковАлександр';
$a["ranges"]='';
$a["urls"]='';
$a["text"]=<<<'EOT'
 [ АК-62. Власть провоцирует на гражданскую. 15.09.2018г. ]( https://youtu.be/MqREEeh-oGY )  

<youtube key="MqREEeh-oGY">     
               
EOT;
$a["publish"]='1';
$a["tk"]='-11099';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
