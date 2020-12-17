<?php

function makeview99(&$data99, $pr, $pa, $can_set_result){
    $data99[0]['ID_POLL_RESULT'] = $pr;
    if ($pr == null){
            $data99[0]['DO_CREATE_POLL_RESULT'] = 1;
        } else {
            $data99[0]['DO_CREATE_POLL_RESULT'] = null;
            $r8 = new db(DB_POBEDIM,'select * from w2_poll where id_poll=:qq',[':qq'=>$pr]);
            $r7 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_answer=:pa',[':pa'=>$pa]);
            if ($can_set_result===1){
            $r11 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_poll=:qq',[':qq'=>$pr]
                    ,function($row,$pa,$lp){
                        $row['SELECTED']=iif($row['ID_ANSWER']===$lp['pa'] ,' selected="selected" ',null);
                        return $row;
                    }
                    ,['pa'=>$pa ]
               );
               $data99[0]['data99s']= $r11->rows;
            } else {
               $data99[0]['data99s']= null;            
            }
            $data99[0]['NAME_POLL_RESULT'] = $r8->row('NAME_POLL');
            $data99[0]['NAME_ANSWER_RESULT'] = iif($r7->length === 0,' -- решение не принято -- ', $r7->row('NAME_ANSWER'));
        }    
}


    $pr = $r0->row('ID_POLL_RESULT');    //
    $kv = $r0->row('ID_KIND_VECHE');    //
    $pa = $r0->row('ID_ANSWER_RESULT')+0;
    $data99 = null;
    
if ($kv == 8){
    $data99[0]['VECHE'] = $iv;
    makeview99($data99, $r0->row('ID_POLL_RESULT'), $pa, $can_set_result);
     $data99[0]['VECHE_IS_COMMETTEE'] = 'съезда';
     $data99[0]['NAME_POLL_VECHE'] = ' ';
} else {

 
$r9 = new db(DB_POBEDIM, <<<TXT
    select p.NAME_POLL, pa.NAME_ANSWER, p.ID_POLL , pa.ID_ANSWER_RESULT
        from W2_POLL_ANSWER pa
            inner join w2_poll p on p.ID_POLL = pa.ID_POLL
        where pa.veche_answer = :iv
TXT
        , [':iv' => $iv]);

    if ($r9->length > 0){
        $data99 = $r9->rows;
    /*    
    $data99[0]['VECHE_IS_COMMETTEE'] = null;
    $data99[0]['ID_POLL_RESULT'] = null;
    $data99[0]['NAME_POLL_RESULT'] = null;
    $data99[0]['NAME_ANSWER_RESULT'] = null;
    */  $data99[0]['VECHE'] =$iv;  
        $data99[0]['VECHE_IS_COMMETTEE'] = $r9->row('NAME_ANSWER');
        $data99[0]['ID_POLL_VECHE'] = $r9->row('ID_POLL');
        $data99[0]['NAME_POLL_VECHE'] = $r9->row('NAME_POLL');

        $pa = $r9->row('ID_ANSWER_RESULT');  //

        if ($pr == null){
            $data99[0]['DO_CREATE_POLL_RESULT'] = 1;
        } else {
            $data99[0]['DO_CREATE_POLL_RESULT'] = null;
            $data99[0]['ID_POLL_RESULT'] = $pr;
            $r8 = new db(DB_POBEDIM,'select * from w2_poll where id_poll=:qq',[':qq'=>$pr]);
            $r7 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_answer=:pa',[':pa'=>$pa]);
            if ($can_set_result===1){
            $r11 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_poll=:qq',[':qq'=>$pr]
                    ,function($row,$pa,$lp){
                        $row['SELECTED']=iif($row['ID_ANSWER']===$lp['pa'] ,' selected="selected" ',null);
                        return $row;
                    }
                    ,['pa'=>$pa ]
               );
               $data99[0]['data99s']= $r11->rows;
            } else {
               $data99[0]['data99s']= null;            
            }
            $data99[0]['NAME_POLL_RESULT'] = $r8->row('NAME_POLL');
            $data99[0]['NAME_ANSWER_RESULT'] = iif($r7->length === 0,' -- решение не принято -- ', $r7->row('NAME_ANSWER'));
        }
    }

}
$ht->data('data99', $data99);
 
 