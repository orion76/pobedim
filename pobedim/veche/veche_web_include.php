<?php


/*
if ($ax === 'member'){
   if ($bx === 'in') {
       $r = new db(DB_POBEDIM,'insert into u2_veche(u,veche) values(:u,:iv)',[':u'=>$iu,':iv'=>$iv]);
   }
   if ($bx === 'out') {
       $r = new db(DB_POBEDIM,'update u2_veche set END_ASSOCIATE=current_date where u=:u and veche=:iv'
               ,[':u'=>$iu,':iv'=>$iv]);
   }
}
*/

if ($ax === 'be_delegate'){ // AJAX
    $iv = val_rq('iv'); //  == iv
    $ck = val_rq('checked',0); //  == iv
    $u = $iu;
    if ($ck == 'true') { $v = 1;} else { $v = 0; }
    
   $r = new db(DB_POBEDIM, 'select * from U2_VECHE_IS_DELEGAET_SET( :u, :iv, :v)', [ ':u'=>$u,':iv'=>$iv ,':v'=> $v ] );
   
   $r = $r->row();
   echo json_encode($r);
   exit;
}




