<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
require_once $dir0 . '/derjava/wall_fn.php';

$fu = current_fu();
$u = current_iu();
$m = val_rq('m', null);

$pa = val_rq('pa')+0;

if ($pa == 0) {
    $get = array_keys($_GET);
    $pa = $get[0];
}

$ht = new html('/derjava/opinion.html');
$menu= menu_user($ht, '');

unset($r0);
$r0 = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER pa where pa.id_answer=:pa'
        , [':pa' => $pa ]
        , function($row,$pp,$lp){
            $dir0 = f_root();
            
            $pa = $row['ID_ANSWER'];
            $w = $row['ID_WALL'];
            $src = "/w/$w/$w.jpg";
            
            $nf = $dir0 . $src;
            if (!file_exists($nf)) {
                $src = '';
            }
            $row['IMG_ANSWER'] = $src;
            return $row;
        }
        ,['pa'=>$pa , 'u'=>$u]
    );

        
$data0 =  $r0->rows;

$r1 = new db(DB_POBEDIM, 'SELECT P.* FROM W2_POLL_ANSWERS Q INNER JOIN W2_POLL P ON P.ID_POLL=Q.ID_POLL WHERE Q.ID_ANSWER = :pa'
        , [':pa'=>$pa ]
        , function($row, $pp, $lp){
            $dir0= f_root();
    
                $qq = $row['ID_POLL'];
                $img1 = "/data.poll/$qq/thumb64.jpg";
                $nf0 = $dir0."/data.poll/$qq/$qq.jpg";
                $tf0 = filemtime_($nf0);
                if ($tf0 !== false) {
                    $nf1 = $dir0. $img1;
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
            
            
            $row['IMGSRC_POLL'] = $img1;
            return $row;
         }
         ,['dir0'=> f_root()]
      );

$ht->part['head'][0]['TITLE'] = str_trunc( $r1->row('NAME_POLL'), 50).': ' . $r0->row('NAME_ANSWER').' | Держава.сайт / pobedim.su';;
$ht->part['head'][0]['TITLE_OG'] = ' Мнение ' . str_trunc( $r1->row('NAME_POLL'), 50).': '. str_trunc( $r0->row('NAME_ANSWER') );
$ht->part['head'][0]['IMG_OG'] = f_host('');
$ht->part['head'][0]['DESCRIPTION']= str_trunc( $r0->row('TEXT_ANSWER') ).'...';


$ht->data('data1', $r1->rows );






$r8 = getWall5($data0, $u, '0', '0', '0','/derjava/opinion.php?'.$pa 
        , $r0->row('ID_WALL'), null,$m, null
        ,'/derjava/opinion.php?'.$pa, val_rq('sxw') );

$ht->data('data8', $r8->rows);

   

$ht->data('data0', $data0 );
echo $ht->build('',true);

