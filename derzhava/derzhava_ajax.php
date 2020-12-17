<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/derzhava/derzhava_fn.php';


$ax = val_rq('ax');
$u = current_iu();
$iv = val_rq('iv');
$qq = val_rq('qq');


switch ($ax) {
    case 'do_ignore_polls':
        $ux = val_rq('ux');
        $v = val_rq('v');
        $r = new db(DB_POBEDIM,'update or insert into u2_user (u, u_user, do_ignore_polls) values (:u, :ux, :v)'
                , [':u'=>$u, ':ux'=>$ux, ':v'=>$v]) ;
        exit;
        break;
    
    
    case 'get_likelist':
        $qq = val_rq('qq');
        $v = val_rq('v');
        $r = new db(DB_POBEDIM, 'select * from u2_poll where id_poll=:qq and is_like is not distinct from  :v'
                                 ,[':qq'=>$qq,':v'=>$v]);;
        
        $r->filter = function($row){
            $html = null;
            return tag_li( tag_user($html, $row['U']) );
        };                        
        unset($a);
        $a['HTML'] = tag_ul( $r->printf() );
       
        echo json_encode($a);
        exit;
        break;


    case 'get_like':
        $qq = val_rq('qq');
        $r = get_like_cnt($qq);
        unset($a);
        $a['CNT_LIKE'] = $r->row('CNT_LIKE');
        $a['CNT_DISLIKE'] = $r->row('CNT_DISLIKE');
        echo json_encode($a);
        exit;
        break;
        
    case 'set_like':
         
        $v = val_rq('v');
        $r = new db(DB_POBEDIM,'update or insert into u2_poll (u, id_poll, IS_LIKE) values (:u, :qq, :v)'
                , [':u'=>$u, ':qq'=>$qq, ':v'=>$v]) ;

        $r = get_like_cnt($qq);
        unset($a);
        $a['CNT_LIKE'] = $r->row('CNT_LIKE');
        $a['CNT_DISLIKE'] = $r->row('CNT_DISLIKE');
        echo json_encode($a);
        exit;
        break;
    

    case 'vote4admin':
        $ck = val_rq('checked');
        $v = val_rq('v');
        $iv = val_rq('iv');
        if ($ck && $iv !== '') {
            $r = new db(DB_POBEDIM,
                    'select U_VOTE4ADMIN from U2_VECHE_VOTE4ADMIN_UPD(:u, :iv, :user4admin )'
                    , [':u' => $u, ':iv' => $iv, ':user4admin' => $v]);
        }
        break;


    case 'update_web_poll':
        $bx = val_rq('bx', 'edit');
        $qq = val_rq('qq');
        $pwu = val_rq('pwu');
        $pwu__ = val_rq('pwu__');
        $pwt = val_rq('pwt');

        $r = new db(DB_POBEDIM, 'select * from W2_POLL_WEB_IU(:pwu,:pwt,:pwun , :qq ,:u)'
                , [':pwu' => $pwu, ':pwt' => $pwt, ':pwun' => $pwu__, ':qq' => $qq, ':u' => $u]);

        echo htm_redirect1("/derzhava/derzhava_golosovanie.php?ax=$bx&qq=$qq&ex=derzhava");
        break;


    case 'territory':
        $ivp = val_rq('tp');
        $r1 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=$ivp");
        $r1->filter = function(&$row) {
            return tag_option($row['NAME_VECHE'], $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
        };

        if ($r1->length === 0) {
            echo '';
        } else if ($r1->length === 1) {
            echo $r1->printf();
        } else {
            echo '<option>...</option>' . $r1->printf();
        }
        //exit;
        break;

        
    case 'create_answer':
        include_once $dir0 . '/derzhava/derzhava_golosovanie_edit_fn.php';
        $qq = val_rq('qq');
        
        $r = db_row('select ID_POLL_ANSWER from W2_POLL_ANSWER_I (:iqq, :na,:u) '
                    , [':iqq' => $qq, ':na' => $npa, ':u' => $u]
                    , DB_POBEDIM);
            
        $pa = $r['ID_POLL_ANSWER'];
        $r = ['pa'=>$pa, 'tr'=>script_edit_golosovanie_answer($qq,$pa,'',1)];
        echo  json_encode($r);
        break;
    


}

