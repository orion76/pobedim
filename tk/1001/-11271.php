<?php  
$a["subdir"]='/tk/1001/';
$a["tku"]='190321220937';
$a["iu_owner"]='1001';
$a["sorting"]='';
$a["tkp"]='';
$a["topics"]='документация';
$a["subjects"]='';
$a["ranges"]='pobedim.su';
$a["urls"]='pobedim.su';
$a["title"]=<<<'EOT'
Добавление нового МСУ, группы, советов  на  pobedim.su
EOT;
$a["text"]=<<<'EOT'
Интерфейс максимально упрощён, для создания группы/совета нужно перейти в меню  [Группы](/derzhava/poisk_gr.php), через поиск найти группы в своём городе или создать свою, нажав соответствующую кнопку
EOT;
$a["publish"]='1';
$a["tk"]='-11271';
$a["txtt"]='Добавление групп (Советов)';
$a["imgt"]='';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
