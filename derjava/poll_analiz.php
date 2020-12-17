<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$p = val_rq('p', 0);
$u = current_iu();

$data0[0]['ID_POLL'] = $p;

if (isUserLoggedOn())
    $ht = new html('/derjava/poll_analiz.html');
else 
    $ht = new html('/derjava/poll_analiz_guest.html');
$menu= menu_user($ht, '');
 
$s = $p;
$ht->part['head'][0]['TITLE'] =  'Анализ голосования '.$s.' | Держава, pobedim.su';
$ht->part['head'][0]['TITLE_OG'] = 'Анализ голосования '.$s.' | Держава, pobedim.su';



$a = [];
$r1 = new db(DB_POBEDIM, 'select * from W2_POLL_ANALIZ_L(:p ,1)'
        , [':p'=>$p]
         , function ($row,$pp,$lp){
              $row['ID_POLL']=$pp[':p'];
              if ($row['CNT_ANSWER']==null) $row['STYLE_CNT_POLL'] = 'background-color:lightyellow;'; else $row['STYLE_CNT_POLL'] = '';
              if ($row['ID_KIND_ANSWER'] == '2') $row['A2']=  $row['CNT_ANSWER']; else $row['A2']=''; 
              if ($row['ID_KIND_ANSWER'] == '1') $row['A1P']=  $row['CNT_ANSWER']; else $row['A1P']='';
              if ($row['ID_KIND_ANSWER'] == '-1') $row['A1M']=  $row['CNT_ANSWER']; else $row['A1M']='';
/*              if (count($lp['a']) == 0) array_push ($lp['a'],$row);
              else{
                  foreach ($lp['a'] as $row1){
                      if ($row['VECHE']==$row1['VECHE']){
                        $row1['A2']=  $row1['A2']+$row['A2'];
                        $row1['A1P']= $row1['A1P']+$row['A1P'];
                        $row1['A1M']= $row1['A1M']+$row['A1M'];
                        return null;
                      }
                  }
                  array_push ($lp['a'],$row);
              }
  */
              return $row;
            }
        ,['a'=>&$a]
        );

        
$ht->data('data1', $r1->rows );
$ht->data('data0', $data0 );
 

echo $ht->build('',true);

 


