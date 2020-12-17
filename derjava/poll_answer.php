<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
include $dir0 . '/derzhava/derzhava_fn.php';

$ax = val_rq('ax', 0);
$qq = val_rq('qq');
$pa = val_rq('pa')+0;

$fu = current_fu();
$u = current_iu();


if ($pa == 0) {
    $get = array_keys($_GET);
    $pa = $get[0];
}


if ($ax==='pollanswer_add') {
    $r = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWERS_I1(:p,:pa,:u)'
            ,[':p'=> val_rq('p'), ':pa'=> $pa, ':u'=> $u ] );

}
if ($ax==='pollanswer_approve') {
    if ($u == '1001'){
        $r = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWERS_I1(:p,:pa,:u)'
                ,[':p'=> val_rq('p'), ':pa'=> $pa, ':u'=> $u ] );
    }
}
if ($ax==='pollanswer_del') {
    if ($u == '1001'){
        $r = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWERS_D1(:p,:pa,:u)'
                ,[':p'=> val_rq('p'), ':pa'=> $pa, ':u'=> $u ] );
    }
}



$ht = new html('/derjava/poll_answer.html');
$menu= menu_user($ht, '');


$r1 = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER_L8(:u,:qq, :pa)'
        , [':u' => $u, ':qq' => $qq ,':pa'=>$pa ]
        , function($row, $pp, $lp){
            $pa = $row['ID_ANSWER'];
            $r = new db(DB_POBEDIM,<<<SQL
    select p.* 
           ,paa.ID_ANSWER
           ,CASE WHEN up.ID_ANSWER IS NOT DISTINCT FROM paa.ID_ANSWER THEN 1 ELSE  0 END as V_ANSWER
           ,paa.CNT as CNT2APPROVE
            from
                (
                  select ID_ANSWER, ID_POLL, 1 as T, null as CNT from W2_POLL_ANSWERS
                 union   
                  select upaa.ID_ANSWER, upaa.ID_POLL, 0,  count(*)
                      from u2_POLL_ANSWERS upaa
                        left outer join W2_POLL_ANSWERS paa0 
                                on paa0.ID_ANSWER=upaa.ID_ANSWER and
                                   paa0.ID_POLL=upaa.ID_POLL
                      where paa0.ID_ANSWER is null
                      group by 1,2
                    
                 )  paa 
                inner join w2_POlL p on p.ID_POLL=paa.ID_POLL 
                left outer join u2_poll up on up.id_poll=paa.ID_POLL and
                                              up.id_answer=paa.ID_ANSWER and
                                              up.u = :u
        where paa.ID_ANSWER = :pa
SQL
                    ,[':pa'=>$pa, ':u'=>$pp[':u']]
                    ,function($row,$pp,$lp){
                            if($row['V_ANSWER'] == '1'){ 
                                $row['IS_ANSWER_CHECKED']=1; 
                                $row['VISIBLE_BTN_ANSWER_CHECK']=null; 
                            }
                            else {
                                $row['IS_ANSWER_CHECKED']= null; 
                                // чтобы голосовали пусть переходят в голосование
                                $row['VISIBLE_BTN_ANSWER_CHECK']=null; //1; 
                            }
                            return $row;
                    }
                    ,[]
                    );
            $row['rows1']=$r->rows;
            $qq = $row['ID_POLL'];
            $src = "/data.poll/$qq/$pa.jpg";
            
            $nf = dirname(__DIR__) . $src;
            if (!file_exists($nf)) {
                $src = '';
            }
            $row['IMG_ANSWER'] = $src;
            
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



$ht->part['head'][0]['TITLE'] =  'Референдум '.$r1->row('NAME_ANSWER').' | Держава.сайт / pobedim.su';;
$ht->part['head'][0]['TITLE_OG'] = ' Проголосуйте на Держава/pobedim.su за ' . str_trunc( $r1->row('NAME_ANSWER') );
$ht->part['head'][0]['IMG_OG'] = f_host($r1->row('IMG_ANSWER'));


$ht->data('data1', $r1->rows );
$ht->data('data0', $data0 );
echo $ht->build('',true);

