<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

require_once $dir0.'/tk/db_talk.php';
require_once $dir0.'/derzhava/derzhava_fn.php';


$ax = val_rq('ax','list_publication');

$iv = val_rq('iv',0);
$u = current_iu();

$sx = val_rq('sx','');



$ht = new html('/derjava/text_search.html');
$menu= menu_user($ht, 'ACTIVE3');
$menu['RX']='';

$data0['SX']=$sx;
$data0['VECHE']=$iv;

$ht->data('data0', $data0 );
$ht->data('menu1', $menu );


//$r_searched =  talks_search4($html, $iv, $search, $iu);
$i_page_search =0;
$r1 = new db(DB_POBEDIM,  'select first 50 * from W2_TALK_SEARCH_L3(:iv,  :u, :XSEARCH, :XPAGE_SEARCH)'
                ,[':iv'=>$iv, ':u'=>$u, ':XSEARCH'=>$sx , ':XPAGE_SEARCH'=>$i_page_search] 
        , function($row){
            // $row['HREF_TALK'] = '/tk/'.$row['U'].'/'.$row['ID_TALK'].'.php';
            $row['HREF_TALK'] = '/derjava/text.php?tk='.$row['ID_TALK'];
            return $row;
        }
        );


$ht->data('data1', $r1->rows );

echo $ht->build('',true);


exit();



/*


 tag_td( tag_user($html, $row['U']) 
                                            . tag_ul( tag_li($row['TS_SYS'] , a_class('i'))
                                                     .tag_li($row['L_T5_TK'], a_class('i'))
                                                     .tag_li('', a_class('i'))
)
                //   
//                                                    '/derzhava/tk_derzhava.php?tk='.$row['ID_TALK']
             
*/




 
html_tt($html, DIV_CONTENT, tag_a( '/derzhava/ltk_derzhava.php?ax=make_publication&iv='.$iv , 'Создать публикацию' , a_target('tk')) );
html_tt($html, DIV_CONTENT, BR2. tag_a( '/derzhava/ltk_derzhava.php?ax=list_publication&iv='.$iv , 'Публикации') );


   
if($ax == 'make_publication')  {
   html_tt($html, DIV_TEXT,  
        tag_div(
                tag_form('/tk/tk.php?ax=9204&iv='.$iv,
                          tag_input0('ttku',  ''
                             , a_placeholder('Интернет-ссылка (http://site.ru/page?) или Заголовок публикации')
                              .a_size(50)
                            )
                        .tag_submit('создать публикацию')  
                      , a_class('ltk_pn_new'). a_style('margin-top:100px;'))    . NBSP2 
           )
        );   
   
}   

if ($ax == 'list_publication')
{
   html_tt($html, DIV_SEARCH,  
           tag_form_get('/derzhava/ltk_derzhava.php?iv='.$iv, 
                             tag_input0('search', $search
                                        , '' .a_placeholder('#тема , @автор, :эпоха/место, &lt;издатель/инфо-канал +:проект')
                                     . a_size(25)
                                        ) 
                            . tag_submit('найти!')
                            . tag_div(talks_lhashtag($iu, $search) , a_class('lhashtag'))
                           , a_class('ltk_search')
                         )
           .tag_p( 'Пример запроса: +wwww -vvv= #ttttt -#uuuuu +:ppppp= +&lt;iii  '
                . ' означает что все ярлыки vvv будут исключены, должен быть ярлык с текстом wwww, могут быть темы с ttttt, не должно быть тем с uuuuu, эпоха строго ppppp, должн быть издатель с iii '
                , a_style('max-width:300px;'))
              ); 
   html_tt($html, DIV_TEXT,   $r_searched['HTM']  );
}
   
   $html['head'] = array_merge($html['head'],[
                               tag_link_css('/tk/talk.css')
//                              , tag_link_css('/css/cmenu.css')
//                              , tag_link_script('/js/ajax0.js')
//                              , tag_link_script('/js/ut0.js') 
                              , tag_('script','var ltk_unloaded = null;')
                              , tag_link_script('/tk/talk.js')
                              , tag_link_script('/css/tt.js')
                              , tag_link_script( iif(isUserLoggedOn() , '/tk/talk_user.js', '/tk/talk_guest.js') )
                              , tag_('script','function ltk_body_loaded(event){  '
                                      //. 'window.onresize = doResized; doResized();'
                                      . 'ltk_window_scroll_init(); }')
                              ]  );
   
   $html['attr_body'] = a_('onload', 'ltk_body_loaded(event)');
   