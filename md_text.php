<?php

/*
 http://ilfire.ru/kompyutery/shpargalka-po-sintaksisu-markdown-markdaun-so-vsemi-samymi-populyarnymi-tegami/#link12
 */

function md_parsing_text($text_md){
    $text_md .= '        ';
    $ln = mb_strlen($text_md);
    $a = array('');  $n = 0;
    for($i = 0; $i < $ln ; $i++) 
    {
        $ch = mb_substr($text_md,$i,1);
         if ($ch == chr(13))  {$ch = '';continue;}
        switch ($ch){
            case chr(10): $ch = ''; array_push( $a, '' );continue;
            case '[': 
                $ch1 = mb_substr($text_md,$i+1,1);
                if ($ch1 == '^') {
                    $k = mb_strpos($text_md, ']', $i+1);
                    $ch2 = mb_substr($text_md,$k+1,1); // ожидаем ":"
                    if ($k > 0  && ($k-$i < 10) ) {
                        $ch = '';
                        $s = mb_substr($text_md,$i, $k - $i +1);
                        if ($ch2 == ':') { $s .= ':'; $i++; }
                        array_push( $a, array('n'=>$s , 'e'=> $ch2 ) );
                        $i += mb_strlen($s);  
                        array_push( $a, '' );
                        // $n += 2;
                        // echo $s;
                    }
                }
                break;
            default:
                break;
        }        
        if ($ch != '') { 
            $n = count($a)-1;
            $a[$n] .= $ch; 
        }
    }
    $r = implode( ' ', md_parsing_compile($a));
    return $r;
}

function md_parsing_compile(&$a){
    array_push( $a, '' ); 
    array_push( $a, '' ); 
    $aa = array(); array_push( $aa, '' ); 
    $n = count($a)-1;
    for( $i = 0; $i < $n; $i++)
    {
        if ( !is_array( $a[$i] )){
            $v = md_parsing_hxx($a[$i]);
            if ($v === false) { 
                if (!empty($a[$i])){
                    if (is_array($a[$i-1])){
                        $v = mb_parsing_arrayitem ( $a[$i-1] );
                        if (mb_parsing_testv_sup($v)) {
                            $k = count($aa) -1;
                            $aa[$k] = mb_parsing_insert_before_tagend($aa[$k], $a[$i]);
                        } else { $v = false;}
                    } 
                if( $v === false ) {
                    array_push( $aa,  tag_p( $a[$i] )); 
                    }        
                }
            }
            else { array_push( $aa, '<'.$v['tag'].'>' );
                   array_push( $aa, $v['s'] );
                   array_push( $aa, ' </'.$v['tag'].'>' );
            }
        } else {
            $v = mb_parsing_arrayitem ( $a[$i] );
            if (mb_parsing_testv_sup($v)){
                $k = count($aa)-1;
                $aa[$k] =  mb_parsing_insert_before_tagend($aa[$k],'<sup>'.$v['s'].'</sup>');
            }
            
        }
    }
    return $aa;
}

            
function mb_parsing_insert_before_tagend($s, $s_clip){
    $j = mb_strrpos($s, '</');
    if ($j > 0 ) { 
                    $sx = mb_substr($s,0,$j);
                    $r =  $sx . $s_clip; 
                    $r .= mb_substr($s,$j); }
    else { $r =  $s . $s_clip; }
    return $r;            
}

function mb_parsing_testv_sup($v){
    if (($v['ch1']=='[') && ($v['ch2']==']') && ($v['e']=='')){ return true;} else {return false;}
}

function mb_parsing_arrayitem($a){
    $r['n'] = $a['n'];
    if ($a['e'] !== ':') {$r['e'] = '';} else {$r['e'] = ':';}
    $r['ch1'] = mb_substr($a['n'],0,1);
    if ($r['ch1'] ==='['){
        $r['ib'] = mb_strpos($a['n'], ']');
        $r['s'] = mb_substr($a['n'],2,$r['ib']-2);
        $r['ch2'] = ']';
    }
    return $r;
}

function md_parsing_hxx($s){
    $s = ' '.trim($s);
    $k = 0;
    if ( stripos($s, ' # ') === 0 ) { $k = 1;}
    else if ( stripos($s, ' ## ') === 0 ) {$k = 2;}
    else if ( stripos($s, ' ### ') === 0 ) {$k = 3;}
    else if ( stripos($s, ' #### ') === 0 ) {$k = 4;}
    else if ( stripos($s, ' ##### ') === 0 ) {$k = 5;}
    if ($k > 0) {
        $r['tag']='h'.$k; $r['s'] = trim (mb_substr($s, $k +2));  
    } else {$r = false;}
    return $r;
}



?>