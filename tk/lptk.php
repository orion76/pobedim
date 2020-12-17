<?php


$dir = dirname(__DIR__ );
require_once ($dir.'/ut.php'); 
require_once ($dir.'/tk/db_talk.php');
   
 $iu = val_sn('iu');
 $ax = val_rq('ax');
   
$htm = array();
   
   array_push($htm, cmenu_current_email0('',cmenu_lli_djuga(0) ) );
   
   $s = '';
   $r = db_get('select * from w2_talking_p_l', [],2);
   foreach ($r['DS'] as $row){
       $s .= tag_li_a('/tk/ptk.php?p='. urlencode($row['P_TALKING']) ,  $row['P_TALKING']);
   }
   
    
   
   array_push($htm, tag_('ul', $s , a_class('ptk') ) );
   
   echo tag_html(  $htm , [

                  tag_link_css('/tk/style00.css')
                , tag_link_css('/tk/talk.css')
                , tag_link_css('/css/cmenu.css')
                , tag_link_script('/ajax0.js')
                , tag_link_script('/core/util.js') 
                , tag_link_script('/tk/talk.js') 
                , tag_('script','function lptk_body_loaded(event){   }')
           ] ,''
           , a_('onload', 'lptk_body_loaded(event)'));   
   