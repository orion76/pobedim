<?php

require_once ('ut.php');

function popuplist2($lli, $subclass_style='cmenu_ltr' , $m = '**'){
    $lli = trim($lli); 
    $s = iif($lli != '', '<ul class="cmenu '.$subclass_style.' popuplist" > <li>'.$m.'<ul> '.$lli.'</ul></li> </ul>');
    return $s;
}



function tag_option2($s, $v, $attr='', $v_selected) {
    return( '<option value="'.$v.'" '.iif($v==$v_selected,'selected="selected" ').$attr.'>'.$s.'</option>'); 
}

function rows_to_options($rs,$f_label,$f_value,$v_selected){
    $s = '';
    foreach($rs as $row){
        $s .= tag_option2($row[$f_label],$row[$f_value],'',$v_selected);
    }
    return $s;
}

function tag_tr_select($lbl,$name, $loption, $attr='' ){
    //$loption = rows_to_options($rs,$f_label,$f_value,$v);
    return( tag_tr( tag_td($lbl, 'style="white-space: nowrap;"')
                   .tag_td( '<select name="'.$name.'" '.$attr.'>'.$loption.'</select>' )) 
           
           ); 
}


 