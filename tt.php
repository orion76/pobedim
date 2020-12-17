<?php

/*

 /css/tt.html  -  отладочный шаблон
 /css/tt.css  -   шаблон
 
div-hmenu 
div-fmenu 

div-header 
div-footer 
        
div-title         
div-content
div-search
div-found
        
div-subtitle
div-text

 */


const DIV_HMENU = 'div-hmenu';
//const DIV_CMENU = 'div-cmenu';
const DIV_FMENU = 'div-fmenu'; 
const DIV_HEADER = 'div-header'; 
const DIV_FOOTER = 'div-footer'; 
const DIV_TITLE = 'div-title';         
const DIV_CONTENT = 'div-content';
const DIV_SEARCH = 'div-search';
const DIV_FOUND = 'div-found';
const DIV_SUBTITLE = 'div-subtitle';
const DIV_TEXT = 'div-text';
const DIV_ALARM = 'div-alarm';



function tt_set_content($tt, $class_div, $content_div){
    $i = strpos($tt,$class_div);
    if ($i === false) { return $tt; }
    $j = strrpos($tt,'<div ',$i);
    if ($i >0) {
        $i = strpos( $tt, '>', $i);
        $j = strpos( $tt, '</div>', $i);
    }
    return substr($tt,0,$i+1). $content_div . substr($tt,$j);
}       

/*
пример использования шаблона

$dir0 = __DIR__ ;
$t = @file_get_contents("$dir0/css/tt.html");


$t = tt_set_content($t, 'div-title', 'SaaaaaaaaaaaaaaaaaY');
$t = tt_set_content($t, 'div-content', 'RcccccccccccccccccccU');

echo $t;

*/