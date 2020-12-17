<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';


$ax = val_rq('ax', 0);
$fu = current_fu();
$u = current_iu();


$ht = new html('/derjava/ek_sxema.html');
$menu= menu_user($ht, '');
 
$data0[0]['TEXT'] = md_parse(<<<TXT
        
        
###Первые наброски экономической модели народовластия

В основе  экономических взаимоотношений в системе народовластия лежит труд.  
Соотнесение разновидностей труда осуществляется по  справочнику. 
При построении тарифной сетки учитывается  срок обучения, срок стажировки, важность той или иной профессии для народа.

Природные ресурсы распределяются бесплатно по нормативам и не являются объектами купли-продажи внутри страны. 
Продажа ресурсов сверх нормативов и на экспорт осуществляется только государственными предприятиями с позиции экологической сообразности.

Госплан  формирует  госзаказ на основе статистических данных и прямых заказов потребителей.

Государство ограничивает поле своей деятельности только социальными потребностями для обеспечения жизни  общества, прочее потребление обеспечивается деятельностью предпринимателей.

Пенсионеры  признаются госслужащими и их деятельность оценивается как воспитательная, их важная роль по воспитанию внуков и обеспечение преемственности поколений. 
Соответственно, размер пенсии определяется исходя из количества внуков. Люди, которые не занимались воспитанием детей и не тратились на них, вполне могут самостоятельно 
накопить пенсию или через пенсионный фонд (но это дополнительные возможночти обеспечения).        

Родители малолетних детей поощряются как воспитатели

Продавец имеет самую низкую ставку по оплате труда.

Оборона, Образование, Медицина — госслужащие

Госплан создаёт справочник трудозатрат и материальёмкость каждого вида продукции.  Эти данные используются при исчислении налога  у предпринимателей.


Чтобы обеспечить  стабильность фуполнения гос функций  госпредприятия реализуют товары народного потребления по фиксированным ценам  деноминированным в соц рублях.
Зарплата  в  госорганизациях выплачивается в  соц рублях. 

Госплан устанавливает цены и зарплаты в безинфляционных соцрублях для всех специальностей и категорий работников


Каждый вид продукции имеет  нормативную с/с  ( труд + материал/товар  ).  
Налоговой базой считается выручка за вычетом нормативной себестоимости (прочие выплаты не принимаются для целей налогооблажения).
Шкала налогооблажения — прогрессивная.   
Кроме налога с дохода существует экологический сбор, которым облагается каждая единица  продукции. 
Налог на имущество прогрессивный (деприватизационный), жильё до 100 м2 на человека без налога.

Как вариант, заменить все налоги единым налогом с оборота (убивает чёрный нал), а налоговым агентом сделать банк.        
        
        
Госпредприятия не  производят денежных взаиморасчётов. 
Выручка из  торговой сети зачисляется в бюджет и из бюджета напрямую выплачивается заработная плата.

Предприниматели могут  брать на исполнение госзаказ и погашать свои налоговые обязательства  соответствующей продукцией или трудом.

Отчётность предоставляется в натуральных показателях, а предприниматели дополняют её также суммой выручки.
Сдача в аренду или выдача займов  не тарифицируются, трудозатраты равны нулю.

Госплан формирует госзаказ в натуральных показателях  и  за выполнение выплачивает в соц рублях.

Денежное обращение регулирует госбанк.  
Госбанк эмитирует соц рубли, инкассирует экспортную выручку, делает платежи за рубеж,  продаёт валюту ком банкам,
устанавливает курсы валют рын рубля к соц рублю,
рыночный рубль не конвертируется в соц рубль, инкассирует налоги в рыночных рублях, эмитирует рыночные рубли.

Курс соц рубля =  ФЗП рынка/ ФЗП соц сектора

Курс рын рубля =   Импортный труд / экспортный труд
       
        
        
TXT
        );
        
$ht->data('data0', $data0 );
 

echo $ht->build('',true);


 
