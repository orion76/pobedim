<?php

/*
$a = array_keys($_GET);
$ax = $a[0];
if ($ax === 'out') { echo $a['title']; exit; }
*/

if (count($_GET) === 0) { exit; }


$dir = dirname( __DIR__ );
require_once ($dir . '/ut.php'); 
require_once ($dir . '/tk/db_talk.php');

// session_start();
   
   $ax = val_rq('ax',9200);
   $iv = val_rq('iv',0);
   
   $iu = current_iu();
   
   $htm = array();
   switch ($ax){
       case 9200:
           array_push($htm, 'hello');
           echo tag_html($htm);
           break;
       
           
       case 9201:
           $nf = dir_to_file().val_rq('md');
           if (strpos($nf, '/tk/text/') === false 
               && strpos($nf, '/text/') !== false     
                ) { $nf = str_replace ('/text/', '/tk/text/', $nf); }
           
           $f = substr($nf, 0, strrpos($nf,'.')).'.ini';
           $cf = file_ini_read($f);
           
           $htm = tag_div(
                         tag_div( $cf['t'] , a_class('tk_tl'))
                            . tag_div( md_file( $nf ), a_class('tk_text'))
                        , a_class('tk_pn'). a_('tk',$cf['id'])  
                    );
           
           echo tag_html( cmenu_current_email(). $htm 
                             , [ tag_ ('title', $cf['title'] )
                              , tag0_description($cf['description'])
                              , tag_link_css('/tk/style00.css')    
                              , tag_link_css('/tk/talk.css')
                              , tag_link_script('/ajax0.js')                           
                              , tag_link_script('/core/util.js') 
                              , tag_link_script('/tk/talk.js') 
                              , tag_link_script( iif($iu != 1000 , '/tk/talk_user.js', '/tk/talk_guest.js') ) 
                                ] ,''
                                , a_('onload', 'tk_body_loaded(event)') ); 
           
           exit;
           break;
       
       case 9204:   
           
            $nf = tk_create($iu, val_rq("topics")
                                , val_rq("subjects")
                                , val_rq("ranges")
                                , val_rq("urls") 
                                , val_rq('tkp')
                                , val_rq('sorting') 
                                , $iv
                            );  
            echo htm_redirect1( $nf );
            exit;
         
       case 9207:   
           $r = db_row('select count(1) as CNT from U2_TALKS_NEW_L(:iu)',[':iu'=>$iu],2);
           echo json_encode($r);
           break;
       
       case 9208:   
           $r = db_row('select count(1) as CNT from W2_TALKINGS_NEW_L(:iu)',[':iu'=>$iu],2);
           echo json_encode($r);
           break;
       
       case 9209:   
           $r = db_row('select 1 from W2_TALK_STAT_1(9209)',[],2);
           echo json_encode($r);
           break;
       
   }
   unset($htm);
   
   
   
   
   /*
        $nf = tk_create($iu, $topics, $subjects, $ranges, $sources , $maindoc, $order_sorting );  
        echo htm_redirect1( $nf );
   */
   function tk_create($iu, $topics, $subjects, $ranges, $sources , $maindoc, $order_sorting ,$iv = 0){
        if (!isUserLoggedOn()) return false;
       
           $tku = gen_id();
           $subdir =  '/tk/'.$iu.'/';
           $uploaddir =  dirname(__DIR__ , 1 )  .$subdir;
           if (!file_exists($uploaddir)) mkdir($uploaddir,0777);
           $r =  php_uploadfile($uploaddir);
           $rn = chr(13).chr(10);
           $t = val_rq('ttku');
           $s = '<?php  ' . $rn
                  . '$a["subdir"]="'.$subdir .'";'.$rn
                  . '$a["tku"]="'.$tku.'";'.$rn
                  . '$a["veche"]="'.$iv.'";'.$rn
                  . '$a["iu_owner"]="'.$iu.'";' .$rn
                  . '$a["sorting"]="'.$order_sorting.'";'
                  . '$a["tkp"]="'.$maindoc.'";'
                  .  wrap_raw_text_as_php_( $topics ,'$a["topics"]') 
                  .  wrap_raw_text_as_php_( $subjects ,'$a["subjects"]') 
                  .  wrap_raw_text_as_php_( $ranges ,'$a["ranges"]') 
                  .  wrap_raw_text_as_php_( $sources ,'$a["urls"]') 
                  .  wrap_raw_text_as_php_($t,'$a["title"]') 
              . ' require_once( dirname( __DIR__ ). "/tku.php"); ' .$rn
              . 'tku_edit($a,__FILE__); '.$rn;

           file_put_contents( $uploaddir.'tmp'.$tku.'0.php', $s);
           $nf = $subdir.'tmp'.$tku.'0.php';
           return $nf;  //   echo htm_redirect1( $nf );
   }

   
function tk_new($lp){
    return
    ' <form class="ltk_pn_new" action="tk/tk.php?ax=9204" method="post">'
       . iif( isset( $lp['ttku']) ,' <input name="ttku" id="ttku" type="text" placeholder="Заголовок " value="'.$lp['ttku'].'">' )
       . iif( isset( $lp['topics']) ,'<input name="topics" id="topics" type="text" placeholder="  " value="'.$lp['topics'].'">' )
       . iif( isset( $lp['subjects']) ,'<input name="subjects" id="subjects" type="text" placeholder="  " value="'.$lp['subjects'].'">' )
       . iif( isset( $lp['ranges']) ,'<input name="ranges" id="ranges" type="text" placeholder="  " value="'.$lp['ranges'].'">' )
       . iif( isset( $lp['urls']) ,'<input name="urls" id="urls" type="text" placeholder="  " value="'.$lp['urls'].'">' )
       . iif( isset( $lp['submit']) ,'<input type="submit" value="'.$lp['submit']. '">')
       . iif( isset( $lp['tkp']) , tag_hidden('tkp', $lp['tkp']))
       . iif( isset( $lp['sorting']) , tag_hidden('sorting', $lp['sorting']) ) 
      .'</form>';
}   