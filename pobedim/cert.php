<?php


$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
$dir = $dir0;
require_once $dir0 . '/ut.php';


echo htm_redirect1('/derjava/home.php');

exit;












$dir = dirname(__DIR__);
require_once ($dir . '/ut.php');

$html = array_html();
$_SESSION[SN_HTML_BASE] = '';

$html['title'] = 'Информация о сертификате подписи | pobedim.su';

$ax = val_rq('ax');
$bx = val_rq('bx');

$ic = val_rq('ic');

if ($ax == 'admin') {

    if ($bx == 'save_cert') {
        $s = '<?php  $c = [];' . chr(13) . chr(10)
                . wrap_assignvalue_as_php_("$ic", '$c["ic"]')
                . wrap_assignvalue_as_php_(val_rq('snils'), '$c["snils"]')
                . wrap_assignvalue_as_php_(val_rq('n1p'), '$c["n1p"]')
                . wrap_assignvalue_as_php_(val_rq('n2p'), '$c["n2p"]')
                . wrap_assignvalue_as_php_(val_rq('n3p'), '$c["n3p"]')
                . wrap_assignvalue_as_php_(val_rq('sex_p'), '$c["sex_p"]')
                . wrap_assignvalue_as_php_(val_rq('city_p'), '$c["city_p"]')
                . wrap_assignvalue_as_php_(val_rq('citizenship_p'), '$c["citizenship_p"]')
                . wrap_assignvalue_as_php_(val_rq('mob_p'), '$c["mob_p"]')
                . wrap_assignvalue_as_php_(val_rq('phone_p'), '$c["phone_p"]')
                . wrap_assignvalue_as_php_(val_rq('email_p'), '$c["email_p"]')
                . wrap_assignvalue_as_php_(val_rq('d_birth_p'), '$c["d_birth_p"]')
                . wrap_assignvalue_as_php_(val_rq('place_birth_p'), '$c["place_birth_p"]')
                . wrap_assignvalue_as_php_(val_rq('state_birth_p'), '$c["state_birth_p"]')
                . wrap_assignvalue_as_php_(val_rq('a1_p'), '$c["a1_p"]')
                . wrap_assignvalue_as_php_(val_rq('a2_p'), '$c["a2_p"]')
                . wrap_assignvalue_as_php_(val_rq('a3_p'), '$c["a3_p"]')
                . wrap_assignvalue_as_php_(val_rq('a4_p'), '$c["a4_p"]')
                . wrap_assignvalue_as_php_(val_rq('a5_p'), '$c["a5_p"]')
                . wrap_assignvalue_as_php_(val_rq('a6_p'), '$c["a6_p"]')
                . wrap_raw_text_as_php_(val_rq('text_p'), '$c["text_p"]')
        ;
        $nf = $dir . '/pobedim/cert/db.cert/' . $ic . '.php';
        file_put_contents($nf, $s);
        echo htm_redirect1("/pobedim/cert.php?ic=$ic&ax=admin");
        exit;
    }


    $iu_d = '0000';
    $nu_d = 'Иванов Иван Иванович';

    $t = <<<EOT

# pobedim.su
## Сертификат подписи № $ic

Сертификат используется при подсчёте результатов голосования в которых участвовал
$nu_d [$iu_d] и при подаче исковых заявлений.

Для голосования на городском уровне и выше от вас не требуется никаких сведений кроме СНИЛС ( РФ),
используемого для подтверждения  уникальности вашей подписи.
(сейчас вообще ничего не требуется, но в будущем мы будем это использовать, чтобы не было накруток голосов)


НИКАКИХ ЛИЧНЫХ ДАННЫХ ДЛЯ ЦЕЛЕЙ ГОЛОСОВАНИЯ ЗАПОЛНЯТЬ НЕ НУЖНО.


Проверяйте использование сертификата в голосованииях. Если ваше мнение стало не совпадать с позицией делегата ($nu_d [$iu_d]),
то предайте сертификат другому делегату или голосуйте сами.


<hl>

### Специальные сертификаты пользователей

Для исковых заявлений в международный требуются сведения согласно перечня ниже.
Эти данные хранятся обособленно.


Для включение вас в исковые заявления от вас требуется дополнительно соответствующие
письма по электронной почте и/или наземной в зависимости от требований суда.



<br>
   Функционал по работе с сертификатом будет сделан позже.
<br>


EOT;

    html_body($html, md_parse($t));


    $c = [];
    $nf = $dir . '/pobedim/cert/db.cert/' . $ic . '.php';
    if (file_exists($nf)) {
        require_once ($nf);
    }

    html_body($html,
            tag_form("?ax=admin&bx=save_cert&ic=" . $ic
                    , BR1 . 'СНИЛС' . tag_input0('snils', $c['snils'], a_placeholder('СНИЛС') . a_size(50))
                    . BR1 . 'Имя ' . tag_input0('n1p', $c['n1p'], a_placeholder('Имя') . a_size(50))
                    . BR1 . 'Отчество ' . tag_input0('n2p', $c['n2p'], a_placeholder('Отчество') . a_size(50))
                    . BR1 . 'Фамилия ' . tag_input0('n3p', $c['n3p'], a_placeholder('Фамилия') . a_size(50))
                    . BR1 . 'Пол ' . tag_input0('sex_p', $c['sex_p'], a_placeholder('sex') . a_size(50))
                    . BR1 . 'Город проживания ' . tag_input0('city_p', $c['city_p'], a_placeholder('City') . a_size(50))
                    . BR1 . 'Государство ' . tag_input0('city_p', $c['city_p'], a_placeholder('State of Residence') . a_size(50))
                    . BR1 . 'Мобильный тел.' . tag_input0('mob_p', $c['mob_p'], a_placeholder('Mobile phone') . a_size(50))
                    . BR1 . 'Домашний тел. ' . tag_input0('phone_p', $c['phone_p'], a_placeholder('Private phone') . a_size(50))
                    . BR1 . 'Эл. почта ' . tag_input0('email_p', $c['email_p'], a_placeholder('Email') . a_size(50))
                    . BR1 . 'Дата рождения ' . tag_input0('d_birth_p', $c['d_birth_p'], a_placeholder('Date of Birth') . a_size(50))
                    . BR1 . 'Место рождения ' . tag_input0('place_birth_p', $c['place_birth_p'], a_placeholder('Place of Birth') . a_size(50))
                    . BR1 . 'Государство места рождения ' . tag_input0('state_birth_p', $c['state_birth_p'], a_placeholder('State of Birth') . a_size(50))
                    . BR1 . 'Гражданство ' . tag_input0('citizenship_p', $c['citizenship_p'], a_placeholder('Nationality') . a_size(50))
                    . BR1 . 'Адрес, строка 1 ' . tag_input0('a1_p', $c['a1_p'], a_placeholder('Address') . a_size(50))
                    . BR1 . 'Адрес, строка 2 ' . tag_input0('a2_p', $c['a2_p'], a_placeholder('Address') . a_size(50))
                    . BR1 . 'Адрес, строка 3 ' . tag_input0('a3_p', $c['a3_p'], a_placeholder('Address') . a_size(50))
                    . BR1 . 'Адрес, строка 4 ' . tag_input0('a4_p', $c['a4_p'], a_placeholder('Address') . a_size(50))
                    . BR1 . 'Адрес, строка 5 ' . tag_input0('a5_p', $c['a5_p'], a_placeholder('Address') . a_size(50))
                    . BR1 . 'Адрес, строка 6 ' . tag_input0('a6_p', $c['a6_p'], a_placeholder('Address') . a_size(50))
                    . '<hl>'
                    . BR1 . 'Комментарий ' . tag_textarea('text_p', $c['text_p'], a_placeholder('Комментарий нигде не используется') . a_('cols', 50) . a_('rows', 5))
                    . BR1 . tag_submit()
            )
    );
    /*
      Г-ну СИДОРОВУ И.Ю.
      а/я 11,
      отделение почтовой связи № 1,
      г. Старый Оскол,
      Белгородская обл., 309502
      РОССИЯ / RUSSIE
     */


    echo tag_html2($html);
    exit;
}



if (!isset($_POST['ic']) || empty($ic)) {




    html_body($html, tag_('h2', 'Введите номер сертификата голоса для получения информации'));


    html_body($html, tag_form('?ax=info',
                    tag_input0('ic', '434235285344354', a_placeholder('Номер сертификата подписи') . a_size(50))
                    . tag_submit()
                    , a_style(CSS_TEXTCENTER . 'width:100%;')));


    html_tt($html, 'body', <<<TXT
        Вы всегда можете узнать как "проголосовал" ваш сертификат.
То есть, делегировав право голоса делегату, вы в любой момент можете проверить как был использован
ваш голос.  Если ваше мнение не совпадает с делегатом, то берите свой голос в свои руки и
голосуйте самостоятельно, либо передайте его другому лицу.

        
TXT
    );

    echo tag_html2($html);
    exit;
}



html_body($html, tag_('h1', 'Информация по сертификату № ' . $ic));




$r = db_foreach(function(&$row, &$lp) {
    return tag_tr(
            tag_td(tag_a('/derzhava/derzhava_golosovanie.php?qq=' . $row['ID_POLL'], tag_img('/32/newwdw32.png'), a_target('qq')))
            . tag_td($row['NAME_ANSWER'])
            . tag_td($row['NAME_POLL'])
            . tag_td($row['NAME_VECHE'])
    );
}, <<<EOT
         select pc.* , pa.NAME_ANSWER, v.NAME_VECHE, qq.NAME_POLL
               from  W2_POLL_CERTIFICATES pc
                inner join w2_poll_answer pa on pa.ID_ANSWER = pc.ID_ANSWER
                inner join w2_poll qq on qq.id_poll = pa.id_poll  and
                                         qq.SORTING_POLL is distinct from -1
                left outer join w2_veche v on v.veche = qq.veche
            where pc.ID_CERTIFICATE  = :ic

EOT
        , [':ic' => $ic], [], DB_POBEDIM);

html_body($html, tag_table(tag_('caption', 'Результаты голосования по сертификату') . $r['S']));



html_body($html,
        tag_div(<<<'EOT'

 Если вы владелец сертификата и ваше мнение не совпадает с результатами голосования,
 то прекратите действие этого сертификата и голосуйте сами или передайте свой голос другому делегату,
 мнение которого полностью разделяете.

EOT
        )
        . tag_form("?ax=admin"
                , tag_input0('pw', '', a_placeholder('пароль для управления сертификатом') . a_size(40), 'password')
                . tag_hidden('ic', $ic)
                . tag_submit()
                , a_target('ic'))
);


echo tag_html2($html);

