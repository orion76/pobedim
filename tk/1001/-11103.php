<?php  
session_start();
$a["subdir"]='/tk/1000/';
$a["tku"]='180918215742';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'
zzzzzzzzzzzzz
EOT;
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["text"]=<<<'EOT'

EOT;
$a["publish"]='1';
$a["tk"]='-11103';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
