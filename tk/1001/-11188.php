<?php  
$a["subdir"]='/tk/1001/';
$a["tku"]='181011082709';
$a["iu_owner"]='1001';
$a["title"]=<<<'EOT'
Разговор с чатом. Юрий о выборах и экономике.
EOT;
$a["topics"]='';
$a["subjects"]='';
$a["ranges"]='';
$a["urls"]='';
$a["tkp"]='';
$a["sorting"]='';
$a["text"]=<<<'EOT'
 [ Разговор с чатом. Юрий о выборах и экономике.... ]( https://www.youtube.com/watch?v=WCfC4il-a5o&feature=youtu.be )  

<youtube key="WCfC4il-a5">     
       
EOT;
$a["publish"]='1';
$a["tk"]='-11188';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
