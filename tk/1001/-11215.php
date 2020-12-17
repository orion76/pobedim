<?php  
$a["subdir"]='/tk/1001/';
$a["tku"]='181121102741';
$a["iu_owner"]='1001';
$a["title"]=<<<'EOT'
Прочие публикации
EOT;
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["tkp"]='';
$a["sorting"]='';
$a["text"]=<<<'EOT'

EOT;
$a["publish"]='1';
$a["tk"]='-11215';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
