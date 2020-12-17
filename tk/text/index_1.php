<?php

// session_start();

$dir = dirname(__DIR__);
require_once ($dir.'/ut.php');
require_once ($dir.'/tk/db_talk.php');
$cip = get_cfg_ip($_SERVER['REMOTE_ADDR']);

// если поисковик, то автоматом входить не надо 
if (strpos($cip['host'], 'yandex') !== false) {
    $_SESSION['fb_autologin'] = 0;
} else {
    // if (!isset($_SESSION['fbu'])) { $_SESSION['fb_autologin'] = 1; }
}


require_once ($dir.'/auth/auth_fb.php');
if (facebook_autologin()) {
    exit;
}

 

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
//if ($email !== '') { $k = strpos($email,'@'); if ($k > 0) { $ip = substr($email,0,$k);}}

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
    if (!file_exists($daf)) {
        mkdir($daf, 0777, true);
    }
    $caf = $df . '\\text\\' . $znf . '.ini';

    $c['fb'] = '';

    if (file_exists($caf)) {
        $c = file_ini_read($caf);
    }


    $lfb = '';
    $aa = array_keys($c);
    foreach ($aa as $a)
        if (strpos($a, 'fb') !== false) {
            $fb = trim($c[$a]);
            $i = strpos($fb, 'http');
            if ($i > 1) {
                $s = substr($fb, 0, $i);
                $fb = substr($fb, $i);
            } else {
                $s = '';
            }
            if (!empty($fb))
                $lfb .= tag_li_a($fb, $s . tag_('img', null, a_('src', '/16/fb.jpg')), a_target('fb'));
        }
    if ($lfb !== '') {
        $lfb = NBSP2 . BR1 . ' Прокомментировать статью можно в группе на facebook '
                . tag_('ul', $lfb, a_style('-display:inline-block;'));
    }


    $l = '';
    if (strpos($srv, '.kotabl.ru') > 1) {
        $l = 'http://kotabl.ru/';
    }

    $htm1 = tag_a($l . 'text/', 'другие статьи', a_target('_top')
                    . a_style('
                            background-color: navy;
                            color:white;
                            font-size: 14px;
                            padding: 15px;')
            ) . BR1;

    $cf = $daf . '/' . $pf;
    $nf = $pd . $bnf;


    // читать файл комментария
    if (file_exists($cf)) {
        $ccf = @file_get_contents($cf);
    } else {
        $ccf = 'ip=' . $ip . chr(13) . chr(10) . ' ';
        file_put_contents($cf, $ccf);
    }

    $a2 = array();
    $aa = array();
    $lu = array(); // перечень пользователей в переписке

    $a3 = directoryToArray($df . '/text/' . $znf, false);


/*    
    if (is_numeric(mb_substr($znf, 3, 5))) {
    $c['id'] = mb_substr($znf, 3, 5); // временное решение
    }
*/    
    $tk = $c['id'];
    
    
    echo htm_redirect1('http://djuga.ru/'.$tk);
    exit;
    
    
    $ntk = $c['t'];
    
    $c['cnt'] = count($a3);
    $c['ds'] = date("d.m.Y");
    $c['ip'] = $ip;

    if (empty($c['title'])) {
        $c['title'] = $ntk;
    }
    if (empty($c['description'])) {
        $c['description'] = $ntk;
    }
    
    file_put_contents($caf, ini_from_array($c));
    
    $r2sn = talk_init($tk,$ntk, $md);

    $htm1 .= BR1 . '  ` ' . count($a3);
  

    /*    
    foreach ($aa as $a) {
        $htm2 .= tag_('ul',  $a['text']
                , a_class('comments_text'));
    }
*/
    
    $lu = array_unique($lu);
    $lhead = [tag_link_css('text/github.css')];
    array_push($lhead, tag_('title', $c['title']));
    array_push($lhead, tag_('meta', null, a_name("description") . a_('content', $c['description'])));

    foreach ($lu as $iu) {
        array_push($lhead, tag_link_css('/u/x' . $iu . '.css'));
    }
    
    array_push($lhead, tag_('script', ' ', a_('src', 'core/util.js')));
    array_push($lhead, tag_('script', ' ', a_('src', 'ajax0.js')));
    array_push($lhead, tag_link_css('talk.css'));
    array_push($lhead, tag_('script', ' ', a_('src', 'tk/talk.js')));

    $fbu = $_SESSION['fbu'];
    
    $cmenu =  cmenu_current_email('','','','','cmenu_rtl');
    
    echo tag_html(tag_('body', $cmenu . ' ' . $htm1
                    . tag_div(
                        tag_('h2',' ', a_class('tk_tl'))
                       .tag_div(
                               md_file($nf)   // ++++++++++++++++++++++++++
                                , a_class("tk_text")
                            ) , a_class('tk_pn'). a_('tk',$c['id'])
                        )
                    . BR1 . tag_('p', tag_a('http://djuga.ru/djuga/djuga.php', tag_('b', 'Концепция построения народного государства в России')), 'align="right"')
                    . $htm2
                    . $lfb
                    . tag_('img', null, a_('src', $fbu['picture']['data']['url']))
                    , a_('onload', 'tk_body_loaded(event)'))
            , $lhead);
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
        
    //    .tag_div( tag_('h3','Комментарии к статьям').  $htm_talks['HTM'] ,a_style( CSS_INLINEBLOCK.'width:50%;' ));



$fbu = $_SESSION['fbu'];

echo tag_html(' ' . tag_('img', null, a_('src', $fbu['picture']['data']['url'])) . BR1
        . tag_p(tag_a('http://djuga.ru/djuga/djuga.php', tag_('b', 'Концепция построения народного государства в России')), 'align="right"')
        . BR1 . tag_('h1', 'Статьи')
        . cmenu_current_email('','','','','cmenu_rtl') . $htm
        );


