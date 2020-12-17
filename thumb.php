<?php 

    require_once ('ut.php'); 

    $tmpdir = dir_main().'tmp\\';

    $ax = val_rq('ax',100);

    
    
    function himg_thumb($f,$sz_thumb=100){
        $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
        if ($f == null) { $f = '';}
        $fn = $dir0.'/'.$f;
        if ($f != ''){
            $b = file_exists($fn) && strpos($f,'.') > 1;
            if ($b) {
                $tmpf = str_ireplace(array('\\','/'),'_', $sz_thumb.'__'.$f);
                $tmpfn = $dir0.'\\tmp\\'. $tmpf;
                $tmpfn = str_ireplace('\\\\','\\', $tmpfn);

                if (!file_exists($tmpfn)){
                    $im = new Imagick_();
                    $im->readImage( $fn );    
                    $im->thumbnailImage( $sz_thumb, $sz_thumb, true );
                    $im->writeImage( $tmpfn );
                }

                if (file_exists($tmpfn)){ $f= '<img src="/tmp/'.$tmpf.'" />'; }
                        else { $f = '';}
            } else { $f = '';}
        }
        return $f;
    }
    
    
    
    
    
    
   function thumb_img_src($s,$href){ return '';}

   
   
   
    function himg_thumb_offer($f,$path=''){
        
        if ($f == null) { $f = '';}
        if ($f != ''){
            $fn = dir_main().'o\\'. str_ireplace('/','\\', $f);
            $fn = str_ireplace('\\\\','\\', $fn);
            $b = file_exists($fn) && strpos($f,'.') > 1;
            if ($b) {
                $tmpf = str_ireplace(array('\\','/'),'_', $f);
                $tmpfn = dir_main().'tmp\\'. $tmpf;
                $tmpfn = str_ireplace('\\\\','\\', $tmpfn);

                if (!file_exists($tmpfn)){
                    $im = new Imagick_();
                    $im->readImage( $fn );    
                    $im->thumbnailImage( 100, 100, true );
                    $im->writeImage( $tmpfn );
                }

                if (file_exists($tmpfn)){ $f= '<img src="'.$path.'tmp/'.$tmpf.'" />'; }
                        else { $f = '';}
            } else { $f = '';}
        }
        return $f;
    }

    function himg_offer($f){
        if ($f == null) { $f = '';}
        if ($f != ''){
            $fn = dir_main().'o\\'. str_ireplace('/','\\', $f);
            $fn = str_ireplace('\\\\','\\', $fn);
            $b = file_exists($fn) && strpos($f,'.') > 1;
            if ($b) {
                $f = str_ireplace('//','/', 'o/'.$f);
                $f= '<img src="'.$f.'" />';
            } else { $f = '';}
        }
        return $f;
    }






	function im_print($s)
	{
            /*
		$Imagick = new Imagick_();
		$bg = new ImagickPixel();
		$bg->setColor( 'white' );
		$ImagickDraw = new ImagickDraw();
		$ImagickDraw->setFont( 'Calibri' );
		$ImagickDraw->setFontSize( 20 );
		$Imagick->newImage( 85, 30, $bg ); 
		$Imagick->annotateImage( $ImagickDraw, 4, 20, 0, $s );
		$Imagick->swirlImage( 20 );
		$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		$ImagickDraw->line( rand( 0, 70 ), rand( 0, 30 ), rand( 0, 70 ), rand( 0, 30 ) );
		$Imagick->drawImage( $ImagickDraw );
		$Imagick->setImageFormat( 'png' );
		header( "Content-Type: image/{$Imagick->getImageFormat()}" );
		echo $Imagick->getImageBlob( );
             
             */
	}


    if ($ax == 109) {
        /*
        $tmpf = "C:\\tmp\\P1010829.JPG";
        $im = new Imagick( );
        $im->readImage( $tmpf );    
        $im->thumbnailImage( 100, 100, true );
        $im->writeImage( $tmpdir.'tmp.jpg' );
        header( "Content-Type: image/{$im->getImageFormat()}" );
        echo $im->getImageBlob( );
        */
        echo im_print('hello');
    }
?>
