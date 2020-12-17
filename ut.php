<?php

const DB_USER = 0;
const DB_COOP = 3;
const DB_KOTABL = 1;
const DB_POBEDIM = 2;

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/server/k62.php';
require_once $dir0 . '/auth/auth.php';

@session_start();



function save_geo_data($ip_host){
    $r = new db(DB_USER,'select * from w0_host where ip_host=:ip',[':ip'=>$ip_host]);
    $geo = $r->row('JSON_GEO_HOST');
    $geo0['city_host'] = $r->row('CITY_HOST');
    $geo0['region_host'] = $r->row('REGION_HOST');
    if ( $geo0['city'] == '' ||  $geo0['region_name'] == '')
    {
        if ( $geo == '') {
           // account doleynik@pobedim.su
           $geo = file_get_contents("http://api.ipstack.com/$ip_host?access_key=5db4308c32e1cecf668eead8c000e227&language=ru");
        }
        if ($geo !== false) {
            
            $geo0 = json_decode($geo, true);
            if ($geo0 == null ) $geo0=[];

            $geo0['city_host'] = $geo0['city'];
            $geo0['region_host'] = $geo0['region_name'];
            
            $r = new db(DB_USER,'update or insert into w0_host (ip_host, json_geo_host, city_host,REGION_HOST) values (:ip,:t, :city,:region)'
                        ,[':ip'=>$ip_host 
                          ,':city'=>va($geo0,'city')
                          ,':region'=>va($geo0,'region_name')
                          ,':t'=>$geo]);
        }
    }
    return $geo0;
}


function image_thumb_save($src,$dst,$w_dst,$h_dst){
        $im = new Imagick_();
        $im->readImage($src);
        $im->thumbnailImage($w_dst,$h_dst);
        $im->writeImage($dst);
}

class Imagick_ {
   public $im;
   public $size;
   public  function pingImage($tf) { 
       return file_exists($tf); 
   }
   public function readImageBlob( $response ){
      $this->im = @imagecreatefromstring($response); 
      $this->size=@getimagesizefromstring($response);  
   }
   public function readImage($nf) {
       $response = @file_get_contents( $nf );
       $this->readImageBlob( $response );
   }
   public function thumbnailImage($w64, $h64, $_true = true){
       /*
        $w=$this->size[0]; $h=$this->size[1]; $x = 0; $y=0;

        $img = @imagecreatetruecolor($w64, $h64);
        $white = @imagecolorallocate($img, 0xFF, 0xFF, 0xFF);
        //$white = @imagecolorallocate($img, 100, 100, 100);
        @imagefilledrectangle($img, 0, 0, $w64 - 1, $h64 - 1, $white );
        
                if ($w < $h * 0.8  && $w > 100 ) {
                    $x = round( 0.1*$w ); 
                    if (($h-$w)/2 -10 > 0) $y = round(($h-$w)/2 -10);   
                    $w = round(0.8*$w); 
                }
                
                if ($h < $w * 0.8  && $h > 100 ) {
                    $y = round( 0.1*$h ); 
                    if (($w-$h)/2 -10 > 0) $x = round(($w-$h)/2 -10);   
                    $h = round(0.8*$h);
                    $w = $h;
                }
*/
        //@imagecopyresampled($img,$this->im,0,0,$x,$y, $w64,$h64,$w,$w);
        //@imagecopyresized ( $img, $this->im ,0 , 0 , $x , $y , $w64,$h64 , $w , $w );
        $this->im =@imagescale ( $this->im , $w64, $h64 );
        //@imagedestroy($this->im);
        //$this->im = $img;
        //@imagedestroy($img);
   }
   
   public function writeImage($nf){
       if (strrpos($nf, '.jpg') !== false)
            @imagejpeg($this->im,$nf);
       else 
            @imagepng($this->im,$nf);
   }
   public function destroy(): bool {  @imagedestroy($this->im);  }
   function __construct(){
        $im = null;    
   }
}




/*
$nsn = session_name();
//session_status() === PHP_SESSION_ACTIVE

if (isset($_COOKIE[$nsn])) {

    $snu = $_COOKIE[ $nsn ];
    session_commit();
    session_id($snu);
    $ssn = session_status();
   // if ( $ssn !== PHP_SESSION_ACTIVE) {
        session_start();
        $ssn = session_status();
        $sn = session_id();
  //  }

    $_SESSION['sn'] = $snu;
    $sn = $_SESSION['sn'];

    $u = current_iu();
    //  $sn = session_id();
} else {
    if (count($_SESSION) === 0) {
      //  session_start();
      //  db2_get_sn2();
        $sn = '';
    }
}
*/

$_SESSION['ROOT']= str_replace('derzhava.su', 'держава.сайт', f_host());
        
function va(&$a,$nv,$v_default=''){ if (isset($a[$nv])) return trim($a[$nv]); else return $v_default; }
function va_empty(&$a,$nv){ return (empty( va($a,$nv) )); }
function va0(&$a,$nv,$v_default=''){ return va($a[0],$nv,$v_default); }
//function v($nv, $a=null){ if ($a === null) return val_rq ($nv); else return va($a,$nv);}


function str_trunc($s, $len=250, $quote='`'){
    if (strlen($s) > 250) $s =  ' '.substr( $s,0, $len); else return trim($s);
    if ($quote=='"') return trim(substr($s, 0, strrpos($s,' ')));
    else  return trim(str_replace('"', '`', substr($s, 0, strrpos($s,' ')))); 
}



function check_session() {
    if (empty(session_id())) {
        echo 'bad session id';
    }
}

//--------------------------------------




function social_share(&$html, $url, $content = '', $imgsrc = '') {

    if (!empty($content)) {
        html_tt($html, 'head', '<meta property="og:title" content="' . $content . ' " />');
    }
    if (!empty($imgsrc)) {
        html_tt($html, 'head', '<meta property="og:image" content="' . f_host($imgsrc) . ' " />');
    }
    //  <meta property="vk:image"  content="' . $src . '" />'

    $href = urlencode(f_host() . $url);
    return
            tag_a('https://www.facebook.com/sharer/sharer.php?u=' . $href, tag_img('/auth/64-fb.png'), a_target('_blank'))
            . tag_a('https://vk.com/share.php?url=' . $href, tag_img('/auth/64-vk.png'), a_target('_blank'))
            . tag_a('https://connect.ok.ru/offer?url=' . $href, tag_img('/auth/64-ok.png'), a_target('_blank'))
    ;
}

const EX_POBEDIM = 'pobedim';
const EX_DERZHAVA = 'derzhava';
const EX_DERJAVA = 'derjava';

/* процедура вызывается из array_html() */

function make_href_login($rx) {
    $rx = 'rx=' . urlencode($_SERVER['PHP_SELF'] . '?' . $rx);
    $href = f_host('/derzhava/login-derzhava.php?') . $rx;
    return $href;
}

function tag_a_derzhava_login($rx, $txt = 'Вход / регистрация') {
    return tag_a('/derzhava/login-derzhava.php?rx=' . $rx
            , $txt, a_class('login-derzhava'));
}

function init_enviroment(&$html, $ex0 = '') {
    /* ex - параметр GET запроса, инициирующий проект */
    if ($ex0 === '-') {
        return;
    }

    $ex = val_rq('ex', $ex0);
    if (!empty($ex) || !isset($_SESSION['ex'])) {
        $_SESSION['ex'] = $ex;
    }

    if ($_SESSION['ex'] === EX_DERZHAVA) {
        html_tt($html, 'head_ex', tag_link_css('/derzhava/index.css'));
    }  if ($_SESSION['ex'] === EX_DERJAVA) {
        html_tt($html, 'head_ex', tag_link_css('/derjava/derjava.css'));
    }
    else {
        html_tt($html, 'head_ex', tag_link_css('/pobedim/index.css'));
    }  
}

function tag_form_search($script_search, $label_search = 'Поиск', $ajax_search = 'ajax_form_submit_custom(event)') {
    return $label_search . BR1 . tag_form('/search/?'
                    , tag_input0('search', '', a_placeholder('искомая строка'))
                    . tag_hidden('script_search', $script_search)
                    . tag_submit('Найти !')
                    , a_onsubmit($ajax_search)
    );
}

const TT_DEFAULT = '/css/tt.html';
const TT_NSSP = '/css/tt.html';
const TT_POBEDIM = '/css/tt_pobedim.html';
const TT_DERZHAVA = '/derzhava/tt_derzhava.html';

require_once 'tt.php';

// sn vars are cleaned with  tag_html2()
const SN_HTML_PATH = 'HTML_PATH';   // path from base  begins / and ends /
const SN_HTML_ROOT = 'HTML_ROOT';     // dir to root without ending /
const SN_HTML_BASE = 'HTML_BASE';   // from site's root to  path
const IU_NOBODY =  1000;  // неавторизированный пользователь
function is_nobody($u){ return ($u == '1000'); } 

function tag_link_css2($heads_, $href = '') {
    $heads = '';
    if (is_array($heads_)) {
        foreach ($heads_ as $head) {
            $heads .= $head;
        }
    }


    if (empty($href)) {
        return '';
    }

    $nf = '';
    $dbg = f_host();
    $href0 = f_host() . f_base();
    if (strpos($href, $href0) === 0) {
        $href = substr($href, strlen($href0));
    }

    if (strpos($href, 'http') !== 0) {
        $pu = parse_url($href);

        if (strpos($href, '/') === 0) {
            $nf = f_root() . $href;
            $href = f_host(f_ref($pu['path'], FR_PATHNAME));
        } else {
            $nf = f_root() . f_base() . f_ref($pu['path'], FR_PATHNAME);
            $href = f_host(f_base() . f_ref($pu['path'], FR_PATHNAME));
        }
        $sz = @filesize($nf);
        if (!isset($pu['query'])) {
            $pu['query'] = '';
        }
        if ($sz !== false) {
            $href = $href . iif(empty($pu['query']), '?', '?' . $pu['query'] . '&') . 'sz=' . $sz;
        } else {
            return '<!-- ' . $href . ' -->';
        }
    }
    $href0 = substr($href . '?', 0, strpos($href, '?'));
    if (empty($href0)) {
        $href0 = $href;
    }
    if (strpos($heads, $href0) > 0) {
        return '';
    }
    return '<link type="text/css" href="' . $href . '" rel="stylesheet" />' . chr(13) . chr(10);
}

const FR_HOST = 1;
const FR_ROOT = 2;
const FR_BASE = 3;
const FR_PATH = 4;
const FR_DIRPATH = 5;
const FR_DIR = 6;
const FR_PATHSCRIPT = 7;
const FR_NAME = 8;
const FR_SCRIPT = 9;
const FR_PATHNAME = 10;

function f_ref($ref, $t_ref) {
    $pu = parse_url($ref);
    switch ($t_ref) {
        case 1:
        case FR_HOST:
            if (strpos($ref, 'http') !== 0) {
                
                $srv = $_SERVER['HTTP_HOST'];
                //$srv = strtolower( $_SERVER['SERVER_NAME'] );
                if ($srv === 'xn--80aafgfg3e.xn--80aswg') $srv = 'держава.сайт';
                if ($srv === 'derzhava.su') $srv = 'держава.сайт';
                //if ($srv === 'pobedim.su') $srv = 'pobedim.su';
                if (strpos($srv, 'pobedim.su') > 0) $srv = 'pobedim.su'; 
                if (strpos($srv, '/') === strlen($srv)) {
                    $srv = substr($srv, 0, strlen($srv) - 1);
                }
                if (empty($ref)) {
                    return protocol_srv($srv) . $srv;
                }
                if (strpos($ref, '/') !== 0) {
                    $ref = '/' . $ref;
                }
                $ref = protocol_srv($srv) . $srv . $ref;
            }
            return $ref;
            break;
        case FR_DIR: return f_dir($ref);
            break;
        case FR_NAME: $k = strrpos($ref, '/');
            if ($k !== false) {
                $ref = substr($ref, $k + 1);
            }
            break;
        case FR_PATHNAME:
            $ref = $pu['path'];
            if (strpos($ref, '/') === 0) {
                $ref = substr($ref, 1);
            }
            break;
        case FR_PATHSCRIPT:
            if (strpos($ref, '/') === 0) {
                $ref = substr($ref, 1);
            }
            break;
    }
    return $ref;
}

function f_host($path = '') {
    $r = f_ref($path, 1 );
    return $r;
}

function f_dir($dir = '') {
    if (empty($dir)) {
        $dir = f_root() . f_base();
    } else {
        $ch = substr(' ' . $dir, strlen($dir), 1);
        if (($ch == '/') || ($ch == '\\')) {
            $dir = substr($dir, 0, strlen($dir) - 1);
        }
    }
    return $dir;
}

function f_script($q = '') {
    return f_host() . $_SERVER['SCRIPT_NAME'] . iif(!empty($q), iif(strpos($q, '?') !== 0, '?') . $q);
}

function f_dirpath() {
    return f_dir() . f_path();
}

function f_root() {   //  =    nnnnn
    // $_SERVER['DOCUMENT_ROOT']
    $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
    if ($dir0 == null) 
        $dir0 = __DIR__;
    return $dir0;
}

function f_base($base = '') {  //    = /nnnnn , _
    if (empty($base)) {
        $base = val_sn(SN_HTML_BASE, '');
    }
    $base = trim(str_replace('\\', '/', $base));
    if (strrpos($base, '/') !== strlen($base) - 1) {
        $base = $base . '/';
    }
    if (strpos($base, '/') !== 0) {
        $base = '/' . $base;
    }
    return $base;
}

function f_path($path = '') {  //   =  /nnnnnn/ , /
    if (empty($path)) {
       $path = dirname($_SERVER['SCRIPT_NAME']);
    }
    $path = str_replace(['//', '\\\\', '\\'], '/', $path . '/');
    if (strpos($path, '/') === 0) {
        $path = substr($path, 1);
    }
    return $path;
}

const BR1 = '<br>'; //  const BR = '<br>';
const NBSP1 = '&nbsp;';
const NBSP2 = '&nbsp;&nbsp;';
const BR2 = '<br><br>';
const CSS_NOWRAP = 'white-space:nowrap;';
const CSS_INLINEBLOCK = 'display:inline-block;';
const CSS_TEXTCENTER = 'text-align:center;';
const CSS_TEXTJUSTIFY = 'text-align:justify;';
const CSS_TEXTRIGHT = 'text-align:right;';
const CSS_TEXTLEFT = 'text-align:left;';

function strcut2str($str, $substr, $start = 0) {
    $i = strpos($str, $substr, $start);
    if ($i === false) {
        return $str;
    } else {
        return substr($str, 0, $i);
    }
}

function gen_id() {
    return date('ymdHis');
}

function md_file($nf) {
    $t = @file_get_contents($nf);
    return md_parse($t);
}

function md_parse($text, $pp=[]) {
    if (!empty($text) || isset($text)) {
        require_once ('parsedown.php');
        $Parsedown = new Parsedown();
        $s = $Parsedown->text( $text  );
        $a_target = '_blank'; 
        if (isset($pp['target'])) $a_target = $pp['target']; 
        $i = 0;
        while ($i !== false )
        { 
            $i = strpos($s,'<a ',$i);
            if ($i !== false){
                $j= strpos($s,'>',$i); 
                $sx = substr($s, $i, $j-$i);
                if (stripos($sx,'target')===false && stripos($sx,'href')!==false){
                    $s = substr($s, 0, $j) . ' target="'.$a_target.'" ' . substr($s, $j);
                }
                $i++;
            } else
                break;
        }
    } else $s='';
    return '<div class="md_">'. $s .'</div>';
    // require_once ('md_text.php'); return md_parsing_text($text_md);
}

function yandex_counter() {
    return '';
   
}

function ini_from_array($arr) {
    if (!is_array($arr)) return '';
    $s = chr(13) . chr(10);
    foreach ($arr as $key => $val) {
        if (!is_array($key)){
            $val = str_replace("\r", ' ', $val);
            $val = str_replace("\n", ' ', $val);
            $val = trim($val);
            if(empty($val)) continue;
            $s .= $key . ' = ' .  $val  . ' ' . chr(13) . chr(10);
        }
    }
    return $s;
}

function file_ini_read($nf) {
    $aa = false; // parse_ini_file ($nf);
    $h = fopen($nf, 'r');
    if ($h) {
        $aa = array();
        $s = '';
        while (($c = fgetc($h)) !== false) {
            if (strpos(chr(13) . chr(10), $c) !== false) {

                $k = strpos($s, '=');
                if ($k > 0) {
                    $p = trim(substr($s, 0, $k));
                    $v = trim(mb_substr($s, $k + 1));
                    $aa[$p] = $v;
                }
                $s = '';
            } else {
                $s .= $c;
            }
        }
        fclose($h);
    }
    return $aa;
}

function cmenu_kotabl() {
    return cmenu_current_email('');
}


function ini_to_array($s){
    if (empty($s)) return false;
    
    $s= str_replace("\r", "\n", $s);
    $lines = explode("\n", $s);
    $aa = array();
  
    foreach($lines as $line) {
        $line = trim($line); if (empty($line))  continue;
        $i = strpos($line,'=');
        $v = trim(substr($line, $i+1));
        if (empty($v)) continue;
        $k = trim(substr($line, 0, $i));
        $aa[$k]=$v;
    }
/*    
    if (!file_exists('c:/tmp/u')) mkdir('c:/tmp/u',0777);
    $nf = 'c:/tmp/u/_'.rand().'.txt';
    file_put_contents($nf, $s);
    $aa = file_ini_read($nf);
   
    rename($nf, 'c:/tmp/u/u_'.$aa['iu'].'_'.time().'.txt' );
*/    
    return $aa;
}


function logOn($cf) {  // array_ini из файла авторизации
    if ( va($cf,'iu')=='' ) gen_iu($cf);
    
    unset($r);$r = new db(DB_USER, 'select U_PARENT,U, TEXT_LOGIN from W0_USER where u=:u', [':u'=> $cf['iu']] );
    $s = $r->row('TEXT_LOGIN');
    if (empty($s)) $a = $cf;
    else {
        $a = ini_to_array($s);
        if (is_array($a)) $cf = array_merge($cf, $a);
    }
    $cf['u_parent'] = $r->row('U_PARENT');
    $cf['ip_host'] = $_SERVER['REMOTE_ADDR'];
    
    $aa = save_geo_data($cf['ip_host']);
    $cf['city_host'] = va($aa,'city_host');
    $cf['region_host'] = va($aa,'region_host');
    user_adjust_data($cf);
            
    unset($r);$r = new db(DB_USER, 'update W0_USER set TEXT_LOGIN=:t, TS_LOGIN=current_timestamp, IP_HOST=:ip where u=:u'
            , [':u'=> $cf['iu'], ':t'=>ini_from_array($cf) ,':ip'=>$cf['ip_host'] ] );    
    
    
    
    unset($_SESSION['isn']);
    unset($_SESSION['sn']);
    unset($_SESSION['fbu']);
    unset($_SESSION['vku']);
    session_regenerate_id(true);
    
    $nu = $cf['nu'];
    
    unset($r); $r = new db(DB_POBEDIM, 'select * from w2_user_login2(:u,:up, :nu)', [':u' => $cf['iu'],':up' => $cf['u_parent'], ':nu' => $cf['nu']]);

    // возможно, что есть родительский пользователь, на который стоит перенаправить вход
    if (!empty($cf['u_parent'])){
        $cf['iu'] = $cf['u_parent'];
        $cf['u'] = $cf['u_parent'];
    }

    $_SESSION['iu'] = va($cf,'iu') + 0;
    $_SESSION['email'] = va($cf,'email');
    $_SESSION['nu'] = va($cf,'nu');
    
    session_set_cookie_params(30000000,'/');
    //session_start(['session.cookie_lifetime' => 99999]);
    $sn = session_id();
    //$nsn = session_name();
    //setcookie($nsn, $sn, strtotime('+30 days'), '/');
    $_SESSION['fu'] = $cf;
    $_SESSION['iu'] = $cf['iu'];
    $_SESSION['isUserLogged'] = true;
}

function logOff() {
    unset($_SESSION['rx']);
    unset($_SESSION['kr']);
    session_unset();
    $nsn = session_name();
    setcookie($nsn, '', strtotime('-1 days'), '/');
    session_destroy();
}

function current_nu() {
    return '*current_nu*';
}

function current_iu() {
    //$ssn = session_status();
    //if ( $ssn !== PHP_SESSION_ACTIVE) { return IU_NOBODY; }

    if (!isset($_SESSION['fu'])) {
        $_SESSION['fu'] = array('iu' => 1000 );
        $iu = 1000;
        $_SESSION['isUserLogged'] = false;
    } else {
        $iu = (0 + $_SESSION['fu']['iu']);
    }
    if ($iu == "0") {$iu = 1000; unset($_SESSION['fu']); $_SESSION['isUserLogged'] = false;}
    return $iu;
}

function isUserLoggedOn() {
    $u = current_iu();
    $u= !is_nobody($u);
    return ( $u );
}

function current_fu() {
    $iu = 0 + current_iu();
    if (count($_SESSION['fu']) === 1) {
        $_SESSION['fu'] = get_f_user($iu, 'iu');
    }
    if ($iu === IU_NOBODY) {
        $_SESSION['isUserLogged'] = false;
    } else {
        $_SESSION['isUserLogged'] = true;
        user_set_onlinetime($iu);
    }
    return $_SESSION['fu'];
}

function current_user() {
    return 'ut.current_user is obsolete';
}

function cmenu_lli_djuga($imenu = 0) {
    return
            tag_li_a('/tk/ltku.php?ax=ltk_viewed', 'Просмотренные публикации')
            . tag_li_a('/tk/ltku.php?ax=ltku_own', 'Собственные публикации')
//        .tag_li_a('/tk/lptk.php', 'Документы')
    ;
}

function cmenu_current_djuga() {
    return cmenu_current_email0('', cmenu_lli_djuga(0));
}

function cmenu_current_email($lli = '', $homepage = '', $rx_onEnter = '', $rx_onExit = ''
        , $subclass_style = 'cmenu_rtl') {
    return cmenu_current_email0($lli, '', $homepage, $rx_onEnter, $rx_onExit, $subclass_style);
}

function cmenu_current_email0($lli, $lli0 = '', $homepage = '', $rx_onEnter = '', $rx_onExit = ''
        , $subclass_style = 'cmenu_rtl') {
    if (empty($homepage)) {
        $homepage = 'index.php';
    }

    $iu = current_iu();
    $fu = current_fu();
    $logged = isUserLoggedOn();
    $menu_user = '';
    if ($logged) {
        $menu_user = $fu['nu'];
        if (empty($menu_user)) {
            $menu_user = 'БезИмени' . iif($logged, $iu);
        }
    } else {
        $menu_user = '  Вход  ';
    }

    $spt = $_SERVER['SCRIPT_NAME'];
    $rqt = $_SERVER['QUERY_STRING'];
    if (empty($rx_onEnter)) {
        $rx_onEnter = str_replace('/main/', '/', $spt . '?' . $rqt);
        //$rx_onEnter = f_host().$rqt;
    }

    if (empty($rx_onExit)) {
        $rx_onExit = $rx_onEnter;
    }

    $lli =   $lli0
            . iif($logged
                    , $lli
                    . '<hl>'
                    . tag_li_a('/auth/ajax0.php?ax=log_off&rx=' . urlencode($rx_onExit), 'выход')
                    ,
                    login_tag_li_a_facebook($rx_onEnter)
                    . login_tag_li_a_vkontakte($rx_onEnter)
                    . login_tag_li_a_odnoklassniki($rx_onEnter)
                    . login_tag_li_a_email($rx_onEnter));

    if (!$logged) {
        $cmenu = tag_a_derzhava_login($rx_onEnter);
    } else {
        $cmenu = cmenu($lli, $subclass_style, $menu_user);
    }

    return '<span class="cmenu_current_user">'
            . $cmenu
            . '</span>';
}

function htm_login_proposal($rx = '', $lp = []) {
    $fu = current_fu();
    $iu = $fu['iu'];
    $htm_login = '';
    $_SESSION['rx'] = $rx;
    if (!isUserLoggedOn()) {
        if (strpos($rx, '?') === 0) {
            $rx = $_SERVER['PHP_SELF'] . $rx;
        } else if (empty($rx)) {
            $rx = $_SERVER['PHP_SELF'];
        }

        $fn = __DIR__ . '/auth/ip/' . str_replace(':', '-', $_SERVER['REMOTE_ADDR']) . '.ini';

        $htm_login = tag_div(
                tag_p('Вы на сайте как ' . tag_span('неавторизованный пользователь', a_style('color:red'))
                        . '<br><span>Используйте соцсеть или адрес почты:</span>'
                )
                . tag_ul(login_tag_li_a_facebook($rx)
                        . login_tag_li_a_vkontakte($rx)
                        . login_tag_li_a_odnoklassniki($rx)
                        . login_tag_li_a_email($rx)
                )
                , a_class('login_proposal')
                . a_('title', 'Никаких ваших личных паролей, номеров телефонов и прочего вводить не требуется.При входе по email пароль вам будет выслан автоматически.')
        );
    } else {

        
        // требование дозаполнить профиль
        foreach ($lp as $k) {
            switch ($k) {
                case 'email_notify_confirmed': if (empty($fu[$k])) {
                        $htm_login .= tag_li('укажите/подтвердите адрес для уведомлений');
                    } break;
                case 'city_u': if (empty($fu[$k])) {
                        $htm_login .= tag_li('укажите ближайший город проживания');
                    } break;
            }
        }
        if (!empty($htm_login)) {
            $htm_login = 'Заполните основные настройки в ' . tag_a('/auth/user.php?ax=basic', ' личном кабинете', a_target('lk')) . ':'
                    . tag_ul($htm_login);
        }
        //}
    }

    return tag_div($htm_login, a_style(CSS_TEXTCENTER)); // .'<!-- '.$rx.' -->' ;
}

function login_check($rx = '') {
    $email = val_sn('email', '');

    if (empty($email)) {

        if (empty($rx))
            $rx = protocol_srv() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $s = htm_redirect1('/auth/login.php?rx=' . urlencode($rx));
        echo $s;
        return false;
    }
    $_SESSION['DBU'] = $email;
    return $email;
}

function full_url($path, $query = '', $lp = null) {
    $href = $path;
    if ((stripos($path, 'http://') === false) && (stripos($path, 'https://') === false)) {
        $home = val_sn('home'); // например, http://kotabl.ru
        if ($home != '') {
            $href = $home . '/' . $path;
        }
        $href = str_replace('//', '/', $href);
        $href = str_replace('http:/', 'http://', $href);
        $href = str_replace('https:/', 'https://', $href);
    }
    return $href . $query;
}

function notify_email($mn, $txt) {
    require_once('n/' . $mn);
    notify_by_email($txt);
}

function cmenu($lli, $subclass_style = 'cmenu_ltr', $m = '') {
    $lli = trim($lli);
    if (empty($m)) {
        $m = '...';
    }
    //style="display:inline-block;margin-left:15px;" li
    $s = iif($lli != '', '<ul class="cmenu ' . $subclass_style . '" > <li>' . $m . '<ul><li></li> ' . $lli . '</ul></li> </ul>');
    return $s;
}

function droplist($name_input, $aa, $attr_li = 'onclick="droplist_click0(event)"', $subclass_style = 'cmenu_ltr') {
    $lli = '';
    foreach ($aa as $a) {
        $a = trim($a);
        $lli .= '<li ' . $attr_li . '>' . $a . '</li>';
    }
    $s = iif($lli != '', '<ul class="cmenu ' . $subclass_style . ' droplist" > <li>[]<ul name="' . $name_input . '"> ' . $lli . '</ul></li> </ul>');
    return $s;
}

function popuplist($lli, $subclass_style = 'cmenu_ltr') {
    $lli = trim($lli);
    //style="display:inline-block;margin-left:15px;" li
    $s = iif($lli != '', '<ul class="cmenu ' . $subclass_style . ' popuplist" > <li>**<ul> ' . $lli . '</ul></li> </ul>');
    return $s;
}

function popupform($href, $l_tr_i, $subclass_style = 'cmenu_ltr') {
    $l_tr_i = trim($l_tr_i);
    $s = iif($l_tr_i != '', '<ul class="cmenu ' . $subclass_style . ' popupform" >
                            <li>**<ul><li><form method="post" action="' . $href . '">
                                    <table> ' . $l_tr_i . '</table> '
            . tag_submit()
            . '</form></li></ul>
                            </li></ul>');
    return $s;
}

function dir_main() {
    $s = str_ireplace('/', '\\', __DIR__ . '\\');
    $i = stripos($s, '\\main');
    $s = substr($s, 0, $i) . '\\main\\';
    return $s;
}

function htm_refresh1($ax, $text = '', $q = '', $qx = '') {
    $time = 0;
    if ($text == '') {
        $time = 0;
    } else {
        $time = 10;
    }
    if ($q == '') {
        $q = '&' . $_SERVER['QUERY_STRING'];
    }
    $q = str_ireplace('&ax=', '&xx=', $q);
    $q = str_ireplace('?ax=', '&xx=', $q);
    $s = http_url() . $_SERVER['PHP_SELF'] . '?ax=' . $ax . $q . '&' . $qx;
    $s = '<html><head><meta http-equiv="refresh" content="' . $time . ';url=' . $s . '"></head><body>' . $text . '</body></html>';
    return $s;
}

function htm_refresh($ax, $q = '', $qx = '') {
    if ($q == '') {
        $q = '&' . $_SERVER['QUERY_STRING'];
    }
    $q = str_ireplace('&ax=', '&xx=', $q);
    $q = str_ireplace('?ax=', '&xx=', $q);
    $s = http_url($_SERVER['PHP_SELF'] . '?ax=' . $ax . '&' . $q . '&' . $qx);
    $s = '<html><head><meta http-equiv="refresh" content="0;url=' . $s . '"></head><body></body></html>';
    return $s;
}

function protocol_srv($srv = '') {
    if (!isset($_SERVER['HTTPS'])) {
        return 'http://';
    } else {
        return 'http' . iif($_SERVER['HTTPS'] === 'on', 's') . '://';
    }
}

function http_url($path = '') {
    if (strpos($path, 'http') === 0) {
        return $path;
    } else {
        $srv = $_SERVER['HTTP_HOST'];
        if (empty($path)) {
            return protocol_srv() . $srv;
        }

        if (strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        return protocol_srv() . $srv . $path;
    }
}

function htm_path($newpath, $oldpath = '/kotabl/') {
    if (stripos($newpath, 'http') !== 0) {
        //$srv = strtolower( $_SERVER['SERVER_NAME'] ); $s = $srv . $_SERVER['PHP_SELF'];
        $s = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        
        $i = stripos($s, $oldpath);
        if ($i === false) {
            $i = stripos($s, '/main/');
            if ($i !== false)
                $newpath = protocol_srv() . substr($s, 0, $i + 5) . $newpath;
            else
                $newpath = protocol_srv() . $_SERVER['HTTP_HOST'] . $newpath;
        } else {
            $newpath = protocol_srv() . substr($s, 0, $i) . $newpath;
        }
    }
    return $newpath;
}

function tag_redirect($newpath, $delay = 10, $lp = array('oldpath' => '/kotabl/')) {
    return '<meta http-equiv="refresh" content="' . $delay . ';url=' . htm_path($newpath, $lp['oldpath']) . '">';
}

function htm_reset($q, $delay = 0) {
    $delay = 0;
    if ($text != '') {
        $delay = 10;
    }
    $newpath = f_script($q);  // f_host() .$_SERVER['SCRIPT_NAME'] .'?'. $q;
    return '<html><head><meta http-equiv="refresh" content="'
            . $delay
            . ';url=' . $newpath . '"></head><body>' . $text . '</body></html>';
}

function htm_redirect1($newpath, $text = '', $lp = array('oldpath' => '/kotabl/')) {
    $delay = 0;
    if ($text != '') {
        $delay = 10;
    }
    $s = '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="'
            . $delay
            . ';url=' . htm_path($newpath, $lp['oldpath']) . '"></head><body>' . $text . '</body></html>';
    return $s;
}

function a0_($n_attr, $v, $cond = true) {
    $v = str_replace('"', '\'', $v);
    return iif($cond, ' ' . $n_attr . '="' . $v . '" ');
}

function a_($n_attr, $v, $cond = true) {
    return iif($cond, ' ' . $n_attr . '="' . str_replace('"', '`', $v) . '" ');
}

function a_checked($b) {
    return iif($b, a_('checked', 'checked'));
}

function a_onclick($v) {
    return ' onclick="' . $v . '" ';
}

function a_onchange($v) {
    return ' onchange="' . $v . '" ';
}

function a_value($v) {
    return ' value="' . $v . '" ';
}

function a_ID($v) {
    return ' id="' . $v . '" ';
}

function a_name($v) {
    return ' name="' . str_replace(' ', '', $v) . '" ';
}

function a_target($v) {
    return ' target="' . $v . '" ';
}

function a_class($v) {
    if (empty($v)) {
        return '';
    } else {
        return ' class="' . $v . '" ';
    }
}

function a_size($v) {
    return ' size="' . $v . '" ';
}

function a_style($v) {
    return ' style="' . $v . '" ';
}

function a_placeholder($v) {
    return ' placeholder="' . $v . '" ';
}

function url_nf($nf) {
    $nf1251 = iconv('UTF-8', 'WINDOWS-1251', $nf);
    if ($nf1251 !== false) {
        $nf = $nf1251;
    }
    $url_nf = urlencode($nf);
    return $url_nf;
}

function tag_img($src, $attr = '' , $hide_notExists = false) {
    if (empty($src)) { return ''; } 
    if (strpos($src, '/')===0 && strpos($src, '?')===false) {
        $nf = __DIR__ . $src;
        $szf = filesize_($nf);
        if ($szf === false && $hide_notExists ) { return ''; } 
        else { $src = "$src?$szf"; }
    }
    return '<img src="' . $src . '" ' . $attr . '>';
}

function tag0_($tagname, $attr = '') {
    if ($attr == '') {
        return '<' . strtolower($tagname) . '>';
    } else {
        return( '<' . strtolower($tagname) . ' ' . $attr . ' >' );
    }
}

function tag0_description($text_description) {
    return tag0_('meta', a_name("description") . a_('content', $text_description));
}

function tag_($tagname, $s = ' ', $attr = '', $cond = true) {
    return( '<' . strtolower($tagname) . ' ' . $attr . iif($s === null, '/>', '>' . iif($cond, $s) . '</' . $tagname . '>'));
}

function tag_ul($lli = ' ', $attr = '') {
    return( tag_('ul', $lli, $attr));
}

function tag_span($s = ' ', $attr = '', $cond = true) {
    return( tag_('span', $s, $attr, $cond));
}

function tag_h1($s = '', $attr = '') {
    return( tag_('h1', $s . chr(13) . chr(10), $attr));
}

function tag_h2($s = '', $attr = '') {
    return( tag_('h2', $s . chr(13) . chr(10), $attr));
}

function tag_h3($s = '', $attr = '') {
    return( tag_('h3', $s . chr(13) . chr(10), $attr));
}

function tag_h4($s = '', $attr = '') {
    return( tag_('h4', $s . chr(13) . chr(10), $attr));
}

function tag_h5($s = '', $attr = '') {
    return( tag_('h5', $s . chr(13) . chr(10), $attr));
}

function tag_h6($s = '', $attr = '') {
    return( tag_('h6', $s . chr(13) . chr(10), $attr));
}

function tag_div($s = '', $attr = '', $cond = true) {
    return( tag_('div', $s . chr(13) . chr(10), $attr, $cond));
}

function tag_table($s = '', $attr = '', $cond = true) {
    return( tag_('table', $s . chr(13) . chr(10), $attr, $cond));
}

function tag_link($type, $href, $attr = '') {
    return( '<link type="' . $type . '" href="' . $href . '" ' . $attr . '/>');
}

function tag_kotabl() {
    return tag_span(
            tag_a('http://kotabl.ru', 'kotabl.ru')
    );
}

function tag_tr($s, $attr = '') {
    return( '<tr ' . $attr . '>' . $s . '</tr>');
}

function tag_th($s, $attr = 'style="text-align:center"') {
    return( '<th ' . $attr . '>' . $s . '</th>');
}

function tag_td($s, $attr = '', $cond = true) {
    return( '<td ' . $attr . '>' . iif($cond, $s) . '</td>');
}

function tag_td_sum($s, $attr = 'style="text-align:right;width:80px;"', $cond = true) {
    return tag_td( $s, $attr, $cond );
}

function tag_li($s, $attr = '') {
    return( '<li ' . $attr . '>' . $s . '</li>');
}

function tag_option($s, $v, $attr = '') {
    return( '<option value="' . $v . '" ' . $attr . '>' . $s . '</option>');
}

function tag_hidden($name, $v, $attr = '') {
    return( '<input type="hidden" value="' . $v . '" name="' . $name . '" id="' . $name . '" ' . $attr . ' />');
}

function a_onsubmit($v = 'ajax_form_submit_custom(event)') {
    return ' onsubmit="' . $v . '" ';
}

// resultJS_nFunction - имя функции, вызываемая с параметрами ( theButton, text_response)
// resultJS_nFunction( theButton, text_response) => false  если кнопка должна остаться заблокированной
function tag_button_ajax($s, $href_ajax, $ajax_btn_before='', $ajax_btn_after='', $attr='') {
    return '<button onclick="ajax_btn_click(event)" '.$attr
            . ' href="' . $href_ajax . '"'
            . ' fn_before="' . iif(empty($ajax_btn_before), 'ajax_btn_before', $ajax_btn_before) . '"'
            . ' fn_after="' . iif(empty($ajax_btn_after), 'ajax_btn_after', $ajax_btn_after) . '">'
            . "$s</button>";
}

function tag_input_ajax($value, $attr, $href_ajax) {
    if (strpos($attr, 'type') === false || strpos($attr, '"text"') !== false) {
        $t = 0;
    } else {
        $t = 1;
    }
    return '<input ' . iif($t === 0, 'onchange', 'onclick') . '="ajax_input_change(event)"'
            . ' href="' . $href_ajax . '"'
            . ' value="' . $value . '" ' . iif($t === 0, ' type="text" ')
            . $attr
            . ' >';
}

//  result printesd to 'div-*'
function tag_a_ajax($href_ajax, $s, $attr = '') {
    return '<a onclick="ajax_a_click(event)"'
            . ' href="' . $href_ajax . '" ' . $attr
            . ">$s</a>";
}

function tag_select($name, $selected, $options, $attr = '') {
    $v = 'value="' . $selected . '"';
    $options = str_replace($v, $v . ' selected="selected" ', $options);
    return tag_('select', $options, a_name($name) . $attr);
}

function tag_input0($name, $v, $attr = '', $tp = 'text') {
    return '<input type="' . $tp . '" name="' . $name . '" value="' . $v . '" '
            . iif(stripos($attr, 'id') === false, a_ID($name)) . $attr . ' />';
}

function tag_input($lbl, $name, $v, $attr = '', $t = 'text') {
    if (strpos($v, '"') !== false) {
        $v = htmlspecialchars($v);
    } // str_ireplace('"','&quote',$v);}
    return( $lbl . iif($t == 'text'
                    , '<span class="inputtext"><input type="text" name="' . $name . '" value="' . $v . '" ' . iif(stripos($attr, 'id') === false, a_ID($name)) . $attr . ' /></span>' //<button class="x" onclick="input_clear(event)">x</button>
                    , '<input type="' . $t . '" name="' . $name . '"' . a_value($v) . iif(stripos($attr, 'id') === false, a_ID($name)) . $attr . ' />'));
}

function tag_li_input($lbl, $name, $v, $attr = '', $t = 'text') {
    return( tag_li(tag_input($lbl, $name, $v, $attr, $t)) );
}

function tag_tr_input($lbl, $name, $v, $attr = '', $t = 'text') {
    return( tag_tr(tag_td($lbl, 'style="white-space: nowrap;"') . tag_td(tag_input('', $name, $v, $attr, $t))) );
}

function tag_submit($v = 'отправить', $cond = true, $attr = '') {
    return( iif($cond, '<input type="submit" value="' . $v . '" ' . $attr . ' />', ''));
}

const FORM_AJAX_SUBMIT = 'ajax_form_submit(event)';
/* hidden vars  for    ajax_form_submit(event)

 */

function tag_form($href_action, $body_form, $attr = '', $cond = true) {
    if (empty($href_action)) {
        $href_action = $_SERVER['SCRIPT_NAME'];
    } else if (strpos($href_action, '?') === 0) {
        $href_action = $_SERVER['SCRIPT_NAME'] . $href_action;
    }
    return iif($cond, tag_('form', $body_form, 'action="' . $href_action . '" method="post" ' . $attr));
}

/*
  function tag_form_ajax( $action, $body_form, $attr='', $cond = true ){
  return '<form action="'.$action.'" method="post" onsubmit="ajax_form_submit(event)" '.$attr.' >'
  .$body_form
  .'</form>';
  } */

function tag_form_get($href_action, $body_form, $attr = '', $cond = true) {
    return iif($cond, tag_('form', $body_form, 'action="' . $href_action . '" method="get" ' . $attr));
}

function tag_li_form($href_action, $body_form, $attr = '', $cond = true) {
    return tag_li(tag_form($href_action, $body_form, $attr, $cond));
}

function tag_a_script($ref, $s, $attr = '', $lp_ref = '') {
    return tag_a(f_script($ref), $s, $attr, $lp_ref);
}

function tag_a($ref, $s, $attr = '', $lp_ref = '') {

    if ($ref == '')
        return $s;

    if (empty($s)) {
        $s = $ref;
    }

    if (is_array($lp_ref)) {
        foreach ($lp_ref as $key => $p) {
            $ref = $ref . '&' . $key . '=' . urlencode($p);
        }
    } else {
        $ref = $ref . $lp_ref;
    }

    return '<a href="' . full_url($ref) . '" ' . $attr . '>' . $s . '</a>';
}

function relative_path_to_file($nf = '/robots.txt') {
    $dir = pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_DIRNAME);
    $base = '';
    if (!file_exists($dir . $nf)) {
        $base .= '../';
        $dir = dirname($dir);
        if (!file_exists($dir . $nf)) {
            $base .= '../';
            $dir = dirname($dir);
            if (!file_exists($dir . $nf)) {
                $base = '';
            }
        }
    }
    return $base;
}

function require_once_($nf) {
    $nf = dir_to_file() . $nf;
    require_once($nf);
}

function dir_to_file($nf = '/robots.txt') {
    $dir = dirname($_SERVER['SCRIPT_FILENAME']);
    if (!file_exists($dir . $nf)) {
        $dir = dirname($dir);
        if (!file_exists($dir . $nf)) {
            $dir = dirname($dir);
            if (!file_exists($dir . $nf)) {
                return false;
            }
        }
    }
    return $dir;
}

function tag_link_css($href) {
    return tag_link_css2('', $href);
}

function tag_link_script($href) {
    if (empty($href)) {
        return '';
    }

    if (strpos($href, 'http') === false) {
        if ((strpos($href, '/js/') === false)) {

            if ((strpos($href, 'core') !== false) || (strpos($href, 'ajax0') !== false) || (strpos($href, 'json') !== false)) {
                $href = '/js/' . $href;
                $href = str_replace('//', '/', '/' . $href);
            }
        }
    }


    $pu = parse_url($href);

    if (strpos($href, '/') === 0) {
        $href = f_host() . $href;
        $nf = str_replace('//', '/', __DIR__ . '/' . $pu['path']);
    } else {
        $href = f_host(f_base() . f_ref($pu['path'], FR_PATHNAME));
        $nf = str_replace('//', '/', dir_to_file() . '/' . $pu['path']);
    }

    $sz = filesize($nf);

    if ($sz !== false) {
        $href = $href . iif(empty($pu['query']), '?', '&') . 'sz=' . $sz;
    }
    return chr(13) . chr(10) . '<script src="' . $href . '" > </script>' . chr(13) . chr(10);
}

function array_html($ex0 = '') {
    $html = array(
          'body' => []
        , 'head' => []
        , 'head_ex' => []
        , 'menu' => ''
        , 'base' => ''
        , 'base_target' => '_self'
        , 'title' => ''
        , 'attr_body' => ''
        , 'attr_div_body' => a_style(CSS_INLINEBLOCK . CSS_TEXTLEFT)
        , 'attr_html' => 'lang="ru"'
        , 'clip' => []
        , DIV_HMENU => []
        , DIV_FMENU => []
        , DIV_HEADER => []
        , DIV_FOOTER => []
        , DIV_TITLE => []
        , DIV_CONTENT => []
        , DIV_SEARCH => []
        , DIV_FOUND => []
        , DIV_SUBTITLE => []
        , DIV_TEXT => []
        , DIV_ALARM => []
    );
    init_enviroment($html, $ex0);
    return $html;
}

function html_push(&$html_items, $item_html, $active = true) {
    if ($active == true) {
        array_push($html_items, $item_html);
    }
}

function html_head(&$html, $html_true, $html_false = '', $cond = -1) {
    html_tt($html, 'head', $html_true, $html_false, $cond);
}

function html_body(&$html, $html_true, $html_false = '', $cond = -1) {
    html_tt($html, 'body', $html_true, $html_false, $cond);
}


function html_clip(&$html, $n_clip, $html_clip, $cond = 1) {
    if ($cond) {
        if (!isset($html['clip'][$n_clip])) {
            $html['clip'][$n_clip] = [];
        }
        array_push($html['clip'][$n_clip], $html_clip);
    }
}


function html_tt(&$html, $DIV_TEMPLATE, $html_true, $html_false = '', $cond = -1) {
    if ($cond === -1) {
        if ($html_false === '') {
            $cond = true;
        } else {
            $cond = isUserLoggedOn();
        }
    }
    if (!isset($html[$DIV_TEMPLATE])) {
        $html[$DIV_TEMPLATE] = [];
    }
    if ($cond) {
        array_push($html[$DIV_TEMPLATE], $html_true);
    } else {
        array_push($html[$DIV_TEMPLATE], $html_false);
    }
}

function html_login_proposal(&$html, $rx = '', $options_login = []) {
    html_head($html, tag_link_css('/auth/login.css'));
    html_tt($html, DIV_HEADER, htm_login_proposal($rx, $options_login));
}

function get_session_varname_converse_cnt($u_ct, $u, $dbrequest = false) {
    return 0;
}



function get_userhref( $u ) {
    return '/derjava/user_contact.php?'.$u;
}
function get_username( $u ) {
    $r0 = new db(DB_USER,'select NAME_U from w0_user where u = :u ',[':u'=>$u]);
    return $r0->row('NAME_U');
}


function tag_username(&$html, $u, $class_status = '') {
    if ($class_status === '?') {
        $class_status = iif(user_is_online($u), 'online', 'offline');
    }
    $sx = tag_user($html, $u, '');
    return "<span class=\"u\" data-u=\"$u\"><span class=\"nu $class_status\"></span></span>";
}

function unlink_($nf){
    if (strpos($nf, ':') !== 1) $nf = f_root ().$nf;
    if (file_exists($nf)) unlink ($nf);
}


function tag_user_imgsrc0($u, $class_status = ''){
    return user_imgsrc($u);
}


function tag_user(&$html, $u, $class_status = '?') {
    if (empty($u)) return '';
    return  tag_span('<a href="/derjava/user_contact.php?' . $u . '" target="u' . $u . '">' . $u .'</a>' );
}

function tag_user0(&$html, $u, $class_status = '') {
  return tag_span('<a href="/derjava/user_contact.php?' . $u . '" >'. $u . '</a>');
}

function get_templated_html(&$html, $DIV_TEMPLATE) {
  $s = '';  foreach ($html[$DIV_TEMPLATE] as $vtt) {  $s .= $vtt;   } return $s;
}

function get_templated_html_clip(&$html, $n_clip) {
    $s = '';
    foreach ($html['clip'][$n_clip] as $vtt) { $s .= $vtt;  }   return $s;
}

function tag_html3(&$html, $template = TT_DEFAULT) {
    return tag_html2($html, $template);
}

function tag_html2(&$lp, $template = '') {

// <link rel="shortcut icon" href="/images/icons/favicons/fav_im.ico?6" />
    /*
      <link rel="apple-touch-icon" href="/images/safari_60.png?1">
      <link rel="apple-touch-icon" sizes="76x76" href="/images/safari_76.png?1">
      <link rel="apple-touch-icon" sizes="120x120" href="/images/safari_120.png?1">
      <link rel="apple-touch-icon" sizes="152x152" href="/images/safari_152.png?1">
     */

    if (!isset($lp['body'])) {
        $lp['body'] = [];
    }
    if (!isset($lp['head'])) {
        $lp['head'] = [];
    }
    if (!isset($lp['attr_html'])) {
        $lp['attr_html'] = 'lang="ru"';
    }
    if (!isset($lp['attr_body'])) {
        $lp['attr_body'] = '';
    }

    html_head($lp, <<<'EOT'
  <script>
      SendRequestGET ('/fontsize.php?ww='+window.innerWidth
                +'&hw='+ window.innerHeight
            , function(data){} , true );
  </script>
EOT
    );

    $index_css = 'index.css'; // index page
    $spt = f_ref($_SERVER['SCRIPT_NAME'], FR_NAME);
    ;
    $css = str_ireplace('.php', '.css', $spt);

    $heads =  tag_link_css('/css/font_roboto.css')
            . tag_link_css('/css/u.css')
            . tag_link_css('/js/fileuploader.css')
            . tag_link_script('/js/fileuploader.js')
            . tag_link_script('/js/ut0.js')
            . tag_link_script('/js/ajax0.js')
            ;

    foreach ($lp['head'] as $tag) {
        if (!empty($tag)) {
            if (strpos($heads, $tag) === false) {
                $heads .= $tag;
            }
        }
    }

    $v = '';
    $dir_css = f_dirpath();
    $path_css = f_path();


    $k = strlen(f_root());


    $body = '';
    if (isset($lp['menu'])) {
        $body = $lp['menu'];
    }

    foreach ($lp['body'] as $tag) {
        $body .= $tag;
    }

    $heads .= tag_link_css2($heads, '/css/cmenu.css');

    if (!empty($lp['attr_div_body'])) {
        $body = tag_div($body, $lp['attr_div_body']);
    }

    if (stripos(substr($body, 1, 100), 'body') === false) {
        $body = tag_('body', $body, $lp['attr_body']);
    }

    $title = '';
    if (isset($lp['title'])) {
        $title = $lp['title'];
    }
    if (stripos(substr($heads, 1, 200), '<title') === false) {
        $title = '<title>' . $title . '</title>';
    }

    $base = '';
    if (!isset($lp['base'])) {
        $lp['base'] = '';
    }
    if (stripos(substr($heads, 1, 300), 'base') === false) {
        $base = '<base href="' . f_host() . f_base($lp['base']) . '" target="' . $lp['base_target'] . '" >';
    }

    $style_fonts = get_style();

    $head_ex = get_templated_html($lp, 'head_ex');

    $head = '<meta name="referrer" content="origin"/>'
            . $title

            . $style_fonts
            . tag_('script', 'var rx=null;  var isn=' . val_sn('isn', 0) . '; var iu=' . val_sn('iu', 0) . ';/*  ' . $spt . ' */')
            . '<meta charset="utf-8" />' . $base . $heads
            . tag_link_css2($heads, f_base($path_css) . $index_css)
            . tag_link_css2($heads, f_base($path_css) . $css)
            . yandex_counter();


    if (!empty($template)) {
        if (strlen($template) < 100) {
                $ns = $_SERVER['PHP_SELF'].'.html';
                if ( empty($template) && file_exists(__DIR__ . $ns)){ $template = $ns; }  
                $template = @file_get_contents(__DIR__ . $template);
            
            $i = strpos($template, '<!DOCTYPE');
            if ($i > 0) {
                $template = substr($template, $i);
            }
        }

        $k = array_keys($lp);
        foreach ($k as $tt) {
            if (strpos($tt, 'div-') === 0) {
                $vtt = get_templated_html($lp, $tt);
                $template = tt_set_content($template, $tt, $vtt);
            }
        }

        /* вырезаем все комментарии из шаблона и заменяем вставками %n_clip% */
//-------
/*
     <!--  любой текст   %varname%  любой текст комментария --> 
    все  комментарии уничтожаются  и при наличии  переменной  %varname%
    вставляется соответствующее значение вместо блока <!-- --> .
    в блоке <!-- -->  должна быть только одна переменная
 * 
     <!-- %varname --> любой текст <!-- varname% -->    
     при выводе всё между блоками  <!-- %varname --> и  <!-- varname% --> 
   заменяется на значение переменной
 */        
        $k = array_keys($lp['clip']);
        $i = strpos($template, '<!--');
        $j = 0;
        $tt = '';
        while ( $i !== false ) {
            $tt .= substr($template, $j,$i-$j);
            $j = strpos($template, '-->',$i+4);
            if ($j === false) { $j=$i;  break;}
            $j = $j+3;
            $s = substr($template, $i,$j-$i);
            foreach ($k as $n_clip) {
                $p1 = strpos($s, "%$n_clip");
                $p2 = strpos($template, "$n_clip%" ,$i+4);
                if ( $p1 !== false && $p2 !== false) {
                    $j = strpos($template, '-->',$p2);
                    $j = $j+3;
                    $html_clip = get_templated_html_clip($lp, $n_clip);
                    $tt .= $html_clip;
                    break;
                }
            }
            //file_put_contents('c:/tmp/out.txt', $tt);
            if ($j < $i) { $j=$i;  break;}
            $i = strpos($template, '<!--', $j);
        }
        $tt .= substr($template, $j);
//---------------       
        $template = str_replace('</head>', $head . $head_ex . '</head>', $tt);

        $i = stripos($body, '<body');
        $j = strpos($body, '>', $i + 3);

        // приклеим BODY не по шаблону
        $template = str_replace('</body>', substr($body, $j + 1), $template);

        $body = substr($body, $i + 5, $j - $i - 5); // оригинальные аттрибуты BODY страницы


        $i = strpos($template, '<body');

        if (strpos($body, 'onload=') === false) {
            $j = $i + 5;
        } else {
            $j = strpos($template, '>', $i + 2);
        }

        $template = substr($template, 0, $i + 5) . " $body " . substr($template, $j);

        return $template;
    }


    return '<!DOCTYPE html>
            <html ' . $lp['attr_html'] . '>
            <head>' . $head . '</head>'
            . chr(13) . chr(10) . $body
            . chr(13) . chr(10) . '</html>';
    unset($lp);
}

function tag_html($htm, $head = '', $attr_html = 'lang="ru"', $attr_body = '') {
    $spt = $_SERVER['SCRIPT_NAME'];
    $css = str_ireplace('.php', '.css', $spt);
    $dir = dir_to_file($spt);
    $v = '';
    if (!file_exists($dir . $css)) {
        $css = '';
    } else {
        $v = '?' . filemtime($dir . '/' . $css);
        $css = http_url() . str_ireplace('/main/', '', $css);
    }

    $heads = '';
    if (is_array($head)) {
        foreach ($head as $tag) {
            if (strpos($heads, $tag) === false) {
                $heads .= $tag;
            }
        }
    } else
        $heads = $head;

    if (!empty($css)) {
        if (strpos($heads, $css) === false) {
            $heads = $heads . tag_link_css($css . $v);
        }
    }

    if (is_array($htm)) {
        $html = '';
        foreach ($htm as $tag) {
            $html .= $tag;
        }
        $htm = $html;
        unset($html);
    }

    if (strpos($heads, 'cmenu.css') === false) {
        $heads .= tag_link_css('/css/cmenu.css');
    }

    $base = '';
    if (stripos(substr($heads, 1, 200), 'base') === false) {
        $base = '<base href="' . http_url() . '" >';
    }


    if (stripos(substr($htm, 1, 100), 'body') === false) {
        $htm = tag_('body', $htm, $attr_body);
    }
    return '<!DOCTYPE html>
            <html ' . $attr_html . '>
            <head>' . tag_('script', 'var isn=' . val_sn('isn', 0) . '; var iu=' . val_sn('iu', 0) . ';')
            . '<meta charset="utf-8" />' . $base . $heads . '</head>'
            . chr(13) . chr(10) . $htm
            . chr(13) . chr(10) . yandex_counter() . '</html>';
}

function tag_li_a($ref, $s, $attr_a = '', $lp_ref = '') {
    return tag_li(tag_a($ref, $s, $attr_a, $lp_ref));
}

function tag_p($s, $attr = '') {
    return ('<p ' . $attr . '>' . $s . '</p>');
}

function tag_textarea($name, $s, $attr = '') {
    return '<textarea name="' . $name . '" id="' . $name . '" ' . $attr . ' >' . $s . '</textarea>';
}

function tag_textarea1($name, $s, $attr = '') {
    return '<div  ' . $attr . ' class="textarea" >'
            . '<textarea name="' . $name . '" ></textarea>'
            . ' <code contenteditable="true"  onblur="blurTextarea(event)">'
            . htmlentities($s) . '</code></div>';
}

function tag_uploadfile($formaction = '?ax=uploadfile', $formfields = '', $text_submit = 'Отправить файл') {
    return '
    <form  enctype="multipart/form-data" action="' . $formaction . '" method="POST">
        ' . $formfields . '
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <input name="userfile" type="file" />
                    <input type="submit" value="' . $text_submit . '" />
     </form>';
}

function tag_uploadfile2($formaction = '?ax=uploadfile'
        , $formfields = '', $attr_form = '') {
    return '
    <form  enctype="multipart/form-data" action="' . $formaction . '" ' . $attr_form . ' method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
            <input name="userfile" type="file" />'
            . $formfields . '
     </form>';
}

function gallery_list($dir_gallery, $path_gallery) {
    /*
      array_push( $html['head'] , tag_link_css( '/css/gallery.css' ));
      array_push( $html['body'] , gallery_list( __DIR__ .'/gallery', '/pobedim/za_socializm/gallery/') );
     */
    // ../gallery/
    $s = '';
    $page = $_SERVER['SCRIPT_NAME'];
    $photo = val_rq('photo');

    $dirlist = directoryToArray($dir_gallery, false);
    if (count($dirlist) > 0) {

        if (!empty($photo)) {
            $i = array_search($photo, $dirlist);
            $i++;
            if (count($dirlist) <= $i)
                $i = 0;
            $fn = $dirlist[$i];

            $s = tag_('div', tag_a($page . '?photo=' . $fn . '#photo'
                            , tag0_('img', a_('src', $path_gallery . url_nf($photo)) . a_class("bigphoto"))
                            . tag0_('img', a_class("bigphotonext") . a_('src', htm_path('/32/arrow_right.png')))
                            , a_name('photo'))
                    , a_class("bigphoto"));
        }

        $s2 = '';
        foreach ($dirlist as $fn) {
            if (strpos($fn, '-') !== 0) {
                $fn = url_nf($fn);
                $s2 .= tag_a($page . '?photo=' . $fn . '#photo', tag0_('img', a_('src', $path_gallery . $fn) . a_class('photo')));
            }
        }
    }
    return $s . tag_div($s2, a_class('photos'));
}

function php_uploadfile($uploaddir) {
    $r['ERR'] = '';
    $r['NF'] = '';

    //if ($ax=='uploadfile'){
    //  http://php.net/manual/ru/features.file-upload.post-method.php
    //$_FILES['userfile']['name'] //Оригинальное имя файла на компьютере клиента.
    //$_FILES['userfile']['type'] // Mime-тип файла, в случае, если браузер предоставил такую информацию. В качестве примера можно привести "image/gif". Этот mime-тип не проверяется на стороне PHP, так что не полагайтесь на его значение без проверки.
    //$_FILES['userfile']['size'] //Размер в байтах принятого файла.
    //$_FILES['userfile']['tmp_name'] //Временное имя, с которым принятый файл был сохранен на сервере.
    //$_FILES['userfile']['error'] // http://php.net/manual/ru/features.file-upload.errors.php
    //$uploaddir =$dir.'/data/';
    if (count($_FILES) > 0) {
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0777, true);
        }
        $uploadfile = $uploaddir . str_replace(' ', '_', Transliterate(basename($_FILES['userfile']['name'])));
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            $r['MSG'] = "Файл корректен и был успешно загружен.";
            $r['NF'] = $uploadfile;
        } else {
            $r['MSG'] = "Возможная атака с помощью файловой загрузки!";
            $r['ERR'] = 1;
        }
    }
    // echo htm_refresh(0);
    return $r;
}

function db_get_id_import() {
    $row = db_row("select * from S1_IMPORTDATA_S1 ( null )", []);
    return $row['ID_IMPORTDATA'];
}

function getFromURL($url, $array_data = null, $maxlen = null) {
    return POST($url, $array_data, $maxlen);
}

function POST($url, $array_data, $maxlen = null) {
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($array_data)
        )
    );
    $context = stream_context_create($opts);
    if ($maxlen === null) {
        $r['content'] = @file_get_contents($url, false, $context);
    } else {
        $r['content'] = @file_get_contents($url, false, $context, 0, $maxlen);
    }
    $r['headers'] = $http_response_header;
    return $r;
}

function get_period_begin() {
    if (isset($_REQUEST['bp'])) {
        $_SESSION['bp'] = $_REQUEST['bp'];
    }
    $bp = val_sn('bp', date("d.m.Y", time() - 5 * 60 * 60));
    return $bp;
}

function get_period_end() {
    if (isset($_REQUEST['ep'])) {
        $_SESSION['ep'] = $_REQUEST['ep'];
    }
    $ep = val_sn('ep', date("d.m.Y", time()));
    return $ep;
}

function sn() {
    return get_sid();
}

function select_st($st) {
    $q = "select st.* from w1_site st where st.lg_site=:XST";
    $pa = array(':XST' => $st);
    $r = db_get($q, $pa);
    return $r;
}

function get_st() {
    return db_get("select stu.*, st.AB from u1_site stu inner join w1_site st on st.lg_site=stu.lg_site where stu.dbu=current_user", []);
}

function get_ab_st($st) {
    $r = select_st($st);
    $ab = $r['DS'][0]['AB'];
    return($ab);
}

function session_update($sid, $xst) {
    $xip = $_SERVER['REMOTE_ADDR'];
    $pa = array(':xip' => $xip, ':xlg_site' => $xst, ':xsid' => $sid);
    $q = "select * from S1_SESSION_I3 ( :xip, :xlg_site,  :xsid )";
    $r = db_get($q, $pa);

    if ($r['ROW_COUNT'] == 1) {
        $rs = $r['DS'][0];

        if ($rs['REMOTE_HOSTNAME'] == null) {
            $rn = $xip; // gethostbyaddr ( $xip );
            $pa = array(':xip' => $xip, ':xname' => $rn);
            $q = "update w1_remote_ip set REMOTE_HOSTNAME=:xname where remote_ip=:xip";
            db_get($q, $pa);
        }
    }

    $q = "select * from S1_SESSION_S2 (:XST, :XSESSION )";
    $pa = array(':XST' => $xst, ':XSESSION' => $sid);
    $r = db_get($q, $pa);

    if ($r['ROW_COUNT'] == 1) {
        $_SESSION['DBU'] = $r['DS'][0]['DBU'];
        $_SESSION['ULA'] = $r['DS'][0]['LEVEL_ADMIN'];
        $_SESSION['LG_USER'] = $r['DS'][0]['LG_USER'];
        $_SESSION['IS_MNGR'] = $r['DS'][0]['IS_MNGR'];
        $_SESSION['LIST_UR'] = $r['DS'][0]['LIST_UR'];
        $_SESSION['SN'] = $sid;
        $_SESSION['CNT_BASKET'] = $r['DS'][0]['CNT_BASKET'];
        $_SESSION['ID_SESSION'] = $r['DS'][0]['ID_SESSION'];
        $_SESSION['DATE_START_DOCACCOUNTING'] = $r['DS'][0]['DATE_START_DOCACCOUNTING'];
        $_SESSION['DATE_SESSION'] = $r['DS'][0]['DATE_SESSION'];
    }
}

function get_btsn($btsn) {
    if ($btsn == '' || $btsn == '0') {
        $btsn = val_sn('LG_USER', val_rq('sn', ''));
    }
    return $btsn;
}

function get_sid($m = 1) {
    $sn = val_sn('sn');
    return $sn;
}

function set_sid($xsn, $xst) {
    @session_start();
    if ($xsn == '') {
        session_regenerate_id(false);
        $xsn = session_id();
    };
    session_id($xsn);
    $_SESSION['LG_SITE'] = $xst;
    return get_sid();
}

function val_sn($n, $v = '') {
    if (isset($_SESSION[$n])) {
        if ($_SESSION[$n] == 'undefined' || $_SESSION[$n] == '') {
            $_SESSION[$n] = $v;
            return $v;
        } else {
            return $_SESSION[$n];
        }
    } else {
        $_SESSION[$n] = $v;
        return $v;
    }
}

function val_get($nameVar, $defaultValue = '') {
    $r = filter_has_var(INPUT_GET, $nameVar);
    if ($r !== false) {
        return (filter_input(INPUT_GET, $nameVar, FILTER_DEFAULT));
    } else {
        return($defaultValue);
    }
}

function val_post($nameVar, $defaultValue = '') {
    //  INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER или INPUT_ENV.
    $r = filter_has_var(INPUT_POST, $nameVar);
    if ($r !== false) {
        return (filter_input(INPUT_POST, $nameVar, FILTER_DEFAULT));
    } else {
        return($defaultValue);
    }
}

function val_request($nameVar, $defaultValue = '') {
    if (filter_has_var(INPUT_GET, $nameVar)) {
        return filter_input(INPUT_GET, $nameVar, FILTER_DEFAULT);
    } else {
        if (filter_has_var(INPUT_POST, $nameVar)) {
            return (filter_input(INPUT_POST, $nameVar, FILTER_DEFAULT));
        } else {
            return val_cookie($nameVar, $defaultValue);
        }
    }
}

function val_server($nameVar, $defaultValue = '') {
    //  INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER или INPUT_ENV.
    $r = filter_has_var(INPUT_SERVER, $nameVar);
    if ($r !== false) {
        return (filter_input(INPUT_SERVER, $nameVar, FILTER_DEFAULT));
    } else {
        return($defaultValue);
    }
}

function val_cookie($nameVar, $defaultValue = '') {
    //  INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER или INPUT_ENV.
    $r = filter_has_var(INPUT_COOKIE, $nameVar);
    if ($r !== false) {
        return (filter_input(INPUT_COOKIE, $nameVar, FILTER_DEFAULT));
    } else {
        return($defaultValue);
    }
}

function val_rq($n, $v = '') {

    if (count($_GET) == 1){
        $q = $_SERVER['QUERY_STRING'];
        $qq = '';
        $k = strpos($q, '?');
        if ($k !== false) { 
            $qq = substr($q,$k+1); 
            $q = substr($q, 0, $k); 
            $k = strpos($q, '/'); if ($k !== false) { $q = substr($q, $k); }
            parse_str($qq,$a);
            $x = va($a,$n); 
            //echo $x;
            if (!empty($x)) return $x;
        }
    }
    
    if (isset($_REQUEST[$n])) {
        $x = $_REQUEST[$n];
        if ($x === '0') return '0';
        if (empty($x) || $x == 'undefined' || $x == 'null') {
            return $v;
        } else {
            return $x;
        }
    } else {
        return $v;
    }
}

function nullif($v, $vnull = '') {
    if (trim($v) == $vnull || empty($v))
        return null;
    else
        return $v;
}

function val_v($n, $v) {
    if (isset($_REQUEST[$n])) {
        if ($_REQUEST[$n] == 'undefined' || $_REQUEST[$n] == 'null') {
            return val_sn($n, $v);
        } else {
            return $_REQUEST[$n];
        }
    } else {
        return $v;
    }
}

function chk_ur($xur) {
    $zlur = val_sn('LIST_UR', '');
    if (stripos($zlur, $xur) == false) {
        return false;
    } else {
        return true;
    }
}

function iif_ur($xur, $true_str, $false_str = '') {
    return iif(chk_ur($xur), $true_str, $false_str);
}

function directoryToArray($directory, $recursive) {
    return directoryToArray1($directory, $recursive, '');
}

function directoryToArray1($directory, $recursive, $subdir) {
    $array_items = array();
    if (file_exists($directory)) {
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($directory . "/" . $file)) {
                        if ($recursive) {
                            $array_items = array_merge($array_items, directoryToArray1($directory . "/" . $file, $recursive, $subdir . "/" . $file));
                        }
                    } else {
                        if (($subdir != '') && (strrpos($subdir, '/') < strlen($subdir) - 1)) {
                            $subdir = $subdir . "/";
                        }
                        $file = $subdir . $file;
                        $array_items[] = preg_replace("/\/\//si", "/", $file);
                    }
                }
            }
            closedir($handle);
        }
    }
    return $array_items;
}

/*
  function save_rs2csv($rsh,$rs,$a)
  {
  $tmpfname = tempnam("./tmp", "$$-");
  rename($tmpfname,$tmpfname. '.csv');
  $tmpfname = $tmpfname. '.csv';

  $filename = basename($tmpfname);
  $h =fopen($tmpfname, "w");

  fputcsv($h, $rsh);

  foreach ($rs as $fields) {  fputcsv($h, $fields); }
  fclose($h);

  $a = '1';
  if ($a == '') { $r = $filename; } else { $r = '<a href="./tmp/'. $filename .'">CSV</a>';	}

  return $r;
  }


  function save_rs2csv2($rsh,$rsf,$rs)
  {
  $tmpfname = tempnam("./tmp", "$$-");
  rename($tmpfname,$tmpfname. '.csv');
  $tmpfname = $tmpfname. '.csv';

  $filename = basename($tmpfname);
  $h =fopen($tmpfname, "w");

  fputcsv($h, $rsh);

  foreach ($rs as $fields)
  {
  $aa = array();	for ($i = 0; $i < count($rsf); $i++)
  {
  if ($rsf[$i] == '') {$az = '';} else
  {
  $az =$fields[$rsf[$i]];
  if($az == null || $az == 'NULL' || $az == 'null') $az = '';
  }
  array_push($aa, $az );
  }
  fputcsv($h, $aa);
  }
  fclose($h);

  $a = '1';
  if ($a == '') { $r = $filename; } else { $r = '<a href="./tmp/'. $filename .'">CSV</a>';	}

  return $r;
  }

 */

function __period($bp, $ep) {
    return tag_span(
            'с ' . tag_input0('bp', $bp)
            . ' по ' . tag_input0('ep', $ep)
    );
}

function str0($x) {
    if ($x == 0 || $x == null)
        return ('');
    else
        return($x);
}

function iif($x, $true_str, $false_str = '') {
    if ($x) {
        return ($true_str);
    } else {
        return($false_str);
    }
}

function fmtDDMMYYYY($s) {
    $d = date("d.m.Y", strtotime($s));
    if ($d === false) return null; else return $d;
}



function db_row($q, $pa, $db = 1) {
    $r = db_get($q, $pa, $db);
    if (count($r['DS']) === 0) {
        $row['ROW_COUNT'] = 0;
    } else {
        $row = $r['DS'][0];
        $row['ROW_COUNT'] = $r['ROW_COUNT'];
    }
    $row['ERR'] = $r['ERR'];
    $row['HTML'] = $r['HTML'];
    $row['PARAMS'] = $r['PARAMS'];
    return $row;
}

function user_is_online($u) {
    $ts = user_get_onlinetime($u);
    if ($ts <= 0) {
        return false;
    } else {
        return ( time() - $ts < 600 );
    }
}

function user_set_onlinetime($u) {
    // функция logOff удаляет этот файл
    // 
    //file_put_contents(__DIR__ . "/u/$u/time.tmp", time());
}

function user_get_onlinetime($u) {
    $r = new db(DB_USER,'select COALESCE( current_time - TIME_SESSION ,0) as T from u0_session where date_session=current_date and u=:u',[':u'=>$u]);
    $s = $r->row('T');
    if ($s != '' && 1*$s > 0) return 1; else return -1;
    /*
    $nf = __DIR__ . "/u/$u/time.tmp";
    if (!file_exists($nf)) {
        return -1;
    } else {
        $ts = 0 + @file_get_contents($nf);
        return $ts;
    }
     */
}



class db {

    
    public $params = [];
    public $length = 0;
    public $r;
    public $filter = null;
    
    public $rows = [];
   
    public $html = null;
                function length() {
        if (!isset($this->r['DS'])) {return 0;}
        else { return count($this->r['DS']); }
    }

    function row($nf = '', $ir = 0) {
       // if ($this->length===0)return '';
        if (count($this->r['DS'])==0) return '';
        if (empty($nf)) {
            if (isset($this->r['DS'][$ir])) return $this->r['DS'][$ir]; else return '';
        } else {
            if (isset($this->r['DS'][$ir][$nf])) return $this->r['DS'][$ir][$nf]; else return '';
        }
    }

    function printf2($lp = []) {
        $lp_ = array_merge( $this->params, $lp);
        $fx = $this->filter;
        $s = '';
        if ($fx !== null) {
            if (count($this->r['DS']) > 0) {
                foreach ($this->r['DS'] as $row) {
                    if (is_callable($fx)) {
                        $s .= $fx($this, $row, $lp_);
                    } else {
                        $s .= vsprintf($fx, $row);
                    }
                }
            }
        }
        return $s;
    }
    
    function printf($fx = null, $lp = []) {
        $lp_ = array_merge( $this->params, $lp);
        if ($fx !== null) {
            $this->filter = $fx;
        }
        $fx = $this->filter;
        $s = '';
        if ($fx !== null) {
            if (count($this->r['DS']) > 0) {
                foreach ($this->r['DS'] as $row) {
                    if (is_callable($fx)) {
                        $s .= $fx($row, $lp_);
                    } else {
                        $s .= vsprintf($fx, $row);
                    }
                }
            }
        }
        return $s;
    }

    // $fs - field of string, $fv - field of value, $selected - value of selected
    function tag_select($fs, $fv, $selected, $attr_select = '', $attr_option = '', $tag_option = '') {
        $s = '';
        $fx = $this->filter;
        foreach ($this->r['DS'] as $row) {
            $b = true;
            $attr_data = '';
            if ($fx !== null) {
                $attr_data = $fx($row);
                if ($attr_data === false) {
                    $b = false;
                }
            }
            if ($b === true) {
                $s .= tag_option($row[$fs], $row[$fv], iif($selected === $row[$fv], a_('selected', 'selected')) . $attr_option . $attr_data);
            }
        }
        return tag_('select', $tag_option . $s, $attr_select);
    }

    function htm_ul($ft_li, $attr_li = '', $attr_ul = '') {
        return tag_ul($this->printf(tag_li($ft_li, $attr_li)), $attr_ul);
    }

    // $ft_tr  представлен как шаблон строки таблицы с разделителем колонок "|" , например:    %1$s |  %3$s | %2$s
    function htm_rows($ft_tr) {
        $ft_tr = '<tr><td>' . str_replace('|', '</td><td>', $ft_tr) . '</td></tr>';
        return $this->printf($ft_tr);
    }

    function __construct($db, $q, $pa = [], $fx_build_row = null , $lp=[]) {
        $this->r = db_get($q, $pa, $db);
        $this->length = $this->r['ROW_COUNT'];
        $_SESSION['ERR']=$this->r['ERR'];
        
        if ($fx_build_row !== null) {
            $rows = [];
            if (is_array($this->r['DS'])){
                foreach ($this->r['DS'] as $row) {
                    $row = $fx_build_row( $row, $pa, $lp );
                    if ($row !== null ) array_push( $rows, $row );
                }
            }
            $this->rows = $rows;
        } else 
        {
            $this->rows = &$this->r['DS'];    
        }
    }
}

function db_foreach($fx, $q, $pa, $lp = [], $db = 1) {
    $r = db_get($q, $pa, $db);
    $s = '';
    foreach ($r['DS'] as $row) {
        $s .= $fx($row, $lp);
    }
    $r['S'] = $s;
    return $r;
}





function db_get($q, $pa, $db = 1) {
    $r = array('ERR' => '', 'SQL'=>$q, 'DS' => null, 'ROW_COUNT' => 0, 'HTML' => '', 'R' => '', 'PARAMS' => $pa);
 //   try {
        $dbh = db_connect($db, $q);
        if ($dbh === null) {
            $qh = 0;
            $r['DS'] = null;
            $r['ROW_COUNT'] = 0;
            $r['ERR'] = ' Подключение к БД не установленно ';
            $r['Q'] = $q;
            return $r;
        } else {
            $qh = $dbh->prepare($q);
        }
        if (!$qh) {
            $r['DS'] = null;
            $r['ROW_COUNT'] = 0;
            if(isset($_SESSION['DBU'])) { $err = $_SESSION['DBU'];} else {$err = '';}
            $err =  $err. ' ' . implode('<br>',$dbh->errorInfo());
            $r['ERR'] = 'Ошибка в SQL запросе ' . $err;
            $r['Q'] = $q;
        } else {

            $px = $pa;
            foreach ( array_keys($pa) as $kp){
                if (strpos($q, $kp) === false) {
                    unset( $px[$kp] );
                }
            }

         //   $nf = 'c:/temp/db-q-ex-'.time().'.txt';
         //   file_put_contents($nf, $q);
            set_time_limit(120);
            
            $x = $qh->execute($px);
            if ($x) {
                $r['DS'] = $qh->fetchAll(PDO::FETCH_ASSOC);
            }
            
        //    if (file_exists($nf)) unlink($nf);
            
            
            $r['ERR'] = $qh->errorCode();
            $ie = null;
            if ($r['ERR'] == '00000') {
                $r['ERR'] = '';
            } else {
                $ie = $qh->errorInfo();
                $r['ERR'] = 'ERR ' . $r['ERR'] . ' ' . $ie[2];
            }
            $dbh->commit();
            if (is_array($r['DS'])) $r['ROW_COUNT'] = count($r['DS']); else $r['ROW_COUNT'] = 0;
            if ($r['ROW_COUNT']>0) {
                foreach ($r['DS'] as $row){
                    foreach($row as $v){
                        if(stripos($v, '<script') !==false) { $v = '!!!'; }
                    }
                }
            }
        }
/*        
    } catch (PDOException $Exception) {
        $ie = $Exception->getMessage();
        if (isset($qh)) {
            $ie = $qh->errorInfo()[2];
        }
        $r['ERR'] = 'ERR ' . $r['ERR'] . ' ' . $ie;
                
        $nf = 'c:/tmp/dberr'.time().'.txt';
        file_put_contents( $nf , $r['ERR'] . ' '.$q );
    }
  */
    return $r;
}

/*
  *  сортирует данные по массивам
 * 
  $rs = db_rs($r['DS'], function($row) {
  $ss = trim($row['CH_STATE']);
  if ($ss == '' || $ss == '!' || $ss == 'V' || $ss == 'W' ) return 'M';
  else if ($ss != 'X' )return 'P';
  else return false;
  });
 */

function db_rs($DS, $FN) {
    $a = array([]);
    foreach ($DS as $row) {
        $k = $FN($row);
        if ($k !== false) {
            if (!array_key_exists($k, $a)) {
                $a[$k] = array($row);
            } else {
                array_push($a[$k], $row);
            }
        }
    }
    return $a;
}

function db_tag_options($q, $v_selected, $pa = [], $db = 1) {
    $s = '';
    $r = db_get($q, $pa, $db);
    foreach ($r['DS'] as $row) {
        $row1 = array_values($row);
        $s = $s . tag_option2($row1[0], $row1[1], $v_selected);
    }
    return $s;
}

function db_tag_li_a($ref, $attr, $q, $pa = [], $db = 1) {
    $s = '';
    $r = db_get($q, $pa, $db);
    foreach ($r['DS'] as $row) {
        $row1 = array_values($row);
        $s = $s . tag_li_a($ref . $row1[1], $row1[0], $attr);
    }
    return $s;
}

// don't use
function db_exec($q, $pa) {
    $r = db_get($q, $pa);
    if ($r['ERR'] == '') {
        if (count($r['DS']) > 0) {
            return $r['DS'][0];
        } else {
            return null;
        }
    } else {
        return array('R' => 'ERR', 'ROW_COUNT' => 0, 'ERR' => 'error: ' . $r['ERR']);
    }
}

// don't use
function db_fetchAll($q, $pa) {
    $r = db_get($q, $pa);
    if ($r['ERR'] == '') {
        return $r['DS'];
    } else {
        return 'ERR: ' . $r['ERR'];
    }
}

//---------

function is_dbconnected() {
    if ($_SESSION['DB_ALIVE'] === 0)
        return FALSE;
    else
        return TRUE;
}

function db_connect($db = 1, $q='') {
//    @session_start();
    $dbsrv = srv_database($db);
    $srv = $dbsrv['db'];
    $pw = $dbsrv['dbpw'];
    $dbh = null;
    $dbu = 'SYSDBA';
    $pw = 'masterkey';
    
/*    
        $dbu = 'KOTABL';
        $pw = 'alfjkslsf;a';
        
        try {
            //$dbu = '"'.$dbu.'"';
            //if (strpos($dbu,['@']) !== false ) $dbu = '"'.$dbu.'"';
            $dbh = new PDO('firebird:dbname=' . $srv . ';role=R1', '"' . $dbu . '"', $pw);
            return $dbh;
        } catch (PDOException $Exception) {
            $dbh = new PDO('firebird:dbname=' . $srv, 'SYSDBA', 'masterkey');    //$dbh = new PDO('firebird:dbname='.$srv.';role=' , 'KOTABL', 'alfjkslsf;a');
            $err = $dbh->errorInfo();
            $qh = $dbh->query("SELECT * FROM SEC$" . "USERS WHERE SEC$" . "USER_NAME = '" . $dbu . "'");
            $rs = $qh->fetchAll(PDO::FETCH_ASSOC);
            if (count($rs) == 0) {
                $q = 'create user "' . $dbu . '" password \'1\'';
                $dbh->exec($q);
                $err = $dbh->errorInfo();
                $dbh->commit();
                $dbh->exec('grant R1 to "' . $dbu . '" with admin option ');
                $err = $dbh->errorInfo();
                $dbh->commit();
            }
        }
*/    

    
    $ts = time();    
//    try {
        $dbh = new PDO('firebird:dbname=' . $srv , '"' . $dbu . '"', $pw);
        //$dbh = new PDO('firebird:dbname=' . $srv . ';role=R1', '"' . $dbu . '"', $pw);
        $_SESSION['DB_ALIVE'] = 1;
/*        
    } catch (PDOException $Exception) {
        $dbh = null;
        $nf = 'c:/temp/dberr'.time().'.txt';
        file_put_contents( $nf , $srv.'|'.$dbu.'|'.$q);

        $_SESSION['DB_ALIVE'] = 0;
    }
    $t = time()-$ts;
    if ($t > 20){
        $nf = 'c:/tmp/dberr'.time().'.txt';
        file_put_contents( $nf , $srv.'|'.$dbu.'|'.$q);
    }
 */
    return $dbh;
}


/*
 
  // PGSQL
  function db_fetchall_refcursor($q, $params)
  {
  $dbh = db_connect();
  $dbh->beginTransaction();
  $qh = $dbh->prepare($q);
  $qh->execute($params);
  $rs = $qh->fetch(PDO::FETCH_NUM);
  $s = $rs[0];
  $qh = $dbh->prepare('fetch all in "'.$s.'";');
  $qh->execute();
  $rs = $qh->fetchAll(PDO::FETCH_ASSOC);
  $dbh->commit();
  return $rs;
  }
 
 */

function Transliterate($string) {
    $cyr = array(
        "Щ", "Ш", "Ч", "Ц", "Ю", "Я", "Ж", "А", "Б", "В",
        "Г", "Д", "Е", "Ё", "З", "И", "Й", "К", "Л", "М", "Н",
        "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ь", "Ы", "Ъ",
        "Э", "Є", "Ї", "І",
        "щ", "ш", "ч", "ц", "ю", "я", "ж", "а", "б", "в",
        "г", "д", "е", "ё", "з", "и", "й", "к", "л", "м", "н",
        "о", "п", "р", "с", "т", "у", "ф", "х", "ь", "ы", "ъ",
        "э", "є", "ї", "і"
    );
    $lat = array(
        "Shch", "Sh", "Ch", "C", "Yu", "Ya", "J", "A", "B", "V",
        "G", "D", "e", "e", "Z", "I", "y", "K", "L", "M", "N",
        "O", "P", "R", "S", "T", "U", "F", "H", "",
        "Y", "", "E", "E", "Yi", "I",
        "shch", "sh", "ch", "c", "Yu", "Ya", "j", "a", "b", "v",
        "g", "d", "e", "e", "z", "i", "y", "k", "l", "m", "n",
        "o", "p", "r", "s", "t", "u", "f", "h",
        "", "y", "", "e", "e", "yi", "i"
    );
    for ($i = 0; $i < count($cyr); $i++) {
        $c_cyr = $cyr[$i];
        $c_lat = $lat[$i];
        $string = str_replace($c_cyr, $c_lat, $string);
    }
    $string = preg_replace(
            "/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $string);
    $string = preg_replace(
            "/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}'", $string);
    $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
    $string = preg_replace("/^kh/", "h", $string);
    $string = preg_replace("/^Kh/", "H", $string);
    $string = str_replace('"', "_", $string);
    $string = str_replace("'", "_", $string);
    return $string;
}

function check_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    //$q = "select * from W1_REMOTE_IP_BLACK where  REMOTE_IP = :REMOTE_IP";
    $q = "select * from W1_REMOTE_IP_IS_BLACK_S(:REMOTE_IP)";
    $pa = array(':REMOTE_IP' => $ip);
    $r = db_get($q, $pa);
    if ($r['ROW_COUNT'] > 0) {
        $s = $ip . ' is banned ' . $rs['LIST_OPTIONS'];
        sleep(99);
        exit($s);
    }
}


function wrap_raw_text_as_php_($s, $assignToLeft = '') {
    /*   wrap_raw_text_as_php_('value', '$a["var"]')
      =>  $a['var']=<<<'EOT'\r\n  value \r\nEOT;
     */
    return iif(!empty($assignToLeft), $assignToLeft . '=') . '<<<\'EOT\''
            . chr(13) . chr(10) . $s . chr(13) . chr(10) . 'EOT;' . chr(13) . chr(10);
}

function wrap_assignvalue_as_php_($s, $assignToLeft) {
    /*   wrap_assignvalue_as_php_('value', '$a["var"]')
      =>  $a['var']= value;\r\n
     */
    return $assignToLeft . '=\'' . $s . '\';' . chr(13) . chr(10);
}

function get_style() {

    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (!isset($_SESSION['width_window'])) {
        $_SESSION['width_window'] = 1024;
    }
    if (!isset($_SESSION['height_window'])) {
        $_SESSION['height_window'] = 768;
    }

    $ww = $_SESSION['width_window'];
    $hw = $_SESSION['height_window'];


    if ($hw > $ww) {
        $h1 = '38pt';
        $h2 = '30pt';
        $h3 = '26pt';
        $h4 = '22pt';
        $h5 = '20pt';
        $h6 = '18pt';
        $bx = '30px';
        if (stripos($ua, 'Android') > 0) {
            $bx = '50px';
        }
    } else {
        $h1 = '28pt';
        $h2 = '24pt';
        $h3 = '20pt';
        $h4 = '18pt';
        $h5 = '16pt';
        $h6 = '14pt';
        $bx = '20px';
    }



    $t = <<<EOT
<style>
body  {font-size: 12pt;}
td , p  {font-size: 14pt;}
th , ul , li  {font-size: 12pt;}
small {font-size: 10pt;}

h6 {font-size: $h6; font-weight: bold;}
h5 {font-size: $h5; font-weight: bold;}
h4 {font-size: $h4; font-weight: bold;}
h3 {font-size: $h3; font-weight: bold;}
h2 {font-size: $h2; font-weight: bold;}
h1 {font-size: $h1; font-weight: bold;}

input[type="radio"], input[type="checkbox"] { width: $bx;  height: $bx; }
</style>
EOT;


    return $t . tag_link_css('/css/style0.css');
}

function tag_label_input($text, $attr = 'for="id?"') {
    return '<label ' . $attr . '><span class="l"></span>' . $text . '</label>';
}



function filesize_($nf){
  if (strpos($nf, '/')===0) { $nf = f_root() ."/$nf"; }
  $k = strpos($nf, '?'); if ($k !== false) {$nf = substr($nf,0,$k-1); }
  if (file_exists($nf) === false) { return false;}
     else { return filesize($nf); }
}

function filemtime_($nf){
  if (strpos($nf, '/')===0) { $nf .= __DIR__; }
  if (file_exists($nf) === false) { return false;}
     else { return filemtime($nf); }
}



function checkAddressPort($host,$port){
    if (!$socket = @fsockopen($host, $port, $errno, $errstr, 3)) {
        //echo "Offline!";
        return false;
    } else {
        //echo "Online!";
        fclose($socket);
        return true;
    }
}

function pingAddress($ip){
    $pingresult = shell_exec("start /b ping $ip -n 1");
    $dead = "Request timed out";
    return   (strpos($dead, $pingresult) === false);
}


// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------


function user_adjust_data(&$fu){
    $u = $fu['iu'];
    
    $fu['u']=$u;
    
    if (va_empty($fu, 'nu1')) $fu['nu1'] = va($fu, 'first_name');
    if (va_empty($fu, 'nu2')) $fu['nu2'] = va($fu, 'second_name');
    if (va_empty($fu, 'nu3')) $fu['nu3'] = va($fu, 'last_name');
    
    $fu['nu'] = trim(va($fu,'nu1') .' '.va($fu,'nu2') .' '.va($fu,'nu3'));   
    if (strpos($fu['nu'],'user') !==false) $fu['nu'] = '';
    if (va($fu,'nu')==va($fu,'nu3')) $fu['nu'] = '';
    
    $email = va($fu, 'email');
    if ( empty($fu['nu']) ){
        $fu['nu'] = va($fu, 'name');
        if ( empty($fu['nu']) ) $fu['nu'] = va($fu, 'nick_name');
        if ( empty($fu['nu']) ) {
        $i = strpos($email,'@'); if ($i !== false) $fu['nu']= substr($email, 0,$i); 
            if (empty($fu['nu'])) $fu['nu']= 'user'.$u;
        }
    }
    
    if (stripos($fu['nu'], 'pobedim') !== false) $fu['nu']= 'user.'.$u;
    else if (stripos($fu['nu'], 'победим') !== false) $fu['nu']= 'user.'.$u;
    else if (stripos($fu['nu'], 'держава') !== false) $fu['nu']= 'user..'.$u;
    else if (stripos($fu['nu'], 'derjava') !== false) $fu['nu']= 'user-'.$u;
    else if (stripos($fu['nu'], 'derzhava') !== false) $fu['nu']= 'user-'.$u;
    
    if (va_empty($fu, 'nu1')) $fu['nu1'] = va($fu, 'nu');
    
    $s = va($fu,'email_notify_confirmed');
    if (empty($email) && !empty($s)){
        $fu['email'] = $fu['email_notify_confirmed'];
    }
    if (!empty($email) && empty($s)){
        $fu['email_notify_confirmed'] = $fu['email'];
    }
    
    if (va_empty($fu, 'email_notify')) $fu['email_notify'] = $email;
    
    
    $s = va($fu,'url_photo_u'); 
    $s2 = va($fu,'url_photo_nu'); 
    if (empty($s) && !empty($s2)){
        $fu['url_photo_u'] = $s2;
    }
    $s = va($fu,'url_photo_u'); if (empty($s) || $s == '__URL_PHOTO_U__' ) $fu['url_photo_u'] = va($fu,'photo');
    
    if (va_empty($fu, 'info_u')) $fu['info_u']= va($fu,'info2_u_public');
    if (va_empty($fu, 'web_u')) $fu['web_u']= va($fu,'url_u_public');
    if (va_empty($fu, 'contacts_u')) $fu['contacts_u']= va($fu,'info1_u_public');
    
    $g = va($fu,'gender_u');
    if (strlen($g)>1) { if ( stripos($g, 'f')===0 ) {$fu['gender_u'] = 'f';} if ( stripos($g, 'm')===0 ) {$fu['gender_u'] = 'm';} }
   
   unset($fu['info1_u_public']);
   unset($fu['info2_u_public']);
   unset($fu['url_u_public']);
   unset($fu['iufb']); 
   unset($fu['file']);
   unset($fu['picture']);
   unset($fu['url_photo_nu']);
   unset($fu['url_photo_n']);   
   unset($fu['ts']);   
   unset($fu['rx']);  
   unset($fu['last_name']);
   unset($fu['first_name']);
   unset($fu['name']);
   unset($fu['user_id']);
   unset($fu['id']);
}



function set_f_user(&$cf ){
    $u = $cf['iu'];
    if ($u != '1000') {
        
        user_adjust_data($cf);
        
        //file_put_contents($nf, ini_from_array($cf));
        
        if ( va($cf,'email') != '' ){
            unset($r);
            $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_EMAIL(u,LOGIN_EMAIL_U,TEXT_LOGIN) values (:u,:s,:t)'
                       , [':u'=> $u, ':s'=>$cf['email'] ,':t'=>ini_from_array($cf)] );    
        }
        
        if ( va($cf,'user_odnoklassniki') != '' ){
            unset($r);            
            $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_OK(u,LOGIN_OK_U,TEXT_LOGIN) values (:u,:s,:t)'
                       , [':u'=> $u, ':s'=>$cf['user_odnoklassniki'] ,':t'=>ini_from_array($cf)] );    
        }
        
        if ( va($cf,'user_vkontakte') != '' ){
            unset($r);
            $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_VK(u,LOGIN_VK_U,TEXT_LOGIN) values (:u,:s,:t)'
                       , [':u'=> $u, ':s'=>$cf['user_vkontakte'] ,':t'=>ini_from_array($cf)] );    
        }
        
        if ( va($cf,'user_facebook') != '' ){
            unset($r);
            $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_FB(u,LOGIN_DB_U,TEXT_LOGIN) values (:u,:s,:t)'
                       , [':u'=> $u, ':s'=>$cf['user_facebook'] ,':t'=>ini_from_array($cf)] );    
        } 
        
        
//----------------- begin -------------------------------------------
    unset($r);
    $r = new db(DB_POBEDIM,'update or insert into w2_user (u, name_u,  LIST_SPECIALITY_U ,CITY_U ,BIRTHDAY_U )'
            . ' values (:iu,:nu,:ls ,:city, :bd )'
            , [':iu' => $u
                , ':nu' => va($cf,'nu')
                , ':ls' => va($cf,'lspeciality')
                , ':city' => va($cf,'city_u')
                , ':url' => va($cf,'url_photo_u')
                , ':bd' => va($cf,'birthday_u')
                , ':info' => va($cf,'info_u')
                , ':web' => va($cf,'web_u')
            ]
             );
    
    unset($r);
    $t = ini_from_array($cf);
    $r = new db(DB_USER,'update or insert into w0_user (u, name_u, MAIL_U,TEXT_LOGIN) values (:iu,:nu, :ml, :t)'
            , [':iu' => $u
                , ':nu' => str_trunc( va($cf,'nu') ,45)
                , ':ml' => va($cf,'email_notify_confirmed')
                , ':t'=>$t
                ]
             );
    
    //unset($r);  $r = new db(DB_USER, 'update W0_USER set TEXT_LOGIN=:t where u=:u', [':u'=> $cf['iu'], ':t'=>ini_from_array($cf)] );    
    
    unset($r);
    $r = new db(DB_POBEDIM, 'select * from U2_CERTIFICATE_IU3(:u,:ic,:tv, :n1c,:n2c,:n3c,:sex,:dbc,:bpc, :ec, :cc)'
            ,[':u'=>$cf['iu']
                ,':ic'=>null
                ,':tv'=>null
                , ':n1c'=>va($cf,'nu1')
                ,':n2c'=>va($cf,'nu2')
                ,':n3c'=>va($cf,'nu3')
                ,':sex'=>va($cf,'gender_u')
                ,':dbc'=> fmtDDMMYYYY( va($cf,'date_birthday'))
                ,':bpc'=>va($cf,'place_birthday')
                ,':ec'=>va($cf,'email_notify_confirmed')
                ,':cc'=>va($cf,'contacts_u') ]);
    $ic = $r->row('ID_CERTIFICATE_U');
    
    unset($r);
    $r = new db(DB_POBEDIM, 'update U2_CERTIFICATE set IS_EMAIL_CONFIRMED=:b where u=:u and ID_CERTIFICATE=:ic '
            ,[':b'=>(va($cf,'email_notify_confirmed') == va($cf,'email_notify')) , ':u'=>$cf['iu'],':ic'=>$ic ]);
//------------- end        
    }
}

function user_imgsrc($u,$sz=64) { 
        $url="/w/u_photo/$u/photo_$sz.png"; 
        if(file_exists(f_root().$url)) return $url; 
        else return "/photo_u.php?x=$sz&u=".$u; 
}

function tag_user_imgsrc($u, $fu = false) { return user_imgsrc($u,64); }

function fill_row_user(&$row){
    $fu = get_f_user($row['U'],'u');
    //$row['SRC_IMG_U'] = va($fu,'url_photo_u');
    //if (empty($row['SRC_IMG_U'])) 
    $row['SRC_IMG_U'] =  user_imgsrc($row['U'],64);
    
    $row['HREF_U'] = '/derjava/user_contact.php?'.$row['U'];
    $row['NAME_U'] = va($fu,'nu','--');
    $row['IP_HOST'] = va($fu,'ip_host');
    $row['CITY_HOST'] = va($fu,'city_host');
    $row['REGION_HOST'] = va($fu,'region_host');
    
    return $fu;
}

function user_geo_get(&$row, &$fu = null ){
    // $row['U']; !!!! 
 if ($fu !==  null){
    $row['IP_HOST'] = va($fu,'ip_host');
    $row['CITY_HOST'] = va($fu,'city_host');
    $row['REGION_HOST'] = va($fu,'region_host');
 } else {
        $s = va($row,'CITY_HOST');
        if ( empty($s) ){
            $r= new db(DB_USER,'select h.* from w0_host h inner join w0_user u on u.u=:u and u.ip_host=h.ip_host',[':u'=>$row['U']]);
            $row['IP_HOST'] = $r->row('IP_HOST');
            $row['CITY_HOST'] = $r->row('CITY_HOST');
            $row['REGION_HOST'] = $r->row('REGION_HOST');
        }
 }
}

function get_f_user($v_param, $tu = 'email') {   // user_facebook , email, iu
    if ($tu === 'iu' || $tu === 'u') {
       $r = new db(DB_USER, 'select U,TEXT_LOGIN from W0_USER where U=:s', [':s'=>$v_param]);
       $cf = ini_to_array($r->row('TEXT_LOGIN'));
    } else {
        if ($tu === 'user_odnoklassniki'){unset($r); $r = new db(DB_USER, 'select U,TEXT_LOGIN from W0_U_LOGIN_OK where LOGIN_OK_U=:s', [':s'=>$v_param]);}
        if ($tu === 'user_vkontakte'){unset($r);  $r = new db(DB_USER, 'select U,TEXT_LOGIN from W0_U_LOGIN_VK where LOGIN_VK_U=:s', [':s'=>$v_param]);}
        if ($tu === 'user_facebook'){unset($r); $r = new db(DB_USER, 'select U,TEXT_LOGIN from W0_U_LOGIN_FB where LOGIN_FB_U=:s', [':s'=>$v_param]);}
        if ($tu === 'email'){unset($r); $r = new db(DB_USER, 'select U,TEXT_LOGIN from W0_U_LOGIN_EMAIL where LOGIN_EMAIL_U=lower(:s)', [':s'=>$v_param]); }
        $c = ini_to_array($r->row('TEXT_LOGIN')); 
        $r0 = new db(DB_USER, 'select TEXT_LOGIN from W0_USER where U=:u', [':u'=>$r->row('U')]); 
        $cf = ini_to_array($r0->row('TEXT_LOGIN')); 
        if (is_array($c)) { if ($cf === false) $cf=$c; else  $cf = array_merge($cf,$c); }
    }
    
    if ($r->length == 1){ 
        if (is_array($cf)) { $cf['iu'] = $r->row('U'); return $cf; }
    } 
    return false;
}


function gen_iu(&$cfu) {
    unset($r); $r = new db(DB_USER,'select * from W0_LOGIN_NEW_S(:h,:nu)',[ ':h'=>$_SERVER['REMOTE_ADDR'] ,':nu'=>va($cfu,'nu') ] );
    if ($r->row('IS_ALLOWED') != '1') return false;
    
    $iu = $r->row('U');
    $cfu['iu'] = $iu;
    set_f_user($cfu);
    return $iu;
}




//-----------------------------------

function db2_get_sn2() {
/*
    if (!isset($_SESSION['sn'])) {
        $sn = session_id();
    } else {
        $sn = $_SESSION['sn'];
    }
    $u = current_iu();
    $pa = array(':KEY_SESSION' => $sn
        , ':XIP_REMOTE' => $_SERVER['REMOTE_ADDR']
        , ':XID_USER' => $u);

    $q = "select * from w2_SESSION_I4 ( :KEY_SESSION ,:XIP_REMOTE, :XID_USER )";
    $r = db_get($q, $pa, 2);

    $_SESSION['sn'] = $sn;
    $_SESSION['isn'] = $r['DS'][0]['ID_SESSION'];
    session_commit();
    return $sn;
 */
}



function db2_get_sn($sn = '') {
/*
    if (!isset($_SESSION['sn'])) {
        $sn = session_id();
    } else {
        $sn = $_SESSION['sn'];
    }

    $u = current_iu();
    $pa = array(':KEY_SESSION' => $sn
        , ':XIP_REMOTE' => $_SERVER['REMOTE_ADDR']
        , ':XID_USER' => $u);

    $q = "select * from w2_SESSION_I4 ( :KEY_SESSION ,:XIP_REMOTE, :XID_USER )";
    $r = db_get($q, $pa, 2);

// $_SESSION['sn'] = $sn;
//    $_SESSION['isn'] = $r['DS'][0]['ID_SESSION'];
//    $_SESSION['iu'] = $r['DS'][0]['U'];
//    $_SESSION['u'] = $r['DS'][0]['U'];
    return $sn;
  */
}

