<?php  
$a["subdir"]='/tk/1007/';
$a["tku"]='180927225553';
$a["iu_owner"]='1007';
$a["title"]=<<<'EOT'
test 1007
EOT;
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["tkp"]='';
$a["sorting"]='';
$a["text"]=<<<'EOT'
zhxjckvjlbnkl;m
EOT;
$a["publish"]='1';
$a["tk"]='-11163';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
