<?php

$dir0 = dirname(__DIR__ ,2 );
require_once ($dir0.'/ut.php'); 

function veche_ax_member($ax,$bx,$iu,$iv){
    if ($ax==='member'){
       if ($bx==='in') {
           $r = new db(DB_POBEDIM,'insert into u2_veche(u, veche) values(:u,:iv)',[':u'=>$iu,':iv'=>$iv]);
       }
       if ($bx==='out') {
           $r = new db(DB_POBEDIM,'delete from u2_veche where u=:u and veche=:iv',[':u'=>$iu,':iv'=>$iv]);
       }
    }
}

function veche_ax_add($ax,$iu,$iv){
    return false;
}

function veche_ax_add_web($ax,$iu,$iv){

}


function veche_web_row0($iv){
 $row0 = db_row(<<<EOT
   select v.* , vp.NAME_VECHE as NAME_VECHE_PARENT
       , vp1.VECHE_PARENT as VECHE_PARENT1
       , vp1.NAME_VECHE as NAME_VECHE_PARENT1
       , vp2.VECHE_PARENT as VECHE_PARENT2    
       , vp2.NAME_VECHE as NAME_VECHE_PARENT2
      from W2_VECHE v 
         inner join W2_VECHE vp on vp.VECHE = v.VECHE_PARENT  -- // city
         left outer join W2_VECHE vp1 on vp1.VECHE = vp.VECHE_PARENT  -- // oblast
         left outer join W2_VECHE vp2 on vp2.VECHE = vp1.VECHE_PARENT  -- // okrug                    
          where v.VECHE=:iv
EOT
        , [':iv'=>$iv], DB_POBEDIM);
  $url = f_script('iv=');
  $row0['htm'] =  tag_span(
          iif($row0['VECHE_PARENT2'], tag_a( $url.$row0['VECHE_PARENT2'], $row0['NAME_VECHE_PARENT2']))
        . iif($row0['VECHE_PARENT1'], tag_a( $url.$row0['VECHE_PARENT1'], $row0['NAME_VECHE_PARENT1'])) 
        . iif($row0['VECHE_PARENT'], tag_a( $url.$row0['VECHE_PARENT'], $row0['NAME_VECHE_PARENT'])) 
        .  $row0['NAME_VECHE'] , iif($row0['VECHE'] > 1, a_class('path_veche_web') ) );
   return $row0;
}


function veche_web_r1($iv){   
    $r1 = db_foreach( function(&$row,&$lp){
                $uv = $_SERVER['SCRIPT_NAME'].'?iv='.$row['VECHE'];
                return tag_li_a(  $uv, $row['NAME_VECHE'] );
                }
            ,'select * from W2_VECHE where VECHE_PARENT = :iv', [':iv'=>$iv],[], DB_POBEDIM);
    return $r1;        
}

function veche_web_r2($iv){

}
