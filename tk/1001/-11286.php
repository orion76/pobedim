<?php  
$a["subdir"]='/tk/1001/';
$a["tku"]='190526100959';
$a["iu_owner"]='1001';
$a["sorting"]='';
$a["tkp"]='';
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["title"]=<<<'EOT'
Схема народовластия
EOT;
$a["text"]=<<<'EOT'
Воля народа - это результат голосований по всем вопросам жизни общества.
Все голосования считаются постоянно действующими, то есть, голосования не 
EOT;
$a["publish"]='1';
$a["tk"]='-11286';
$a["txtt"]='описание схемы народовластия';
$a["imgt"]='';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);