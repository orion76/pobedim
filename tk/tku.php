<?php
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once( $dir0. '/ut.php');
require_once( $dir0. '/html.php');
require_once( $dir0.'/tk/db_talk.php');
require_once( $dir0.'/simple_html_dom.php');
require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax',9210);

$dir = $dir0;



function tku_echo ($htm){
    $iu = current_iu();
    header('X-XSS-Protection: 1');
    
    $ht = new html('/derjava/text_page.html');
    $data0[0]['NAME_TALK'] = '';
    $data0[0]['TEXT'] = $htm;
    $data0[0]['BODY_ONLOAD'] = 'tku_body_loaded(event)';
    
    
    $ht->data('data0', $data0 );
    $menu= menu_user($ht, '');

    echo $ht->build('',true);    
    
    exit;
    
    
    $html = array_html();
    //$_SESSION[SN_HTML_BASE] = '';
    //array_push( $html['body'] , tag_div( tag_a(f_host('/tk/ltk.php'),'Список публикаций') ) );
    array_push( $html['body'] , tag_div('iu='.$iu .' nu= '. current_nu()) );
    array_push( $html['body'] , tag_div(
                        tag_div( $htm , a_style('width:98%;'.CSS_INLINEBLOCK)) , a_class('tku_preview')
                    )
            );
    
    array_push( $html['head'] , tag_link_css( '/css/cmenu.css') );
    array_push( $html['head'] , tag_link_css( '/auth/login.css') );
    array_push( $html['head'] , tag_link_css( '/tk/talk.css') );
    array_push( $html['head'] , tag_link_script('/tk/talk.js') );
    array_push( $html['head'] , tag_link_script( iif($iu != 1000 , '/tk/talk_user.js', '/tk/talk_guest.js') ) );
    
    $html['attr_body']= 'onload=tku_body_loaded(event)';
    echo tag_html2(  $html );
}      




function tku_is_image_content($s){
    $pi = pathinfo($s,PATHINFO_EXTENSION); 
    switch ($pi){
      case 'png':
      case 'jpg':
      case 'gif':
          return true;
    }
    return false;
}

/*

function simple_htmltag_to_md ( $node_dom ){
    $t  = '';
    $children = $node_dom->nodes;
    if (count($children)>0){
            foreach ( $children as $n)
            {
                $s = $n->text(); $tag = $n->tag;
                $md = ''; $md2 = ''; 
                switch ( $n->tag){
                    case 'form':
                    case 'button':
                    case 'input':
                    case 'nav': continue; break;
                    case 'text':  $md2= $n->text(); break;
                    case 'h1': $md = chr(13).chr(10).'#';break;
                    case 'h2': $md = chr(13).chr(10).'##';break;
                    case 'h3': $md = chr(13).chr(10).'###';break;
                    case 'h4': $md = chr(13).chr(10).'####';break;
                    case 'h5': $md = chr(13).chr(10).'#####';  break;
                    case 'b': $md = '**'; $md2=$md; break;
                    case 'u': $md = '_'; $md2=$md; break;
                    case 'strike':
                    case 's': $md = '--'; $md2=$md; break;
                    case 'i': $md = '__'; $md2=$md; break;
                    case 'li': $md = '*'; $md2=''; break;
                    case 'p': $md = chr(13).chr(10); $md2=$md; break;
                    case'a': 
                        $href = $n->getAttribute('href');
                        if (empty($href)) { break;}
                        if(tku_is_image_content($href)) { $md =''; $md2 = '![]('.$href.')';}
                            else { $md = ' ['; $md2 = ']('.$href.')';}
                        break;
                    case 'img': $href = $n->getAttribute('src');
                        $md = ''; $md2 = '![]('.$href.')';  break;
                    case 'sup': $md = '<sup>'; $md2 = '</sup>';
                    default: break;
                }
                if ( empty($md) && !empty($md2) ){  $t .= $md2 ; }
                else { $t .= $md. simple_htmltag_to_md($n) . $md2; }
            }
    } else {
        $s = $node_dom->text(); 
        if (isset($s)) {
            $t .= $s;
        }   
    }
    return  iif(isset($t), $t,''); 
}

function simple_html_first_p( $dom , $nh ) {
    $p = null;
    $et0 = $dom->getElementByTagName($nh);
    if (isset($et0)) {
        $p = $et0->getElementByTagName('p');
    }
    if (isset($p)) {return $et0;}
}
function simple_html_to_md ( $dom ){
    $et0 = $dom->getElementByTagName('article');
    if (isset($et0)) {return simple_htmltag_to_md ($et0);}
    else {
        
//        if (!isset($et0)){ $et0 = simple_html_first_p($dom ,'h1'); }
//        if (!isset($et0)){ $et0 = simple_html_first_p($dom ,'h2'); }
//        if (!isset($et0)){ $et0 = $dom->getElementByTagName('br');}
//        if (!isset($et0)){ $et0 = $dom->getElementByTagName('p');}
        
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('h1');}
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('h2');}
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('p'); }
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('br');}
        if (!isset($et0)) { return ''; } else {
            return simple_htmltag_to_md ($et0->parentNode());
        }
    }
}

*/

function tku_htm( &$a  ){ 

  $tk = $a['tk'];
  if ( substr($tk,0,2)==='80') {$tk = '-'.substr($tk,2); $a['tk']=$tk;}
  if (va($a,'iu_owner') == '1000' || va($a,'iu_owner')=='') $a['iu_owner'] = '1001';

    
    $rx = '';
    if (!empty($tk)) {
        $row0 = db_row('select * from w2_talk tk inner join w2_talk tk0'
                . ' on tk0.id_talk=tk.id_talk_parent where tk.id_talk=:tk'
                      , [':tk'=>$tk],2);
        if ($row0['ROW_COUNT'] == 1){
            $rx = tag_a(http_url('/tk/'.$row0['U'].'/'.$row0['ID_TALK'].'.php'), ' => '.$row0['NAME_TALK']);
        }
        $r0 = db_get('select * from w2_talk where id_talk_parent=:tk order by order_sorting', [':tk'=>$tk],2);
        $sx  = '';
        foreach ($r0['DS'] as $row0){
            $sx .= tag_li( tag_a(http_url('/tk/'.$row0['ID_USER'].'/'.$row0['ID_TALK'].'.php'), $row0['ID_TALK']).' '.$row0['NAME_TALK']);
        }
        if (!empty($sx)) { $rx .= tag_('ul',$sx); }
    }
    if (!empty($rx)) { $rx = tag_div($rx, a_class('tkbundle'));}
    $rn = chr(13).chr(10);
    $text  = str_replace( '</youtube>','' , $a["text"] );
    $j = false;
    $i = strpos($text,'<youtube ');
    if ($i > 0) {$j = strpos($text,'>',$i); }
    if ($i === false || $j === false){ $text  = md_parse( $a["text"]); }
    else 
       { 
          $sx = str_replace( '/>','>', substr($text,$i, $j-$i+1) ).'</youtube>';
          $text = md_parse( substr($text,0,$i-1) ). $sx. md_parse( substr($text,$j+1) );
       }
        
    $sx = $rn . tag_('h2', ' '. $a["title"] .' ' , a_class('tk_tl')) 
         .$rn.tag_div( $text ,  a_class('tk_text') ) .$rn. $rx;
    $r =   tag_div($sx .' ', a_class('tk_pn'). a_('tk',$tk) ) .$rn;
    return $r;
}

function tku_html( &$a ,$htm = '' ){ 
    $iu = current_iu();
    
  $tk = $a['tk'];
  if ( substr($tk,0,2)==='80') {$tk = '-'.substr($tk,2); $a['tk']=$tk;}
  if ( va($a,'iu_owner') == '1000' || va($a,'iu_owner')=='') $a['iu_owner'] = '1001';
    
    
    $r = iif( $iu == va($a,'iu_owner')
               , tag_a( http_url() .'/tk/'.$a['iu_owner'].'/'.$tk.'.php?ax=edit','edit', a_target('_blank')) )
            . $htm. tku_htm($a); 
    
    
    
    return tag_html( htm_login_proposal().$r 
            , [ tag_ ( 'title', $a['title'] )
                        , tag_link_css('/tk/style00.css')
                        , tag_link_css('/tk/talk.css')
                        , tag_link_css('/css/cmenu.css')
                        , tag_link_css('/auth/login.css')
                        , tag_link_script('/js/ajax0.js')
                        , tag_link_script('/js/core/util.js') 
                        , tag_link_script('/tk/talk.js') 
                        , tag_link_script( iif($iu != 1000 , '/tk/talk_user.js', '/tk/talk_guest.js') )
                ] ,''
                       , iif( !empty($htm) ,a_('onload', 'tk_body_loaded(event)')) );  
}



function tku_talkings_add($tt, $tk, $ltt){
        foreach ( explode( ';', $ltt ) as $txt )
        { 
            $txt = trim($txt);
            if (!empty($txt)){
                talk_add($tk, null, $tt, $txt);
            }
        }
}

function tku_show_edit(&$get, &$a, $fs){
   if ( isset($get["ax"]) ){
         if ($get["ax"]==="edit")  { tku_edit($a, $fs);}
         if ($get["ax"]==="text")  {  echo md_parse( $a['text'] ); }
   } else { tku_show($a, $fs); } 
}

function tku_show(&$a, $fs){
   if (isset($a['echo'])){ if ( $a['echo']===FALSE){ return; }  }
   $tk = $a['tk'];
   echo htm_redirect1('/derjava/text.php?tk='.$tk);
   return;
  /*
   if ( substr($tk,0,2)==='80') $tk = '-'.substr($tk,2);
   echo tku_html($a ,   cmenu_current_djuga() );   
   $iu = current_iu();
   $r = db_row('select * from U2_TALK_VIEW(:tk, :iu)', [':tk'=>$tk, ':iu'=>$iu] ,2 );
  */
}


function tku_edit(&$a , $fs=''){
  $htm = '';
  $iu = current_iu();  
  $tk = $a['tk'];
  if ( substr($tk,0,2)==='80') {$tk = '-'.substr($tk,2); $a['tk']=$tk;}
  if ($a['iu_owner'] == '1000') $a['iu_owner'] = '1001'; 
  $u = $a['iu_owner'];
  $a['subdir'] = "/tk/$u/";
    
    foreach ($_POST as $key => $v){ $a[$key] = $v;  }

    if ($a['mk_copy'] == 1){
        $a['tk'] = '';
        $a['mk_copy'] = 0;
        $a['published'] = 0;
        $a['sorting'] = -1;
        $a['tku'] = gen_id();
        $fs = __DIR__ .'/'.$iu.'/tmp'.$a['tku'].'0.php';
    }
    
    $s =  $a["title"];
    $frame = tku_tag_iframe( $s );
    $url_content = '';

    if (!empty($frame['url'])){
        $url = $frame['url'];
        
        $url_content = @file_get_contents($url);
        $dom = str_get_html($url_content);
        $url_headers = $http_response_header;
        $url_type_content = '';
        foreach($url_headers as $sx){
          if (false !== stripos($sx, 'Content-Type:'))
                  { 
                        $url_type_content = trim(substr($sx,  strpos($sx,':')+1)); 
                  }
          if ($url_type_content != '') { break;};
        }
        
        $url_is_image = ( stripos($url_type_content,'image') !== false );
        $url_is_text = ( stripos($url_type_content,'text') !== false );
        
        
       $a["title"] = iif( $url_is_image,$frame['t1'], tku_title_of_url($frame['url'])); 
       $url_title = str_replace(['"','\'','«','»'], '', $a["title"]) ;

       $text = '';
       if ($url_is_text) {
            $text = simple_html_to_md ($dom ); 
       } else {
            if (empty($frame['iframe']) && strpos('<youtube',$frame['t2'])===false ){
                $frame['iframe'] = tag_('iframe',' ',a_('src',$url)  );
            }
       }
       
        $a["text"] =  $a["text"] 
                    . iif ($a["title"] !== $frame['t1'], $frame['t1'])
               
                    . iif( $url_is_image===false, '[ '.$url_title  .'... ]','![]') .'( '. $frame['url'] .' ) '
                
                     .$frame['t2']   
                .$text
                ;
    }

    
    if (count($_POST)>0){
        $rn = chr(13).chr(10);

        if (count($_FILES) > 0){
            $uploaddir = f_root().'/tk/'.$iu.'/';
            $r =  php_uploadfile($uploaddir);
            if ($r['ERR'] === ''){
                $a["nf"]=$r['NF'];
                $a["text"] =  $a["text"] . $rn 
                        .iif(tku_is_image_content($r['NF']),'!') 
                        .'[](/tk/'.$iu.'/'. basename($r['NF']).')';
            }
        }

        
        if ($a['publish'] === '1'){
            
            $r = db_row('select * from w2_talk_iu3 (:iv, :iu,:tl,:tkp, :tks ,:tk, :txtt, :imgt )'
                    ,[  ':iv'=>$a['veche']
                     ,  ':iu'=>$iu
                     ,  ':tl'=> mb_substr(  $a['title'],0,200)
                     , ':tkp'=> nullif( $a['tkp'] ,0)
                     , ':tks'=>$a['sorting']
                     , ':tk' => nullif( $a['tk'] ,0 )
                     , ':txtt' => $a['txtt'] 
                     , ':imgt'=>  $a['imgt'] 
                    ]   , DB_POBEDIM);

           if (!empty($r['ERR'])){ echo $r['ERR']; wrap_raw_text_as_php_($r['ERR'],'$a["err"]'); }
             else  { $a['tk'] = $r['ID_TALK']; }
            
            tku_talkings_add( 4, $a['tk'] ,$a["topics"]);
            tku_talkings_add( 5, $a['tk'] ,$a["subjects"]);
            tku_talkings_add( 3, $a['tk'] ,$a["ranges"]);
            tku_talkings_add( 2, $a['tk'] ,$a["urls"]);
        }

        if ($a['iu_owner'] == '1000') $a['iu_owner'] == '1001';
        
        $fs1000 = $fs;
        if ( $iu != 1000  && $a['iu_owner'] == 1000) {
           $a["subdir"]="/tk/".$iu."/";
           $a['iu_owner'] = $iu;
           $fs =  dirname(__DIR__) . $a["subdir"].'/'.basename($fs) ;
        } 

        $s = '';
        foreach ($a as $key => $v){
           switch ($key){
              case 'title': $s .= wrap_raw_text_as_php_($v,'$a["title"]'); break;
              case 'text':  $s .= wrap_raw_text_as_php_($v,'$a["text"]'); break;
              case 'mk_copy':
              case 'MAX_FILE_SIZE': break;
              default: $s .= wrap_assignvalue_as_php_($v,'$a["'.$key.'"]');break;
           } //  $s .= '$a["'.$key.'"] = \''. str_replace('\'','\\\'' ,$v).'\';'.$rn;
        }
        $s = '<?php  ' . $rn
                  . $s
              . ' require_once( dirname(__DIR__). "/tku.php"); ' .$rn
              . 'tku_show_edit($_GET,$a, __FILE__);'.$rn ;   
        
        //$fs = substr($fs, 0, strrpos( $fs, '.')-1 ).rand(1,99).'.php';
        if (!empty($a['tk'])){
          $fs =  dirname(__DIR__) . $a["subdir"].'/'.$a['tk'].'.php';  
        }
        if ( file_put_contents($fs, $s) ){
            // if ($fs != $fs1000 ){unlink($fs1000);  }
            
            if ($a['publish'] === '1'){
                echo htm_redirect1( "/tk/".$iu.'/'.$a['tk'].'.php?ax=edit');
                exit;
            }
        }
    }
    
    function droplist_($ni,$a){
        return droplist($ni,$a,'onclick="droplist_click0(event)"', 'cmenu_rtl');
    }
   
    $aa2=array();
    $aa3=array();
    $aa4=array();
    $aa5=array();
    $r = db_get('select T_TALKING,HASHTAG from W2_TALK_HASHTAG_L1(null)',[],2);
    foreach ($r['DS'] as $row)
         switch($row['T_TALKING']){
            case 2: array_push($aa2, $row['HASHTAG']); break;
            case 3: array_push($aa3, $row['HASHTAG']); break;
            case 4: array_push($aa4, $row['HASHTAG']); break;
            case 5: array_push($aa5, $row['HASHTAG']); break;
         }
    
    $rnf = str_replace('\\', '/',  $a['subdir'].basename($fs) );
    
    
   $htm_preview = tku_htm ($a);
    
   if(empty($a['txtt'])) { $a['txtt'] = str_trunc($a['text']); } 
   
   // -- пытаемся найти картинку
   if(empty($a['imgt'])) {
        $i = strpos($htm_preview,'<img '); $i = strpos($htm_preview,' src',$i);
        if ($i !== false){
            $j = strpos($htm_preview,'>',$i+5);
            $si = substr($htm_preview,$i,$j-$i);
            $i = strpos($si,'"');
            $j = strpos($si,'"',$i+1);
            $a['imgt'] = substr($si,$i+1,$j-$i);
        }
   }
   
   // -- пытаемся найти youtube
   if(empty($a['imgt'])) {
        $i = strpos($htm_preview,'<youtube '); 
        if ($i !== false){
            $j = strpos($htm_preview,'>',$i+5);
            $si = substr($htm_preview,$i,$j-$i);
            $i = strpos($si,'"');
            $j = strpos($si,'"',$i+1);
            $si = substr($si,$i+1,$j-$i-1);
            $si = "https://img.youtube.com/vi/".$si."/hqdefault.jpg";
            $a['imgt'] = $si;
        }
   }
   
   // -- картинка задана, значит надо сделать  иконку 100х100
    if(!empty($a['imgt'])) {
        $nf = $a['imgt'];
    try {
                $arrContextOptions=array( "ssl"=>array( "verify_peer"=>false, "verify_peer_name"=>false ) ); 
                set_time_limit (120);
                $response = @file_get_contents($nf, false, stream_context_create($arrContextOptions)); 

        $im = new Imagick_();
        //if ($im->pingImage($response)){
                //$im->readImage( $nf );    
                $im->readImageBlob( $response );    
                $im->thumbnailImage( 100, 100, true );
                $nf = dirname(__DIR__) . "/tk/".$iu.'/'.$a['tk'].'_thumb.jpg';
                $im->writeImage( $nf );
          //  } else {
            //    $err = 'Формат файла не поддерживается';  
          //  }
        } catch (Exception $e) {
            $err = 'Ошибка обработки изображения';    
        }                 
    }
   
    
    $htm .=
            

    tag_uploadfile( $a['subdir'].basename($fs).'?ax=edit'
            
            ,
            
        tag_div(            
            tag_input0('title', $a['title'], a_placeholder('Заголовок публикации')
                                               . a_size(80))
            
            .BR1.tag_('b', 'аннотация') .  tag_textarea('txtt', $a['txtt'], a_placeholder('аннотация'). a_('cols',50). a_('rows',2))
            .BR1.tag_('b', 'картинка') . tag_input0('imgt', $a['imgt'], a_placeholder('ссылка на картинку'). a_size(80))
                .tag_img( $a['imgt'] , a_('width','100px').a_('height','100px'))
            
            .BR1.tag_('b', 'объединение ид.№') . tag_input0('veche', $a['veche'], a_placeholder(''). a_size(40))
            
                       .BR1. tag_('b', '#') . tag_input0( 'topics', $a['topics'], a_placeholder('Темы (о чём будет идти речь) через \' \',\';\' или \',\' ')
                                              . a_size(80)). droplist_('topics',$aa4)
                       .BR1. tag_('b', '@').tag_input0( 'subjects', $a['subjects'], a_placeholder('Субъекты (чьё мнение отражено) через \' \',\';\' или \',\' ' )
                                              . a_size(80)). droplist_('subjects',$aa5)    
                       .BR1. tag_('b', ':').tag_input0('ranges' , $a['ranges'], a_placeholder('Проект,эпоха,год,страна,город,место через \';\' или \',\'' )
                                              . a_size(80)). droplist_('ranges',$aa3)
                       .BR1. tag_('b', '&lt;').tag_input0( 'urls', $a['urls'], a_placeholder('Источники( издание, тв-канал, сайт ) через  \';\'' ) 
                                              . a_size(80)). droplist_('urls',$aa2)

                       .BR2. tag_('b', 'основной документ ').tag_input0( 'tkp', $a['tkp'], a_placeholder('уник. код' ). a_size(12))
                           . tag_('b', 'индекс сортировки ').tag_input0( 'sorting', $a['sorting'], a_placeholder(' ' ). a_size(12))
            
            .BR1 . 'при мндексе сортировки меньше нуля публикация не отображается в поиске.'
            .BR1
            .BR1.'Текст публикации оформляется в формате markdown, ниже простой образец '
        , a_('align','left') )
   . tag_textarea('text', $a['text']
          ,  a_placeholder('Сопроводительный текст'). a_style('width:100%;height:550px')  //a_('cols', 100).a_('rows', 30) 
           )
 
        .BR1.iif($a["publish"], tag_hidden('publish', 1),

                tag_('select', tag_option('НЕ публиковать', 0)
                            .tag_option(tag_('font', '<b>ОПУБЛИКОВАТЬ</b>', a_('color','red')) , 1) 
                , a_name('publish')   ). $a["publish"] )
            
        .BR1
       . tag_div(
            BR1.'Сохранить в новом файле '.BR1.tag_input0('mk_copy', '1', a_('disabled','disabled'), 'checkbox') 
                , a_style(CSS_NOWRAP)
                )
       
        )
            
            .BR1 
            . tag_p(' # простой пример '.BR1
                    .'## подзаголовок статьи'.BR2
                    .'![](http://site.ru/kartinka.jpg) '.BR1
                    .'Чтобы перейти нажмите на [эту ссылку](http://site.ru/text.htm). '
                    );

    $htm =   htm_login_proposal()
       .tag_div('Предварительный просмотр публикации, форма редактирования - '.tag_a( $a["subdir"].'/'.basename($fs).'?ax=edit#editform','ниже')
                 , a_style('font-size:14pt'))
            
             . tag_div( $htm_preview , a_class('tku_editpreview') )
            
            . tag_div( tag_p( BR2 ) )
            
            .tag_div( 
                    
               tag_div('<a name="editform">Форма редактирования публикации</a>'
                       , a_style('font-size:14pt') )
             
                    . tag_div($htm)
                );
    tku_echo($htm);
}



function tku_tag_iframe($chm, $h=315, $w=420 ){
//   создание вставки для видео
//
    $url = '';
    $t1 = '';
    $t2 = '';
    $r['iframe'] = '';
    $r['key_youtube']='';
    
    $k = stripos( ' '.$chm, 'http' );
    if ($k > 0 ){
        $k--;
        $t1 = substr( $chm, 0, $k).' ';
        $n = strpos( $chm.' ', ' ', $k+4);
        $s = trim(substr( $chm, $k, $n-$k+1));
        $l = strpos($s,'<'); if ($l !== false) $s = substr($s,0,$l);
        
        $s = str_replace('&amp;', '&', $s);
        
        $r['url'] = $s;
        $url = $r['url'];
        $t2 = substr( $chm, $n).' ';

        
/*
http://img.youtube.com/vi//default.jpg
http://img.youtube.com/vi//hqdefault.jpg
http://img.youtube.com/vi//mqdefault.jpg
http://img.youtube.com/vi//maxresdefault.jpg
*/        
        
        if (  (stripos($url,'youtube.com') > 0 )
            || (stripos($url,'youtu.be') > 0 )
            || (stripos($url,'ok.ru/video/') > 0) 
            || (stripos($url,'vk.com/video') > 0)
                ){

            /*
            $fn = dirname( __DIR__ ). '\\m\\' . $m; if (!file_exists($fn))mkdir($fn,777,false);
            $fn  .= '\\url.png';  
            if (!file_exists($fn)){
                $php =   dirname( php_ini_loaded_file() );
                $exec = $php . "\\wkhtmltoimage.exe --crop-h 600 --crop-w 780 --crop-x 10 --disable-javascript";
                shell_exec($exec.' '.$url.' '.$fn);
            }
            */
            
            if (stripos($url,'youtu.be') > 0){ $url=str_replace('youtu.be/','youtube.com/embed/',$url);}
            if (stripos($url,'vk.com/video') > 0) {
                $url=str_replace('_','&id=',$url);  // vk.com
                $url=str_replace('video','video_ext.php?oid=',$url);
            } else {
                $i = strpos($url,'/watch?'); 
                if ($i !== false){
                 $j = strpos($url,'v=',$i);
                 $url= 'https://youtube.com/embed/'. substr($url,$j+2);
                 //$url=str_replace('/watch?v=','/embed/',$url);    // youtube.com
                }
                $url=str_replace('/video/','/videoembed/',$url); // ok.ru
                $i = strpos($url,'embed/');
                $j = strpos($url,'&',  $i+6);
                if ($j !== false) $url = substr($url,0,$j);
            }
            
            if (stripos($url,'/channel/') === false ) {
                if (stripos($url,'youtu') > 0  && stripos($url,'/channel/') ===false ) {
                    $r['iframe'] = '';
                    $key = substr($url, strrpos($url, '/')+1);
                    $i = strpos($key,'&'); if ($i > 0) { $key = substr($key,0,$i-1); }
                    $i = strpos($key, '?');
                    if ($i !== false) $key = substr ($key,0,$i);
                    $t2 .= chr(13).chr(10).'<youtube key="'.$key.'" href="'.$r['url'].'">';
                    $r['key_youtube']=$key;
                }
                else {
                    $r['iframe'] = '<iframe width="'.$w.'" height="'.$h.'" src="'.$url.'"  frameborder="0" allowfullscreen></iframe>' ;
                }
            }
          } else {
            $r['url'] = $url;
        }
       } else { $t1 = $chm; }

    $r['t1'] =  $t1;
    $r['t2'] =  $t2;
    return $r;
}



function tku_parse_message($tm){
    //// $fn = dirname( __DIR__ ). '\\m\\' . $m;
    $r['url']  ='';
    $r['t1'] = $tm;
    $r['t2'] = '';
    $r['th'] = ''; // thumbnail
    $k = stripos( ' '.$tm, 'http' );
    if ($k != false ){
        $k--;
        $r['t1'] = substr( $tm, 0, $k).' ';
        $n = strpos( $tm.' ', ' ', $k+4);
        $r['url'] = trim(substr( $tm, $k, $n-$k+1));
        $r['t2'] = substr( $tm, $n).' ';
        $tm = '';

        if (  $r['url'] != '' )  
        {
            if ( (trim($r['t1'] ) == '')  && (trim($r['t2'] ) == ''))
            {
////                $r['t1'] = title_of_url($r['url']);
////                if ($r['t1'] !== '') {
////                    $tm = ut2_save_message($m, $r['t1'] , $r['url']);
///                }
            }


            $k = stripos($r['url'],'youtu');
            if ($k == false) $k = stripos($r['url'],'ok.ru/video/');
            if ($k == false) $k = stripos($r['url'],'vk.com/video');

            if (  $k != false )
            {
                $r['th'] = tku_tag_iframe($r['url'],100,200,$m ). t('a','&','href="'.  $r['url'] .'"');
            } else {   
    ////      if (!file_exists($fn)){mkdir($fn,0777,true);}

    ////            $fn = dirname( __DIR__ ). '\\m\\' . $m. '\\url.png';
    ////            if (!file_exists($fn)){
    ////                
    ////                $php =   dirname( php_ini_loaded_file() );
    ////                $exec = $php . "\\wkhtmltoimage.exe --crop-h 600 --crop-w 780 --crop-x 10 --disable-javascript";
    ////                shell_exec($exec.' '.$r['url'].' '.$fn);
    ////            }

                ////$fn2 = dirname( __DIR__ ) .'\\tmp\\';
                
                $s = '';////   ut2_thumb_img_src('', $fn, $fn2 );
                $r['th'] =  '<a href="'.$r['url'].'" target="chmurl">'.$s.'</a>';
            }
        } 
    } 
    return $r;
}



function tku_title_clear($t){
    // === ht_title_clear($t)
    $t = str_replace( array ( '- YouTube','– смотреть видео онлайн в Моем Мире','| ВКонтакте'), '' , $t);
    $t =trim( str_replace( array( '«','»','“','”') ,'"' , $t));
    return ($t);
}

function tku_title_of_url($url){
    $url = trim($url);
    $p = ''; 
    
    //  https://www.youtube.com/watch?v=RiEPdVGXQD0
    
    ini_set("allow_url_fopen", '1'); //  it requires INI to include php_openssl.dll
   
    $h = fopen($url, 'r');
    $i = 0;
    $charset = '';
    
    $tTitle='title';
    $lTitle= strlen($tTitle);
    
    while ($h){
        if ($i > 100) break;
        $i++;               
        $s =fgets($h); // charset=windows-1251
        if ($s === false) break;  

        $k = stripos( $s, 'charset');
        if ($k !== false) {
            $charset = $s;
        } else {
            
            if ( (stripos($s,'</title>') === false) && (stripos($s,'<body>') !== false) )
            {
                $tTitle = 'h1'; $lTitle = 2; $p = '';
            }
            
            if ($p === '') {
                $k = stripos( $s, '<'.$tTitle.'>');
                if ($k !== false) {
                    $s = substr ($s, $k+2+$lTitle);
                    $p = ' ';
                }
            }
            if ($p !== '') {
                $k = stripos( $s, '</'.$tTitle.'>');
                if ($k !== false ){ $p = $p. substr($s,0,$k); break; } 
                else $p = $p . $s;
            }
        }
    }
    $s = '';
    if (stripos( $charset, 'windows-1251') !== false) {
        $p = iconv('Windows-1251', 'UTF-8', $p);
    }
    fclose($h);
    return tku_title_clear( $p );
}
