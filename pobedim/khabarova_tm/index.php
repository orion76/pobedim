<?php

 $dir = dirname( dirname(__DIR__ ) );
 require_once ($dir.'/ut.php'); 

 $page = basename( __FILE__ );
 $dir_base = '/pobedim/khabarova_tm/';
 $dir_gallery = 'gallery';

// ----- begin gallery  ---------------------------
     $photo = val_rq('photo');

    $dirlist = directoryToArray( __DIR__ .'/'.$dir_gallery , false );
    if (count($dirlist) === 0 ) { }

    $s = '';
    if (!empty($photo)) {
        
        $i = array_search($photo, $dirlist);
        $i++; if (count($dirlist) <= $i ) $i = 0;
        $fn = $dirlist[$i]; 
        $s = tag_('div', tag_a( $page. '?photo='. $fn
                , tag0_('img', a_('src', $dir_gallery.'/'. $photo) . a_class("bigphoto")   )
                . tag0_('img', a_class("bigphotonext"). a_('src','arrow_right.png') )
                 )
              ,a_class("bigphoto")). BR2 ;
    }

    foreach( $dirlist as $fn) 
    {
        $s .=  tag_a( $page . '?photo='. $fn, tag0_('img', a_('src', $dir_gallery.'/'. $fn) .a_class('photo') ));
    }
// ----- end gallery  ---------------------------



$s1 = 
<<<'EOT'


EOT;


// function url_nf($nf){  return urlencode( iconv( 'UTF-8','WINDOWS-1251', $nf ) );}

    echo tag_html(   

            tag_div(  tag_p( $s1 )
                    .tag_div( tag_div( $s , a_class("photos")) , a_class("photos0")  )
                    
                    , a_style(CSS_TEXTLEFT) )
            
            ,[tag0_('base', a_('href',http_url($dir_base)))
                , tag_('script','   function scroll_raised(){
                    
                                }
                                //setInterval(scroll_raised, 3000);
                                ')
                ]
        );
