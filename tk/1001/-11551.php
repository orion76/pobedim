<?php  
$a["subdir"]='/tk/1001/';
$a["tku"]='200316155359';
$a["veche"]='0';
$a["iu_owner"]='1001';
$a["sorting"]='';
$a["tkp"]='';
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["title"]=<<<'EOT'
test pub
EOT;
$a["txtt"]='ыаыаыфаыфавфыафыав';
$a["imgt"]='';
$a["text"]=<<<'EOT'
тестовая публикация
EOT;
$a["publish"]='1';
$a["tk"]='-11551';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
