<?php

$dir = dirname(__DIR__ );
require_once ($dir.'/ut.php'); 
require_once ($dir.'/tk/db_talk.php');

 if ( isset($_REQUEST['iu']) ) {
   $iu = val_rq('iu',0);
 } else {
   $iu = current_iu();
 }
 
 
$ax = val_rq('ax','ltku');
$htm = array();

   
   array_push($htm, cmenu_current_djuga());
   
   $r = talks_u( $iu , $ax);
   
   
   switch ($ax){
       case 'ltku_own':
       case 'ltku': 
           
           //  этот функционал перенесён в /u/?ax=publication
           
           array_push($htm, 'Список собственных публикаций пользователя '.$iu);
           break;
       case 'ltku_new': array_push($htm, 'Список новых публикаций ');
           break;
       case 'ltgu_new':  array_push($htm, 'Список новых комментариев к публикациям, на которые подписан '.$iu);
           break;
       
       case 'ltk_viewed':
       default:         array_push($htm, 'Список просмотренных публикаций пользователем '.$iu);
           break;
   }
   
   array_push($htm, tag_('ul', $r['HTM'] , a_class('ltku'). a_('page_search', 0)) );
   
   echo tag_html(  $htm , [

                  tag_link_css('/tk/style00.css')
                , tag_link_css('/tk/talk.css')
                , tag_link_css('/css/cmenu.css')
                , tag_link_css('/auth/login.css')
                , tag_link_script('/ajax0.js')
                , tag_link_script('/core/util.js') 
                , tag_link_script('/tk/talk.js') 
       , tag_link_script( iif(isUserLoggedOn(), '/tk/talk_user.js', '/tk/talk_guest.js') )
                , tag_('script','function ltku_body_loaded(event){   }')
           ] ,''
           , a_('onload', 'ltku_body_loaded(event)'));   
   



