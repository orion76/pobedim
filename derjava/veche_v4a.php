<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax');
$bx = val_rq('bx');
$iv = val_rq('iv');

$u = current_iu();


$ht = new html('/derjava/veche_v4a.html');
$menu= menu_user($ht, '');
$menu['RX']='';

$data0['VECHE'] = $iv;

$v4a = val_rq('v4a',0);
$data0['IMGSRC_USER_V4A'] = tag_user_imgsrc($v4a);
$ht->data('data0', $data0);

// ----------------------------  проголосовавшие за координатора  ---------------------------------
    
    $r1 = new db(DB_POBEDIM, <<<TXT
select v.U , c.NAME_CERTIFICATE
  from U2_VECHE v
    inner join w2_user u on u.u = v.u
    left outer join v2_certificate c on c.id_certificate= u.id_certificate_u
            
  where v.u_vote4admin =:v4a and v.veche=:iv
TXT
        , [':v4a' => $v4a , ':iv'=>$iv ]
            ,function($row){ $row['IMGSRC_USER'] = tag_user_imgsrc($row['U']);   return $row;  }
     );
        
$ht->data('data1', $r1->rows );        


echo $ht->build('', true);

 