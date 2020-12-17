<?php
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once( $dir0. '/ut.php');
require_once( $dir0. '/html.php');
require_once( $dir0.'/simple_html_dom.php');

/*
if ( count($_GET) > 0) {
  $url = 'https://www.youtube.com/watch?v=CcdPOoQsvJE&feature=emb_logo';
  echo ht_parse_title($url);
}
*/

function ht_parse_NEWMSG($s){
    // это обрезанная версия от ht_parse_title 
    // запускается при создании нового поста на стене
    
    $htm = '';
    $a_text = '';
    $a_img = '';
    $frame = ht_tag_iframe( $s );
    $url_content = '';

    if (!empty($frame['url'])){
        $url = $frame['url'];
        
        $url_content = @file_get_contents($url);

        $url_headers = $http_response_header;
        $url_type_content = '';
        foreach($url_headers as $sx){
          if (false !== stripos($sx, 'Content-Type:')){ $url_type_content = trim(substr($sx,  strpos($sx,':')+1)); }
          if ($url_type_content != '') { break;};
        }
       $url_is_image = ( stripos($url_type_content,'image') !== false );
       $url_is_text = ( stripos($url_type_content,'text') !== false );
        
       $a_title = iif( $url_is_image,$frame['t1'], ht_title_of_url($frame['url'])); 
       $url_title = str_replace(['"','\'','«','»'], '', $a_title) ;

//       $text = '';
//       if ($url_is_text) {
//            $dom = str_get_html($url_content);
//            $text = ''; // simple_html_to_md ($dom ); 
//       } else {
//            if (empty($frame['iframe']) && strpos('<youtube',$frame['t2'])===false ){
//                $frame['iframe'] = tag_('iframe',' ',a_('src',$url)  );
//            }
//       }
       
        $a_text =  $a_text 
                    . iif ($a_title !== $frame['t1'], $frame['t1'])
                    . iif( $url_is_image===false, '[ '.$url_title  .'... ]','![]') .'( '. $frame['url'] .' ) '
                ;
    } else {
        $a_text = $s;
    }
    return $a_text;
}



function ht_parse_title($s){
    $htm = '';
    $a_text = '';
    $a_img = '';
    $frame = ht_tag_iframe( $s );
    $url_content = '';

    if (!empty($frame['url'])){
        $url = $frame['url'];
        
        $url_content = @file_get_contents($url);
// file_put_contents('c:/tmp/ddd.txt', $url_content);
        $dom = str_get_html($url_content);
        $url_headers = $http_response_header;
        $url_type_content = '';
        foreach($url_headers as $sx){
          if (false !== stripos($sx, 'Content-Type:')){ $url_type_content = trim(substr($sx,  strpos($sx,':')+1)); }
          if ($url_type_content != '') { break;};
        }
       $url_is_image = ( stripos($url_type_content,'image') !== false );
       $url_is_text = ( stripos($url_type_content,'text') !== false );
        
       $a_title = iif( $url_is_image,$frame['t1'], ht_title_of_url($frame['url'])); 
       $url_title = str_replace(['"','\'','«','»'], '', $a_title) ;

       $text = '';
       if ($url_is_text) {
            $text = simple_html_to_md ($dom ); 
       } else {
            if (empty($frame['iframe']) && strpos('<youtube',$frame['t2'])===false ){
                $frame['iframe'] = tag_('iframe',' ',a_('src',$url)  );
            }
       }
       
        $a_text =  $a_text 
                    . iif ($a_title !== $frame['t1'], $frame['t1'])
               
                    . iif( $url_is_image===false, '[ '.$url_title  .'... ]','![]') .'( '. $frame['url'] .' ) '
                
                     .$frame['t2']   
                .$text
                ;
    } else {
        $a_text = $s;
    }
return $a_text;






    
   $htm_preview = $a_text;
   // -- пытаемся найти картинку
        $i = strpos($htm_preview,'<img '); $i = strpos($htm_preview,' src',$i);
        if ($i !== false){
            $j = strpos($htm_preview,'>',$i+5);
            $si = substr($htm_preview,$i,$j-$i);
            $i = strpos($si,'"');
            $j = strpos($si,'"',$i+1);
            $a_img = substr($si,$i+1,$j-$i);
        }
   
   // -- пытаемся найти youtube
   if(empty($a_img)) {
        $i = strpos($htm_preview,'<youtube '); 
        if ($i !== false){
            $j = strpos($htm_preview,'>',$i+5);
            $si = substr($htm_preview,$i,$j-$i);
            $i = strpos($si,'"');
            $j = strpos($si,'"',$i+1);
            $si = substr($si,$i+1,$j-$i-1);
            $si = "https://img.youtube.com/vi/".$si."/hqdefault.jpg";
            $a_img = $si;
        }
   }
   
   // -- картинка задана, значит надо сделать  иконку 100х100
    if(!empty($a_imgt)) {
        $nf = $a_img;
/*        
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
*/        
    }
    
}



function ht_title_clear($t){
    $t = str_replace( array ( '- YouTube','– смотреть видео онлайн в Моем Мире','| ВКонтакте'), '' , $t);
    $t =trim( str_replace( array( '«','»','“','”') ,'"' , $t));
    return ($t);
}

function ht_title_of_url($url){
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
        if (empty($s)) continue;

        $k = stripos( $s, 'charset');
        if ($k !== false) {
            $charset = $s;
        } else {
            
            if ( (stripos($s,'</title>') === false) && (stripos($s,'<body>') !== false) )
            {
                $tTitle = 'h1'; $lTitle = 2; $p = '';
            }
            
            if ($p === '') {
                $k = stripos( $s, '<'.$tTitle);
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
    return ht_title_clear( $p );
}



function ht_tag_iframe($chm, $title='', $h=315, $w=420 ){
//   создание вставки для видео
//
    $url = '';
    $t1 = '';
    $t2 = '';
    $r['iframe'] = '';
    $r['key_youtube']='';
    $r['title'] = '';
    
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
        $i = strpos($url, '?'); $p = [];
        if ($i !== false) 
        { 
           parse_str(substr($url, $i+1),$p);
        }
        $t2 = substr( $chm, $n).' ';
        
        $r['title'] = $title;
        
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
                }
                $url=str_replace('/video/','/videoembed/',$url); // ok.ru
                $i = strpos($url,'embed/');
                $j = strpos($url,'&',  $i+6);
                if ($j !== false) $url = substr($url,0,$j);
            }
            
            if (stripos($url,'/channel/') === false && stripos($url,'img.youtu') === false ) {
                if (stripos($url,'youtu') > 0  && stripos($url,'/channel/') ===false ) {
                    $r['iframe'] = '';
                    $key = substr($url, strrpos($url, '/')+1);
                    $i = strpos($key,'&'); if ($i > 0) { $key = substr($key,0,$i-1); }
                    $i = strpos($key, '?');
                    if ($i !== false) $key = substr ($key,0,$i);
                    
                    $r1 = new db(DB_POBEDIM, 'select * from w2_youtube where key_youtube=:key',[':key'=>$key] );
                    if ($r1->length == 0){
                        $title = ht_title_of_url($r['url']);
                        $r1 = new db(DB_POBEDIM, 'insert into w2_youtube(KEY_YOUTUBE,TITLE_YOUTUBE) values (:key,:t)',[':key'=>$key,':t'=>$title] );
                    } else {
                        $title = $r1->row('TITLE_YOUTUBE');
                    }
                    $r['title'] = $title;
                    //if (empty($title)) $title = ht_title_of_url($r['url']);
                    $r['t'] = va($p,'t');
                    $t2 .= chr(13).chr(10).'<youtube key="'.$key.'" href="'.$r['url'].'&t='.$r['t'].'" title="'.$r['title'].'"  >';
                    $r['key_youtube']=$key;
                    
                }
                else {
                    $r['iframe'] = '<iframe width="'.$w.'" height="'.$h.'" src="'.$url.'"  title="'.$r['title'].'" frameborder="0" allowfullscreen></iframe>' ;
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








function ht_is_image_content($s){
    $pi = pathinfo($s,PATHINFO_EXTENSION); 
    switch ($pi){
      case 'png':
      case 'jpg':
      case 'gif':
          return true;
    }
    return false;
}


function simple_htmltag_to_md ( $node_dom ){
    $t  = '';
    $children = $node_dom->nodes;
    if ($children == null) return '';
    if (count($children)>0){
            foreach ( $children as $n)
            {
                $s = $n->text(); $tag = $n->tag;
                $md = ''; $md2 = ''; 
                switch ( $n->tag){
                    case 'form':
                    case 'button':
                    case 'input':
                    case 'nav': break;///***
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
                        if(ht_is_image_content($href)) { $md =''; $md2 = '![]('.$href.')';}
                            else { $md = ' ['; $md2 = ']('.$href.')';}
                        break;
                    case 'img': $href = $n->getAttribute('src');
                        $md = ''; $md2 = '![]('.$href.')';  break;
                    case 'sup': $md = '<sup>'; $md2 = '</sup>';
                    default: break;
                }
                if ( empty($md) && empty($md2) )        continue;
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
        /*
        if (!isset($et0)){ $et0 = simple_html_first_p($dom ,'h1'); }
        if (!isset($et0)){ $et0 = simple_html_first_p($dom ,'h2'); }
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('br');}
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('p');}
        */
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('h1');}
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('h2');}
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('p'); }
        if (!isset($et0)){ $et0 = $dom->getElementByTagName('br');}
        if (!isset($et0)) { return ''; } else {
            return simple_htmltag_to_md ($et0->parentNode());
        }
    }
}
