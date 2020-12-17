<?php


$dir = dirname(__DIR__ );
require_once ($dir.'/ut.php'); 
//require_once ($dir.'/tk/db_talk.php');
   
 $iu = val_sn('iu');
 $ax = val_rq('ax');
 $p = val_rq('p');
   
$htm = array();
   
   array_push($htm, cmenu_current_email());
   
   $s = '';
   $r = db_get('select * from w2_talking_lp_l(:p)', [':p'=>$p],2);
   foreach ($r['DS'] as $row){
       $s .= tag_li( tag_a( http_url().'/tk/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php#'.$row['ID_TALKING']
               , $row['P_TALKING']
               , a_target('tk')
               ).' '.  $row['TEXT_TALKING']);
   }
   
    
   
   array_push($htm, tag_('ul', $s , a_class('lptk') ) );
   
   echo tag_html(  $htm , [

                  tag_link_css('/tk/style00.css')
                , tag_link_css('/tk/talk.css')
                , tag_link_css('/css/cmenu.css')
                , tag_link_script('/js/ajax0.js')
                , tag_link_script('/js/core/util.js') 
                , tag_link_script('/tk/talk.js') 
                , tag_('script','function ptk_body_loaded(event){   }')
           ] ,''
           , a_('onload', 'ptk_body_loaded(event)'));   
   