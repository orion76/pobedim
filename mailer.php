<?php

//namespace PHPMailer\PHPMailer;
// @session_start();

require_once 'phpmailer/PHPMailer.php';
require_once 'ut.php';


$sid = get_sid();

class Mailer extends PHPMailer {

    function init() {

        $this->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $this->IsSMTP();

        $this->SetFrom('robot@kotabl.ru', 'robot@kotabl.ru');

        $this->Host = "kotabl.ru";
        $this->Port = 25;
        $this->SMTPSecure = "";


        /*
          $this->SetFrom('robot@kotabl.ru', 'robot@kotabl.ru');
          $this->SMTPAuth   = true;                  // enable SMTP authentication
          $this->SMTPSecure = "ssl";                 // sets the prefix to the servier
          $this->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
          $this->Port       = 465;                   // set the SMTP port for the GMAIL server
          $this->Username   = "robot@kotabl.ru";  // GMAIL username
          $this->Password   = " ";            // GMAIL password
         */

        //$this->AddReplyTo("kotabl.ru@yandex.ru","First Last");
        //$this->AddAttachment("images/phpmailer.gif");      // attachment
        //$this->AddAttachment("images/phpmailer_mini.gif"); // attachment

        /*
          $this->SMTPSecure = "ssl";
          $this->Host       = "smtp.yandex.ru";
          $this->Port       = 465;
          $this->Username   = "kotabl-ru";
          $this->Password   = " ";
          $this->SetFrom('kotabl-ru@yandex.ru', 'kotabl.ru');
         */
    }

    function Send_utf8($subj, $body, $address) {
        $this->CharSet = 'UTF-8';
        //$this->ContentType = 'text/plain';
        $this->ContentType = 'text/html';
        //$this->ContentType = 'multipart/mixed'; //'text/html';
        $this->Subject = $subj;
        $this->MsgHTML($body);
        $this->AddAddress($address, "");
        //$this->AddAddress('d@kotabl.ru', "");
        $r = $this->Send();
        $err = $this->ErrorInfo;
        if ($err == null) {
            $err = '';
        }
        if ($r === false) {
            $err = 'Mail is not sent ' . $address . ' ' . $err;
        } else {
            $err = '';
        }

        //if ($err != '' && 1 == 0) {
            
            $r= new db(DB_USER, 'insert into W0_MAILERR(mail,text_err)values(:m,:t)',[':m'=>$address,':t'=>$err]);
            file_put_contents('c:/temp/mailer-err.txt', $address.': '.$err);
            
        //}
        return $err;
    }

    function DoSend($subj, $body, $address) {
        $this->Subject = $subj;
        $this->MsgHTML($body);
        //$this->AddAddress($address, "");
        $this->AddAddress('d@kotabl.ru', "");
        $r = $this->Send();
        $err = $this->ErrorInfo;
        if ($err == null) {
            $err = '';
        }
        if ($r == false) {
            $err = 'Mail is not sent ' . $address . ' ' . $err . ' ' . $body;
        }

        if ($err != '') {
            $sid = get_sid();
            $sg = 680;
            $q = "select * from W1_SAYING_I1 ( :XID_SAYING_Q, :XLG_USER, :XTEXT_SAYING , :XT_SAYING, :XSESSION )";
            $pa = array(
                ':XID_SAYING_Q' => $sg
                , ':XLG_USER' => ''
                , ':XTEXT_SAYING' => $err
                , ':XT_SAYING' => 3
                , ':XSESSION' => $sid
            );
            db_get($q, $pa);
        }
        return $r;
    }

    function DoSend_utf8($subj, $body, $address) {
        $this->CharSet = 'UTF-8';
        $this->ContentType = 'text/plain';
        return $this->DoSend(val_sn('LG_SITE', '') . ' ' . $subj, $body, $address);
    }

    function DoSend_utf8_U($subj, $body, $xlg_user) {
        require_once ('m/m.php');
        $mb = get_user($xlg_user);
        if ($mb['ROW_COUNT'] == 1) {
            $address = $mb['DS'][0]['LIST_EMAIL1'];
            unset($mb);
            return $this->DoSend_utf8($subj, $body, $address);
        } else {
            return false;
        }
    }

}
