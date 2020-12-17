<?php

/*
  mkb  845 
  tchk 315
 */


require_once ('ut.php'); 

@session_start();
$srv = $_SERVER['SERVER_NAME'];

 
//if ($_SERVER['QUERY_STRING'] == '') {$_SESSION['fb_autologin'] = 0;}


//require_once ('auth/auth_fb.php'); 
//if (facebook_autologin()) { exit; }


require_once ('db2.php'); 
$sn = db2_get_sn();


$email = val_sn('email');

$_SESSION['DIR'] = 'kotabl';
 

require_once ('ut2.php'); 


if (val_sn('LG_SITE','') == ''){ $_SESSION['LG_SITE'] = 'kotabl.ru'; }


$s = '
<div id="k0body">
    <div id="k0hdr">
	    <div style="text-align:center;">
		    <a href="index.php"><img id="m0img" style="max-width:300px;" src="kotabl/logo2.png"  /></a>'
            .'
	    </div>
         <br/> <a href="mailto:mail@kotabl.ru" > mail@kotabl.ru </a>
    </div>
</div>'
 

.cmenu_current_email('')
. iif( 1==1 && 'localhost'== $_SERVER['SERVER_NAME']
        ,  tag_('ul', tag_li_a('kotabl/reconciles.php','Сверки взаиморасчётов '
                     .cmenu( tag_li_a('h/h82_reconciles.php','Это бесплатный сервис упрощающий сверку взаиморасчётов между контрагентами')) ,a_target('m82') )
                  .tag_li_a('m/m83_papers.php?ax=8300','журнал операций',a_target('m83'))      
                  .tag_li_a('m/m84_causes.php?ax=8400','основание',a_target('m84'))  
                  .tag_li_a('m/m85_persons.php?ax=8500','контрагенты',a_target('m85')) 
               ) 
        );


$v = filemtime( __DIR__ .'\\kotabl\\kotabl.css') ;
echo  
	'<!DOCTYPE html >
		<HTML lang="ru" xml:lang="ru">
		<head>
            <meta name="yandex-verification" content="cb5d45972328dcee" />
			<BASE href="" />   <meta name="yandex-verification" content="cb5d45972328dcee" />
		' 
        .tag_('title','')
		.'<link href="/css/cmenu.css?'.$v.'" rel="stylesheet" type="text/css" />'
        .' <link href="kotabl/kotabl.css?'.$v.'" rel="stylesheet" type="text/css" />


</head>
<body class="kotabl" style="background: url(kotabl/bg.png) repeat-y;">'

        
     .$s .' - ' .$_SERVER['SERVER_NAME']


. iif( !isUserLoggedOn() , tag_div( md_file( __DIR__. '/kotabl/oferta2018.md' ) ) )
 .'
</body>
</html>';




/*
            <li><a href="kotabl/t001_price.php" onclick="s1menu_click(event)">Цены</a></li>
			<li><a href="kotabl/m1_submit.php" onclick="s1menu_click(event)" class="pix_sms" >Связаться</a></li>
			<li><a href="kotabl/t003_rules.php" onclick="s1menu_click(event)">Наши правила</a></li>
			<li><a href="kotabl/t001_about.php" onclick="s1menu_click(event)">Реквизиты</a></li>

	</div>			 
*/
/*

<div id="home_txt1">
 


<div style="display:table;margin-top:10px;width:100%">

<!-- 
	<div id="home_ad1" style="display:table-cell;max-width:50%;">
		<ul>
			<li>Регистрация ООО / готовые фирмы</li>
			<li>Внесение изменений в ЕГРЮЛ и учредительные документы</li>
		</ul>
		<p>	
			Мы оказываем данные услуги в рамках договора бухгалтерского сопровождения.
		</p>
	</div>



  form action="kotabl/enter.php?ax=4502&st=kotabl.ru&sn='.$sn.'" method="post" style="margin-left:20px;background-color:yellow;display:table-cell;min-width:200px;text-align:left;padding:10px;">
            Вход в кабинет:<br/>
                  <input name="m45name" type="text" value="'.val_rq('u','').'" placeholder="Псевдоним(логин):"/>
                  <input name="m45pwd" type="password"  placeholder="Пароль:"/>
                  <input type="submit" />
    </form
/-->

</div>

<!--
<div align="right" style="display:none;">
	<a href="kotabl/textbook/index.php">Бухгалтерский учёт нужен для эффективности ... </a>
</div>


<h1>Схема работы с нами:</h1>
<ol>
 <li>Вы звоните по телефону (905) 700-00-61 или направляете нам <a href="kotabl/m1_submit.php" onclick="s1menu_click(event)" >сообщение</a>.</li>
 <li>Определяем круг решаемых задач и способ взаимодействия.</li>
 <li>Заключаем договор.</li>
 <li>Исполняем договор ( смотрите <a href="kotabl/t003_rules.php" onclick="s1menu_click(event)">наши правила</a>).</li>
</ol>

</div>

/-->


';

*/
