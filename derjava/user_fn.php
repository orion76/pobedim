<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';



function get_CNT_POLL_U($iu){
 $r = new db(DB_POBEDIM, <<<SQL
select  count(1) as CNT
    from u2_poll pu
    where pu.U= :uc and 
          pu.ID_ANSWER is not null
SQL
        , [':uc'=>$iu]   );
   return $r->row('CNT');
}        


