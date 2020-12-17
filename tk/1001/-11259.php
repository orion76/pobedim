<?php  
$a["subdir"]='/tk/1000/';
$a["tku"]='190216164714';
$a["iu_owner"]='1000';
$a["sorting"]='105';
$a["tkp"]='-11258';
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='ПроектКонституцииПСЛ';
$a["urls"]='progulki.pobedim.su';
$a["title"]=<<<'EOT'
тестовая статья
EOT;
$a["text"]=<<<'EOT'
ффффффффф
EOT;
$a["publish"]='1';
$a["tk"]='-11259';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
