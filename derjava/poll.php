<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
//$dir0 = $_SERVER['DOCUMENT_ROOT'];

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';
require_once $dir0 . '/derjava/wall_fn.php';

$ax = val_rq('ax', 'view');
$bx = val_rq('bx', 0);
$m = val_rq('m', null);
$fu = current_fu();
$iu = $fu['iu'];
$u = $fu['iu'];
$err = '';

$qq = val_rq('qq', 0) + 0;
if ($qq == 0) {
    $get = array_keys($_GET);
    if (count($get) ===0 ){
      echo htm_redirect1('/derjava/poll_search.php');  exit; 
    }
    else {
        $qq = $get[0];    
    }
}


if ( is_nobody($u) ){
    if ($qq === 0){ echo htm_redirect1('/derjava/poll_search.php');  exit; }
} else {
   $r = new db(DB_POBEDIM, 'insert into LOG_POLL_VIEW (u, id_poll) values (:u,:p)',[':u'=>$u, ':p'=>$qq] );    
   unset($r);
}




$r0 = new db(DB_POBEDIM, 'select * from w2_poll_s1 ( :u , :qq)' , [':qq' => $qq, ':u' => $u]
  , 
    function($row,$pa){
    
            $r = new db(DB_POBEDIM,<<<SQL
   SELECT first 1  1 as POLL_CHECK
    FROM U2_POLL PU
      INNER JOIN W2_POLL_ANSWERS PAA ON PAA.ID_POLL = PU.ID_POLL AND
                                        PAA.TS_INSERTED > PU.TS_READ_POLL
    WHERE PU.U = :u and PU.ID_POLL = :p
SQL
             ,[':u'=>$pa[':u'],':p'=>$pa[':qq']]
           );
        $row['POLL_CHECK'] = $r->row('POLL_CHECK');
        unset($r);
        
     
        if ($row['VECHE'] == 4) {
            $row['ID_KIND_POLL'] = 4;
        }
    
        $b0 = null;
        if ($row['ID_KIND_POLL'] == 3){
            $b0 = 1;
        }
            
        $row['VISIBLE_TOOLS0'] = iif( $row['ID_KIND_VECHE'] != 6 &&
                $row['ID_KIND_VECHE'] != 7  &&
                $row['ID_KIND_VECHE'] != 8 
                ,1, $b0 );
        
        
        $row['VISIBLE_TOOLS6'] = iif( $row['ID_KIND_VECHE'] == 6 ,1, null );
        $row['VISIBLE_TOOLS7'] = iif( $row['ID_KIND_VECHE'] == 7 ,1, null );
        $row['VISIBLE_TOOLS8'] = iif( $row['ID_KIND_VECHE'] == 8 && $b0 == null ,1, null );        
        
        $r = new db(DB_POBEDIM,'select * from W2_POLL_STATUTE_S (:qq)',[':qq'=>$row['ID_POLL']]);
        $row['NAME_STATUTE'] = $r->row('NAME_STATUTE');
        $row['ID_STATUTE'] = $r->row('ID_STATUTE');
        
        if ($row['CNT_VECHE_ANSWER'] == 0) $row['CNT_VECHE_ANSWER'] = null;
      return $row;
    }      
  ,[] 
);

$p =  $r0->row('ID_POLL') + 0;   
if ($p == 0) {
    echo htm_redirect1('/derjava/poll_search.php');
    exit;
}
    

$iv = $r0->row('VECHE') + 0;
$w = $r0->row('ID_WALL');
$data0 = $r0->rows;

if (isUserLoggedOn()){
    if ($data0[0]['ID_KIND_VECHE'] ==8  && $data0[0]['ID_KIND_POLL'] != 3 ){
        $ht = new html('/derjava/poll8_page.html');
    } 
    else {
        
        if ($data0[0]['ID_KIND_POLL'] == 4) {
            $ht = new html('/derjava/slovar.html');
        } else 
        $ht = new html('/derjava/poll.html');
    }
} else {
    $ht = new html('/derjava/poll_guest.html');
}

$menu= menu_user($ht, '');  
$isf = ( $r0->row('IS_FAVORITE')+0  === 1 );
$data0[0]['VISIBLE_BTN_FAV_IN'] = iif($isf,'display:none;');
$data0[0]['VISIBLE_BTN_FAV_OUT'] = iif(!$isf,'display:none;');

//-----------------------------------------------------

$src = "/data.poll/$qq/$qq.jpg";
$src_w = "/w/$w/$w.jpg";
$nf = f_root() . $src;
$nf_w = f_root() . $src_w;

if (!file_exists($nf)) {
    $src = '';
    $data0[0]['STYLE_IMG_POLL'] = 'height: 0px;';
    $data0[0]['SHOW_IMG_POLL'] = null;
    $data0[0]['IMG_POLL'] = '';
} else {
    $data0[0]['IMG_POLL'] = f_host($src);
    $data0[0]['SHOW_IMG_POLL'] = 1;
    $data0[0]['STYLE_IMG_POLL'] = 'height: auto;';    
    if (!file_exists($nf_w)){
        if (!file_exists(dirname($nf_w))) mkdir(dirname($nf_w),0777);
        copy($nf, $nf_w);
    }
}



$s =   $r0->row('NAME_POLL') ; 
            $r = veche_get_imagehref( $s ); 
                if (!empty($r['imgsrc'])){
                    if (empty($r['onclick'])) { $imgstyle = null; } else { $imgstyle= 'cursor: pointer'; }
                    $data0[0]['IMGSRC_MSG_WALL'] = trim($r['imgsrc']);
                    $data0[0]['IMG_CLICK']=$r['onclick'];
                    $data0[0]['IMG_STYLE']=$imgstyle;
                    $data0[0]['TITLE']=$r['title'];
                    $data0[0]['KEY_YOUTUBE']=$r['key_youtube'];
                    $data0[0]['T_YOUTUBE']=$r['t'];
                    $data0[0]['IMG']=null;
                    $data0[0]['IFRAME']=null;
                    $data0[0]['SHOW_IMG_POLL'] = null;
                }

                
$ht->part['head'][0]['TITLE'] =  'Голосование '.str_trunc($s).'| Держава, pobedim.su';
$ht->part['head'][0]['TITLE_OG'] = str_trunc($s);
if (!empty($src)) $ht->part['head'][0]['IMG_OG'] = f_host($src) ;



if (va0($data0,'ID_KIND_POLL') == '4') { $data0[0]['IS_SLOVAR']=1; $data0[0]['NAME_SLOVAR'] = urlencode($data0[0]['NAME_POLL']); }
                                else { $data0[0]['IS_SLOVAR']=null; $data0[0]['NAME_SLOVAR']=null; }

$data0[0]['SHARE_POLL'] = urlencode(f_host() . '/derjava/poll.php?qq='.$qq);
$data0[0]['TEXT_POLL'] = md_parse(va0($data0,'TEXT_POLL'));

$data0[0]['BEGIN_POLL'] = iif($r0->row('BEGIN_POLL') !=null , fmtDDMMYYYY( $r0->row('BEGIN_POLL') ) );
$data0[0]['END_POLL'] = iif($r0->row('END_POLL') == null ,' - Бессрочно', ' - ' . fmtDDMMYYYY($r0->row('END_POLL')) );


if ( va0($data0, 'CNT_POLL') == '0' || va0($data0,'TS_ACTIVATED') === null ){
    $data0[0]['CAN_EDIT_POLL'] = 1;
} else {
    $data0[0]['CAN_EDIT_POLL'] = null;
}
//$data0[0]['CAN_EDIT_POLL'] = 1;


if(strpos(va0($data0,'OPTIONS_POLL') , 'AA') !== false){
    $data0[0]['CAN_ADD_ANSWER'] = 1;    
    $ht->data('data0a', $data0);
} else {
    $data0[0]['CAN_ADD_ANSWER'] = null;    
    $ht->data('data0a', null);
}
if(strpos(va0($data0,'OPTIONS_POLL'), 'WW') !== false){
    $data0[0]['CAN_ADD_WEBLINK'] = 1;    
} else {
    $data0[0]['CAN_ADD_WEBLINK'] = null;    
}

if (va0($data0,'ID_STATUTE') == null){
    $data0[0]['TR_STYLE_STATUTE'] = 'display:none';
    $data0[0]['BTN_STYLE_STATUTE'] = '';
    
} else {
    $data0[0]['TR_STYLE_STATUTE'] = '';
    $data0[0]['BTN_STYLE_STATUTE'] = 'display:none';
}


if(strpos(va0($data0,'OPTIONS_POLL'), 'PP') !== false) $can_answer_be_vote = 1; else $can_answer_be_vote = null;

if(strpos(va0($data0,'OPTIONS_POLL'), 'VV') !== false) { $can_answer_be_referendum = 1; $can_answer_be_vote = null;}
else { $can_answer_be_referendum = null;$can_answer_be_vote = 1; }

$data0[0]['CAN_ANSWER_BE_REFERENDUM'] = $can_answer_be_referendum;
$data0[0]['CAN_ANSWER_BE_VOTE'] = $can_answer_be_vote;

if ($ax === 'admin') { $data0[0]['BTN_ADMINU']=1; } else { $data0[0]['BTN_ADMINU']=null; }
 


$r1 = new db(DB_POBEDIM, <<<TXTSQL
      select * from W2_POLL_ANSWER_L8(:u,:qq, null) order by  cnt3 desc, cnt desc, TS_SYS
TXTSQL
        , [':u' => $u, ':qq' => $qq ]
        , function($row,$pp, $lp){

            if ($lp['ax'] === 'admin') { $row['BTN_ADMINU']=1; } else { $row['BTN_ADMINU']=null; }

            $row['ID_CERTIFICATE_U'] = $lp['cu'];
            $row['CAN_ANSWER_BE_REFERENDUM'] = $lp['can_answer_be_referendum'];
            $row['CAN_ANSWER_BE_VOTE'] = $lp['can_answer_be_vote'];
            $row['VISIBLE_HREF_ANSWER'] = null; 
            $row['VISIBLE_HREF_POLL'] = null;
            
            $row['IMGSRC_U_ANSWER'] = tag_user_imgsrc($row['U_ADMIN']);
            
            if ($row['CAN_ANSWER_BE_REFERENDUM'] != null)  $row['VISIBLE_HREF_ANSWER'] = 1; 
            if ($row['CAN_ANSWER_BE_VOTE'] != null)  $row['CAN_ANSWER_BE_VOTE'] = 1; 

            $dir0 = $lp['dir0'];
            
            $qq = $row['ID_POLL'];
            $pa = $row['ID_ANSWER'];
            $src = "/data.poll/$qq/$pa.jpg";
            
            $src0 = "/data.poll/$qq/$pa.0.jpg";
            $nf = $dir0 . $src0;
            if (!file_exists($nf)) {
                $src0 = null;
            } else {
                $wf = $dir0.'/w/'.$row['ID_WALL']; if (!file_exists($wf)) mkdir ($wf,0777);
                $wf .= '/'.$row['ID_WALL'].'.0.jpg';
              if (!file_exists($wf)){
                  copy($nf, $wf);
              }    
            }
            
            $nf = $dir0 . $src;
            if (!file_exists($nf)) {
                $src = '';
            } else {
                $wf = $dir0.'/w/'.$row['ID_WALL']; if (!file_exists($wf)) mkdir ($wf,0777);
                $wf .= '/'.$row['ID_WALL'].'.jpg';
              if (!file_exists($wf)){ copy($nf, $wf); }
            }
            
            $row['IMG_ANSWER'] = $src;
            $row['IMG_ANSWER_0'] = $src0;
            $row['KEY_YOUTUBE']= null;

            if (empty($src)){            
                $r = veche_get_imagehref( $row['NAME_ANSWER'] ); 
                if (!empty($r['imgsrc'])){
                        if (empty($r['onclick'])) { $imgstyle = null; } else { $imgstyle= 'cursor: pointer;'; }
                        $row['IMGSRC_MSG_WALL'] = trim($r['imgsrc']);
                        $row['IMG_CLICK']=$r['onclick'];
                        $row['IMG_STYLE']=$imgstyle;
                        $row['TITLE']=$r['title'];
                        $row['KEY_YOUTUBE']=$r['key_youtube'];
                        $row['T_YOUTUBE']=$r['t'];
                        $row['IMG']=null;
                        $row['IFRAME']=null;
                    $row['IMG_ANSWER'] = null;
                    $row['IMG_ANSWER_0'] = null;
                }
                unset($r);
            }
            $row['NAME_ANSWER_BRIEF'] = substr($row['NAME_ANSWER'],0,200);
            $row['NAME_ANSWER'] = md_parse($row['NAME_ANSWER']);
            
           
            $r = get_like_answer_cnt($pa);
            $row['CNT_LIKE'] = $r->row('CNT_LIKE');
            $row['CNT_DISLIKE'] = $r->row('CNT_DISLIKE');
       

//-----  сертификаты, проголосовавшие за позицию
    $r6 = new db(DB_POBEDIM, <<<SQL
     select ct.ID_CERTIFICATE
            ,pc.U_DELEGATE
            ,pc.U_ADMIN
            ,pc.ID_ANSWER
            ,pc.ID_KIND_ANSWER
            , uct.NAME1_CERTIFICATE
            , uct.NAME2_CERTIFICATE
            , uct.NAME3_CERTIFICATE
            from W2_CERTIFICATE ct 
                inner join U2_CERTIFICATE uct on uct.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                                 uct.U = ct.U_ADMIN
                INNER join W2_POLL_CERTIFICATES pc on pc.ID_POLL = :qq and
                                                      pc.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                                      pc.ID_ANSWER = :pa and
                                                      pc.ID_KIND_ANSWER <> 0
            where ct.U_ADMIN=:u 
SQL
        , [':pa' => $row['ID_ANSWER'], ':u'=>$pp[':u'] , ':qq'=>$pp[':qq']]
        , function($row){
                $row['NO'] = null;
                $row['YES'] = null;
                $row['CHECK'] = null;
                if ($row['ID_KIND_ANSWER'] == 2) $row['CHECK'] = 1;
                if ($row['ID_KIND_ANSWER'] == 1) $row['YES'] = 1;
                if ($row['ID_KIND_ANSWER'] == -1) $row['NO'] = 1;
                return $row;
            }    
            );
     $row['poll_certificates'] = $r6->rows;


//$row['V_ANSWER'] = $r6->length;

//-----  галочка или кнопка 
            if($row['V_ANSWER'] > 0){ 
                $row['IS_ANSWER_CHECKED']=1; 
                $row['VISIBLE_BTN_ANSWER_CHECK']=null; 
            }
            else {
                $row['IS_ANSWER_CHECKED']= null; 
                $row['VISIBLE_BTN_ANSWER_CHECK']=1; 
            }

            
//-----  итоги голосования
            if ($row['VECHE_ANSWER'] == null ){
                
                $row['BTN_VECHE_ANSWER_CREATE'] = 1;
                $row['BTN_VECHE_ANSWER_ENTER'] = null;
                $row['VECHE_ANSWER_RESULT'] = null;
            }
            else {
                $row['BTN_VECHE_ANSWER_ENTER'] = 1;
                $row['BTN_VECHE_ANSWER_CREATE'] = null;
                $r5 = new db(DB_POBEDIM, 'select * from w2_poll_answer where id_answer=:pa', [':pa' => $row['ID_ANSWER_RESULT']]);
                $row['VECHE_ANSWER_RESULT'] = iif($row['ID_ANSWER_RESULT'] == null,'-- решение не принято --', $r5->row('NAME_ANSWER') );
            }
        
             
if ($row['CNT'] > 0){
    $row['CNT_GT_ZERO'] = 1;
    $row['CNT_ZERO'] = null;
    if ($row['TOTAL'] > 0) {
    $row['PNT3'] = 100.0*$row['CNT3']/$row['TOTAL'];
    $row['PNT9'] = 100.0*$row['CNT']/$row['TOTAL'];
    } else {
    $row['PNT3'] = '';
    $row['PNT9'] = '';
    }

} else
{
    $row['PNT3'] = 0;
    $row['PNT9'] = 0;
    $row['CNT_GT_ZERO'] = null;
    $row['CNT_ZERO'] = 1;
    $row['BTN_VECHE_ANSWER_CREATE'] = null;
    $row['VECHE_ANSWER_RESULT'] = null;
    $row['BTN_VECHE_ANSWER_ENTER'] = null;
    $row['VECHE_ANSWER_RESULT'] = null;
}

            return $row;
         }
         , [ 'can_answer_be_referendum'=> $can_answer_be_referendum 
             ,'can_answer_be_vote'=> $can_answer_be_vote
             , 'cu'=> va0($data0,'ID_CERTIFICATE_U') 
             , 'ax'=>$ax 
             , 'dir0' => f_root() 
             , 'data0' => &$data0
             ]
      );
$ht->data('data1', $r1->rows );



// ссылки на интернет рессурсы
$r2 = new db(DB_POBEDIM, 'select * from w2_poll_web where id_poll =:qq ' , [':qq' => $qq ]);
$ht->data('data2', $r2->rows );


$r3 = get_like_cnt($qq);
$data3 = $r3->rows;
$data3[0]['ID_POLL'] = $qq;
$ht->data('data3', $data3 );

$data0[0]['IS_NO_ANSWER_U'] = null;  
if ( va0( $data0,'ID_ANSWER_U') == '0' ){
   $data0[0]['ID_ANSWER_U'] = null;  
   $data0[0]['IS_NO_ANSWER_U'] = 1;
}


$data4 = null;        
if ( va0( $data0,'ID_ANSWER_U') != null 
     || va0( $data0,'ID_KIND_VECHE') == 8 
        ){
    // итоги голосования
    if ($can_answer_be_referendum === null) $data4 = $r1->rows;
    }

if ($can_answer_be_referendum === null) {
    $data0[0]['HAS_TOTALS'] = 1;
} else 
    $data0[0]['HAS_TOTALS'] = null;




$r8 = getWall5($data0, $u, '0', '0', $qq,'/derjava/poll.php?qq='.$qq , null, null,$m, null
        ,'/derjava/poll.php?qq='.$qq, val_rq('sxw') );


$ht->data('data8', $r8->rows);
$ht->data('data0', $data0 );
$ht->data('data4', $data4 );

$data99 = null;

if ( va0($data0,'ID_KIND_VECHE') == 8) {
    $data99[0]['ID_POLL'] = $qq;
    include $dir0 . '/derjava/poll_include99.php';
    $pr = $data0[0]['ID_POLL_RESULT'];
    $pa = $data0[0]['ID_ANSWER_RESULT'];
    $can_set_result = 1;
    makeview99($data99, $pr, $pa, $can_set_result);
} 

$ht->data('data99', $data99 );

unset($r1);
unset($r2);
unset($r3);
unset($r6);
unset($r8);

echo $ht->build('',true);

