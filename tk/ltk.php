<?php

$dir = dirname(__DIR__ );
require_once ($dir.'/ut.php'); 
require_once ($dir.'/tk/db_talk.php');

$ax = val_rq('ax','list_publication');

$iu = val_sn('iu');
$search = val_rq('search','');

// поиск нужно сделать прежде всего, чтобы заполнилась таблица W2_TALK_SEARCHED
$r_searched =  talks_search0($search, $iu);





$html = array_html('-');
$_SESSION[ SN_HTML_BASE ] = '';

$html['title'] = 'Сделаем страну народной | Публикации | pobedim.su';

html_head( $html, tag_link_css( '/pobedim/menu.css' ));
$html['menu'] = ''; //  
html_tt($html,DIV_HMENU, cmenu_current_djuga() );


html_tt($html, DIV_HEADER, tag_div( tag_('small',' ', a_class('ltk_timer'))  
                            .tag_span(' ', a_class('ltk_out'))
                            .'  '.tag_a( '/tk/ltku.php?ax=ltku_new', '*публикации '.tag_('sup',' ', a_class('ltk_new')) 
                                        , a_target('ltku'))
                            .'  '.tag_a( '/tk/ltku.php?ax=ltgu_new', '*комментарии '.tag_('sup',' ', a_class('ltg_new')) 
                                        , a_target('ltgu'))           
                    , a_class('ltk_events')  ) );
   
   

html_tt($html, DIV_TITLE, 'Публикации');
html_tt($html, DIV_CONTENT, tag_a( '/tk/ltk.php?ax=make_publication' , 'Создать публикацию') );
html_tt($html, DIV_CONTENT, BR2. tag_a( '/tk/ltk.php?ax=list_publication' , 'Публикации') );
   
if($ax == 'make_publication')  {
   html_tt($html, DIV_TEXT,  
        tag_div(
                tag_form('tk/tk.php?ax=9204',
                          tag_input0('ttku',  ''
                             , a_placeholder('Интернет-ссылка (http://site.ru/page?) или Заголовок публикации')
                              .a_size(50)
                            )
                        .tag_submit('создать публикацию')  
                      , a_class('ltk_pn_new'))    . NBSP2 
           )
        );   
   
}   

if ($ax == 'list_publication')
{
   html_tt($html, DIV_SEARCH,  
           tag_form_get('/tk/ltk.php', 
                             tag_input0('search', $search
                                        , a_placeholder('#тема , @автор, :эпоха/место, &lt;издатель/инфо-канал +:проект')
                                     . a_size(35)
                                        ) 
                            . tag_submit('найти!')
                            . tag_div(talks_lhashtag($iu, $search) , a_class('lhashtag'))
                           , a_class('ltk_search')
                         )
           .tag_p( 'Пример запроса: +wwww -vvv= #ttttt -#uuuuu +:ppppp= +&lt;iii  '
                . ' означает что все ярлыки vvv будут исключены, должен быть ярлык с текстом wwww, могут быть темы с ttttt, не должно быть тем с uuuuu, эпоха строго ppppp, должн быть издатель с iii '
                , a_style('width:300px;'))
              ); 
   html_tt($html, DIV_TEXT,  tag_('ul',  $r_searched['HTM'] , a_class('ltalk'). a_('page_search', 0)) );
}
   
   $html['head'] = array_merge($html['head'],[
                               // tag_link_css('/tk/style00.css')
                               tag_link_css('/tk/talk.css')
//                              , tag_link_css('/css/cmenu.css')
//                              , tag_link_script('/js/ajax0.js')
//                              , tag_link_script('/js/ut0.js') 
                              , tag_('script','var ltk_unloaded = null;')
                              , tag_link_script('/tk/talk.js')
                              , tag_link_script('/css/tt.js')
                              , tag_link_script( iif($iu != 1000 , '/tk/talk_user.js', '/tk/talk_guest.js') )
                              , tag_('script','function ltk_body_loaded(event){  '
                                      . 'window.onresize = doResized; doResized();'
                                      . 'ltk_window_scroll_init(); }')
                              ]  );
   
   $html['attr_body'] = a_('onload', 'ltk_body_loaded(event)');
   
   echo tag_html3(  $html );   