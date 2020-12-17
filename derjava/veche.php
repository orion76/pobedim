<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';
require_once $dir0 . '/derjava/wall_fn.php';


$ax = val_rq('ax');
$iv = val_rq('iv', 0);
$m = val_rq('m', null);

if ($iv == 0) {
    $get = array_keys($_GET);
    $iv = $get[0];
}

$cnt_cu_show = val_rq('c', 5);

if (empty($iv)) {
    $iv = 0;
}
$iu = current_iu();
$u = $iu;
$bx = val_rq('bx');


if (isUserLoggedOn()){
    if ($iv == '4')
        $ht = new html('/derjava/veche_4page.html');
    else 
        $ht = new html('/derjava/veche_page.html');    
    
} else {
    $ht = new html('/derjava/veche_page_guest.html');    
}

$menu= menu_user($ht, iif($iv == '900322', 'ACTIVE4' ));
$ht->data('menu1', $menu );


$r0 = new db(DB_POBEDIM, <<<TXT
        select v.*
               ,uv.U_VOTE4ADMIN 
               ,uv.IS_FAVORITE
               ,vk.NAME_KIND_VECHE
               ,p70.ID_POLL as ID_POLL_70
            from w2_veche v
                left outer join u2_veche uv on uv.veche=v.veche and uv.u=:u
                left outer join w2_veche_kind vk on vk.id_kind_veche = v.id_kind_veche
                left outer join w2_poll p70 on p70.veche=v.veche and p70.id_kind_poll=70
           where v.veche=:iv and
                 v.sorting_veche is distinct from -1
TXT
        , [':iv' => $iv, ':u' => $u]
, 
    function($row,$pp){
        $r = new db(DB_POBEDIM,'select count(1) as cnt from W2_VECHE_CERTIFICATES where VECHE = :iv and ID_ROLE_VECHE is distinct from 10',$pp);
        $row['CNT_CV'] = $r->row('CNT'); // количество участников
        $row['INFO_CNT_CV'] = 'Всего '.$row['CNT_CV']. 'участников. ';
        
        $row['VISIBLE_TOOLS0'] = iif( $row['ID_KIND_VECHE'] != 6 &&
                                      $row['ID_KIND_VECHE'] != 7 &&
                                      $row['ID_KIND_VECHE'] != 8
                                    ,1, null );
        
        $row['VISIBLE_TOOLS6'] = iif( $row['ID_KIND_VECHE'] == 6 ,1, null );
        $row['VISIBLE_TOOLS7'] = iif( $row['ID_KIND_VECHE'] == 7 ,1, null );
        $row['VISIBLE_TOOLS8'] = iif( $row['ID_KIND_VECHE'] == 8 ,1, null );
        $row['NAME_VECHE'] = str_replace('"', ' ', $row['NAME_VECHE']);
        
        
        $row_tree0 = null;
        $r1 = new db(DB_POBEDIM,'select * from W2_VECHE_TREE_UP_L(:iv)',$pp
            ,function($row,$pp,$lp){
            if ($row['VECHE'] == $pp[':iv']) {$lp['rt0'][0] = $row; return null;}
            else  return $row;
            }
            ,['rt0'=>&$row_tree0]);
        $row['rows_tree_up'] = $r1->rows;
        $row['row_tree0'] = $row_tree0;
        $row['rows_tree_children'] = new db(DB_POBEDIM,'select VECHE,NAME_VECHE from W2_VECHE where VECHE_PARENT=:iv and SORTING_VECHE is distinct from -1 order by SORTING_VECHE',$pp);
        $row['rows70'] = new db(DB_POBEDIM, 'select j.*, 0 as WAGE_JOB_VECHE from V2_VECHE_JOB j where j.VECHE = :iv' , [':iv' => $pp[':iv']], function($row){return $row;} );
        $row['rows70s'] = null;
        if ($row['rows70']->length > 0)  $row['rows70s'] = 1;
      return $row;
    }
        ,['cnt_cu_show'=>$cnt_cu_show]
);
        
if ($r0->length == 0) {
    echo htm_redirect1('/derjava/veche_not_found.php');
    exit;
}



        
$data0 = $r0->rows;
$data0[0]['TEXT_VECHE']= md_parse($data0[0]['TEXT_VECHE']);
$data0[0]['SX'] = str_replace('*', '%', val_rq('sx'));

if (!empty($data0[0]['SX'])) {
    $cnt_cu_show = $data0[0]['CNT_CV']+1;
}
        
if ( $data0[0]['CNT_CV'] > 5 ) {
    $data0[0]['VISIBLE_CNT_CV'] = 1;
} else {
    $data0[0]['VISIBLE_CNT_CV'] = null;
}


//----- синхронизация с кооперативом
$r10 = new db(DB_COOP, 'select * from w2_veche v where v.veche=:v',[':v'=>$iv]);
if ($r10->length == 0) {
    $r10 = new db(DB_COOP, 'insert into w2_veche (veche,VECHE_PARENT,name_veche,id_kind_veche) values (:v,:vp,:nv,:kv)'
                            ,[':v'=>$iv
                             , ':vp'=>$r0->row('VECHE_PARENT')
                             , ':nv'=>$r0->row('NAME_VECHE')
                             , ':kv'=>$r0->row('ID_KIND_VECHE') ] );
}
//-------------------


// --- является ли эта группа комитетом?  заполняем data99
$can_set_result = 0;  
include $dir0 . '/derjava/veche_include99.php';


 
// ------------------------------  ЯРМАРКА КООПЕРАТИВА ---------------------------------------------
if ($r0->row('ID_KIND_VECHE') === "4"){
    $data0[0]['VISIBLE_BTN_COOP'] = true;
//  html_tt($html, DIV_CONTENT, BR2. tag_a( '/derzhava/bazar.php?iv='.$iv , 'Кооперативная ярмарка ') );
} else {
    $data0[0]['VISIBLE_BTN_COOP'] = null;
}



$imgsrc = "/data.veche/$iv/$iv.jpg";
$szf = filesize_($imgsrc);
$data0[0]['IMG_VECHE'] = $imgsrc ."?$szf";
if ($szf === false) {
    $data0[0]['STYLE_IMG_VECHE'] = 'height:0px;';
} else {
    $data0[0]['STYLE_IMG_VECHE'] = ''; 
}

$data0[0]['TEXT_VECHE'] = md_parse( $r0->row('TEXT_VECHE'));

$data0[0]['HREF_BTN_POLL_NEW'] = '/derjava/poll_create.php?iv='.$iv;
        
//-------------------  пригласить в объединение через соцсети 
$s = str_trunc( $r0->row('NAME_VECHE') , 200 ).' | '. $r0->row('NAME_KIND_VECHE') ; 
$ht->part['head'][0]['TITLE'] =  $s.'| Держава, Народовластие';
$ht->part['head'][0]['TITLE_OG'] = 'Присоединяйтесь! ' . $s;
$ht->part['head'][0]['IMG_OG'] = iif($szf !== false, f_host("/data.veche/$iv/$iv.jpg") );


//-----------------------------------------------------
$data0[0]['SHARE_VECHE'] = urlencode(f_host() . '/derjava/veche.php?iv='.$iv);
$isf = ( $data0[0]['IS_FAVORITE']+0  ===1 );
$data0[0]['VISIBLE_BTN_FAV_IN'] = iif($isf,'display:none;');
$data0[0]['VISIBLE_BTN_FAV_OUT'] = iif(!$isf,'display:none;');



// состоит ли пользователь в объединении
        $r7 = new db(DB_POBEDIM, <<<TXT
        select u.ID_CERTIFICATE_U, vc1.VECHE ,vc1.ID_ROLE_VECHE  from w2_user u 
            left outer join W2_VECHE_CERTIFICATES vc1 on vc1.ID_CERTIFICATE = u.ID_CERTIFICATE_U and
                                                         vc1.VECHE = :iv and
                                                         vc1.ID_ROLE_VECHE is distinct from 10
            where u.u = :u
TXT
                , [':iv' => $iv, ':u' => $u]
            );
        $icu = $r7->row('ID_CERTIFICATE_U');
        $u_role_veche = $r7->row('ID_ROLE_VECHE');
        
        if($r7->row('VECHE')  == null){
            $ht->data('member_in',['VECHE'=>$iv,'IC'=>$icu]);
            $ht->data('member_out',null);
        } else {
            $ht->data('member_in',null);
            $ht->data('member_out',['VECHE'=>$iv,'IC'=>$icu]);
        }

//------  получаем перечень администрируемых сертификатов        
    $r7 = new db(DB_POBEDIM, <<<TXT
    select u.ID_CERTIFICATE_U
          , ct.ID_CERTIFICATE
          , coalesce(cu.NAME3_CERTIFICATE,'') 
              ||' '|| coalesce(cu.NAME1_CERTIFICATE,'')
              ||' '|| coalesce(cu.NAME2_CERTIFICATE,'') AS NAME_CERTIFICATE
          , vc1.veche  
          , vc1.U_DELEGATE
        from w2_user u
            left outer join w2_certificate ct on ct.u_admin = u.u
            left outer join u2_certificate cu on cu.u = u.u and cu.ID_CERTIFICATE=ct.ID_CERTIFICATE
            left join W2_VECHE_CERTIFICATES vc1 on vc1.ID_CERTIFICATE = ct.ID_CERTIFICATE and
                                                         vc1.VECHE = :iv and
                                                         vc1.ID_ROLE_VECHE is distinct from 10
        where u.u = :u  and
              u.ID_CERTIFICATE_U <> ct.ID_CERTIFICATE
        order by NULLIF(u.ID_CERTIFICATE_U, ct.ID_CERTIFICATE) nulls first , ct.ID_CERTIFICATE
TXT
            , [':iv' => $iv, ':u' => $u]
            , function($row, $pa, $lp) {
                if($row['VECHE'] === null){
                    $row['IN'] = 1;    
                    $row['OUT'] = '';    
                } else {
                    $row['IN'] ='';    
                    $row['OUT'] = 1;    
                }
                $row['VECHE'] = $lp['iv'];
                $row['IC'] = $row['ID_CERTIFICATE'];
                $row['IMGSRC_U_DELEGATE'] = tag_user_imgsrc($row['U_DELEGATE']);
                return $row;
        },['iv'=>$iv]  );

if ($r7->length == 0){
    $ht->data('members_inout',null);
    $ht->data('member_inout',null);
} else {
    $ht->data('members_inout',['CNT'=>$r7->length]);
    $ht->data('member_inout',$r7->rows);
}

//---- проверяем на право редактировать 
$r = new db(DB_POBEDIM,<<<TXT
        select u.u from w2_veche_certificates vc 
            inner join w2_user u on u.id_certificate_u=vc.id_certificate 
                where vc.id_role_veche=1  and   vc.veche = :v
TXT
        , [':v'=>$iv]);
if ($u == $r->row('U')){
    $data0[0]['VISIBLE_CAN_EDIT_VECHE']='1';
} else {
    $data0[0]['VISIBLE_CAN_EDIT_VECHE']=null;
}
//---------------------------------------------------------
 
if ($u_role_veche > 0 && $u_role_veche <=3 )
    $data5 = new db(DB_USER, 'select * from w0_veche v where v.veche=:v',[':v'=>$iv]);
else 
    $data5 = null;
$ht->data('data5', $data5);
//-----------------------------------------------------------

//------- вспомогательные таблицы -----
$r1 = new db(DB_POBEDIM, 'select  *  from w2_veche_coordinator_check3(:iv,:u,1)'
              , [ ':iv'=>$iv , ':u'=>$u ]  );

$d = round($r1->row('DAYS_SUSPEND'),0);
$b = ($d > ($r1->row('DAYSINACTIVITY_ALLOWED')+0));    
$no_admin = $r1->row('IS_NO_COORDINATOR') == 1;
$chg_admin = $r1->row('CAN_CHG_COORDINATOR');

$r3 = new db(DB_POBEDIM, 'select * from W2_VECHE_ROLE_L ( :kv )', [':kv'=>$r0->row('ID_KIND_VECHE')]);

      
$rv = $r1->row('ID_ROLE_VECHE') + 0;
$uv4a = $r1->row('U_VOTE4ADMIN'); // лицо за которое пользователь проголосовал

    $s = '';
    if ($b && !$no_admin) {
       $s = "Уже более $d дней  координатор пренебрегает своими обязанностями, проголосуйте за другого координатора.<br>"
           ."Все могут принимать участие в выборе координатора.<br>";
       $chg_admin = true;
    }
    if ($no_admin) {
       $s = "В группе нет координатора!<br>Все могут принимать участие в выборе координатора<br>";
    }
    
if ($uv4a == null){
$s .= <<<TXT
              Если вы готовы быть координатором или сокоординатором объединения, то проголосуйте за себя.
            Координатором автоматически становится тот, кто проголосует за себя
                и наберёт большинство голосов участников объединения. 
                    <br> 
TXT
;}

if ($r1->row('CNT_POLL') > 0) $data0[0]['CNT_POLL'] = $r1->row('CNT_POLL'); else $data0[0]['CNT_POLL'] = null;
$data0[0]['CNT_TALK'] = $r1->row('CNT_TALK');


$ht->data('info',[ 'TEXT_VOTE4ADMIN'=>$s]);

$r1 = new db(DB_POBEDIM,
            'select * from W2_VECHE_USER_LIST6( :iv, :u, :xsx_u, :cnt_rows)'
            , [':iv' => $iv, ':u' => $u, ':xsx_u' => $data0[0]['SX'], ':cnt_rows'=>$cnt_cu_show ] 
            , function ($row, $pa, $lp) {
                $row['VECHE'] = $lp['iv'];
                $row['IMGSRC_USER'] = tag_user_imgsrc($row['U']);
                $row['IMGSRC_DELEDATE'] = tag_user_imgsrc($row['U_DELEGATE']);
                
                if( $row['ID_CERTIFICATE_U_DELEGATE'] != $row['ID_CERTIFICATE'] ) { $row['U_DELEGATE_EX'] = $row['U_DELEGATE']; } else { $row['U_DELEGATE_EX'] = null; }
                
                $rv = $lp['rv'];
                if ($lp['chg_admin'] || $row['CAN_CHG_COORDINATOR'] ) {$voting = true; } 
                else {
                    if ($rv < 1 || $rv > 6) {
                        $voting = false;
                    } else {
                        $voting = true;
                    }
                }
                if ($row['ID_CERTIFICATE_U'] != $row['ID_CERTIFICATE']) {
                    $voting = false;
                    $row['CNT_VOTE4ADMIN'] = null;
                }
                
                if ($rv === 1 || $rv === 2) {
                $row['NAME_ROLE_VECHE'] =
                   $lp['vr']->tag_select('NAME_ROLE_VECHE', 'ID_ROLE_VECHE', $row['ID_ROLE_VECHE']
                    , a_('onchange', 'veche_role_u_change(event)')
                    . a_('href', '/derjava/ajax.php?ax=veche_set_ru&ic=' . $row['ID_CERTIFICATE'] . '&iv=' . $lp['iv'] . '&ru=' . $row['U'] . '&vr=')
                    , '', '<option value="0">?</option>');
                }
                
                if (empty($row['NAME_ROLE_VECHE'])) 
                { 
                   $row['NAME_ROLE_VECHE'] = 'ожидает верификации координатором'; 
                } else {
                   if ($rv === 1 )
                       $row['NAME_ROLE_VECHE'] .= tag_select('wa', $row['ID_ROLE_WALL']
                            , tag_option('', 0)
                              .tag_option('редактор', '102')
                            ,  a_('onchange', 'veche_wall_user_change(event)')
                                . a_('action', '/derjava/ajax.php?ax=veche_set_wallu&ic=' . $row['ID_CERTIFICATE'] . '&iv=' . $lp['iv'] . '&u=' . $row['U']. '&wr=' )
                            );
                   else { 
                    if ($row['ID_ROLE_WALL'] == '102') {
                        $row['NAME_ROLE_VECHE'] .= '<br> редактор ';
                    }
                   }
                }
                
                
                
                if ( $row['IS_C_VERIFIED_U'] == $lp['u']) { $row['VISIBLE_BTN_VFY_U'] = null; }
                else { $row['VISIBLE_BTN_VFY_U'] = '1'; }

                
                $row['IS_U_VOTE4ADMIN'] = 
                     iif($row['U_VOTE4ADMIN']===$row['U'] && $voting
                        , iif($row['ID_ROLE_VECHE'] != '1', '<br> *** готов быть координатором ') // пользователь уже кооржинатор
                     ); // пользователь готов быть координатором
                
                
                if ($lp['v4au'] === $row['U'] && $voting){
                    $row['VISIBLE_VOTED_U'] = true;
                } else {
                    $row['VISIBLE_VOTED_U'] = null;
                }
                
                if( $voting &&  $row['VISIBLE_VOTED_U']===null  )
                {
                    $row['VISIBLE_BTN_VOTE_U'] = '1';
                } else {
                    $row['VISIBLE_BTN_VOTE_U'] = null;
                }
                
                return $row;
            }
            , [ 'vr' => &$r3 , 'rv'=>$rv , 'iv'=> $iv , 'chg_admin'=>$chg_admin
               , 'u'=>$u , 'v4au'=> $uv4a   ] 
        );


$r2 = new db(DB_POBEDIM, 'select * from W2_VECHE_WEB2 where VECHE = :iv'
        , [':iv' => $iv]
        , function ($row){
            if (empty($row['URL_WEB_VECHE']) || $row['URL_WEB_VECHE']==null) return null;
            if (empty($row['TEXT_WEB_VECHE']) || $row['TEXT_WEB_VECHE']==null) $row['TEXT_WEB_VECHE'] = $row['URL_WEB_VECHE'];
            return $row;
            }
        );
if ($r2->length === 0){
    $ht->data('urls_veche',null);
    $ht->data('url_veche',null);
} else {
    $ht->data('urls_veche', ['CNT'=>$r2->length]);
    $ht->data('url_veche', $r2->rows);
}


//-------------- сообщения группы
$r8 = getWall5($data0,$u,'0', $iv, '0','/derjava/veche.php?iv='.$iv , null, null,$m, null
        ,'/derjava/veche.php?iv='.$iv, val_rq('sxw'));

$ht->data('data8', $r8->rows);

$data_referendum = null;
 if ($data0[0]['ID_KIND_VECHE'] == '1'){
     $r6 = new db(DB_POBEDIM,'select v.VECHE_PARENT as VECHE, v.VECHE as VECHE_REFERENDUM from W2_VECHE v where v.VECHE_PARENT = :iv and v.ID_KIND_VECHE=6',[':iv'=>$iv]);
     $data_referendum = $r6->rows;
     if (count($data_referendum) === 0) { 
         $data_referendum[0]['VECHE_REFERENDUM'] = null; 
         $data_referendum[0]['VECHE'] = $iv;
     }
 }

$ht->data('data_referendum',$data_referendum);




$data_presidium = null;
 if ($data0[0]['ID_KIND_VECHE'] == '1'){
     $r7 = new db(DB_POBEDIM,'select v.VECHE_PARENT as VECHE, v.VECHE as VECHE_PRESIDIUM from W2_VECHE v where v.VECHE_PARENT = :iv and v.ID_KIND_VECHE=7',[':iv'=>$iv]);
     $data_presidium = $r7->rows;
     if (count($data_presidium) === 0) { 
         $data_presidium[0]['VECHE_PRESIDIUM'] = null; 
         $data_presidium[0]['VECHE'] = $iv;
     }
 }

$ht->data('data_presidium',$data_presidium);


$ht->data('data1_lu', $r1->rows);


$data0[0]['IS_USER_ON']= $menu['IS_USER_ON'];
$data0[0]['IS_USER_OFF']= $menu['IS_USER_OFF'];
$data0[0]['IMGSRC_USER_ON']= $menu['IMGSRC_USER_ON'];

$ht->data('data0', $data0 );

echo $ht->build('',true);
