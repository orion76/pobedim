<?php

 function makeview99(&$data99, $pr, $pa, $can_set_result){
    $data99[0]['ID_POLL_RESULT'] = $pr;
    if ($pr == null){
            $data99[0]['DO_CREATE_POLL_RESULT'] = 1;
        } else {
            if ($pa == null) $pa = 0;
            $data99[0]['DO_CREATE_POLL_RESULT'] = null;
            $r8 = new db(DB_POBEDIM,'select * from w2_poll where id_poll=:qq',[':qq'=>$pr]);
            $r7 = new db(DB_POBEDIM,'select * from w2_poll_answer where id_answer=:pa',[':pa'=>$pa]);
            if ($can_set_result===1){
            
            $data99[0]['CAN_SET_RESULT'] = 1;
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
               $data99[0]['CAN_SET_RESULT'] = null;
            }
            $data99[0]['NAME_POLL_RESULT'] = $r8->row('NAME_POLL');
            $data99[0]['NAME_ANSWER_RESULT'] = iif($r7->length === 0,' -- решение не принято -- ', $r7->row('NAME_ANSWER'));
        }
}