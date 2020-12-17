<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';

$ax = val_rq('ax', 0);
$qq = val_rq('qq');
$pa = val_rq('pa');

$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/kodeks_a.html');
$menu= menu_user($ht, '');


$r1 = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER_L8(:u,:qq, :pa)'
        , [':u' => $u, ':qq' => $qq ,':pa'=>$pa ]
        , function($row, $pp, $lp){
            $qq = $row['ID_POLL'];
            $pa = $row['ID_ANSWER'];
            $src = "/data.poll/$qq/$pa.jpg";
            
            $row['VISIBLE_HREF_ANSWER'] = null;
            $row['VISIBLE_HREF_POLL'] = 1;
            
            
            $nf = dirname(__DIR__) . $src;
            if (!file_exists($nf)) {
                $src = '';
            }
            $row['IMG_ANSWER'] = $src;
            
            if($row['V_ANSWER'] == '1'){ 
                $row['IS_ANSWER_CHECKED']=1; 
                $row['VISIBLE_BTN_ANSWER_CHECK']=null; 
            }
            else {
                $row['IS_ANSWER_CHECKED']= null; 
                $row['VISIBLE_BTN_ANSWER_CHECK']=1; 
            }
            
            $r = get_like_answer_cnt($pa);
            $row['CNT_LIKE'] = $r->row('CNT_LIKE');
            $row['CNT_DISLIKE'] = $r->row('CNT_DISLIKE');

            return $row;
         }
      );
$qq = $r1->row('ID_POLL');
      
$r0 = new db(DB_POBEDIM, 'select p.* , v.NAME_VECHE from W2_POLL p  inner join w2_veche v on v.veche=p.veche where p.id_poll=:qq'
        , [':qq' => $qq ]
        , function($row,$pp,$lp){
    
            $row['ID_ANSWER'] = $lp['pa'];
            $row['SHARE_POLL'] = urlencode(f_host() . '/derjava/poll_answer.php?pa='.$lp['pa']);
    
            $r3 = new db(DB_POBEDIM, <<<SQL
select vc1.ID_ROLE_VECHE
    from w2_veche_certificates vc1
        inner join w2_user U on u.ID_CERTIFICATE_U = vc1.ID_CERTIFICATE and
                                u.u = :u
    where vc1.VECHE  = :v 
SQL
                ,[':u'=>$lp['u'], ':v'=>$row['VECHE'] ]  );
            $rv = $r3->row('ID_ROLE_VECHE');

          $row['CAN_CHANGE_IMG_ANSWER'] =  iif($rv == '1',1,null);
          
          return $row;
        }
        ,['pa'=>$pa , 'u'=>$u]
    );
$data0 =  $r0->rows;
$data0[0]['ID_POLL_REDIRECT'] = $r1->row('ID_POLL_REDIRECT');

$head0[0]['TITLE'] = 'Референдум '.$r1->row('NAME_ANSWER').' | Держава / pobedim.su';
$head0[0]['IMG_POLL_ANSWER_OG'] = f_host($r1->row('IMG_ANSWER'));
$head0[0]['TITLE_OG'] = ' Проголосуйте на Держава/pobedim.su за ' . $r1->row('NAME_ANSWER');

$r2 = new db(DB_POBEDIM,'select * from W2_POLL_DETAILS_L(:qq)',[':qq'=>$qq]);

$ht->data('data2', $r2->rows );
$ht->data('data1', $r1->rows );
$ht->data('data0', $data0 );
$ht->data('head0', $head0 );
echo $ht->build('',true);

