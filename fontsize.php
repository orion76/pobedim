<?php

require_once('ut.php');

$ww = val_rq('ww',0);
$hw = val_rq('hw',0);

 $_SESSION['width_window'] = $ww;
 $_SESSION['height_window'] = $hw;


 $ua=$_SERVER['HTTP_USER_AGENT'];
 $ip=$_SERVER['REMOTE_ADDR'];
 
 $r = db_row('select * from s2_http where user_agent=:ua and IP_HOST = :ip '
            , [ ':ua'=>$ua, ':ip'=>$ip] 
            , DB_POBEDIM );

    if ( ($r['ROW_COUNT'] === 0)
            || ( $ww>0 && $hw >0  && $ww !== $r['WIDTH_WINDOW'] && $hw !== $r['HEIGHT_WINDOW'] ) ) {
    $r = db_row('update or insert into s2_http(user_agent, ts_sys, WIDTH_WINDOW, HEIGHT_WINDOW,  IP_HOST) '
                . ' values (:ua, current_timestamp,:ww, :hw,  :ip)'
            , [':ua'=>$ua, ':ww'=>$ww, ':hw'=>$hw , ':ip'=>$ip]
            , DB_POBEDIM );
    }
   

    echo '';
   

