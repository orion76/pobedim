<?php

header('X-Robots-Tag:noindex');

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html_parse.php';
require_once $dir0 . '/derzhava/derzhava_fn.php';
require_once $dir0 . '/file_uploader.php';

$ax = val_rq('ax');
$bx = val_rq('bx');
$u = current_iu();
$iv = val_rq('iv');
$qq = val_rq('qq'); //obsolete
$p = val_rq('p');
$pa = val_rq('pa');


function sign_answer($row){
    // 20191118 добавил поле U_DELEGATE
    if (is_array($row) && !empty(va($row,'ID_POLL'))){
        $s = md5( va($row,'ID_POLL')
                .va($row,'ID_CERTIFICATE')
                .va($row,'ID_ANSWER')
                .va($row,'ID_KIND_ANSWER')
                .va($row,'U_DELEGATE')
                .va($row,'TS_SYS')
                );
        $r = new db(DB_POBEDIM,'update W2_POLL_CERTIFICATES set MD5_SIGNATURE=:s where ID_POLL = :qq and ID_CERTIFICATE=:c and ID_ANSWER=:pa '
                ,[':qq'=>$row['ID_POLL'],':c'=>$row['ID_CERTIFICATE'] , ':pa'=>$row['ID_ANSWER'] ,':s'=>$s ]);
    }
}

switch ($ax) {

    
    case 'uploadfile_answer':
        $bx = val_rq('bx', 'edit');
        $npa = val_rq('npa');

        if ($pa == '0') {
            $r = db_row('select ID_POLL_ANSWER from W2_POLL_ANSWER_I (:iqq, :na,:u) '
                    , [':iqq' => $qq, ':na' => $npa, ':u' => $u]
                    , DB_POBEDIM);
            $pa = $r['ID_POLL_ANSWER'];
        } else {
            $r = db_row('update w2_poll_answer set NAME_ANSWER = :npa  where ID_ANSWER=:pa  and  U_ADMIN = :u '
                    , [':npa' => $npa, ':pa' => $pa, ':u' => $u]
                    , DB_POBEDIM);
        }

        
        $nd = $dir0. "/data.poll/$qq";
        if (!file_exists($nd)){ mkdir($nd,0777);}
        $img = "/data.poll/$qq/$pa.jpg";
        $r = php_uploadfile( $dir0. "/data.poll/$qq/");
        
        
        $parts = pathinfo($r['NF']);
        $ef = $parts['extension'];

        rename($r['NF'], "$nd/$pa.0.$ef");
        image_thumb_save("$nd/$pa.0.$ef",$nd."/$pa.jpg",200,200);

        if ($bx==='edit'){
            echo htm_redirect1("/derjava/poll_edit.php?qq=$qq");            
        } else{
            echo htm_redirect1("/derjava/poll.php?qq=$qq");
        }
        break;
        
    
    
    
    case 'poll_answerpic_upload':
        $nd = $dir0. "/data.poll/$qq";
        if (!file_exists($nd)){ mkdir($nd,0777);}
        $img = "/data.poll/$qq/$pa.jpg";
        $r = php_uploadfile( $dir0. "/data.poll/$qq/");
        
        $parts = pathinfo($r['NF']);
        $ef = $parts['extension'];
        rename($r['NF'], "$nd/$pa.0.$ef");
        image_thumb_save("$nd/$pa.0.$ef",$nd."/$pa.jpg",200,200);
        exit;
        break;
    
     case 'poll_mainpic_upload':
        $nd = $dir0. "/data.poll/$qq";
        if (!file_exists($nd)){ mkdir($nd,0777);}
        $img = "/data.poll/$qq/$qq.jpg";
        $r = php_uploadfile( $dir0. "/data.poll/$qq/");
        
        $parts = pathinfo($r['NF']);
        $ef = $parts['extension'];
        rename($r['NF'], "$nd/$qq.0.$ef");
        image_thumb_save("$nd/$qq.0.$ef",$nd."/$qq.jpg",800,200);
        exit;
        break;
    
    
    
    case 'upd_user_kind':
        $nv = val_rq('n');
        $v = val_rq('v');
        $ku = val_rq('ku');
        switch ($nv){
            case 'NAME_KIND_USER':
                $r = new db(DB_POBEDIM,
                            'update or insert into u2_user_kind (u,id_kind_user,name_kind_user) values ( :u,:ku,:v)'
                            , [':u' => $u, ':ku' => $ku, ':v' => val_rq('v')]
                            );
                echo $r->r['ERR'];
                break;
            case 'SORTING_KIND_USER':
                $r = new db(DB_POBEDIM,
                            'update or insert into u2_user_kind (u,id_kind_user,SORTING_KIND_USER) values ( :u,:ku,:v)'
                            , [':u' => $u, ':ku' => $ku, ':v' => val_rq('v')]
                            );
                echo $r->r['ERR'];
                break;

            case 'DO_IMPORTANT':
                $r = new db(DB_POBEDIM,
                            'update or insert into u2_user_kind (u,id_kind_user,DO_IMPORTANT) values ( :u,:ku,:v)'
                            , [':u' => $u, ':ku' => $ku, ':v' => val_rq('v')]
                            );
                echo $r->r['ERR'];
                break;
            
            case "DO_IGNORE_POLL":
            case "DO_IGNORE_CAST":
            case "DO_DISABLE_MSG":
                $v = val_rq('checked',$v);
                if ($v == 'on' || $v == 'true' || $v == 'checked' ) $v = 1; else $v = 0;
                
                $r = new db(DB_POBEDIM,
                            'update or insert into u2_user_kind (u,id_kind_user,'.$nv.') values ( :u,:ku,:v)'
                            , [':u' => $u, ':ku' => $ku, ':v' => $v]
                            );
                echo $r->r['ERR'];
                break;
        }
        exit();

    case 'set_user_kind':
        $r = new db(DB_POBEDIM,//'update u2_poll set ts_read_poll=current_timestamp where id_poll=:p and u=:u'
                    'update or insert into u2_user (u,u_user,id_kind_user) values ( :u,:ux,:v)'
                    , [':u' => $u, ':ux' => val_rq('ux'), ':v' => val_rq('v')]
                    );
        echo $r->r['ERR'];
        exit();
    
    case 'poll_read_u':
        $r = new db(DB_POBEDIM,//'update u2_poll set ts_read_poll=current_timestamp where id_poll=:p and u=:u'
                    'update or insert into u2_poll (id_poll,u,ts_read_poll) values ( :p,:u,current_timestamp)'
                    , [':u' => $u, ':p' => val_rq('p')]
                    );
        echo $r->r['ERR'];
        exit();
    
    case 'get_u_secret':
        $r = new db(DB_USER, 'select * from u0_secret_s(:u)',[':u'=>$u]);
        echo $r->row('CODE_SECRET');
        exit;
        
     case 'confirm_text_get':
        $ct = val_rq('ct');
        $ka = val_rq('ka');
        $r = new db(DB_POBEDIM,'select TEXT_CONFIRM from U2_POLL_CONFIRMS where U=:u and ID_POLL=:qq and ID_CERTIFICATE=:ct and ID_ANSWER=:pa and ID_KIND_ANSWER=:ka'
                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka, ':tc'=>$tc ]
                    );
        $s =$r->row('TEXT_CONFIRM');
        //if ($r['ERR'] != '')  echo '-1'; else 
            echo $s;
         
        exit;
    
    
     case 'confirm_text_upd':
        $ct = val_rq('ct');
        $ka = val_rq('ka');
        $tc = val_rq('tc');
        $r = db_row('update U2_POLL_CONFIRMS set TEXT_CONFIRM=:tc where U=:u and ID_POLL=:qq and ID_CERTIFICATE=:ct and ID_ANSWER=:pa and ID_KIND_ANSWER=:ka'
                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka, ':tc'=>$tc ]
                    , DB_POBEDIM);
        if ($r['ERR'] != '')  echo '-1'; else echo '';
         
        exit;
        
     case 'confirm_upload':
        $ct = val_rq('ct');
        $ka = val_rq('ka');
//                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka]
        $r = php_uploadfile( $dir0. "/data.poll/$qq/$pa/$ct/$u/");
        $nf = '/'. $r['NF'];
        $r['NF'] = substr($nf, strrpos($nf,'/')+1);
        echo htmlspecialchars(json_encode($r), ENT_NOQUOTES);
         
        exit;
        break;

    
     case 'confirms_list':
        $ct = val_rq('ct');
        $ka = val_rq('ka');
         
            $r = new db(DB_POBEDIM,'select U,TEXT_CONFIRM from U2_POLL_CONFIRMS where ID_POLL=:qq and ID_CERTIFICATE=:ct and ID_ANSWER=:pa and ID_KIND_ANSWER=:ka'
                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka]
                    , function ($row,$pp,$lp){
                        $dir0 = $lp['dir0'];
                        $qq=$pp[':qq'];
                        $pa=$pp[':pa'];
                        $ct=$pp[':ct'];
                        $u=$pp[':u'];
                        $url="/data.poll/$qq/$pa/$ct/$u/";
                        $dir= $dir0. $url;
                        $lf = '';
                        if (file_exists($dir)){
                                $a = directoryToArray($dir, false);
                                foreach ($a as $f){
                                    if (strrpos($f, '.php') === false) {
                                        $lf .= '<br>'. '<a href="'.$url.$f.'" target="ff">'.$f.'</a>';
                                    }
                                }
                        }
                        $row['LF']=$lf;
                        return $row;
                    }
                    , ['dir0'=>$dir0]
                    );
         $s = json_encode($r->rows);
         echo $s;
        exit;
        break;
         
         
    case 'confirm_answer':
        $ct = val_rq('ct');
        $ka = val_rq('ka');
        $v = val_rq('v');
        if ($v == '0') {
            $r = db_row('delete from U2_POLL_CONFIRMS  where U=:u and ID_POLL=:qq and ID_CERTIFICATE=:ct and ID_ANSWER=:pa and ID_KIND_ANSWER=:ka'
                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka]
                    , DB_POBEDIM);
            if ($r['ERR'] != '')  echo '-1'; else echo '';
        } else {
            $r = db_row('update or INSERT INTO U2_POLL_CONFIRMS (U, ID_POLL, ID_CERTIFICATE, ID_ANSWER, ID_KIND_ANSWER) values (:u,:qq,:ct,:pa,:ka)'
                    , [':u' => $u, ':qq' => $qq, ':ct' => $ct, ':pa' => $pa, ':ka' => $ka]
                    , DB_POBEDIM);
            if ($r['ERR'] != '')  echo '-1'; else echo '';
        }
        exit;
        break;

    
    case 'gr_fav_in':
        $r = db_row('update or insert into u2_veche (u, veche, is_favorite ) values (:u,:iv,1)', [':u' => $u, ':iv' => $iv]
                , DB_POBEDIM);

        exit;
        break;

    case 'gr_fav_out':
        $r = db_row('update u2_veche set is_favorite=0 where u=:u and veche=:iv', [':u' => $u, ':iv' => $iv]
                , DB_POBEDIM);

        exit;
        break;

    
    case 'golosovanie_fav_in':
        $qq = val_rq('qq');
        $r = new db(DB_POBEDIM,'update or insert into u2_poll (u,id_poll,is_favorite) values (:u,:qq,1)', [':u' => $u, ':qq' => $qq] );

        exit;
        break;

    case 'golosovanie_fav_out':
        $qq = val_rq('qq');
        $r = new db(DB_POBEDIM, 'update u2_poll set is_favorite=0 where u=:u and id_poll=:qq', [':u' => $u, ':qq' => $qq] );

        exit;
        break;
    
    
    
    case 'verify_n_c':
        $ic = val_rq('ic');
        $urc = val_rq('urc');
        $r = new db(DB_POBEDIM,'select R from U2_CERTIFICATE_CONFIRM(:u,:ic,:urc)'
                , [':u' => $u, ':ic' => $ic, ':urc' => $urc]
                );
       
        exit;
        break;
    
/*
    case 'veche_job_upd':
        $nv=val_rq('nv');
        $v=val_rq('v');
        $j=val_rq('job');
        $r = new db(DB_POBEDIM, "update W2_VECHE_JOB set $nv=:v where ID_JOB_VECHE=:j", [':v'=>$v, ':j'=>$j] );
        echo json_encode( $r->rows );
        break;
    
    case 'veche_job_add':
        $r = new db(DB_POBEDIM, "select * from W2_VECHE_JOB_I1(:iv) ", [':iv'=>$iv, ':u'=>$u] );
        echo json_encode( $r->rows );
        break;
*/    
    case 'veche_option':
        $bx= val_rq('n');
        $v = val_rq('v','0'); if (empty($v)) $v='0';
        $r = new db(DB_POBEDIM, "update w2_veche set $bx=$v where veche=:iv",[':iv'=>$iv, ':u'=>$u]);
        echo '';
        break;
        
    case 'poll_delete':
        $r = new db(DB_POBEDIM, 'update w2_poll set sorting_poll=-1 where id_poll=:qq and u_admin=:u',[':qq'=>$qq, ':u'=>$u]);
        echo htm_redirect1('/derjava/poll_search.php');
        break;
        
    case 'set_answer0':
    $qq = val_rq('qq');
    $pa = val_rq('pa', null);
    $cu = val_rq('cu');
    $ck = val_rq('ck');
    $f = val_rq('f',0);
    if ($ck==='adminu'){ $f = '0';}
    

    $r = new db( DB_POBEDIM, 'select R from W2_POLL_ANSWER_U7(:u,:qq,:pa, 2,:cu,:ck, null,0)'
                             , [    ':u' => $u
                                  , ':qq' => $qq
                                  , ':pa' => $pa
                                  , ':cu'=>$cu
                                  , ':ck'=>$ck ]
                        , function($row){
                               sign_answer($row);
                            return null;
                         }
                        );
        unset($r);
    
        echo ''; 
        exit;
        break;
    
    
    case 'set_answer':
    $qq = val_rq('qq');
    $pa = val_rq('pa', null);
    $cu = val_rq('cu');
    $ck = val_rq('ck');
    $ic = val_rq('ic');
    $icY = val_rq('icY');
    $icN = val_rq('icN');
    $f = val_rq('f',0);
    if ($ck==='adminu'){ $f = '0';}
    
    if (!is_array($ic)){
        if ($f != '1') {
            $r = new db(DB_POBEDIM, 'select count(1) as C from w2_certificate where u_admin=:u',[':u'=>$u]);
           //if ($r->row('C') > 1){
                $s = f_host("/derjava/poll_ct.php?qq=$qq&pa=$pa&u=$u");
                $ck = '1';
                echo $s;
                exit;
            //}
        }
        $ic = array( $cu );
    }

//--- этот запрос нужен, чтобы понять какие галочки были сняты
//--- список галочек, которые установлены передаются в запросе            
                $r1 = new db(DB_POBEDIM,<<<SQL
                        select ct.ID_CERTIFICATE
                            ,pc.U_DELEGATE
                            ,pc.U_ADMIN
                            ,pc.ID_ANSWER
                            from W2_CERTIFICATE ct 
                                LEFT outer join W2_POLL_CERTIFICATES pc on pc.ID_POLL = :qq and
                                                    pc.ID_CERTIFICATE = ct.ID_CERTIFICATE
                            where ct.U_ADMIN=:iu
SQL
                      ,[ ':iu'=>$u , ':qq'=>$qq ]
                      , function($row,$pp,$lp){
                    
                    
                     $ka = 0;$row['CK'] =1;
                    if (array_search($row['ID_CERTIFICATE'], $lp['ic']) !==false && !empty($lp['ic'])){
                        $ka = 2;
                        if ($lp['ck'] === '0') $row['CK'] = 0; else $row['CK'] =1;
                      } 
                    
                    if (array_search($row['ID_CERTIFICATE'], $lp['icY']) !==false && !empty($lp['icY'])){
                        $ka = 1;
                        if ($lp['ck'] === '0') $row['CK'] = 0; else $row['CK'] =1;
                      } 
                    if (array_search($row['ID_CERTIFICATE'], $lp['icN']) !==false  && !empty($lp['icN'])){
                        $ka = -1;
                        if ($lp['ck'] === '0') $row['CK'] = 0; else $row['CK'] =1;
                      } 
                      
                      $r = new db( DB_POBEDIM, 'select R from W2_POLL_ANSWER_U7(:u,:qq,:pa, :ka ,:cu,:ck, null,1)'
                             , [    ':u' => $pp[':iu']
                                    , ':qq' => $pp[':qq']
                                    , ':pa' => $lp['pa']
                                    , ':ka' => $ka
                                    , ':cu'=>$row['ID_CERTIFICATE']
                                    , ':ck'=>$row['CK'] ]
                        );
                      
                      
                      $r = new db (DB_POBEDIM, 'select pc.* from W2_POLL_CERTIFICATES pc where pc.ID_POLL = :qq and pc.U_ADMIN=:u and pc.ID_ANSWER=:pa'
                              , [    ':u' => $pp[':iu']
                                    , ':qq' => $pp[':qq']
                                    , ':pa' => $lp[':pa']
                                ]
                              ,function($row){
                                sign_answer($row);
                                return null;
                              }
                             );
                      
                        return $row;
                      } 
                      , ['qq'=>$qq , 'pa'=>$pa , 'icY'=>$icY, 'icN'=>$icN, 'ic'=>$ic ,'ck'=>$ck ]
                       );
            
            $s = f_host("/derjava/poll.php?qq=$qq&ax=admin#pa$pa");
            
            if ($r1->length ===1 &&  f != '1' ) echo ''; else  echo htm_redirect1($s);            
        exit;
        break;
    
    
    case 'update_answer':
        $qq = val_rq('qq');
        $pa = val_rq('pa') + 0;
        $npa = val_rq('npa');

        if ($pa === 0) {
            $r = db_row('select ID_POLL_ANSWER from W2_POLL_ANSWER_I (:iqq, :na,:u) '
                    , [':iqq' => $qq, ':na' => $npa, ':u' => $u]
                    , DB_POBEDIM);
            $pa = $r['ID_POLL_ANSWER'];
        } else {
            
            $r = new db(DB_POBEDIM,'select R from W2_POLL_ANSWER_U1(:u,:pa, :npa, :tpa)',[':u'=>$u, ':pa' => $pa,':npa'=>$npa, ':tpa'=>$npa] );
            //$r = db_row('update w2_poll_answer set NAME_ANSWER = :npa  where ID_ANSWER=:pa ', [':npa' => $npa, ':pa' => $pa, ':u' => $u], DB_POBEDIM);
            echo $r->row('R');
        }
        break;        
        
        
    case 'answer_set_redirect':
        $r = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER_REDIRECT_U(:pa, :v, :u) '
                ,[':u'=> $u,':pa'=> $pa, ':v'=> val_rq('v')] );
        echo '';
        exit;
        break;    
    
    case 'poll_set_statute':
        $r = new db(DB_POBEDIM, 'update u2_poll set ID_STATUTE=:v where U=:u and id_poll=:qq'
                ,[':u'=> $u,':qq'=> $qq, ':v'=> val_rq('v')] );
        echo '';
        exit;
        break;
    
    case 'kodeks_create':
        $r = new db(DB_POBEDIM, 'insert into w2_statute (NAME_STATUTE,ID_STATUTE_PARENT) values (:ns, :nsp)'
                ,[':ns'=> val_rq('ns'),':nsp'=> val_rq('nsp',null)] );
        echo htm_redirect1("/derjava/kodeks.php?");
        exit;
        break;
    
    case 'set_like_answer':
        
        $v = val_rq('v');
        $r = new db(DB_POBEDIM,'select * from  U2_POLL_ANSWER_IU1(:u, :pa, :v)'
                , [':u'=>$u, ':pa'=>$pa, ':v'=>$v]) ;

        
        $r = new db( DB_POBEDIM, 'select R from W2_POLL_ANSWER_U7(:u,:qq,:pa, :kp,:cu,:ck, null,0)'
                             , [    ':u' => $u
                                  , ':qq' => $qq
                                  , ':pa' => $pa
                                  , ':kp' => $v
                                  , ':cu'=> null
                                  , ':ck'=>1 ]
                        , function($row){
                               sign_answer($row);
                            return null;
                         }
                        );        
        
        
        
        $r = get_like_answer_cnt($pa);
        unset($a);
        $a['CNT_LIKE'] = $r->row('CNT_LIKE');
        $a['CNT_DISLIKE'] = $r->row('CNT_DISLIKE');
        echo json_encode($a);
        exit;
        break;
    
    
    case 'select_veche':
        $ivp = val_rq('tp');
        $n = val_rq('n');
        $r1 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=$ivp and ($n=3 or veche < 10000)");
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
    
    case 'select_veche0':
        $ivp = val_rq('vp');
        $n = val_rq('n');
        $sx = val_rq('sx');
        $kv = val_rq('kv');
        $r1 = new db(DB_POBEDIM, <<<SQLTXT
        select * from W2_VECHE_NEW_SUB_L(:vp,:kv,:sx) 
SQLTXT
                ,[':vp'=>$ivp, ':kv'=>$kv, ':sx'=>$sx]);
        $r1->filter = function(&$row) {
            $u = current_iu();
            $nv = $row['NAME_VECHE']; if ($u == '1001') $nv .= ' '. $row['VECHE'];
            return tag_option( $nv, $row['VECHE'], a_('data-ivp', $row['VECHE_PARENT']));
        };

        if ($r1->length === 0) {
            echo '';
        } else {
            echo '<option value="0">...</option>' . $r1->printf();
        } 
        break;    
    
    
    
    
    
    

    case 'session':
        $n = val_rq('n');
        $v = val_rq('v',0);
        $w = val_rq('w');
        if ($n == 'DO_GET_CAST'){
            $r = new db(DB_POBEDIM,'select * from U2_WALL_SET_CAST(:u,:w,:v)',[':u'=>$u,':w'=>$w,':v'=>$v]);
        } else {
            $_SESSION[$n]=$v;
        }
        exit;
        break;
    
    case 'wall_msg_read':
        $m = val_rq('m',0);
        $r = new db(DB_POBEDIM,<<<SQL
             update or insert into u2_wall_msg (id_msg_wall, u, ts_sys) values (:m,:u,CURRENT_TIMESTAMP)
SQL
   , [':m' => $m, ':u' => $u ]);
        echo '';
        exit;
        break;
        
    
    case 'wall_msg_flag':    
        $v = val_rq('v',1);
        $r = new db(DB_POBEDIM,<<<SQL
                select * from W2_WALL_MSG_FLAG4(
    :m,
    :u,
    :v,
    :IS_APPROVED,
    :IS_BANNED,
    :IS_CLAIMED,
    :IS_LIKED,
    :IS_DELETED,
    :IS_MARKED,
    :IS_CASTED     
   )
SQL
   , [':m' => val_rq('m'), ':u' => $u, ':v' => $v ,
    ':IS_APPROVED'=> ($bx==='approve'),
    ':IS_BANNED'=> ($bx==='ban'),
    ':IS_CLAIMED'=> ($bx==='claim'),
    ':IS_LIKED'=> ($bx==='like'),
    ':IS_DELETED'=> ($bx==='delete'),
    ':IS_MARKED'=> ($bx==='mark'),
    ':IS_CASTED'=> ($bx==='cast'),
                ]);
        
        echo '';
        
        unset($r);
        exit;
        break;
        
    case 'veche_delete':
        $r = new db(DB_POBEDIM, 'select * from W2_VECHE_DELETE (:u, :iv)'
            , [':u' => $u, ':iv' => $iv]);
        $s = $r->row('R');
        echo $s;
        exit;
        break;
    
    case 'veche_set_ru':
        $ru = val_rq('ru');
        $vr = val_rq('vr');
        $iv = val_rq('iv');
        $r = new db(DB_POBEDIM, 'select * from U2_VECHE_ROLE_SET3(:ic,:u,:iv,:ru,:vr)'
                , [':ic' => val_rq('ic'), ':u' => $u, ':iv' => $iv, ':ru' => $ru, ':vr' => $vr]);
        //exit;
        break;
    
    case 'veche_set_wallu':
        $r = new db(DB_POBEDIM, 'select * from w2_wall_veche_role_set(:iv,:u,:wr)'
                , [':wr' => val_rq('wr'), ':u' => val_rq('u'), ':iv' => $iv]);
        //exit;
        break;

    
    
    
    case 'veche_webtext':
        $r = new db(DB_POBEDIM, 'update w2_veche_web2 set TEXT_WEB_VECHE = :t where ID_URL_VECHE = :wv'
            , [':t' => val_rq('v'), ':wv' => val_rq('wv')]);
        exit;
        break;
    
    case 'veche_weburl':
        $r = new db(DB_POBEDIM, 'update w2_veche_web2 set URL_WEB_VECHE = :v where ID_URL_VECHE = :wv'
            , [':v' => val_rq('v'), ':wv' => val_rq('wv')]);
        exit;
        break;
    
    case 'veche_text_members':
        $v = val_rq('v');
        $r = new db(DB_USER, 'update or insert into W0_VECHE (u_admin,veche,TEXT_MSG_MEMBER_VECHE) values (:u, :iv, :tv)', [':u' => $u, ':iv' => $iv, ':tv' => $v ]);
        //echo $r->r['ERR'] . '  -- '.$v;
        exit;
        break;

    case 'veche_text':
        $v = val_rq('v');
        $r = new db(DB_POBEDIM, 'select * from W2_VECHE_T_U (:u, :iv, :tv)'
                , [ ':u' => $u, ':iv' => $iv, ':tv' => $v ]);
        //echo $r->r['ERR'] . '  -- '.$v;
        exit;
        break;

    
    case 'poll_ep':
        $qq = val_rq('qq');
        $ep = val_rq('ep');

        $d = strtotime( str_replace(',', '.', $ep) );
        if ($d === false) { $ep = null; } else { $ep = date( "Y-m-d",$d); }
        $r = new db(DB_POBEDIM,'update w2_poll set end_poll=:ep where id_poll = :qq and u_admin=:u'
                        , [':qq' => $qq, ':ep'=>$ep, ':u' => $u]
                    );
        if (!empty($r->r['ERR'])) { $ep = '* '.$ep; }
        
        echo $ep;
        exit;
        break;
    
    case 'poll_name':
        $np = val_rq('np');
        $qq = val_rq('qq');
        $r = db_row('update w2_poll set name_poll=:np where id_poll = :qq and u_admin=:u'
                , [':qq' => $qq, ':np' => $np, ':u' => $u]
                , DB_POBEDIM);
        exit;
        break;
    
    case 'poll_text':
        $tp = val_rq('tp');
        $qq = val_rq('qq');
        $r = db_row('update w2_poll set text_poll=:tp where id_poll = :qq and u_admin=:u'
                , [':qq' => $qq, ':tp' => $tp, ':u' => $u]
                , DB_POBEDIM);
        exit;
        break;
        
    case 'poll_options_edit':
        $lon = val_rq('lon');
        $qq = val_rq('qq');
        $r = db_row('update w2_poll set options_poll=:lon where id_poll = :qq and u_admin=:u'
                , [':qq' => $qq, ':lon' => $lon, ':u' => $u]
                , DB_POBEDIM);
        exit;
        break;
        
    case 'poll_publish':
        $qq = val_rq('qq');
        $r = new db(DB_POBEDIM, 'UPDATE w2_poll  set ts_activated = CURRENT_TIMESTAMP  where id_poll =:qq and ts_activated is NULL', [':qq' => $qq]);
        echo htm_redirect1(f_host() . "/derjava/poll.php?qq=$qq&ts=". time());
        exit;
        break;
    
    case 'veche_img_upload':
                $dirtmp = $dir0 .'/tmp';
		$allowedExtensions = array(); // list of valid extensions, ex. array("jpg", "xml", "bmp")
		$sizeLimit = 1000 * 1024 * 1024; 
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($dirtmp);
                
    $nd = f_root() . "/data.veche/$iv/";
    if (!file_exists($nd)){ mkdir($nd, 0777); }
    
    $nf = $dirtmp.'/'.$result['filename'];

    if (!empty($nf)){
        try {
            $im = new Imagick_();
            if ($im->pingImage($nf)) {
                $im->readImage($nf);
                $im->thumbnailImage(950, 200, true);
                $im->writeImage($nd . $iv . '.jpg');
            } else {
                $err = 'Формат файла не поддерживается';
            }
        } catch (Exception $e) {
            $err = 'Ошибка обработки изображения';
        } unlink($nf);
    }
    $result['filename']= "/data.veche/$iv/$iv.jpg?".time();
    echo json_encode($result);
//    $htm = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit;
        break;
    
        
    case 'uploadimage_answer':
        $nd = dirname(__DIR__) . "/data.poll/$qq/";
        $r = php_uploadfile($nd);
        $nf = $r['NF'];
        try {
            $im = new Imagick_();
            if ($im->pingImage($nf)) {
                $im->readImage($nf);
                $im->thumbnailImage(200, 200, true);
                $im->writeImage($nd . $pa . '.jpg');
            } else {
                $err = 'Формат файла не поддерживается';
            }
        } catch (Exception $e) {
            $err = 'Ошибка обработки изображения';
        } unlink($nf);
        
        echo htm_redirect1("/derjava/poll_answer.php?pa=$pa&qq=$qq");
        exit;
        break;
    

        
    case 'update_web_poll':
        $bx = val_rq('bx', 'edit');
        $qq = val_rq('qq');
        $pwu = val_rq('pwu');
        $pwu__ = val_rq('pwu__');
        $pwt = val_rq('pwt');

        $r = new db(DB_POBEDIM, 'select * from W2_POLL_WEB_IU(:pwu,:pwt,:pwun , :qq ,:u)'
                , [':pwu' => $pwu, ':pwt' => $pwt, ':pwun' => $pwu__, ':qq' => $qq, ':u' => $u]);

        echo htm_redirect1("/derjava/poll.php?ax=$bx&qq=$qq");
        break;


    case 'msg':
        if ($bx==='get'){
            $r = new db(DB_POBEDIM,'select * from W2_WALL_MSG where ID_MSG_WALL =:m',[':m'=> val_rq('m')]);
            echo $r->row('BLOB_MSG_WALL');
        }
        if ($bx==='set'){
            //$ln = val_rq('ln');
            $t = trim( val_rq('txt') );
            $r = new db(DB_POBEDIM,'select * from W2_WALL_MSG_U1 ( :m,:u,:t)'
                    ,[':u'=>$u, ':m'=> val_rq('m') ,':t'=> $t ]);
            //echo f_host($ln).'#m'.val_rq('m');
            echo '';
        }
        exit;
        break;
    
    case 'newmsg6': // используется только в  
        $muj = val_rq('muj');
        $mp = val_rq('mp');
        $mr = val_rq('mr');
        $w = val_rq('w');
        $pa = val_rq('pa');
        $t = trim( val_rq('txt') );
        $t = ht_parse_NEWMSG($t);
        unset($r);
        $r = new db(DB_POBEDIM,'select * from W2_WALL_MSG_I7(:u, :w, :iv,:qq, :msg, :mp, :mr, :muj)'
                , [ ':u'=>$u  ,':w'=> $w  , ':iv' => $iv , ':qq' => $qq, ':msg'=> $t , ':mp'=>$mp, ':mr'=>$mr, ':muj'=>$muj ] );
        $m = $r->row('ID_MSG_WALL'); 
        $qq = $r->row('ID_POLL'); 
        $iv = $r->row('VECHE'); 
        $w = $r->row('ID_WALL');
        $uw = $r->row('U_WALL');
        unset($r);
        
        $p=[];
        $sx = val_rq('ln');//.'?'.val_rq('lx'); 
        $i = strpos($sx, '?'); if ($i === false) $rx = $sx; else $rx= substr($sx,0,$i);
        
        $rx = f_host( $rx )."?w=$w&iv=$iv&qq=$qq&pa=$pa&m=$m#m$m";

        if (val_rq('redirect')=='1' ){
            echo htm_redirect1($rx);
        } else {       
            echo $rx;
        }
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
        echo htm_redirect1('/derjava/veche.php?iv=' . $iv );
        break;
        
    case 'member':
        $bx = val_rq('bx');
        $iv = val_rq('iv');
        $ic = val_rq('ic');
        if ($bx === 'in') {
            $r = new db(DB_POBEDIM, 'select * from w2_veche_user_add (:iv, :u, :ic)', [':iv' => $iv, ':u' => $u, ':ic' => $ic]);
        }
        if ($bx === 'out') {
            $r = new db(DB_POBEDIM, 'select * from w2_veche_user_del (:iv, :u, :ic)', [':iv' => $iv, ':u' => $u, ':ic' => $ic]);
        }
        echo htm_redirect1('/derjava/veche.php?iv=' . $iv . '#ic' . $ic);
        break;

        
    case 'set_poll_answer_result':
        $pa = val_rq('pa');
        $r = new db(DB_POBEDIM,'select * from W2_POLL_ANSWER_RESULT_SET2 (:iv, :qq, :pa, :u)', [ ':iv' => $iv, ':qq'=>$qq, ':pa'=>$pa ,':u'=>$u  ] );
        if ($r->length === 1  && empty($r->r['ERR'])) { echo ''; } else { echo 'err:'.$r->r['ERR']; }
        break;
        
    case 'poll_result':
        $r =new  db(DB_POBEDIM, 'select * from w2_poll_result_create2(:u, :iv, :qq)', [ ':u'=>$u,  ':iv' => $iv , ':qq'=>$qq ] );
        echo htm_redirect1('/derjava/poll_edit.php?qq='.$r->row('ID_POLL_RESULT'));
        exit;
        break;
        
        
    case 'poll_create':

        $np = val_rq('np');
        $a1 = val_rq('a1');
        $a2 = val_rq('a2');
        $ka = val_rq('ka'); //категория
        $iv = val_rq('iv');
        $kp = val_rq('kp',0);

        if (empty($iv)) {

            $ta = 0;
            $ta1 = val_rq('ta1'); //территория
            $ta2 = val_rq('ta2'); //территория
            $ta3 = val_rq('ta3'); //территория

            if ($ta3 > 0) {
                $ta = $ta3;
            } else if ($ta2 > 0) {
                $ta = $ta2;
            } else {
                $ta = $ta1;
            }

            $iv = $ta;
            if ($iv < 0) {
                $iv = 1;
            }
        }

        $r = db_row(' select ID_POLL from W2_POLL_I3 (:iv, :np, :u, :kp) '
                , [':iv' => $iv, ':np' => val_rq('np'), ':u' => $u, ':kp'=>$kp ]
                , DB_POBEDIM);
        $qq = $r['ID_POLL'];

        if ($qq !== null) {

            $na = val_rq('a1', '');
            if (!empty($na)) {
                $r = db_row('select ID_POLL_ANSWER from W2_POLL_ANSWER_I (:iqq, :na,:u) '
                        , [':iqq' => $qq, ':na' => $na, ':u' => $u]
                        , DB_POBEDIM);
            }

            $na = val_rq('a2', '');
            if (!empty($na)) {
                $r = db_row('select ID_POLL_ANSWER from W2_POLL_ANSWER_I (:iqq, :na,:u) '
                        , [':iqq' => $qq, ':na' => $na, ':u' => $u]
                        , DB_POBEDIM);
            }


            $r = db_row('update w2_poll set end_poll=null , CATALOG_POLL=:ka where id_poll = :iqq and u_admin=:u'
                    , [':iqq' => $qq, ':ka' => $ka, ':u' => $u]
                    , DB_POBEDIM);
            
            
        $nd = f_root() . "/data.poll/$qq/";

        $r = php_uploadfile($nd);
        $nf = $r['NF'];
        if (!empty($nf)) {
            try {
                $im = new Imagick_();
                if ($im->pingImage($nf)) {
                    $im->readImage($nf);
                    $im->thumbnailImage(950, 200, true);
                    $im->writeImage($nd . $qq . '.jpg');
                } else {
                    $err = 'Формат файла не поддерживается';
                }
            } catch (Exception $e) {
                $err = 'Ошибка обработки изображения';
            } unlink($nf);
        }
        echo htm_redirect1(f_host() . "/derjava/poll_edit.php?qq=$qq");
            
        } else {
            echo $r['ERR'];
        }
        exit;
        break;
        
    case 'get_likeanswer_list':
        $v = val_rq('v');
        $r = new db(DB_POBEDIM, 'select * from u2_poll_answer where id_answer=:pa and is_like is not distinct from  :v'
                                 ,[':pa'=>$pa,':v'=>$v]);;
        
        $r->filter = function($row){

$ux = $row['U'];
$imgsrc= tag_user_imgsrc($row['U']);
$nu =  get_username ($row['U']);
$hu =  get_userhref( $row['U'] ) ;
$s = <<<TXT
         <tr>
            <td><a class="p-2" href="$hu" target="u$ux"><img src="$imgsrc" class="online"></a></td>
            <td> $nu </td>
        </tr>
TXT
;
            return $s;
        };                        
        unset($a);
        $a['HTML'] = $r->printf();
        
        echo json_encode($a);
        exit;
        break;

    
    
    
    case 'get_likelist':
        $qq = val_rq('qq');
        $v = val_rq('v');
        $r = new db(DB_POBEDIM, 'select * from u2_poll where id_poll=:qq and is_like is not distinct from  :v'
                                 ,[':qq'=>$qq,':v'=>$v]);;
        
        $r->filter = function($row){

$ux = $row['U'];
$imgsrc= tag_user_imgsrc($row['U']);
$nu =  get_username ($ux);
$hu =  get_userhref( $ux ) ;
$s = <<<TXT
         <tr>
            <td><a class="p-2" href="$hu" target="u$ux"><img src="$imgsrc" class="online"></a></td>
            <td> $nu </td>
        </tr>
TXT
;
            return $s;
        };                        
        unset($a);
        $a['HTML'] = $r->printf();
        
/*        
*/
        
        echo json_encode($a);
        exit;
        break;
        
}

