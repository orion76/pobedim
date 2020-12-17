<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';
require_once $dir0 . '/derzhava/derzhava_fn.php';

$ax = val_rq('ax', '0');
$bx = val_rq('bx');
$iv = val_rq('iv', 0) + 0;
$fu = current_fu();
$u = current_iu();
$c = val_rq('c', 15);
$is = val_rq('is');

$sx = val_rq('sx', '');

if (!empty($sx)) { $c = 100; }


$u = $fu['iu'];
$iv = val_rq('iv', 0) + 0;
$qq = val_rq('qq', 0) + 0;


$template = '';

$data0 = null;
$data0[0]['TITLE'] =  'Поиск голосования | Держава, pobedim.su';
$data0[0]['SX'] = $sx;
$data0[0]['ID_STATUTE'] = $is;


$data0[0]['HREF_BTN_NEW'] = "/derjava/poll_create.php?iv=$iv";


$kv = '';

if ( $iv != "0" ) {
    $r = new db(DB_POBEDIM, 'select NAME_VECHE, ID_KIND_VECHE from w2_veche v where v.veche=:iv', [':iv' => $iv]);
    $data0[0]['VECHE'] = $iv;
    $data0[0]['NAME_VECHE'] = $r->row('NAME_VECHE');
    $kv = $r->row('ID_KIND_VECHE');
} else {
    $data0[0]['VECHE'] = null;
    $data0[0]['NAME_VECHE'] = null;
}


if (isUserLoggedOn()) {
    if ($kv == 8) $ht = new html('/derjava/poll8_search.html');
        else  $ht = new html('/derjava/poll_search.html');
} else {
    $ht = new html('/derjava/poll_search_guest.html');
}
$ht->part['head'][0]['TITLE'] =  'Поиск голосования';
$ht->part['head'][0]['TITLE_OG'] = 'Поиск голосования';
//if (!empty($src)) $ht->part['head'][0]['IMG_OG'] = f_host($src) ;


$menu= menu_user($ht, 'ACTIVE2');
$menu['RX']='';



$r1 = new db( DB_POBEDIM, 'SELECT * from W2_POLL_L11 (:iu,:iv, :sx , :c ,:f, :is , null)'
        , [':iu' => $u, ':iv' => $iv, ':sx' => $sx, ':c' => $c, ':f'=> val_rq('f',0),':is'=>$is]
        , function( &$row, $pa){
                $qq = $row['ID_POLL'];
                $w = $row['ID_WALL'];
                $img1 = "/w/$w/thumb64.jpg";
                $nf0 = f_root()."/data.poll/$qq/$qq.jpg";
                $tf0 = filemtime_($nf0);
                if ($tf0 !== false) {
                    $nf1 = f_root(). $img1;
                    $tf1 = filemtime_($nf1);
                    if ($tf1 === false || $tf0 > $tf1) {
                        $im = new Imagick_();
                        if ($im->pingImage($nf0)) {
                            $im->readImage($nf0);
                            $im->thumbnailImage(64, 64);
                            $im->writeImage($nf1);
                        } else {
                            $err = 'Формат файла не поддерживается';
                        }
                    }
                } else {$img1 = '';}
                $row['IMG_POLL']= $img1;
                $row['HREF_POLL']= '/derjava/poll.php?' . $row['ID_POLL'];
                $row['HREF_VECHE']= '/derjava/veche.php?' . $row['VECHE'];
            return $row;
        } );

 if ($r1->length < 10 && empty($data0[0]['SX']) )        
 {
     $data0[0]['VISIBLE_SEARCH_POLL'] = null;
     
 }   else {
     $data0[0]['VISIBLE_SEARCH_POLL'] = $c;
 }  
        
$ht->data('data1', $r1->rows );


// список неопубликованных

$r2 = new db(DB_POBEDIM ,'SELECT * from W2_POLL_IMPUBLISHED_L2  (:iu,:iv, null, :sx)'
            , [':iu' => $u, ':iv' => $iv, ':sx' => $sx]
            );

$ht->data('data2', $r2->rows );
$ht->data('data0', $data0 );
$ht->data('menu1', $menu );

echo $ht->build('',true);
 