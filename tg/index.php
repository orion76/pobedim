<?php

   $dir = dirname( __DIR__ );
   require_once ($dir . '/ut.php'); 
//   require_once ($dir . '/tk/db_talk.php');
//   require_once ($dir . '/tk/tku.php');
   
   $a = array_keys($_GET);
   $tg = $a[0];
   
   if ( stripos($tg,'tg') ===0 ) {$tg = substr($tg,2);}
   
   $row = db_row('select * from w2_talking where id_talking = :tg',[':tg'=>$tg],2);
   if ($row['ROW_COUNT'] == 0){
       
       echo 'сообщение не найдено';
       exit;
   } else {
      $t =  $row['TEXT_TALKING'] .BR2. tag_a('http://djuga.ru/tk/?'.$row['ID_TALK'].'#tg'.$tg, 'публикация');
      echo tag_html($t, tag_('title', $row['TEXT_TALKING']));
   }
