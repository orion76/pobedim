<?php  
$a["subdir"]='/tk/1000/';
$a["tku"]='190216220941';
$a["iu_owner"]='1000';
$a["sorting"]='209';
$a["tkp"]='-11258';
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='ПроектКонституцииПСЛ';
$a["urls"]='progulki.pobedim.su';
$a["title"]=<<<'EOT'
ст 9  гл 2  Интересное название статьи
EOT;
$a["text"]=<<<'EOT'
содержание статьи которое будет отображаться  при нажатии кнопки подробнее
EOT;
$a["publish"]='1';
$a["tk"]='-11263';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
