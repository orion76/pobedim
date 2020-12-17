<?php

/*
  смотри пример  pobedim_search_ajax.php
 */

    $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
    require_once $dir0 . '/ut.php';
    
    if (count($_GET) === 1) {
        $index_search = array_keys($_GET)[0];
        $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
        $v = $_SESSION['SEARCH'][$index_search];
        $nf = $v['SCRIPT'];
        if(file_exists($dir0.$nf)){
           require_once $dir0.$nf;
           $r = db_search_select_found($v);
           echo json_encode($r);
        } else {
        $r['.div-text']='TEXT ';
        $r['.div-subtitle']='----SUBTITLE--- ';
        echo json_encode($r);
        }
    } 
    
    
    
    /*
    
    
    
    function get_found_search_params(){
        $index_search = array_keys($_GET)[0];
        return $_SESSION['SEARCH'][$index_search];
    }
    
    function text_found_search( $index_search ){
        $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
        $v = $_SESSION['SEARCH'][$index_search];
        $nf = $v['SCRIPT'];
        if(file_exists($dir0.$nf)){
            unset($return_text_found_search); 
           return   require_once $dir0.$nf;
           // return json_encode($return_text_found_search);
        } else {
        $r['.div-text']='TEXT '.$_SESSION['SEARCH'][$index_search]['T'];
        $r['.div-subtitle']='----SUBTITLE--- '.$_SESSION['SEARCH'][$index_search]['T'];
        return json_encode($r);
        }
    }
    
    
    function text_found_search2( $v , &$r){
        $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
        $nf = $v['SCRIPT'];
        if(file_exists($dir0.$nf)){
            unset($return_text_found_search); 
            require_once $dir0.$nf;
        } else {
        $r['.div-text']='TEXT '.$_SESSION['SEARCH'][$index_search]['T'];
        $r['.div-subtitle']='----SUBTITLE--- '.$_SESSION['SEARCH'][$index_search]['T'];
        }
    }
    */