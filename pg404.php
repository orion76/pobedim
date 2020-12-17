<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once ('ut.php'); 

setlocale (LC_ALL, "ru_RU.UTF-8");
header('Content-type: text/html; charset=UTF-8');

//check_ip();

$srv = $_SERVER['SERVER_NAME'];

$q = $_SERVER['QUERY_STRING'];


$k = stripos($srv.$q,'/v/');
if ( $k !== false  ) {
   $sx = substr($srv.$q, $k + 3).'?';
   $k = strpos($sx,'?');
   $sx = str_replace('/','',substr($sx,0,$k));
   echo htm_redirect1('/derjava/veche.php?iv='.$sx);
   exit;
}

$k = stripos($srv.$q,'/q/'); 
if ($k === false) { $k = stripos($srv.$q,'/qq/'); }
if ($k === false) { $k = stripos($srv.$q,'/p/'); }
if ( $k !== false  ) {
   $sx = substr($srv.$q, $k + 3).'?';
   $k = strpos($sx,'?');
   $sx = str_replace('/','',substr($sx,0,$k));
   echo htm_redirect1('/derjava/poll.php?qq='.$sx);
   exit;
}




$k = stripos($srv.$q,'manifest');
if ( $k !== false  ) {
    $q = '/-11218';
   $nf =  __DIR__ .'/tk/1001/-11218.php';
   //include $nf;
   echo htm_redirect1('/tk/1001/-11324.php');
   exit;
}

$k = stripos($srv.$q,'const');
if ( $k !== false  ) {
   // $q = '/-11301';
   //$nf =  __DIR__ .'/tk/1001/-11301.php';
   echo htm_redirect1('/tk/1001/-11324.php');
   exit;
}

$k = stripos($srv.$q,'appeal');
if ( $k !== false  ) {
    $q = '/-11224';
   $nf =  __DIR__ .'/tk/1001/-11224.php';
   include $nf;
   exit;
}


$k = stripos($q,$srv);
$q = substr($q, strpos( $q,'/',$k)+1 );

$k = strpos(str_replace('?','/', $q),'/');
if ($k > 0) { $tx = substr($q,0,$k);} else { $tx = $q; }

if (!is_int( 1*$tx)){    $tx = 0;  }



if ($row['ROW_COUNT'] === 0){
    
    $row = db_row("select first 1 * from w2_talk where id_talk_parent=:tk and lon containing 'RFP' ",[':tk'=>$tx],2);    
    if ($row['ROW_COUNT'] === 0){ 
        $row = db_row('select * from w2_talk where id_talk=:tk',[':tk'=>$tx],2);
    }
    
    if ($row['ROW_COUNT'] === 0){
        
        
        // проверяем  $tx как $iu - списка публикаций пользователя 
        $row = db_row("select first 1 * from w2_talk where id_user=:iu ",[':iu'=>$tx],2);        
        if ($row['ROW_COUNT'] === 0){    
        
            $q = substr( $_SERVER['QUERY_STRING'] ,15);
            $qq = '';
            $k = strpos($q, '?');
            if ($k !== false) { $qq = substr($q,$k+1); $q = substr($q, 0, $k); }
            $k = strpos($q, '/'); if ($k !== false) { $q = substr($q, $k); }
            
          //  echo '<br>'.$qq;
          // echo '<br>'. iconv('windows-1251','utf-8',$q);
            
            $r = new db(DB_USER,'select * from W0_PG404_S (:f,:h)'
                    ,[':f'=>iconv('windows-1251','utf-8',$q)
                      ,':h' => $_SERVER['REMOTE_ADDR']
                        ]
                    );
            $s =  $r->row('URL_TO') ;
            unset($r);
          //  echo '<br>'.$s;
            
            if ($s == '404') {            
                header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                exit;
            }
            
            if (strrpos($s,'.php')){
                $sx = $dir0. str_replace('/', '\\', $s);
               //
               // echo '<script> alert("Эта страница устарела, запрос перенаправлен на '.$s.'"); </script> ' ;
               //
                
                echo $sx;
                //$k = strpos($sx, '?'); if ($k !== false) $sx=substr($sx,0,$k);
                
                
                echo '<br>'.$_SESSION['ERR'];
                if (file_exists( $sx ) ) {
                    require_once ($sx);
                    exit(0);
                } else {
                    echo 'file is not found';
                }
            }
           
            //include ($dir0.'/derjava/veche_search.php');
            exit;
            
            
        } else {
            $nf = __DIR__ . '/tk/ltku.php';
            $_REQUEST['iu'] = $tx;
            //require_once($nf);  
            echo htm_redirect1('/tk/ltku.php');
        }
    }
    else
    {
      // $nf = __DIR__ . '/tk/'.$row['ID_USER'].'/'. $row['ID_TALK'] .'.php';
      // require_once($nf); 
      echo htm_redirect1('/tk/'.$row['ID_USER'].'/'. $row['ID_TALK'] .'.php');
    }
} else {
    // echo htm_redirect1('/tg?'.$q );

    $tg = $tx;
    $t =  $row['TEXT_TALKING']
            . BR2. tag_a(protocol_srv() .$srv.'/tk/?'.$row['ID_TALK'].'#tg'.$tg, 'публикация');
    echo tag_html($t, tag_('title', $row['TEXT_TALKING']));
}
