<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/file_uploader.php';


$ax = val_rq('ax');
$u = current_iu();
$iv = val_rq('iv');


switch ($ax) {
/*    
    case 'ge_main_pic':
            $qq = val_rq('qq');
            $allowedExtensions = array(); // list of valid extensions, ex. array("jpg", "png", "bmp")
            $sizeLimit = 10000 * 1024 * 1024; 
	    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
            $dir = dirname( __DIR__ ). '/tmp/up';
            if (!file_exists($dir)) {	mkdir($dir,0777,true); 	}
            $result = $uploader->handleUpload($dir);
            
            if ($result['success']){
                $err = '';
                $nd = dirname( __DIR__ ). "/data.poll/$qq";
                if (!file_exists($nd)){ mkdir($nd,0777);}
                
                $img = "/data.poll/$qq/$qq.jpg";
                $nf = dirname( __DIR__ ). $img;
                $tf = dirname( __DIR__ ). '/tmp/up/'.$result['filename'];
                $sz=filesize_($tf);
                try {
                    $im = new Imagick_();
                    if ($im->pingImage($tf)) {
                        $im->readImage($tf);
                        $im->thumbnailImage(800, 400, true);
                        $im->writeImage($nf);
                        $nf = dirname($nf);
                        unlink("$nf/thumb64.jpg");
                    } else {
                        $err = 'Формат файла не поддерживается';
                    }
                    $result['img'] = $img."?$sz";
                } catch (Exception $e) {
                    $err = 'Ошибка обработки изображения';
                } 
                unlink($tf);
            }
            echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        break;
*/        
    case 'ge_pa_pic':
            $qq = val_rq('qq');
            $pa = val_rq('pa');
            $allowedExtensions = array(); // list of valid extensions, ex. array("jpg", "png", "bmp")
            $sizeLimit = 10000 * 1024 * 1024; 
	    $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
            $dir = dirname( __DIR__ ). '/tmp/up';
            if (!file_exists($dir)) {	mkdir($dir,0777,true); 	}
            $result = $uploader->handleUpload($dir);
            
            if ($result['success']){
                $err = '';
                $img = "/data.poll/$qq/$pa.jpg";
                $img0 = "/data.poll/$qq/$pa.0.jpg";
                $nf = dirname( __DIR__ ). $img;
                $nf0 = dirname( __DIR__ ). $img0;
                $tf = dirname( __DIR__ ). '/tmp/up/'.$result['filename'];
                $sz=filesize_($tf);
                try {
                    $im = new Imagick_();
                    if ($im->pingImage($tf)) {
                        $im->readImage($tf);
                        $im->writeImage($nf0);
                        $im->thumbnailImage(200, 200, true);
                        $im->writeImage($nf);
                        $nf = dirname($nf);
                        //unlink("$nf/thumb64.jpg");
                    } else {
                        $err = 'Формат файла не поддерживается';
                    }
                    $result['img'] = $img."?$sz";
                } catch (Exception $e) {
                    $err = 'Ошибка обработки изображения';
                } 
                unlink($tf);
            }
            echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        break;
}

