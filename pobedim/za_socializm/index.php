<?php
 
$dir = ( dirname( dirname(__DIR__ ) ));

require_once ($dir.'/ut.php'); 
require_once ($dir.'/tk/db_talk.php');



$html = array_html();

$page = basename( __FILE__ );
$dir_base = '/pobedim/za_socializm/';



array_push( $html['body'] 
        , tag_p( 'За социализм - это значит за справедливость, равенство и братство, счастье.'
        . ' Вариантов достижения целей социализма много, чтобы наши усилия были однонаправлены, требуется:'
        . ' 1) '.tag_a('pobedim/golosovanie.php?ax=view&bx=0&iv=1','обозначить свою позицию').';'
        . ' 2) отстаивать свои убеждения.'));



html_head( $html , tag_link_css( '/css/gallery.css' ));
html_body( $html , gallery_list( __DIR__ .'/gallery', '/pobedim/za_socializm/gallery/') );

$search = '+:СделаемСтрануНародной +#социализм';
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




