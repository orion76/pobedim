<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/search/found.php';

$search = val_rq('search');
$script_search = val_rq('script_search');

require_once $dir0. $script_search;


$ax = val_rq('ax');  // действие вывода
$ax = val_rq('bx');  // действие манипуляции с данныси
$cx = val_rq('cx');  // конфигурация поиска

// получим идентификатор поиска
if (!isset($_SESSION['C_SEARCH'])) { $c_search = 0;} else {$c_search = $_SESSION['C_SEARCH']; }
$c_search++; $_SESSION['C_SEARCH'] = $c_search;

// в зависимости от cx  выбираем  запрос к БД
/*
 *  задача поиска получить список страниц результата
 *  сами страницы результата хранятся во временных файлах и $_SESSION['SEARCH'][i]
 */

//  предположим у нас получилось три страницы результата



$v = [  'SEARCH' =>$search  , 'SCRIPT'=> $script_search ];

// вызываем функцию из скрипта поиска  определённую в файле $script_search
$r1 = db_search_count_found($v);


// это мы выдадим пользователю
$found_search['.div-found'] = $r1->printf( function($row, $lp){

    $i =  $_SESSION['C_SEARCH'];
    $_SESSION['C_SEARCH'] = $i+1;
    $row['SEARCH'] = $lp['SEARCH']; 
    $row['SCRIPT'] = $lp['SCRIPT'];
    $_SESSION['SEARCH'][$i] = $row;
    
    return tag_a_ajax('/search/found.php?'.$i, 
            db_search_select_item_label($row).BR1);
    
} , $v );

$v = $_SESSION['SEARCH'][$c_search];
// вызываем функцию из скрипта поиска  определённую в файле $script_search
//text_found_search2($v, $found_search );

echo json_encode( $found_search );

