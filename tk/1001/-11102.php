<?php  
//session_start();
$a["subdir"]='/tk/1001/';
$a["tku"]='180917095001';
$a["iu_owner"]='1001';
$a["title"]=<<<'EOT'
  России нужен Сталин и он скоро вернется
EOT;
$a["topics"]='Сталин';
$a["subjects"]='ДелягинМихаил';
$a["ranges"]='';
$a["urls"]='';
$a["text"]=<<<'EOT'
 [ Михаил Делягин  России нужен Сталин и он скоро вернется... ]( https://youtu.be/L74Rw4oNbfY )  
<youtube key="L74Rw4oNbfY">     
             
EOT;
$a["publish"]='1';
$a["tk"]='-11102';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
