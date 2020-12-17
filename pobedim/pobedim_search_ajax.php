<?php

$dir0 = dirname(__DIR__ );
require_once $dir0 . '/ut.php'; 


// вызывается из /search/index.php
function db_search_count_found(&$params = [ 'SEARCH' =>'' ]){
    $r0 = new db(DB_POBEDIM, "select count(1) as CNT , 'ALL' as L  from W2_USER where lower(NAME_U) containing lower( :sx )"
            ,[':sx' => $params['SEARCH']]);
    return $r0;
}

// вызывается из /search/index.php
function db_search_select_item_label(&$row){
    return '  Найдено '.$row['CNT'] .' пользователей' ;
}

// вызывается из /search/found.php
function db_search_select_found(&$params ){
    $r0 = new db(DB_POBEDIM, "select * from W2_USER_SX_L ( :sx )"
            ,[':sx' => $params['SEARCH']]);
    $r0->filter=function($row){
        $html = null;
        return tag_user($html, $row['U']);
    };
    unset($r);
    
    $r['.div-text'] = tag_div( $r0->printf() , a_class( 'u64' ));
    $r['.div-subtitle']= 'В результат поиска могут временно не попадать пользователи, зарегистрировавшиеся до 15.04.2019.';    
    return $r;
}

 
