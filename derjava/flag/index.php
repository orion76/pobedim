<?php

$dir0 = dirname(__DIR__,2);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';

$ht = new html('/derjava/flag/flags.html');
$menu= menu_user($ht, '');
$data0 = [];
$data1 = [];

__tr($data1,14);
__tr($data1,6);
__tr($data1,11);
__tr($data1,10);        
__tr($data1,15);
__tr($data1,9);
__tr($data1,8);
__tr($data1,7);
__tr($data1,12);
__tr($data1,4);
__tr($data1,3);
__tr($data1,2);
__tr($data1,1);
 //  .__tr(5)        
        
$ht->data('head0',  $data0 );
$ht->data('data0', $data0 );
$ht->data('data1', $data1 );
$ht->data('menu1', $menu );

echo $ht->build('',true);


exit;


function __tr(&$data1,$i){
    $c4 = '';
    $ns = __DIR__ .'/flags/'.$i.'.php';
    if (file_exists($ns)){
        require_once($ns);
        array_push($data1,['IMGSRC'=>'/derjava/flag/flags/'.$c2
                ,'C1'=>$c1              
                ,'C3'=>$c3
                ,'C4'=>$c4
        ]);
    }
}









$z_color = array();
$z_animal = array();


array_push($z_color,
   ['name_color'=>'золото' ,'color'=>'gold'
    , 'meaning'=>'знатность, могущество, богатство; веру, справедливость, милосердие и смирение'
       ]);

array_push($z_color,
   ['name_color'=>'серебро','color'=>'silver'
    , 'meaning'=>'благородство,откровенность, чистота, невинность, праведность'
       ]);

array_push($z_color,
   ['name_name_color'=>'красный','color'=>'red'
    , 'meaning'=>'храбрость, мужество, любовь; кровь,пролитую в борьбе'
       ]);

array_push($z_color,
   ['name_color'=>'лазурь' ,'color'=>'azure'
    , 'meaning'=>'великодушие, честность, верность и безупречность; или просто небо'
       ]);

array_push($z_color,
   ['name_color'=>'зелень' ,'color'=>'green'
    , 'meaning'=>'надежда, изобилие, свобода и радость; или просто луговая трава'
       ]);

array_push($z_color,
   ['name_color'=>'пурпур' ,'color'=>'purple'
    , 'meaning'=>'благочестие, умеренность, щедрость, верховное господство'
       ]);

array_push($z_color,
   ['name_color'=>'чёрный' ,'color'=>'black'
    , 'meaning'=>'осторожность, мудрость, постоянство в испытаниях; печаль, траур; богатство земли'
       ]);

//----------------------------------------------------------


array_push( $z_animal ,
        [
            'name_animal'=> 'собака'
            ,'text' => 'преданность и повиновение'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'кошка'
            ,'text' => 'независимость'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'волк'
            ,'text' => 'злость и жадность'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'медведь'
            ,'text' => 'предусмотрительность'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'бык'
            ,'text' => 'плодородие земли'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'овца'
            ,'text' => 'кроткость'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'лань'
            ,'text' => 'робость'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'олень'
            ,'text' => 'воин'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'лошадь'
            ,'text' => 'храбрость льва, сила вола, быстрота оленя, ловкость лисицы'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'орёл'
            ,'text' => 'власть и великодушие'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'ворон'
            ,'text' => 'долголентие'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'пеликан'
            ,'text' => 'любовь родителей к детям'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'дельфин'
            ,'text' => 'сила'
        ]);

array_push( $z_animal ,
        [
            'name_animal'=> 'змея'
            ,'text' => 'вечность'
        ]);
