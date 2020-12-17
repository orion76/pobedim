<?php

function get_like_cnt($qq){
    return new db(DB_POBEDIM, 'select sum(case when is_like  is not distinct from  1 then 1 else 0 end) as CNT_LIKE'
                                . ', sum(case when is_like is not distinct from -1 then 1 else 0 end) as CNT_DISLIKE'
                                . '   from u2_poll where id_poll=:qq',[':qq'=>$qq]);
}

function get_like_answer_cnt($pa){
    return new db(DB_POBEDIM, 'select sum(case when is_like  is not distinct from  1 then 1 else 0 end) as CNT_LIKE'
                                . ', sum(case when is_like is not distinct from -1 then 1 else 0 end) as CNT_DISLIKE'
                                . '   from u2_poll_answer where id_answer=:pa',[':pa'=>$pa]);
}



function tree_poll( $poll , $ar = null , $szln=7000 ){
    if (empty($poll)) return '';

    $r1 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_poll=:qq and SORTING_ANSWER>-1',[':qq'=>$poll]);
    $r1->filter = function($row, $lp){
        $vr = $row['VECHE_ANSWER'];
        $sx = ''; //$ar==$row['ID_ANSWER']
        if($vr !== null){
            $r2 = new db(DB_POBEDIM,'select * from w2_veche where veche=:iv',[':iv'=>$vr]);
            $pr = $r2->row('ID_POLL_RESULT');
            $sx = BR1 .  tag_a('/v/'.$vr, tag_img('/css/group.png', a_style('height:32px;')). $r2->row('NAME_VECHE'));

            $r3 = new db(DB_POBEDIM,'select * from w2_poll where veche=:iv',[':iv'=>$vr]);
            $r3->filter = function($row,$lp){
                $sx= '';
                return tag_li( tag_a('/q/'.$row['ID_POLL'], tag_img( iif($row['ID_POLL'] === $lp['pr'],'/css/bx-checked.png', '/css/bx-empty.png'), a_style('height:16px;'))
                         . $row['NAME_POLL'] ) . tree_poll( $row['ID_POLL'] ,   $lp['ar']   )
                              , iif($row['ID_POLL'] === $lp['pr'], a_style('background-color:lightyellow;'))  );
            };

            if ($r3->length > 0) {
                $sx .= tag_ul($r3->printf(null,['pr'=>$pr , 'ar'=> $row['ID_ANSWER_RESULT'] , 'szln'=>$lp['szln'] ]));
            }
        }

        return tag_li( iif($row['ID_ANSWER'] === $lp['ar'], tag_img('/css/bx-check.png', a_style('height:16px;')))
                . iif( mb_strlen($row['NAME_ANSWER'])>$lp['szln'],mb_substr( $row['NAME_ANSWER'],0,$lp['szln']).'<b>...</b>'
                        ,$row['NAME_ANSWER'])
                        . $sx);
    };
    return tag_ul( $r1->printf(null, ['ar'=>$ar, 'szln'=>$szln])  );
}




function select_territory0() {

    $ivp = 1;
    $r1 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=1");
    $r1->filter = function(&$row) {
        return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
    };

    return tag_select('ta1', 0, tag_option('...', 0) . $r1->printf()
                    , a_('onchange', 'select_territory1(event)')
            )
            . BR1 . tag_select('ta2', 0, '', a_ID('t2') . a_('onchange', 'select_territory2(event)'))
            . BR1 . tag_select('ta3', 0, '', a_ID('t3') . a_('onchange', 'select_territory3(event)'))
    ;
}

function select_gr_kind($kv , $attr='') {
    $r = new db(DB_POBEDIM, 'select * from W2_VECHE_KIND where SORTING_KIND_VECHE is not null order by SORTING_KIND_VECHE');
    $r->filter = function($row,$lp) {
        //return tag_option($row['NAME_KIND_VECHE'], $row['ID_KIND_VECHE']);
        return tag_input0('kv', $row['ID_KIND_VECHE'], $lp['attr']. a_checked($lp['kv']==$row['ID_KIND_VECHE']) , 'radio')
                  .'&nbsp;&nbsp;'. $row['NAME_KIND_VECHE'] . BR1;
    };
    return $r->printf(null,['attr'=>$attr,'kv'=>$kv]);  // tag_select('kv', $kv, $r->printf());
}

function select_kategoria($ka = '') {
    return tag_select('ka', $ka,
            tag_('option', '')
            . tag_('option', 'ЖКХ', a_value('ЖКХ'))
            . tag_('option', 'Наука', a_value('Наука'))
            . tag_('option', 'Политика', a_value('Политика'))
            . tag_('option', 'Экономика', a_value('Экономика'))
            . tag_('option', 'Спорт', a_value('Спорт'))
            . tag_('option', 'Идеология и религия', a_value('Идеология и религия'))
            . tag_('option', 'Культура', a_value('Культура'))
            . tag_('option', 'Образование', a_value('Образование'))
            . tag_('option', 'Медицина', a_value('Медицина'))
            . tag_('option', 'Экология', a_value('Экология'))
            . tag_('option', 'Сельское хозяйство', a_value('Сельское хозяйство'))
    );
}

function select_veche($iv, $attr = '', $level = 4) {
    if ($iv < 1) {
        $iv = 1;
    }
    if (empty($attr)) {
        $attr = a_style('width:300px;');
    }

    $r1 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=1 order by SORTING_VECHE");
    $r1->filter = function(&$row) {
        return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
    };

    $r0 = new db(DB_POBEDIM, <<<TXT
            SELECT V.VECHE       AS VECHE4
                , V.VECHE_PARENT AS VECHE3
                , V.NAME_VECHE
                , V.ID_KIND_VECHE
                , V2.VECHE_PARENT AS VECHE2
                , V1.VECHE_PARENT AS VECHE1
                 FROM W2_VECHE V
                   LEFT OUTER JOIN W2_VECHE V2 ON V2.VECHE=V.VECHE_PARENT
                   LEFT OUTER JOIN W2_VECHE V1 ON V1.VECHE=V2.VECHE_PARENT
      WHERE V.VECHE =:iv
TXT
            , [':iv' => $iv]);

    $v1 = $r0->row('VECHE1') + 0;
    $v2 = $r0->row('VECHE2') + 0;
    $v3 = $r0->row('VECHE3') + 0;
    $v4 = $r0->row('VECHE4');

    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = $v4;
        $v4 = 0;
    }
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = 0;
        $v4 = 0;
    }
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = 0;
        $v4 = 0;
    }
    if ($level < 4) {
        $v4 = 0;
    }


    $r2 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v1"
            . " order by SORTING_VECHE", [':v1' => $v1]);
    $r2->filter = function(&$row) {
        return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
    };
    $r3 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v2  order by SORTING_VECHE", [':v2' => $v2]);
    $r3->filter = function(&$row) {
        return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
    };

    $r4 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v3  order by SORTING_VECHE", [':v3' => $v3]);
    $r4->filter = function(&$row) {
        return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
    };

    return tag_select('ta1', $v1, tag_option('...', 0) . $r1->printf()
                    , a_('onchange', 'select_territory1(event)') . $attr
            )
            . BR1 . tag_select('ta2', $v2, iif($r2->length > 1, tag_option('...', 0)) . $r2->printf(), a_ID('t2') . a_('onchange', 'select_territory2(event)') . $attr)
            . BR1 . tag_select('ta3', $v3, iif($r3->length > 1, tag_option('...', 0)) . $r3->printf(), a_ID('t3') . a_('onchange', 'select_territory3(event)') . $attr)
            . iif($level > 3, BR1 . tag_select('ta4', $v4, iif($r4->length > 1, tag_option('...', 0)) . $r4->printf(), a_ID('t4') . a_('onchange', 'select_territory4(event)') . $attr
                    ), tag_hidden('t4', 0, a_ID('t4')))
    ;
}

/*
function derzhava_top_page() {

    return
            '<div class="topline" style="white-space:nowrap;margin-top:0px;">
     <a href="/derjava/home.php" class="a_home"><img src="/derzhava/derzhava.png?" style="margin-top:0px;"></a>
     <form action="/derzhava/index.php" method="post" target="_blank">
           <input name="sxu" type="text" value="" 
           placeholder="  Поиск: фамилия или имя" size="50" class="sxu" >
           <input type="submit" value="" style="width:0px;height:0px;display:none;">
     </form>
</div>   ';
}
*/

/*


function list_href_veche2($iv, $editing = false) {

    $r2 = new db(DB_POBEDIM, 'select * from W2_VECHE_WEB2 where VECHE = :iv', [':iv' => $iv]);

    $tr = '';
    if ($editing === false) {
        $r2->filter = function(&$row, &$lp) {
            $uvw = $row['URL_WEB_VECHE'];
            $tuvw = '';
            if (stripos($uvw, 'facebook.com') !== false) {
                $tuvw = 'fb';
            } else if (stripos($uvw, 'vk.com') !== false) {
                $tuvw = 'vk';
            } else if (stripos($uvw, 'ok.ru') !== false) {
                $tuvw = 'ok';
            } else if (stripos($uvw, 'odnoklassniki.ru') !== false) {
                $tuvw = 'ok';
            }

            return tag_tr( // tag_td($tuvw)
                    
                    tag_td(tag_a($uvw, $row['TEXT_WEB_VECHE'] , a_class('f-link'))), a_class('noborder').a_style('height:35px;')
                    
                    );
        };
    } else {

        $r2->filter = function(&$row, &$lp) {
            $iv = $row['VECHE'];
            $uvw = $row['URL_WEB_VECHE'];
            $tuvw = '';
            if (stripos($uvw, 'facebook.com') !== false) {
                $tuvw = 'fb';
            } else if (stripos($uvw, 'vk.com') !== false) {
                $tuvw = 'vk';
            } else if (stripos($uvw, 'ok.ru') !== false) {
                $tuvw = 'ok';
            } else if (stripos($uvw, 'odnoklassniki.ru') !== false) {
                $tuvw = 'ok';
            }

            return tag_tr(tag_td($tuvw)
                    . tag_td(
                            tag_form('/derzhava/derzhava_gr_edit.php?ax=edit_web_veche&iv=' . $iv,
                                    tag_span('Описание', a_class('n_param') ).
                                    tag_textarea('twv', $row['TEXT_WEB_VECHE'], a_('cols', 40))
                                    . BR1 . tag_span('Адрес (url)', a_class('n_param') ). 
                                    tag_input0('uwv__', $row['URL_WEB_VECHE'], a_size(50)) .
                                    tag_hidden('uwv', $row['URL_WEB_VECHE'])
                                    .tag_submit('Сохранить изменения',true, a_class('btn-little'))
            )) , a_class('noborder'));
        };

        $tr = tag_tr(tag_td('')
                . tag_td(
                        tag_form('/derzhava/derzhava_gr_edit.php?ax=add_web_veche&iv=' . $iv,
                                tag_span('Описание', a_class('n_param') ).
                                tag_textarea('twv', '', a_('cols', 40))
                                . BR1 . tag_span('Адрес (url)', a_class('n_param') ). 
                                tag_input0('uwv__', '', a_size(50)) 
                                . tag_submit('Добавить новую ссылку ',true, a_class('btn-little') )
        ))   , a_class('noborder'));
    }

    return tag_table(
            tag_('caption', 'Интернет-ссылки', a_class('caption'))
            . $r2->printf(null, ['iv' => $iv, 'editing' => $editing]) . $tr
            , a_style('min-width:400px;width:100%')
    );
}


*/

function add_href_veche_web($iv) {
    return iif(isUserLoggedOn()
            ,
            BR2 . 'Добавьте ссылку на группу, занимающейся вопросами территории ' . BR2
            . tag_form('?ax=add_veche_web&iv=' . $iv . '&rx=derzhava',
                    tag_input0('tvw', '', a_placeholder('Наименование (описание) группы соцсети') . a_size(60))
                    . BR1 . tag_input0('uvw', '', a_placeholder('Адрес ( URL ) группы соцсети') . a_size(60))
                    . BR1 . tag_submit()
                    , a_style(CSS_INLINEBLOCK))
            , 'Для добавления ссылки на соцсеть требуется авторизация'
    );
}

function add_href_veche($iv, $ivp) {
    return iif(($ivp + 0) === 1
            , iif(isUserLoggedOn(),
                    tag_form('?ax=add_veche&iv=' . $iv . '&rx=derzhava',
                            'Организуйте собрание местного самоуправления<br>'
                            . tag_input0('nv', '', a_placeholder('Наименование собрания местного самоуправления') . a_size(60))
                            . tag_submit()
                    )
                    , 'Для добавления собрания местного самоуправления требуется авторизация'
            )
            , 'Добавление собрания местного самоуправления возможно только на уровне города');
}
