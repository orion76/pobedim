<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

require_once $dir0 . '/derzhava/derzhava_fn.php';

$ax = val_rq('ax', 0);
$bx = val_rq('bx');
$iv = val_rq('iv', 0) + 0;
$fu = current_fu();
$iu = current_iu();
$c = val_rq('c', 1000);
$p = val_rq('p');
$is = val_rq('is');


if (!empty($sx)) { $c = 100; }

$sx = val_rq('sx', '');

$u = $fu['iu'];
$iv = val_rq('iv', 0) + 0;
$qq = val_rq('qq', 0) + 0;


$template = '';

$data0 = null;
$data0[0]['TITLE'] =  'СЛОВАРЬ | Держава, pobedim.su';
$data0[0]['SX'] = $sx;
$data0[0]['ID_STATUTE'] = $is;

$kv = '';

$data0[0]['VECHE'] = null;
$data0[0]['NAME_VECHE'] = null;


$ht = new html('/derjava/slovar_search.html');

$ht->part['head'][0]['TITLE'] =  'Словарь | Держава, pobedim.su';
$ht->part['head'][0]['TITLE_OG'] = 'Словать';
//if (!empty($src)) $ht->part['head'][0]['IMG_OG'] = f_host($src) ;



$menu= menu_user($ht, 'ACTIVE2');
$menu['RX']='';



$r1 = new db( DB_POBEDIM, 'SELECT * from W2_POLL_SLOVAR_L2 (:iu,:iv,:p, :sx , :c , 4) order by NAME_POLL'
        , [':iu' => $iu, ':iv' => $iv, ':sx' => $sx, ':c' => $c, ':p'=>$p ]
        , function( &$row, $pa){
                $qq = $row['ID_POLL'];
                $img1 = "/data.poll/$qq/thumb64.jpg";
                $nf0 = dirname(__DIR__,1)."/data.poll/$qq/$qq.jpg";
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
                $row['IMG_POLL']= $img1;
                $row['HREF_POLL']= '/derjava/poll.php?qq=' . $row['ID_POLL'];
                $row['HREF_VECHE']= '/derjava/veche.php?iv=' . $row['VECHE'];
            return $row;
        } );

$ht->data('data1', $r1->rows );


// список неопубликованных

$r2 = new db(DB_POBEDIM
            , 'SELECT * from W2_POLL_impublished_L2 (:iu,:iv, 4, :sx )'
            , [':iu' => $iu, ':iv' => $iv, ':sx' => $sx]
            );

$ht->data('data2', $r2->rows );
$ht->data('data0', $data0 );
$ht->data('menu1', $menu );

echo $ht->build('',true);
 