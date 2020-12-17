<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
require_once $dir0 . '/derjava/user_fn.php';

require_once $dir0 . '/mailer.php';
require_once $dir0 . '/derjava/wall_fn.php';


if (count($_GET) >= 1) {
    $get = array_keys($_GET);
    $uc = $get[0];
} else {
  exit;    
}

$u = current_iu();
//user_set_onlinetime($u);

$bx = val_rq('bx');
$ax = val_rq('ax', 'converse');

if ($u !== '1000' ) {
    $r = new db(DB_USER, 'update or insert into U0_USER (u,u_user,ts_view) values (:u,:uc,current_timestamp)', [':uc' => $uc, ':u' => $u]);
}


if ($ax == "send_msg") {
    $r = new db(DB_USER, 'update u2_converse set t_text_converse = t_text_converse+2'
            . ' where id_converse=:c and u=:u  and t_text_converse in (1,2)'
            , [':c' => val_rq('c'), ':u' => $u]);
    exit;
}

if ($bx == "save_re") {
    //$u_to = val_rq('u_to');
    $u_from = current_iu();
    $r = new db(DB_USER, 'select * from  U2_CONVERSE_RE ( :ic, :u_from , :t ) '
            , [':ic' => val_rq('ic'), ':u_from' => $u_from, ':t' => val_rq('tc')]);

    exit;
}

if ($bx == "send_msg") {

    $u_to = val_rq('u_to');
    $u_from = current_iu();
    $r = new db(DB_USER, 'select * from  U2_CONVERSE_I1 ( :u_from , :u_to , :t ) '
            , [':u_from' => $u_from, ':u_to' => $u_to, ':t' => val_rq('tc')]);

    echo htm_redirect1('/derjava/user_contact.php?' . $u_to);
}



$ht = new html('/derjava/user_contact.html');
$menu= menu_user($ht, '');



$r0 = new db(DB_POBEDIM,'select * from w2_user_s3(:u,:uc) ',[':u'=>$u, ':uc'=>$uc]
    , function($row,$pa,$lp){
        $row['U'] = $pa[':uc'];
        
        $iu = $row['U'];
        $fu = get_f_user($iu, 'iu');

        $r = new db(DB_POBEDIM,'select count(1) as CNT from w2_user_dictionary_l(:iu)',[':iu'=>$iu]);
        $row['CNT_LIBRARY'] = $r->row('CNT');
                
        user_geo_get($row,$fu);
        
        $nu = va($fu,'nu');
        $row['INFO_U'] = va($fu,'info_u');
        $row['CONTACTS_U'] = va($fu,'contacts_u');
        $row['WEB_U'] = va($fu,'web_u');
        $row['LSPECIALITY']=va($fu,'lspeciality');
        $row['CITY_U']=va($fu,'city_u');
        $row['BIRTHDAY_U']=va($fu,'birthday_u');
        if(!empty(va($fu,'user_vkontakte'))||!empty( va($fu,'user_facebook'))||!empty( va($fu,'user_odnoklassniki') )){
        $row['VKONTAKTE_U']=va($fu,'user_vkontakte');
        $row['FACEBOOK_U']=va($fu,'user_facebook');
        $row['ODNOKLASSNIKI_U']=va($fu,'user_odnoklassniki');
        } else {
            $row['VKONTAKTE_U']=null;
            $row['FACEBOOK_U']=null;
            $row['ODNOKLASSNIKI_U']=null;
        }
        $row['CNT_POLL'] = get_CNT_POLL_U($iu);
        
        $r = new db(DB_POBEDIM, <<<TXTSQL
                
        select * from W2_USER_KIND_L(:u)
            where coalesce(SORTING_KIND_USER,0) >= 0 and
                  NAME_KIND_USER <> '' 
TXTSQL
                ,[':u'=>$pa[':u']]
                ,function($row,$pp,$lp){
                    $row['SELECTED'] = iif ($row['ID_KIND_USER']==$lp['ku'] , ' selected ', null);
                    return $row;
                }
                ,['ku'=>$row['ID_KIND_USER']]);
        $row['rows20']=$r->rows;

        
        $r = new db(DB_POBEDIM,<<<SQL

select u2.u
    from w2_veche_certificates vc1
        inner join w2_user u1 on u1.u=:u and u1.id_certificate_u = vc1.id_certificate
        inner join w2_veche_certificates vc2 on vc2.VECHE = VC1.VECHE and
                                                vc2.ID_ROLE_VECHE is not null and
                                                vc2.ID_ROLE_VECHE <> 10
        inner join w2_user u2 on u2.ID_CERTIFICATE_U = vc2.ID_CERTIFICATE and
                                 u2.u = :u2
    where vc1.ID_ROLE_VECHE in (1,2)
                
SQL
                ,[':u'=>$lp['u'] ,':u2'=>$lp['uc'] ]);
        if ($r->length > 0) 
            $row['EMAIL_U'] = va($fu,'email_notify_confirmed');
        else 
            $row['EMAIL_U'] =  null;
        
        
        $r = new db(DB_POBEDIM, 'select * from W2_CERTIFICATE_VFY_CNT (:u, :ct)',[':u'=>$lp['u'] ,':ct'=>$row['ID_CERTIFICATE_U'] ] );
        $row['IS_C_VERIFIED_U'] = $r->row('IS_C_VERIFIED_U');
        $row['CNT_U_VERIFICATION'] = $r->row('CNT_U_VERIFICATION');
        
        if ( $row['IS_C_VERIFIED_U'] == $lp['u'] || $lp['u'] == '1000' ) 
        { $row['VISIBLE_BTN_VFY_U'] = null; }
        else { $row['VISIBLE_BTN_VFY_U'] = '1'; }
        
        
        return $row;
    },['u'=> $u , 'uc'=>$uc]
        );
        
$data0 = $r0->rows;




$rx='/derjava/user_contact.php?'.$u;
$w= va0($data0, 'ID_WALL');
$msg_last=0; // пост по которому измерять время
$m=val_rq('m');   // пост который нужно отобразить как единственный
$mx=0;  // пост, который отображать не надо
$sx= val_rq('sxw');
     
$r8 = getWall5($data0,$u, $uc, '0', '0' , $rx
            ,$w ,$msg_last ,$m ,$mx ,$rx ,$sx );
$ht->data('data8', $r8->rows);


// входящие вопросы 
    $r1 = new db(DB_USER, <<<TXT
            select cs1.TS, cs0.ID_CONVERSE
                , cs0.TEXT_CONVERSE as TEXT_CONVERSE0
                , cs0.T_TEXT_CONVERSE as T_TEXT_CONVERSE0
                , cs0.U as U0
                , cs1.TEXT_CONVERSE as TEXT_CONVERSE1
                , cs1.T_TEXT_CONVERSE as T_TEXT_CONVERSE1
                , cs1.U as U1
                from u2_converse cs0
                    inner join u2_converse cs1 on cs1.id_converse = cs0.id_converse and 
                                                cs1.u = :u and
                                                cs1.t_text_converse in (1,3)
                where cs0.u=:u_pf and  cs0.u <> cs1.u
                order by cs0.TS desc
TXT
            , [':u_pf' => $uc , ':u' => $u]
            
          ,  function(&$row) {
        $u = current_iu();

        // мне написали , но я не ответил
         if ( $row['T_TEXT_CONVERSE1'] == 1 && $row['T_TEXT_CONVERSE0'] == 4 ) 
         {
            $canEdit = true; 
            $row['CANEDIT1'] = 1;
          } else 
          {
            $canEdit = false; 
            $row['CANEDIT1'] = null;
          }

        if($row['T_TEXT_CONVERSE0'] == 2 ) {
            $row['TEXT_CONVERSE0'] = '[вопрос ещё не отправлен]';  
            $row = null;
        }
    
        return $row;
    }
            );


            
// исходящие вопросы     
    $r2 = new db(DB_USER, <<<TXT
            select cs1.TS, cs0.ID_CONVERSE
                , cs0.TEXT_CONVERSE as TEXT_CONVERSE0
                , cs0.T_TEXT_CONVERSE as T_TEXT_CONVERSE0
                , cs0.U as U0
                , cs1.TEXT_CONVERSE as TEXT_CONVERSE1
                , cs1.T_TEXT_CONVERSE as T_TEXT_CONVERSE1
                , cs1.U as U1
                from u2_converse cs0
                    inner join u2_converse cs1 on cs1.id_converse = cs0.id_converse and
                                                cs1.u = :u and
                                                cs1.t_text_converse in (2,4)
                where cs0.u=:u_pf and  cs0.u <> cs1.u
                order by cs0.TS desc
TXT
            , [':u_pf' => $uc , ':u' => $u]
            
          ,  function(&$row) {
        $u = current_iu();

        if( $row['T_TEXT_CONVERSE1'] == 2 )
        {
            $row['CANEDIT2'] = 1;
        } else {
            $row['CANEDIT2'] = null;
        }
        
        //if($row['T_TEXT_CONVERSE0'] == 1  &&  empty($row['TEXT_CONVERSE0'])               )  return null;
        return $row;
    }
            );
  
$data0[0]['CNT1']= $r1->length;    
$data0[0]['CNT2']= $r2->length;

$ht->data('data0', $data0);
$ht->data('data1', $r1);
$ht->data('data2', $r2);

$r3 = new db(DB_USER,'select U from w0_user where u_PARENT=:u',[':u'=>$uc]);
$ht->data('data3', $r3);

echo $ht->build('',true);
