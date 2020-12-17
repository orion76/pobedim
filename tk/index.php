<?php

   $dir = dirname( __DIR__ );
   require_once ($dir . '/ut.php'); 
   require_once ($dir . '/tk/db_talk.php');
   require_once ($dir . '/tk/tku.php');
   
   $a = array_keys($_GET);
   $tk = $a[0];
   
   $row = db_row('select * from w2_talk where id_talk = :tk',[':tk'=>$tk],2);
   if ($row['ROW_COUNT'] == 0){
       
       echo 'Публикация не найдена';
       exit;
   } else {
      $r = talk_echo_tk($row);
   }
   
/*
$a["subdir"] = '/tk/1001/';
*/   
   
function talk_echo_tk($row){
    
    $fs = '/tk/' . $row['ID_USER'].'/'.$row['ID_TALK'].'.php';
    $nf = dirname( __DIR__ ). $fs;
    if (file_exists($nf)) { 
       //     ob_clean();
       //     ob_start();
            unset($a);
            require ($nf);
       //     $sx = ob_get_contents ();
       //     ob_end_clean();
       //     echo $sx;
//       $a['echo'] = TRUE;
//       tku_show($a, $nf);
    } else {
        if (!empty( $row['MD_TALK']) ){
            $mdf = dirname( __DIR__ ). $row['MD_TALK'];
            $sx = md_file($mdf);
            $fs = http_url().'/tk?'.$row['ID_TALK'];

            $a["iu_owner"] = $row['ID_USER'];
            $a["tk"] = $row['ID_TALK'];
            $a["title"] = $row['NAME_TALK'];
            $a["text"] =  $sx;  
            
            
            $s = '<?php '
.chr(13).chr(10).'$a["iu_owner"] = '.$row['ID_USER'].';
$a["tk"] = '.$row['ID_TALK'].';
$a["title"] = <<<\'EOT\''.chr(13).chr(10) .$row['NAME_TALK'] .chr(13).chr(10).'EOT;'
.chr(13).chr(10). '
$a["text"] = <<<\'EOT\''.chr(13).chr(10). @file_get_contents($mdf) .chr(13).chr(10).'EOT;'.chr(13).chr(10)
.chr(13).chr(10).'require(dirname(__DIR__)."/tko.php");';
            file_put_contents($nf, $s);
            
            tku_show($a, __FILE__);
        } else {
            return false;
        }
    }    
        return true;
}


