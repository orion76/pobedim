<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/mailer.php';


$ax = val_rq('ax');
$s =  val_rq('s');
$email=  val_rq('ml');
$rz = '';

if ($ax=='log_off'){
    logOff();
    echo htm_redirect1(f_host(). '/index.php', '');
    exit;
}


if ($ax=='btn1'){
  
 $pw1 = va($_SESSION,'PW'); // пароль, который введён на форме
 $pw2 = va($_SESSION,'PW_NEW'); // пароль сгенерированный системой
 
 $cf = get_f_user($email,'email');
    
 $pw2 = '11111';
         
 if ( $pw1 == $pw2 || $pw1 == va($cf,'pw') ) {
      
    if ($cf == false || $cf == null) {
        $cf['email'] = $email;
        $cf['email_notify_confirmed'] = $email;
        $cf['email_notify'] = $email;
        $cf['pw'] = $pw1;
    }  
      
    $rz = 'ok';
    logOn($cf);
  }
  else $rz='';
  
} else
if ($ax=='btn2'){
    
        $_SESSION['PW_NEW'] = substr( rand() ,0,5);
               
        $m = new Mailer();
        $m->init();
        // $_SESSION['PW_NEW'].' '.
        $sx =  $m->Send_utf8('pw', 'password: ' . $_SESSION['PW_NEW'] , $email);    
        if ( trim($sx) == '') $rz = 'Пароль был отправлен на почту.'; else $rz = 'Ошибка отправки почты, err: '.$sx;
        unset($m);
        } 
else    
if ($ax=='ln' || $s == ''){
    $rz = 'pw cap';
    $_SESSION['PW'] = '';
} else {
    if ($ax=='pw' ){
        $rz = 'pw bn1';
        $_SESSION['PW'] = $s;
    }

    if ($ax=='cap'){
        $rz = 'cap bn2';
        if ($s == $_SESSION['captcha']) $rz = 'cap bn2'; else $rz = 'cap ';
        $_SESSION['PW'] = '';
    }
}

echo $rz;