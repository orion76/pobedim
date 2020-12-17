<?php

//session_start();

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once ( $dir0. '/ut.php');
$dir = $dir0;

$ax = val_rq('ax', 9100);

$iu = current_iu();


switch ($ax) {
    case 9101:
           // добавляем в узел сообщения оценку с возможностью прокомментировать 
            $tg = val_rq('tg', 0);
            $tk = val_rq('tk', null);
            $tt = talk_tt_asInt(val_rq('tt', 0));
            $r = talk_add( $tk, $tg, $tt , '');
            $htm =  talk_tag_input($r['ID_TALKING'], $tt, '');
        echo json_encode( [ 'htm' => $htm, 'tg'=>$r['ID_TALKING'] ]);
        break;

    case 9102:
        // сохраняем изменение комментария
        $tg = val_rq('tg', 0);
        $t = val_rq('t', '');
        $r = db_row('select * from W2_TALKING_TEXT_U(:TG,:T, :ISN)'
                  , [':TG'=>$tg ,':T'=>$t ,':ISN' => val_sn('isn',0) ] ,2);
        echo json_encode( [ 'error'=>0,  'htm' => $r['ERR']]);
        break;
    case 9109:
        // сохраняем изменение комментария
        $tg = val_rq('tg', 0);
        $ptg = val_rq('p', '');
        $r = db_row('select * from W2_TALKING_P_U(:TG,:ptg, :ISN)'
                  , [':TG'=>$tg ,':ptg'=>$ptg ,':ISN' => val_sn('isn',0) ] ,2);
        echo json_encode( [ 'error'=>0,  'htm' => $r['ERR']]);
        break;    
    
    case 9103:
        //
        $tk = val_rq('tk', 0);
        $tt = val_rq('tt', 0);
        $row = talk_add($tk, null, $tt, '');
        $row['IS_EDIT'] = 1;
        $row['TEXT_TALKING'] = '';
        //$row['T_TALKING'] = $tt;
        echo json_encode( [ 'tg'=>$row['ID_TALKING'], 'tt'=>$tt,  'htm' => talk_tag_li(null,$row) ]);
        break;
    
    
    case 9105:  // загружена статья в новостную ленту
        unset($row);
        $tk = val_rq('tk');
        //$row['ID_TALK'] = $tk;
        $row = db_row('select * from W2_TALK_U_S1 (:tk,:iu)'  ,[':tk'=>$tk, ':iu'=>$iu],2);
        
        $fs = '/tk/' . $row['ID_USER'].'/'.$row['ID_TALK'].'.php';
        $nf = dir_to_file(). $fs;

        if (file_exists($nf.'.htm')){
            $row['HTM'] = @file_get_contents($nf.'.htm');
        } else  {       
            $row['HTM'] = talk_htm_searched_row($row);
            file_put_contents($nf.'.htm', $row['HTM']);
        }           
        echo json_encode( $row );
        break;
    
    case 9106:
        $tk = val_rq('tk', 0);
        $r = talk_select($tk);
        // получаем статистику лайков по публикации
        $row = db_row('select * from  w2_talk_lht ( :tk )',['tk'=>$tk],2);
        echo json_encode( [  'tk'=>$tk
                            ,'ht5'=>$row['HT5'] 
                            ,'ht4'=>$row['HT4'] 
                            ,'ht3'=>$row['HT3'] 
                            ,'ht2'=>$row['HT2'] 
                            ,'ht1'=>$row['HT1']    ,'ht0'=>$row['HT0']
                            ,'lu' => $r['LU']
                            ,'htm' => $r['HTM'] ]);
        break;
    
    
    default:
        break;
}

function talks_lhashtag($iu, $search = '') {
    $r = db_get('select * from w2_talk_hashtag_L1(:sx) order by CNT_HASHTAG_NEXT desc,HASHTAG',[':sx'=>$search],2);
    $a = array( 2,3,4,5,6,7,10,11);
    foreach($r['DS'] as $row){

        $a[$row['T_TALKING']] .= tag_li( htmlspecialchars( $row['HASHTAG'] )
                             . iif( !empty($search) // $row['CNT_HASHTAG_NEXT'] != $row['CNT_HASHTAG'] 
                                     ,  tag_('sup', $row['CNT_HASHTAG_NEXT'] ) 
                                       . tag_('sup', '/' ) )
                                  . tag_('sup', $row['CNT_HASHTAG'] )
                            , iif($row['CNT_HASHTAG_NEXT'] > 0 
                                    && !empty($search)
                                 , a_style('background-color:yellow;') ) );  
    }
    $r = tag_div( ' '.tag_('ul', $a[5]), a_class('ttg5 tg_st') )
        .tag_div( ' '.tag_('ul', $a[4]), a_class('ttg4 tg_ot') )
        .tag_div( ' '.tag_('ul', $a[3]), a_class('ttg3 tg_rg') )
        .tag_div( ' '.tag_('ul', $a[2]), a_class('ttg2 tg_pr') );
    return $r;
}


function talks_u($iu = 1000 , $ax=''){
    require_once (__DIR__ .'/tku.php');
  
    $r = null;
    $s = '';

    switch ($ax){
        case 'ltku_own':
        case 'ltku': 
            $r = db_get('select * from U2_TALKS_U_L(:iu)',[':iu'=>$iu] ,2);
            break;

        case 'ltku_new': 
            $r = db_get('select * from U2_TALKS_NEW_L(:iu)',[':iu'=>$iu] ,2);
            break;
        case 'ltgu_new': 
            $r = db_get('select * from W2_TALKINGS_NEW_L(:iu)',[':iu'=>$iu] ,2);
            foreach ($r['DS'] as $row){
                $s .= tag_li_a( '/tk?'.$row['ID_TALK'], $row['TEXT_TALKING'] , a_('tk',$row['ID_TALK']) . a_class('unloaded-') );
            }
            break;

       case 'ltk_viewed':
       default:
            $r = db_get('select * from U2_TALK_L(:iu)',[':iu'=>$iu] ,2);
            break;
    }     
    
    if (empty($s)){

        foreach ($r['DS'] as $row){
            $nf = dirname(__DIR__) .'/tk/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php';
            $szf = filesize_($nf);
            if ( $szf > 0) {
                $s .= tag_li_a( '/tk?'.$row['ID_TALK']
                        , $row['NAME_TALK'] ."($szf)"
                            .iif($row['ID_TALK_PARENT'] !== null, '<=='. $row['ID_TALK_PARENT'] )
                        , a_('tk',$row['ID_TALK']) . a_target('tk')  );
            }
        }
    }
    
    $r['HTM'] = $s;
    return $r;
}




function talks_search3($iv, $search , $iu=1000,  $i_page_search  = 0){
    $dir = dir_main().'tk';
   // require_once ( $dir .'/tku.php');
  
    $r = db_get('select ID_TALK, ID_USER, NAME_TALK from W2_TALK_SEARCH_L3(:iv,  :iu, :XSEARCH, :XPAGE_SEARCH)'
                ,[':iv'=>$iv, ':iu'=>$iu, ':XSEARCH'=>$search , ':XPAGE_SEARCH'=>$i_page_search] ,2);
    $s = '';

    foreach ($r['DS'] as $row){

        $nf = $dir .'/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php';
        if (file_exists($nf)){
            $s1 = tag_a( '/tk/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php'
                            , $row['ID_TALK'] , a_target('tk') . a_class('tkref') );
            $c = 'unloaded';
        
            $sz = filesize($nf);
            if ($sz < 1000)  {
                if (file_exists($nf.'.htm')){
                    $s1 = $s1 . BR1. @file_get_contents($nf.'.htm');
                   // $c = 'loaded';
                }
            } else {
                $s1 = $s1 . BR1. tag_div( tag_('h2',$row['NAME_TALK']) );
            }
            
            $s .= tag_li( $s1 , a_('tk',$row['ID_TALK']). a_('iu',$row['ID_USER']) .a_class($c));
        }
    }
    $r['HTM'] = $s;
    return $r;
}




function talks_search0($search , $iu=1000,  $i_page_search  = 0){
    $dir = dir_main().'tk';
   // require_once ( $dir .'/tku.php');
  
    $r = db_get('select ID_TALK, ID_USER, NAME_TALK from W2_TALK_SEARCH_L3(0,:iu, :XSEARCH, :XPAGE_SEARCH)'
                ,[':iu'=>$iu, ':XSEARCH'=>$search , ':XPAGE_SEARCH'=>$i_page_search] ,2);
    $s = '';

    foreach ($r['DS'] as $row){

        $nf = $dir .'/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php';
        if (file_exists($nf)){
            $s1 = tag_a( '/tk/'.$row['ID_USER'].'/'.$row['ID_TALK'].'.php'
                            , $row['ID_TALK'] , a_target('tk') . a_class('tkref') );
            $c = 'unloaded';
        
            $sz = filesize($nf);
            if ($sz < 1000)  {
                if (file_exists($nf.'.htm')){
                    $s1 = $s1 . BR1. @file_get_contents($nf.'.htm');
                   // $c = 'loaded';
                }
            } else {
                $s1 = $s1 . BR1. tag_div( tag_('h2',$row['NAME_TALK']) );
            }
            
            $s .= tag_li( $s1 , a_('tk',$row['ID_TALK']). a_('iu',$row['ID_USER']) .a_class($c));
        }
    }
    $r['HTM'] = $s;
    return $r;
}

function talk_htm_searched_row($row){
    $s  = '';
    $fs = '/tk/' . $row['ID_USER'].'/'.$row['ID_TALK'].'.php';
    $nf = dir_to_file(). $fs;
    if (file_exists($nf)){
        unset($a);
        $a['echo']=FALSE;
        require $nf;
        $s = tku_htm($a);
    } 
    return $s;
}



function talk_init($tk,$ntk, $md){
    
    $r2 = db_row('select IS_BOT from W2_TALK_SESSIONS_I ( :tk, :isn)'
              , [':tk' => $tk, ':isn' => val_sn('isn')], 2);
    
    $r = db_row('select * from W2_TALK_S (:TK, :NTK, :MD)',[':TK'=>$tk, ':NTK'=>$ntk, 'MD'=>$md],2 );
    $r['IS_BOT'] = $r2['IS_BOT'];
    return $r;
}


function talk_add($tk, $tgp, $tt, $txt){
    $iu = current_iu();
    $r = db_row('select * from W2_TALKING_I2 (:ID_TALK, :ID_TALKING_PARENT, :TEXT_TALKING, :T_TALKING, :ID_USER)'
        , [':ID_TALK' => $tk
        , ':ID_TALKING_PARENT' => $tgp
        , ':TEXT_TALKING' => trim( $txt )
        , ':T_TALKING' => $tt
        , ':ID_USER' => $iu]
        , 2);
        $r['T_TALKING'] = $tt;
    return $r;
}


function talk_tt_asInt($tt){
    switch ($tt) {
        case 1:
        case 'yes': $tt = 1;
        break;
        case -1:
        case 'no': $tt = -1;
        break;
        default: $tt = 0;
        break;
    }
    return $tt;
}

function talk_tag_input($tg, $tt, $t, $ptg = ''){
    $ph = '';
    $YesNo = '';
    $c_ = '';
    $iptg = '';
    switch ($tt) {
        case 1:  $YesNo = ' + ';  $ph = 'Поддерживаю';  $c_ = 'tg_yes';  break;
        case -1: $YesNo = ' - ';  $ph = 'Возражаю'; $c_ = 'tg_no';  break;
        case 2:  $YesNo = ' < ';  $ph = 'издатель';  break;
        case 3:  $YesNo = ' : ';  $ph = 'время и место';  break;    
        case 4:  $YesNo = ' # ';  $ph = 'тема, объект';  break;
        case 5:  $YesNo = ' @ ';  $ph = 'субъект, автор';  break;
        case 10:  $YesNo = ' fb$ ';  $ph = 'fb';  break;
        case 11:  $YesNo = ' vk$ ';  $ph = 'vk';  break;
        case 7:  $YesNo = ' ! ';  $ph = 'p'; 
                    $iptg = tag_input0('ptg', $ptg,  a_('onblur','jsPTalkingSave(event)') );  
                    break;
        default:   break;
    }
    $YesNo = '';
    $htm = //$YesNo .
        //. tag_div( 
               // tag_span($t) 
        $ph.':'.
               tag_div($t, a_('onblur','jsTalkingSave(event)') 
                       .a_('tg', $tg)
                       . a_ID('tg'. $tg)
                       .a_('contenteditable',"true")
                       .a_class('tg_edit '.$c_ )
                       .a_style('cursor:text;')
                       .a_name('t')
                       ) . $iptg; 
          // , a_class('tg_input '. $c_) );
    return $htm;
}


function talking_tt_char($tt){
    $r = '.';
    switch ($tt) {
        case 1:  $r = '+';   break;
        case -1: $r = '-';  break;
        case 2:  $r = '<';  break;
        case 3:  $r = ':';  break;    
        case 4:  $r = '#';  break;
        case 5:  $r = '@';  break;
        case 10:  $r = 'F';  break;
        case 11:  $r = 'K';  break;
        default: $r = '.';  break;
    }
    return $r;
}

function talk_tag_li($r, $row){
    
    $sx = '';
    if ( isset($r)){
        foreach ($r['DS'] as $row1){
            if (  $row1['ID_TALKING_PARENT'] == $row['ID_TALKING'] ){ $sx .= talk_tag_li($r, $row1); }
        }
    }
    
    switch ($row['T_TALKING']){
        case  1:  $c_ = 'tg_yes';   break;
        case  -1:  $c_ = 'tg_no';   break;
        case 2:  $c_ = 'tg_pr';  break;
        case 3:  $c_ = 'tg_rg';  break;    
        case 4:  $c_ = 'tg_ot';  break;
        case 5:  $c_ = 'tg_st';  break;        
        case 10:  $c_ = 'tg__fb';  break;        
        case 11:  $c_ = 'tg__vk';  break;       
        case 7:  $c_ = 'tg__p';  break;       
        
        default :  $c_ = ' ';   break;
    }

    $sl_ = '';
    if (($row['IS_EDIT'] == 0  ||  $sx !== '') && empty( $row['TEXT_TALKING']) )
    {
        $sl_ = a_style('display:none;');
    }
    
    $ptg =  ''; 
    switch ( $row['T_TALKING']) {
    case 10:
    case 11:
            $text = trim( md_parse( $row['TEXT_TALKING'] ) );
            if (strpos($text,'<p>') === 0) {$text = substr($text,3, strrpos('</p>')-4);}
            break;
    case 7:
            $ptg = '->'.$row['P_TALKING'];
            $text = $row['TEXT_TALKING'];
            break;
    default: 
            $text = $row['TEXT_TALKING'];
            break;
    }
    
    
    $s =   tag_li(
                iif(  $row['IS_EDIT'] == 1  &&  $sx === ''
                    , talk_tag_input($row['ID_TALKING'], $row['T_TALKING'], $row['TEXT_TALKING'], $row['P_TALKING'] )
                 , // read only
                       iif( $row['T_TALKING'] < 10, 
                          iif($row['CNT_YES_TALKING']!=0, tag_('sup', ' +'.$row['CNT_YES_TALKING'], a_class('tg_yes') )).tag_('button', ' ' , a_class('tg_yes') .a_('onclick','jsTalkingYes(event)').a_('type','button'))
                         .iif($row['CNT_NO_TALKING'] !=0, tag_('sup', ' -'.$row['CNT_NO_TALKING'], a_class('tg_no'))).tag_('button', ' ' , a_class('tg_no') .a_('onclick','jsTalkingNo(event)').a_('type','button'))
                         )
                         .iif($row['IU_TALKING'] > 0 ,
                                 tag_span( '<img src="/32/32.png" height="32px">' 
                                   , a_('iu',$row['IU_TALKING'])
                                   . a_class('tgu')
                                   . a_style('min-width: 32px; max-height: 32px;') 
                                 )
                              )
                          .$text. ' '.$ptg
                   )
                      .iif( $sx !=='' , tag_('ul', $sx , a_class('ltg')) )
              .iif( $row['T_TALKING'] < 2,  tag0_('img',a_('src','/16/copylink16.png'). a_class('copylink').a_('onclick','tg_url2clipboard(event)')) )
            ,  a_class( 'talking '. $c_)  
               .a_('tg', $row['ID_TALKING'])
               .a_('ttg',$row['T_TALKING'] )
               .a_('tgp', $row['ID_TALKING_PARENT']) 
               .a_('ts', $row['TS_SYS'])
               .a_ID('tg'. $row['ID_TALKING']) // требуется для навигации по хэштегам
               .a_('iu',$row['IU_TALKING']) 
            .$sl_
            );
    return $s;
}

function talk_select($tk){
    $r = db_get ('select * from W2_TALKING_L ( :ID_TALK ,:ISN )'
                , [':ID_TALK' => $tk , ':ISN'=> val_sn('isn',0) ], 2);
    $s = '';
    $au = array();
    foreach ($r['DS'] as $row){
       if(!in_array($row['IU_TALKING'], $au)){array_push($au, $row['IU_TALKING']);}
        if (  $row['ID_TALKING_PARENT'] == NULL ){ $s .= talk_tag_li($r, $row); }
    }
    $r['LU'] = $au;
    $r['HTM'] = $s;
return $r;
}
