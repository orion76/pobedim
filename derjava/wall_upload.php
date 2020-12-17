<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';


$ax = val_rq('ax');
$u = current_iu();
$m = val_rq('m');


switch ($ax) {
    case 'w_file':
        $r = php_uploadfile( $dir0. "/w/$m/");
        $nf = '/'. $r['NF'];
        $r['NF'] = substr($nf, strrpos($nf,'/')+1);
        echo htmlspecialchars(json_encode($r), ENT_NOQUOTES);
        //echo $r['NF'];
        break;
}

