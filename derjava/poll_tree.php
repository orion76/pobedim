<?php
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$bx = val_rq('bx', 0);

$fu = current_fu();
$iu = $fu['iu'];
$u = $fu['iu'];

$iv = val_rq('iv', 0) + 0;
$qq = val_rq('qq', 0) + 0;
$pa = val_rq('pa', 0) + 0;

$err = '';


$ht = new html('/derjava/poll_tree.html');
$menu= menu_user($ht, 'ACTIVE6');
$menu['RX']='';

include $dir0 . '/derzhava/derzhava_fn.php';
$r0 = new db(DB_POBEDIM,'select * from w2_poll where id_poll=:qq',[':qq'=>$qq]
        ,function ($row){

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
            $row['IMGSRC_POLL'] = $img1;
            return $row;
        }
        );



$ht->data('data0', $r0->rows );
$ht->subst('data_tree', tree_poll2($qq, null) );
$ht->data('menu1', $menu );

echo $ht->build('',true);





function tree_poll2( $poll , $ar = null , $szln=7000 ){
    if (empty($poll)) return '';

    $r1 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_poll=:qq and SORTING_ANSWER>-1',[':qq'=>$poll]);
    $r1->filter = function($row, $lp){
        $vr = $row['VECHE_ANSWER'];
        $sx = ''; //$ar==$row['ID_ANSWER']
        if($vr !== null){
            $r2 = new db(DB_POBEDIM,'select * from w2_veche where veche=:iv',[':iv'=>$vr]);
            $pr = $r2->row('ID_POLL_RESULT');
            $sx = BR1 .  tag_a('/derjava/veche.php?iv='.$vr, tag_img('/css/group.png', a_style('height:32px;')). $r2->row('NAME_VECHE'));

            $r3 = new db(DB_POBEDIM,'select * from w2_poll where veche=:iv',[':iv'=>$vr]);
            $r3->filter = function($row,$lp){
                $sx= '';
                return tag_li( tag_a('/derjava/poll.php?qq='.$row['ID_POLL'], tag_img( iif($row['ID_POLL'] === $lp['pr'],'/css/bx-checked.png', '/css/bx-empty.png'), a_style('height:16px;'))
                         . $row['NAME_POLL'] ) . tree_poll2( $row['ID_POLL'] ,   $lp['ar']   )
                              , iif($row['ID_POLL'] === $lp['pr'], a_style('background-color:lightyellow;'))  );
            };

            if ($r3->length > 0) {
                $sx .= tag_ul($r3->printf(null,['pr'=>$pr , 'ar'=> $row['ID_ANSWER_RESULT'] , 'szln'=>$lp['szln'] ]));
            }
        }

        return tag_li( iif($row['ID_ANSWER'] === $lp['ar'], tag_img('/css/bx-check.png', a_style('height:16px;')))
                . iif( mb_strlen($row['NAME_ANSWER'])>$lp['szln'],mb_substr( $row['NAME_ANSWER'],0,$lp['szln']).'<b>...</b>'
                        ,$row['NAME_ANSWER'])
                        . $sx);
    };
    return tag_ul( $r1->printf(null, ['ar'=>$ar, 'szln'=>$szln])  );
}
