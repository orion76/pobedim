<?php



function login_tag_li_a_odnoklassniki($rx_onEnter){
    return tag_li_a( '/auth/login.php?ax=110&ok=1&rx='. urlencode($rx_onEnter) 
            , '<img src="/auth/64-ok.png">'
            
            );
}

function login_tag_li_a_vkontakte($rx_onEnter){
    return tag_li_a( '/auth/login.php?ax=109&vk=1&rx='. urlencode($rx_onEnter) , '<img src="/auth/64-vk.png">');
}

function login_tag_li_a_facebook($rx_onEnter){
    return tag_li_a( '/auth/login.php?ax=108&fb=1&rx='. urlencode($rx_onEnter) , '<img src="/auth/64-fb.png">');
}

function login_tag_li_a_email($rx_onEnter){
    return tag_li_a('/auth/login.php?ax=100&rx=' . urlencode($rx_onEnter), '<img src="/auth/64-eml.png">');
}


function htm_redirect_login_fb_($rx){
    return '<html><head><meta http-equiv="refresh" content="0;url='
            .url_redirect_login_fb_($rx)
            .'"></head><body>'.''.'</body></html>'; 
}

function url_redirect_login_fb_($rx){
    $FB_APP_ID = "";
    $query = urldecode(http_build_query(array(
            "client_id"     => $FB_APP_ID,
            "redirect_uri"  => 'https://pobedim.su/auth/login_fb.php',
            "response_type" => "code"
    )));
    $url = "https://www.facebook.com/dialog/oauth?".$query ;
    return $url;
}

function htm_redirect_login_ok_($rx){
    return '<html><head><meta http-equiv="refresh" content="0;url='
            .url_redirect_login_ok_($rx)
            .'"></head><body>'.''.'</body></html>'; 
}

function url_redirect_login_ok_($rx){
    $OK_APP_ID = ""; 
    $OK_SECRET_CODE = "";   
    $OK_PUBLIC_CODE = "";

    $url= urlencode( 'https://pobedim.su/auth/login_ok.php' );
    $s = 'https://connect.ok.ru/oauth/authorize?'
            . 'client_id='.$OK_APP_ID
            . '&scope=VALUABLE_ACCESS'
            . '&response_type=code'
            . '&redirect_uri='.$url;
    return  $s; 
}

function htm_redirect_login_vk_($rx){
    return '<html><head><meta http-equiv="refresh" content="0;url='
            .url_redirect_login_vk_($rx)
            .'"></head><body>'.''.'</body></html>'; 
}

function url_redirect_login_vk_($rx){
    $VK_APP_ID = ""; 
        
    return  'https://oauth.vk.com/authorize?'
            . 'client_id='.$VK_APP_ID
            . '&redirect_uri=https://pobedim.su/auth/login_vk.php'
            . '&response_type=code'
            . '&display=page&v=5.85';
}
