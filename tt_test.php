<?php

require_once 'ut.php';
require_once 'html.php';

$ht = new html();

$ht->data('body', ['BODY'=>'sssssssssssssss']);

echo $ht->build();


/*
$html = array_html();

//$html['attr_body'] = 'onload={alert(1);}';
        
html_tt($html, DIV_ALARM, 'xxxxxxxxxxxxxxxx');
html_tt($html, DIV_TEXT, 'qqqqqqqqqqqq');

echo tag_html2($html, TT_POBEDIM);
 * 
 */