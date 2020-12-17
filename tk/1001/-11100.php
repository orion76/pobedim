<?php  
//session_start();
$a["subdir"]='/tk/1000/';
$a["tku"]='180917090142';
$a["iu_owner"]='1000';
$a["title"]=<<<'EOT'
русофобия в РФ публичными лицами
EOT;
$a["topics"]='русофобия';
$a["subjects"]='';
$a["ranges"]='МихалковНС';
$a["urls"]='БесогонTV';
$a["text"]=<<<'EOT'
 [ Запрещенный к показу на телевидении выпуск БесогонTV... ]( https://youtu.be/lT6zrIEfrZs )  
<youtube key="lT6zrIEfrZs">     
 
EOT;
$a["publish"]='1';
$a["tk"]='-11100';
 require_once( dirname(__DIR__). "/tku.php"); 
tku_show_edit($_GET,$a, __FILE__);
