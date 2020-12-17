<?php  
$a["subdir"]='/tk/1000/';
$a["tku"]='180920224217';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'
test
EOT;
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["text"]=<<<'EOT'

EOT;
$a["publish"]='1';
$a["tk"]='-11143';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
