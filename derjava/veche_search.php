<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';



$ax = val_rq('ax');
$iv = val_rq('iv', 0);
if (empty($iv)) {
    $iv = 0;
}
$u = current_iu();
$iu = $u;
$bx = val_rq('bx');


$sx = val_rq('sx');
$kv = val_rq('kv',3);
$f = val_rq('f',0);  if ($f == '1') {$kv = 0;}

$data0['SX'] = $sx;

$ht = new html('/derjava/veche_search.html');
$menu= menu_user($ht, 'ACTIVE1');
$menu['RX']='';


$ht->data('data0', $data0 );
$ht->data('menu1', $menu );

$r1 = new db(DB_POBEDIM, 'select * from  W2_VECHE_SX_L3(:sx,:kv,:u, :f) order by CNT_VECHE desc, veche_parent desc '
            , [':sx' => $sx, ':kv'=>$kv,':u'=>$u, ':f'=>$f]
            ,
            function(&$row) {

                $v = $row['VECHE'];
                $img1 = "/data.veche/$v/thumb64.jpg";
                $nf0 = dirname(__DIR__,1)."/data.veche/$v/$v.jpg";
                $tf0 = filemtime_($nf0);
                if ($tf0 !== false) {
                    $nf1 = dirname(__DIR__,1). $img1;
                    $tf1 = filemtime_($nf1);
                    if ($tf1 === false || $tf0 > $tf1) {

                        $im = new Imagick_();
                        if ($im->pingImage($nf0)) {
                            $im->readImage($nf0);
                            $im->thumbnailImage(64, 64, true);
                            $im->writeImage($nf1);
                        } else {
                            $err = 'Формат файла не поддерживается';
                        }
                    }
                } else {$img1 = '';}

                $row['HREF_VECHE']='/derjava/veche.php?' . $row['VECHE'];
                $row['IMG_VECHE']= $img1;
                return $row;
            }        
        );

$ht->data('data1', $r1->rows );
$ht->data('kind_veche', rows_kind_veche($kv,'+6','onclick="veche_search_kind_click(event)"'));

echo $ht->build('',true);

 