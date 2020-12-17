<?php

   require_once   __DIR__. '/ut.php';
   
   
   
   
$scripts = '';

function get_scripts() {
    return get_scripts2('', '');
}

// don't use

function get_scripts2($dir, $scripts, $list_js = '') {
    if ($scripts == '') {

        /*
          $r = @fopen("http://code.jquery.com/jquery-1.11.3.min.js", "r ");
          if ($r){$sj = '<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>'; @fclose($r); }
          else {$sj = '<script type="text/javascript" src="'.$dir.'jquery-1.11.3.min.js" ></script>';}
         */

        $sj = iif(stripos($list_js, 's') !== false, '<script type="text/javascript" src="' . $dir . 'jquery-1.11.3.min.js" ></script>');

        $sj = $sj . '<script src="' . $dir . 'json2.js"></script>'
                . iif(stripos($list_js, 'file') !== false, '<link href="' . $dir . 'fileuploader.css" rel="stylesheet" type="text/css" /> 
					 <script src="' . $dir . 'fileuploader.js" type="text/javascript" ></script>');

        $fjs = dir_main() . 'core';
        $dirlist = directoryToArray($fjs, false);

        foreach ($dirlist as $fn) {
            if ($list_js == '' || stripos(';s0.js;util.js;droplist.js;' . $list_js, $fn)) {
                $v = '?' . filemtime($fjs . '\\' . $fn);
                $s1 = ' async';
                if (stripos(';s0.js;util.js;droplist.js;', $fn)) {
                    $s1 = '';
                }
                $sj = $sj . '<script type="text/javascript" src="' . $dir . 'core/' . $fn . $v . '" ' . $s1 . '></script>';
            }
        }

        $scripts = $sj;
    } else {
        $sj = $scripts;
    }
    return $sj;
}





function build_page($xbody) {
    return build_page2('', '<body>' . $xbody . '</body>', '');
}

function build_page2($dir, $xbody, $xheaders) {
    setlocale(LC_ALL, "ru_RU.UTF-8");
    header('Content-type: text/html; charset=UTF-8');

    return
            '<!DOCTYPE html >
        <html>
		<head><link href="' . $dir . 'intf.css" rel="stylesheet" type="text/css" />
              <link href="' . $dir . '/css/cmenu.css" rel="stylesheet" type="text/css" />' . get_scripts2($dir, '') . $xheaders
            . '</head>' . $xbody . '</html>';
}

function build_page3($xbody, $xheaders = '', $list_js = '') {
    $dir = dir_main() . '\\';
    $v = '?' . @filemtime($dir . 'intf.css');

    if (stripos(substr($xbody, 0, 20), '<BODY') === false) {
        $xbody = tag_('body', $xbody);
    }

    return tag_html($xbody, iif(stripos($xheaders, '<base') === false, '<base href="../" />')
            . tag_link("text/css", '/css/cmenu.css' . $v, a_('rel', "stylesheet"))
            . tag_link("text/css", 'intf.css' . $v, a_('rel', "stylesheet"))
            . get_scripts2('', '', $list_js) . $xheaders);
}

function home_img_scroll() {
    $dirprj = $_SESSION['DIR'];
    $dir = __DIR__ . '\\' . $dirprj . '\\img_scroll\\';
    $dirlist = directoryToArray($dir, true);
    $s = '<div id="scroll_img">';
    $s1 = '';
    if (count($dirlist) > 0) {
        foreach ($dirlist as $fn) {
            $k = stripos($fn, '.tmp');
            if ($k < strlen($fn) - 6 || !$k) {
                $s = $s . '<img src="' . $dirprj . '/img_scroll/' . $fn . '" alt="' . $fn . '" ' . $s1 . ' onclick="s00click_scrollimg(event)"/>';
                $s1 = 'style="display:none"';
            }
        }
    }
    $s = $s . '</div>';
    unset($dirlist);
    return ($s);
}





function get_news($xcntnews) {
    $q = "select * from W1_SITENEWS_L2 ( :XCNT_NEWS, :XLG_SITE )";
    $pa = array(':XCNT_NEWS' => $xcntnews, ':XLG_SITE' => $_SESSION['LG_SITE']);
    $r = db_get($q, $pa);
    $rs = $r['DS'];

    if ($r['ROW_COUNT'] == 0) {
        return '';
    } else {
        $s = '';
        foreach ($rs as $row) {
            $s = $s . '<tr><td class="ds"><small>' . $row['SYS_T'] . '</small></td><td>' . $row['TITLE_NEWS'] . '</td><td>' . $row['TEXT_NEWS'] . '</td></tr>';
        }
        if ($xcntnews < 20) {
            $sa = '<a href="javascript:s0_menu(0,\'m0_sitenews.php\')">архив новостей</a>';
        } else {
            $sa = '';
        }
        $s = '<table border="0"><tr><td STYLE="font-size:10pt;color:green;" align="left">Информация:</td><td align="right" STYLE="font-size:8pt;">' . $sa . '</td></tr>
	                        <tr><td colspan="2"><table class="newstable">' . $s . '</table></td></tr></table>';
        return $s;
    }
}

function get_news1($xcntnews) {
    $q = "select * from W1_SITENEWS_L2 ( :XCNT_NEWS, :XLG_SITE )";
    $pa = array(':XCNT_NEWS' => $xcntnews, ':XLG_SITE' => $_SESSION['LG_SITE']);
    $r = db_get($q, $pa);
    $rs = $r['DS'];

    if ($r['ROW_COUNT'] == 0) {
        return '';
    } else {
        $s = '';
        foreach ($rs as $row) {
            $s = $s . '<li><span class="ds"><small>' . substr($row['SYS_T'], 1, 10) . '</small></span> <br/>' . $row['TEXT_NEWS'] . '</li>';
        }
        $s = 'Новости:<br/><ul>' . $s . '</ul>';
        return $s;
    }
}



function __working_period() {

    $sid = get_sid();
    $q = "select DATE_SESSION, LIST_UR from S1_SESSION_S1 (:XSESSION )";
    $pa = array(':XSESSION' => $sid);
    $r = db_get($q, $pa);

    if ($r['DS'][0]['DATE_SESSION'] == null) {
        $d = time() - 5 * 60 * 60;
    } else {
        $d = strtotime($r['DS'][0]['DATE_SESSION']);
    }

    $ula = val_sn('ULA');

    return '
<span id="m0w" style="display:inline-block">
	<span><img id="img0user" src="' . $_SESSION['IMG_LOGO'] . '" /></span>
	<span>
				<span id="wd"> 
					Дата <input class="N01" id="i0d" type="text" value="' . date("d.m.Y", $d) . '" size="8" onchange="s0_curdate_change(event)" />
				</span>
' . iif($ula > 0, '
		        <span id="wp">
		        рабочий период' . cmenu(
                            tag_li(tag_a('javascript:s0_yesterday_a(0)', 'сегодня'))
                            . tag_li(tag_a('javascript:s0_yesterday_a(-1)', 'вчера'))
                            . tag_li(tag_a('javascript:s0_yesterday_a(-2)', 'позавчера'))
                            . tag_li(tag_a('javascript:s0_yesterday_m(0)', 'текущий месяц'))
                            . tag_li(tag_a('javascript:s0_yesterday_m(-1)', 'предшествующий месяц'))
                            . tag_li(tag_a('javascript:s0_yesterday_m(-2)', 'позапрошлый месяц'))
                    )
                    . '
        <input
        id="i0bp" type="text" value="' . date("d.m.Y", time() - 5 * 60 * 60) . '"  size="8" ondblclick="javascript:i0bp_dblclick_v(this.value)"
        />
        - <input 
        id="i0ep" type="text" value="' . date("d.m.Y") . '" size="8" ondblclick="javascript:i0ep_dblclick_v( this.value)"
        />
        </span>')
            . '</span>
</span>';
}


function page($xpage) {
    return 'core/' . $xpage;
}

function cdir($site) {
    $cdir = getcwd();
    if (!stripos($cdir, $site)) {
        $cdir = $site . "/";
    } else {
        $cdir = "";
    }
    return $cdir;
}
