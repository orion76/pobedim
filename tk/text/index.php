<?php

// session_start();

$dir = dirname(__DIR__);
require_once ($dir.'/ut.php');
require_once ($dir.'/tk/db_talk.php');
$cip = get_cfg_ip($_SERVER['REMOTE_ADDR']);

$email = val_sn('email', '');
$iu = val_sn('iu', 0);

function get_cfg_ip($ip) {
    $df = dir_to_file();
    $fip = $df . '\\u\\ip\\_' . str_replace(':', '_', $ip) . '_.ini';
    if (!file_exists($fip)) {
        $cip['ip'] = $ip;
        $cip['ts0'] = date('d.m.Y H:i');
        $cip['type'] = '';
    } else {
        $cip = file_ini_read($fip);
    }
    $cip['ts'] = date('d.m.Y H:i');
    $cip['cnt'] = 1 * ($cip['cnt']) + 1;
    if (empty($cip['host'])) {
        $cip['host'] = gethostbyaddr($ip);
    }
    file_put_contents($fip, ini_from_array($cip));
    return $cip;
}

$ax = val_rq('ax');

$ip = str_replace(':', '_', $_SERVER['REMOTE_ADDR']);
$pf = '_' . $ip . '_.htm'; // iif($email === '', '_'. $ip.'_' , $email).'.htm';

$md = val_rq('md', '');
$bnf = basename($md);
$znf = substr($bnf, 0, strpos($bnf, '.', strlen($bnf) - 4));
$df = dir_to_file();
$pd = $df . '\\text\\';
$srv = $_SERVER['SERVER_NAME'];

if (empty($md)) {

    $k = strpos($srv, '.kotabl.ru');
    if ($k > 3) {
        $md = substr($srv, 0, $k);
        $bnf = $md;
    }
    echo $md;
}

// если ссылка не полная, то пытаемся найти нужный файл 
if ($md !== '') {
    if (!file_exists($pd . $bnf)) {
        $bnf = substr($bnf, 0, 10);
        $a = directoryToArray($pd, false);
        foreach ($a as $f) {
            if ((strpos($f, '.md') > 1) && (strpos($f, $bnf) !== false)) {
                $md = $f;
                $bnf = basename($md);
                $znf = substr($bnf, 0, strpos($bnf, '.', strlen($bnf) - 4));
                break;
            }
        }
    }
}
$daf = $pd . $znf;


unset($c);

if (!empty($md)) {
    $caf = $df . '\\text\\' . $znf . '.ini';

    if (file_exists($caf)) {
        $c = file_ini_read($caf);
    }


    $tk = $c['id'];
    
    
    echo htm_redirect1('http://djuga.ru/'.$tk);
    
    
    exit;
}




// --------------------------------------------------------------------------------
//---  список статей
$lf = directoryToArray($df . '/text/', false);

$aa = array();
foreach ($lf as $f) {
    unset($c);
    if (strpos($f, '.md') !== false) {
        $n = substr($f, 0, strpos($f, '.', strlen($f) - 4));
        $caf = $df . '\\text\\' . $n . '.ini';
        if (file_exists($caf)) {
            $c = file_ini_read($caf);
        } else {
            $c['t'] = $f;
            file_put_contents($caf, ini_from_array($c));
        }
        array_push($aa, array('f' => $f, 'c' => $c, 't' => $c['t']));
    }
}

function li_content(&$aa, $v) {
    $s = '';
    foreach ($aa as $a)
        if ($v == $a['c']['v']) {
            $s .= tag_li(tag_a('text/index.php?md=/text/' . $a['f'], iif(empty($a['t']), $a['f'], $a['t']))
                    . iif(!empty($a['c']['cnt']), ' `' . $a['c']['cnt'])
            );
        }
    return $s;
}

$s = '';

usort($aa, function($x, $y) {
    $x = $x['c']['n'];
    if ($x == null)
        $x = 999;
    $y = $y['c']['n'];
    if ($y == null)
        $y = 999;
    return ($x > $y);
});


$htm_talks = '';// talks_events();

$htm = tag_div( 'Смена власти' . tag_('ul', li_content($aa, 1))
        . BR1 . 'Идеология' . tag_('ul', li_content($aa, 2))
        . BR1 . 'История' . tag_('ul', li_content($aa, 3))
        . BR1 . 'Понтия' . tag_('ul', li_content($aa, 4))
        . BR1 . 'Экономика' . tag_('ul', li_content($aa, 5))
        . BR1 . 'прочие' . tag_('ul', li_content($aa, null))
        . BR1 . 'архив' . tag_('ul', li_content($aa, 9))
        , a_style( 'vertical-align:top;'. CSS_INLINEBLOCK.'width:50%;' ));
        


$fbu = $_SESSION['fbu'];

echo tag_html(' ' . tag_('img', null, a_('src', $fbu['picture']['data']['url'])) . BR1
        . tag_p(tag_a('http://djuga.ru/', tag_('b', 'Концепция построения народного государства в России')), 'align="right"')
        . BR1 . tag_('h1', 'Статьи')
        . cmenu_current_email('','','','','cmenu_rtl') . $htm
        );



