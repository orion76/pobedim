<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();

$sx = val_rq('sx_dt');
$ic = val_rq('ic', '0');
$iv = val_rq('iv', 1);


if ($ax === 'set_delegate') {
    $du = val_rq('v');
    $ic = val_rq('ic');

    $r = new db(DB_POBEDIM, 'select * from W2_VECHE_CERTIFICATES_U(:iv,:ic,:du,:ua)'
            , [':iv' => $iv, ':ic' => $ic, ':du' => $du, ':ua' => $u]);
    unset($r);
    $ic = 0;
    exit;
}


// ------------------------------------------------------------------

$ht = new html('/derjava/veche_delegates.html');
$menu= menu_user($ht, '');
$ht->data('menu1', $menu );


$r0 = new db(DB_POBEDIM, 'select * from W2_VECHE where VECHE=:iv', [':iv' => $iv]
        ,
function ($row){ 
    $row['SX']=val_rq('sx_dt');
    $row['ID_CERTIFICATE']=val_rq('ic');
    return $row;
}
        );

//----------- ПЕРЕЧЕНЬ СЕРТИФИКАТОВ В ГРУППЕ,  АДПИНИСТРИРУЕМЫХ ТЕКУЩИМ ПОЛЬЗОВАТЕЛЕМ

$r2 = new db(DB_POBEDIM, <<<TXT

   select c0.ID_CERTIFICATE
       , cu0.NAME3_CERTIFICATE 
           ||' '|| cu0.NAME1_CERTIFICATE 
           ||' '|| cu0.NAME2_CERTIFICATE
        as NAME_CERTIFICATE
       , coalesce( vc0.U_DELEGATE,0) as U_DELEGATE
       , vc0.VECHE
    from W2_CERTIFICATE c0
         inner join u2_CERTIFICATE cu0 on cu0.ID_CERTIFICATE = c0.ID_CERTIFICATE and
                                          cu0.u = c0.u_admin
          inner join W2_VECHE_CERTIFICATES vc0 on vc0.ID_CERTIFICATE = c0.ID_CERTIFICATE and
                                                  vc0.VECHE = :iv and
                                                  vc0.id_role_veche < 8  
    where c0.U_ADMIN = :u
TXT
        , [':iv' => $iv, ':u' => $u]
        ,function($row,$pa, $lp) {
           if ($lp['ic'] === $row['ID_CERTIFICATE']) { $row['CHECKED'] = a_checked(true); } else {$row['CHECKED'] = '';}
         //  $row['ID_CERTIFICATE'] =$lp['ic'];
           return $row;
}  ,['ic' => $ic]
        );

if ($r2->length === 1) {
    $ic = $r2->row('ID_CERTIFICATE');
}



    $r1 = new db(DB_POBEDIM, <<<TXT

select uv.U , u.ID_CERTIFICATE_U
       , cu.NAME3_CERTIFICATE 
           ||' '|| cu.NAME1_CERTIFICATE 
           ||' '|| cu.NAME2_CERTIFICATE
        as NAME_CERTIFICATE_U
            
    , vc1.U_DELEGATE
    , uc.U as U_CERTIFICATE_ADMIN
    , uc.NAME_U as NAME_U_CERTIFICATE_ADMIN
    , uv.VECHE
    from U2_VECHE  uv
        inner join w2_user u on u.u = uv.u  
        inner join W2_CERTIFICATE ct1 on ct1.ID_CERTIFICATE = :ic
        left outer join W2_VECHE_CERTIFICATES vc1 on vc1.VECHE = uv.VECHE and
                                                     vc1.U_DELEGATE = uv.U and
                                                     vc1.ID_CERTIFICATE = ct1.ID_CERTIFICATE
        inner join u2_CERTIFICATE cu on cu.ID_CERTIFICATE = u.ID_CERTIFICATE_U and
                                        cu.u = u.u  

       left outer join W2_USER uc on uc.U = ct1.U_ADMIN
    where
        uv.VECHE=  :iv  and
            
        cu.NAME3_CERTIFICATE 
        ||' '|| cu.NAME1_CERTIFICATE 
        ||' '|| cu.NAME2_CERTIFICATE   
            containing :sx
            
    order by vc1.U_DELEGATE nulls last

TXT
            , [':iv' => $iv, ':sx' => $sx, ':ic' => $ic]
         , function($row,$pa, $lp) {
        $u = $lp['u'];
        $iv = $lp['iv'];
        $ic = $lp['ic'];
        $row['ID_CERTIFICATE']=$ic;
        $row['IMGSRC_U'] = tag_user_imgsrc($row['U']);
        if ($row['U'] === $row['U_DELEGATE']) { $row['CHECKED'] = a_checked(true); } else {$row['CHECKED'] = '';}
        return $row;
       }   
       ,['u' => $u, 'iv' => $iv, 'ic' => $ic]
            );  

$data0 = $r0->rows;
$data0[0]['CT'] = $ic;
$ht->data('data0', $data0 );
$ht->data('data1',  $r1->rows );
$ht->data('data2',  $r2->rows );
echo $ht->build('',true);




