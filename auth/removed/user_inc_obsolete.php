<?php

 if (!isUserLoggedOn()) {
    $html = array_html();
    array_push($html['head'], tag_link_css('/auth/login.css'));
    array_push($html['body'], htm_login_proposal('/auth/user.php?ax=basic&bx=0', []));
    echo tag_html2($html);
    exit;
}

if ($bx === 'set_policy_ag') {
    $r = new db(DB_POBEDIM, 'update or insert into a2_u(u,policy_ag) values (:u,:pag)'
            , [':u' => $iu, ':pag' => val_rq('pag')]);
    echo htm_redirect1('/kotabl/a2ot.php?ax=');
    exit;
}


if ($ax === 'backtowork') {
    if (isset($_SESSION['rx_pf'])) {
        $rx = $_SESSION['rx_pf'];
    } else {
        $rx = f_host() . f_base() . 'index.php';
    }
    echo htm_redirect1($rx);
    exit;
}



// --------------- begin  SET_VECHE
if ($ax == 'veche' && $bx == 'set_veche') {
    $r = db_row('update w2_user set veche=:v  where ID_USER=:iu'
            ,[':v'=> val_rq('ivp'),':iu'=> $iu] ,DB_POBEDIM );
    
     $r = db_row('select NAME_CITY  from W2_CITY cy '
                . '  inner join w2_veche ve on cy.ID_CITY in (ve.VECHE,ve.VECHE_PARENT) '
             . '  inner join w2_user u on u.U=:iu AND ve.VECHE=u.VECHE'
             , [':iu'=>$iu ], DB_POBEDIM);
        if ($r['ROW_COUNT'] === 1){
            $city_u = $r['NAME_CITY'];
           if ($city_u != $fu['city_u']){
               $fu['city_u'] = $city_u; 
               $_SESSION['fu']['city_u'] = $city_u; 
               file_put_contents($fu['file'], ini_from_array($fu));         
           }
        }
}


// ---------------- begin $bx == 'create_veche'-----------------------------------------------
if ($ax == 'veche' &&  $bx == 'create_veche') {
    $r = db_row('select * from w2_veche_iu(:IU,:NAME_VECHE)',[':IU'=>$iu,':NAME_VECHE'=>val_rq('nv')],DB_POBEDIM );
}

// ---------------- begin $bx == 'save_veche'-----------------------------------------------
if( $bx == 'save_veche') {
     $iv = val_rq('iv',0);
     $nf = dirname(__DIR__ ).POBEDIM_DB_VECHE.$iv.'.md';
     file_put_contents($nf, val_rq('mv')); 
     $r = db_row('update w2_veche set name_veche=:nv where VECHE=:iv and U_ADMIN=:iu'
             , [':nv'=>val_rq('nv'),':iv'=>$iv,':iu'=>$iu], DB_POBEDIM);
}





function tag_div_veche_user(&$html, &$fu, $showing){
    if (!$showing) { return '';}
    
    $iu = $fu['iu'];
    
    html_head( $html , tag_link_script('/js/ajax0.js') );

// ---------------- begin  CHANGE  VECHE  -----------------------------------------
    
    $iv = 1;
    $iva = 0;
    $s = '';
    
if (!empty($fu['city_u'])){
    
  $r0 = db_get('select * from W2_VECHELIST_U_L(:iu) order by VECHE', [':iu'=>$iu], DB_POBEDIM);
   
    
   foreach ($r0['DS'] as $row){
        $s .=  tag_li( $row['NAME_VECHE']
                 . iif( $row0['VECHE'] !== $row['VECHE'], tag_input0('ivp', $row['VECHE'], '','radio'))
                
                    , iif($row['U_ADMIN'] == $iu, a_style('color:navy;' ) )
                );
        if ($row['VECHE'] > $iv) { $iv = $row['VECHE']; $iva = $row['U_ADMIN']; }
   }
   
   
   $s = tag_ul( $s , a_style(CSS_NOWRAP) );

   // используется $iv  из предыдущего запроса     
   $r = db_foreach(function($row,$lp){
                    $nf = dirname(__DIR__ ).POBEDIM_DB_VECHE.$row['VECHE'].'.md';
                    $cf = '';
                    if (file_exists($nf)){ $cf = md_file($nf); }    
                    return   tag_li( $row['NAME_VECHE'] 
                            . tag_input0('ivp', $row['VECHE'] , '','radio')
                            . iif(!empty($cf),BR1.$cf)
                            , a_( 'iv', $row['VECHE'] )
                              . iif($row['ID_USER_ADMIN'] == $lp['iu'], a_style('color:navy;'))
                            );
                }
                , 'select * from W2_VECHE where VECHE_PARENT =:iv order by NAME_VECHE', [':iv'=> $iv], ['iu'=>$iu], DB_POBEDIM);

                
        html_head( $html , tag_('script'
           , chr(10).chr(13)
            . 'function m0veche_set(event) '.<<<'EOT'
            {
                var v = document.querySelector("input[name=ivp]:checked");
                var nm = document.getElementById('m0veche_err');
                if (v == null) 
                {
                   nm.innerHTML = 'Требуется выбрать Вече в состав которого вы входите<br>'; 
                }
                else {
                    var f = document.getElementById('form_set_veche');
                    f.submit();
                    //nm.innerHTML = v.value;
                }
            }
EOT
         ) );
    
         html_head( $html , tag_('script'
           , chr(10).chr(13)
            . 'function m0veche_create(event)'.<<<'EOT'
            {
                var f = document.getElementById('form_new_veche');
                    f.submit();
                    return;

                var v = document.querySelector("input[name=ivp]:checked");
                var nm = document.getElementById('m0veche_err');
                if (v == null) 
                { nm.innerHTML = 'Требуется выбрать Вече в состав которого вы входите<br>'; 
                }
                else {
                    
                    //nm.innerHTML = v.value;
                }
            }
EOT
            )  );    

    // перечень администрируемых  Собраний     
    $r1 = db_foreach( function(&$row,&$lp) {
               $nf = dirname(__DIR__ ).POBEDIM_DB_VECHE.$row['VECHE'].'.md';
               $cf = ''; if (file_exists($nf)) { $cf = @file_get_contents($nf); }
               return tag_form('?ax=veche'
                   , tag_hidden('iv', $row['VECHE'])
                    . tag_hidden('bx', 'save_veche')   
                    . tag_span($row['NAME_VECHE_PARENT'].'/'.$row['NAME_VECHE_PARENT1'].'/'.$row['NAME_VECHE_PARENT2'].BR1)
                    .tag_input0('nv', $row['NAME_VECHE'] , a_size(60)
                               . a_placeholder('Наименование собрания (совет-вече, советов, вече)') )   
                    .BR1.tag_textarea('mv', $cf 
                            , a_('cols',70).a_('rows',10). a_placeholder('Подробное описание совет-вече (вече, советов, собрания) со ссылками на группы в социальных сетях '))
                    .BR1. tag_submit('сохранить')
                    );
            }
            , <<<EOT
   select v.* 
       , vp.NAME_VECHE as NAME_VECHE_PARENT
       , vp1.NAME_VECHE as NAME_VECHE_PARENT1
       , vp2.NAME_VECHE as NAME_VECHE_PARENT2
      from W2_VECHE v 
         inner join W2_VECHE vp on vp.VECHE = v.VECHE_PARENT  -- // city
         left outer join W2_VECHE vp1 on vp1.VECHE = vp.VECHE_PARENT  -- // oblast
         left outer join W2_VECHE vp2 on vp2.VECHE = vp1.VECHE_PARENT  -- // okrug                    
          where v.ID_USER_ADMIN=:iu
EOT
                , [ ':iu' => $iu ], []
                , DB_POBEDIM );
            
    if ($r1['ROW_COUNT'] > 0) {
        $admin_veche =  tag_div( ' Администрируемое вече: '. tag_table($r1['S'])  );
    }
    else
    {
        $admin_veche = 
        tag_div(
            'Если вы выбрали свой город и не находите вече (советов, собраний) в котором вы участвуете (или хотели бы участвовать),'
                . ' создайте своё собрание, назвав его таким образом, чтобы единомышленники смогли присоединиться '
                     .tag_form('?ax=veche', tag_hidden('bx', 'create_veche' ) 
                         . tag_input0('nv','', a_placeholder('Новое наименование собрания'). a_size(60) )
                              .'<button onclick="m0veche_create(event)">'
                              .'Создать собрание/вече'
                           . '</button>'
                           , a_ID('form_new_veche') ) 
              );
    }

    
html_tt( $html , DIV_TEXT 
       , tag_div( 
            tag_table( tag_tr(        
                    tag_td(
            tag_form('?ax=veche'
                 , tag_hidden('bx', 'set_veche').
                    BR1. 'Территориальные вече (советы) в которых вы участвуете:'
                    .BR2 . $s
                    . iif($r['ROW_COUNT'] > 0  
                            ,  BR2. ' Выберете собрание из списка:  '
                            .BR1.  tag_ul( $r['S'] , a_style(CSS_NOWRAP) )  )
                    .BR1 
                    . tag_span('  ', a_ID('m0veche_err'))
                    .'<button onclick="m0veche_set(event)">Подтвердить выбор вече</button>'
               , a_ID('form_set_veche') )
                    , a_style('max-width:300px;') )
                    . tag_td(
                            iif($r['ROW_COUNT'] > 0  ,
                            'Структура вече (советов) имеет иерархическую структуру. '
                                 . 'Вам следует выбрать собрание из нижнего списка.')
                            
                            .BR1.'Если ошиблись при выборе терриории (округ, область, город), '
                            . 'то нужно выбрать правильный регион'.iif($r['ROW_COUNT'] > 0,' из верхнего списка')
                            .', нажать кнопку "Подтвердить выбор вече"'
                            . iif($r['ROW_COUNT'] > 0,' и продолжить выбор правильного вече (советов, собраний) из нижнего списка')
                            . BR1. tag_a('/pobedim/veche.php?ax=0'
                                            , '[Подробнее о Вече]'. tag_img('/pobedim/veche/veche_tree.png', a_style('max-height:200px;'))
                                        , a_target('h'))
                            )
             ) )
            .$admin_veche
             , a_style('background-color:lightgreen;')
        )
     );
} //--end if !empty( city_u )
else {
    html_tt($html, DIV_TEXT ,tag_p('В основных настройках требуется указать город'));
}
   // ---------------- end  CHANGE  VECHE  -----------------------------------------
}

