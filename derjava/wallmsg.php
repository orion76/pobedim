<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

$ax = val_rq('ax', 0);
$ts = val_rq('ts', 0);
$w = val_rq('w', 0);
$m = val_rq('m');
$mode = val_rq('mode');
$fu = current_fu();
$u = current_iu();

if ($w == 0){
$r = new db(DB_POBEDIM, 'select * from w2_wall_msg m where m.ID_MSG_WALL=:m',[':m'=>$m]);
$w = $r->row('ID_WALL');
}
unset($r);

if ($mode!='msgonly'){

    $r = new db(DB_POBEDIM, 'select * from w2_wall w where w.ID_WALL=:w',[':w'=>$w]);
    $v = $r->row('VECHE');
    $p = $r->row('ID_POLL');
    $wu = $r->row('U');
    unset($r);

    if ($v != null) 
    {
        echo htm_redirect1("/derjava/veche.php?iv=$v&w=$w&m=$m#m$m");
        exit;
    }

    if ($p != null) 
    {
        echo htm_redirect1("/derjava/poll.php?qq=$p&w=$w&m=$m#m$m");
        exit;
    }

}

//echo htm_redirect1("/derjava/user_contact.php?$wu&w=$w&m=$m#m$m");

echo htm_redirect1("/derjava/wall.php?w=$w&m=$m#m$m");


