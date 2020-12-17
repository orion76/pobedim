<?php

$dir = ( dirname( dirname(__DIR__ ) ));

require_once ($dir.'/ut.php'); 
require_once ($dir.'/tk/db_talk.php');

$html = array_html();

array_push( $html['head'] , tag_link_css( '/css/gallery.css' ));
array_push( $html['body'] , gallery_list( __DIR__ .'/gallery', '/pobedim/za_kapitalizm/gallery/') );


$search = '+:СделаемСтрануНародной +#капитализм';
$r_searched =  talks_search0($search, $iu);
html_body($html, tag_('ul',  $r_searched['HTM'] , a_class('ltalk'). a_('page_search', 0)) );
html_head($html, tag_link_css('/tk/talk.css'));

html_head($html, tag_link_css('/tk/style00.css') );

html_head($html, tag_link_script('/js/ajax0.js') );
html_head($html, tag_link_script('/js/ut0.js') );
html_head($html, tag_('script','var ltk_unloaded = null;') );
html_head($html, tag_link_script('/tk/talk.js') );
html_head($html, tag_link_script( iif( isUserLoggedOn() , '/tk/talk_user.js', '/tk/talk_guest.js')) );
html_head($html, tag_('script','function ltk_body_loaded(event){  ltk_window_scroll_init(); }') );

$html['attr_body'] = a_('onload', 'ltk_body_loaded(event)');


echo tag_html2($html);

