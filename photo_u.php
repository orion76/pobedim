<?php
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';

$u = val_rq('u');
$text = $u;
$fu = get_f_user($u,'u');
user_adjust_data($fu);

$photo_h= val_rq('h',0);
$photo_w= val_rq('w',0);
$photo_x= val_rq('x',0);
if ( $photo_x > 0 ) { $photo_w = $photo_x; $photo_h = $photo_x;}

function nf_img($u, &$fu, $nd, $url , $url0 , $dir_files ) {
    $dir0 = f_root();
    $photo_u = '';
    if (empty($fu))  return $dir0.$url0;
   
    unlink_("/u/$u/time.tmp");
    unlink_("/u/$u/index.php");
    unlink_("/u/$u/x.css");
    unlink_("/u/$u/$u.user");
    
    if (file_exists($dir0."/u/$u/files")){
        @rename($dir0."/u/$u/files", "$dir_files/$u");
    }
    
    $nf = $dir0 .$url; 
    
    if (!file_exists($nf)) {
    $photo_u = va($fu, 'photo_u');
    if ($photo_u != $url) $photo_u = ''; 
   
    if (empty($photo_u)){
        $url_photo_u = va($fu, 'url_photo_u');
        if (strpos($url_photo_u, '__URL_') !== false ) $url_photo_u = '';
        if (empty($url_photo_u)) { $url_photo_u = va($fu, 'url_photo_nu'); $fu['url_photo_u']=''; }
        if (empty($url_photo_u)) {
            $url_photo_u = va($fu, 'photo_u__'); 
            if (strpos($url_photo_u, '__URL_') !== false ) $url_photo_u = '';
        }
        if (empty($url_photo_u) && file_exists("$dir_files/$u")) {
           $aa = directoryToArray("$dir_files/$u", false);
           if (count($aa) == 0) @rmdir("$dir_files/$u");
           else    
           {
              // $r = @gd_info();
               foreach ($aa as $f) {
                if ( strripos($f,'.jpg') === false 
                  && strripos($f,'.jpeg') === false       
                  && strripos($f,'.png') === false                        
                  && strripos($f,'.gif') === false                        
                  && strripos($f,'.wbmp') === false  
                  && strripos($f,'.xbm') === false
                  && strripos($f,'.xpm') === false
                        )continue;
                $url_photo_u = $f;break;
               }
           }
        }
        $fu['url_photo_u']=$url_photo_u; 

            $response = false;
            
        if (!empty($url_photo_u)){ 
            
            if (strpos($url_photo_u, 'http') !== false) {

                $arrContextOptions = array("ssl" => array("verify_peer" => false, "verify_peer_name" => false));
                set_time_limit(120);// 'max_execution_time'

                    try {  $response = @file_get_contents($url_photo_u, false, stream_context_create($arrContextOptions));
                        } catch (Exception $e) { $response = false; }
            } else {
                if (strpos($url_photo_u, '/') === false){
                    
                    if (file_exists( $dir_files.'/'.$u.'/'.$url_photo_u)){
                        $response = @file_get_contents( $dir_files.'/'.$u.'/'.$url_photo_u);
                        
                    } else
                    {
                      if (file_exists($dir0 .'/u/'.$u.'/files/'.$url_photo_u)){
                        $response = @file_get_contents( $dir0 .'/u/'.$u.'/files/'.$url_photo_u);
                      }
                    }
                    
                    
                } else
                if (file_exists($dir0 .'/' . $url_photo_u) )
                    $response = @file_get_contents( $dir0 .'/' . $url_photo_u);
                else {
                  //  $fu['url_photo_u']='';
                  // $fu['url_photo_nu']='';
                  //  $fu['photo_u__']=$url_photo_u;
                    $response = false;
                }
            }
            if ($response !== false) {

                $im = @imagecreatefromstring($response);
                if ($im !== false){
                    if (!file_exists($nd)) mkdir($nd, 0777, true);
                    if (@imagepng($im, $nf) === false) $nf = '';
                }
                
            } else $nf = '';
        }  else $nf = '';
    }
        
        if(empty($photo_u)){
            $fu['photo_u'] = iif (empty($nf), $url0, $url );
            if ($u == va($fu,'u') || $u == va($fu,'iu') ) {
               $t = ini_from_array($fu);
               $r = new db(DB_USER, 'update w0_user set text_login=:t where u=:u and text_login is distinct from :t', [ ':u'=>$u, ':t'=> $t] );
           }
        }
        $nf = $dir0. $fu['photo_u'];
    }
    return $nf;         
}




define('PATH_TTF',  '/css/font/');
$fonts = array('HeliosCondLight.ttf'//, 'MyriadPro-Regular.ttf', 'MyriadPro-Semibold.ttf'
                    );

$par = array( 'WIDTH' => 180, 'HEIGHT' => 64, 'FONT_SIZE' => 10 );
if ($photo_w != 0) $par['WIDTH'] = $photo_w;
if ($photo_h != 0) $par['HEIGHT'] = $photo_h;

$img = @imagecreatetruecolor($par['WIDTH'], $par['HEIGHT']);

$white = @imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
//$black = imagecolorallocate($img, 0x00, 0x00, 0x00);
@imagefilledrectangle($img, 0, 0, $par['WIDTH'] - 1, $par['HEIGHT'] - 1, $white );


$dir_files = $dir0.'/w/u_files';
$nd = $dir0 . "/w/u_photo/$u";
$url = "/w/u_photo/$u/photo.png";
$url0 = "/32/user32.png";
$nth = '';

//@unlink("$dir0/u/$u/$u.user");
//@unlink("$dir0/u/$u/index.php");
//@unlink("$dir0/u/$u/x.css");
//@unlink("$dir0/u/$u/thumb/thumb64.png");
@rmdir("$dir0/u/$u/thumb");
@rmdir("$dir0/u/$u");

if (!file_exists($dir_files)) mkdir ($dir_files);
        
$nf = nf_img($u, $fu, $nd, $url,$url0,$dir_files); 

if ( $par['WIDTH'] == $par['HEIGHT'] && strpos($nf,'/photo.png') !== false ) {
        $thumb = $par['HEIGHT']; 
        $nth = "$nd/photo_$thumb.png";
        if ( file_exists($nth) ) $nf = $nth;
    }
    else $thumb = false;  

   
$response = @file_get_contents( $nf );
if ($response !== false ){
    $size=@getimagesize($nf);  
    if ($nf != $nth) {
            $im = @imagecreatefromstring($response);
            $w=$size[0]; $h=$size[1]; $x = 0; $y=0;
            
            $angle = va($fu,'photo_rotate',0)*1.0;
            if ($angle != 0){
              if ($angle < 0) $angle = 180 - $angle;  
              $im1=  @imagerotate($im,$angle,$white);  if (abs($angle)==90) { $y = $h; $h=$w; $w=$y;  $y=0; }
              @imagedestroy($im); 
              $im = $im1;
            }
            
            
            $img =@imagescale ( $im , $par['WIDTH'],$par['HEIGHT'] );
            @imagedestroy($im);
            
            /*
                if ($w < $h * 0.8  && $w > 100 ) {
                    $x = round( 0.1*$w ); 
                    if (($h-$w)/2 -10 > 0) $y = round(($h-$w)/2 -10);   
                    $w = round(0.8*$w); 
                }

                @imagecopyresampled($img,$im,0,0,$x,$y, $par['WIDTH'],$par['HEIGHT'],$w,$w);
                @imagedestroy($im);
             
            */
                if ($thumb !== false) {
                    if (!file_exists($nd)) @mkdir($nd, 0777, true);
                    @imagepng($img,$nth);
                }

            
     } else {
            @imagedestroy($img);
            $img = imagecreatefromstring($response);
        }
}    
    
//$c1 = mt_rand(20,150); //r(ed)
//$c2 = mt_rand(20,150); //g(reen)
//$c3 = mt_rand(20,150); //b(lue)
//$color = imagecolorclosest($img, $c1, $c2, $c3);


$font = f_root() . PATH_TTF . $fonts[mt_rand(0, count($fonts) - 1)];
$y = $par['HEIGHT'] - $par['FONT_SIZE'];
$x = 3;
$angle = 0;

function invert_color($c){
    $c = 255 - $c + mt_rand(0,50)-mt_rand(0,50);
    if ($c < 0) $c = 0;
    if ($c > 255) $c = 255;
    return $c;
}

            $i = @imagecolorat($img, $par['WIDTH']/2, $y-5);
            $rgb = @imagecolorsforindex($img,$i);
            $color = @imagecolorallocate($img, invert_color($rgb['red']), invert_color($rgb['green']), invert_color($rgb['blue']));


$nu1 = va($fu,'nu1'); 
$nu3 = va($fu,'nu3'); 
if ($nu1==$nu3) { 
    $nu3 = str_replace('.',' ',va($fu,'nu')); 
    $nu3 = str_replace('_',' ',$nu3); 
    $nu3 = str_replace('-',' ',$nu3);
    
    $k = strpos($nu3,' '); 
    if ($k!==false) { if ($k > strlen($nu3)/2){$nu1=substr($nu3,$k);$nu3=substr($nu3,0,$k);}
    else {$nu1=substr($nu3,0,$k);$nu3=substr($nu3,$k);}   }  
}
@imagealphablending($img,true);
@imagettftext($img, $par['FONT_SIZE'], $angle, $x, $y-$par['FONT_SIZE']-5, $color, $font, trim($nu1));    
@imagettftext($img, $par['FONT_SIZE'], $angle, $x, $y, $color, $font, trim( $nu3));    


header("Content-Type: image/png");
@imagepng($img);
@imagedestroy($img);