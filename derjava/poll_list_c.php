<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/ods1.php'; 

require_once $dir0 . '/derjava/fn.php';
//include $dir0 . '/derzhava/derzhava_fn.php';


$ax = val_rq('ax', 0);
$bx = val_rq('bx', 0);

$fu = current_fu();
$iu = $fu['iu'];
$u = $fu['iu'];

$iv = val_rq('iv', 0) + 0;
$qq = val_rq('qq', 0) + 0;
$pa = val_rq('pa', 0) + 0;

$lon = val_rq('lon','user');


$err = '';

    $r7 = new db(DB_POBEDIM, 'select * from W2_POLL_CERTIFICATES_L3 (:u,:qq, :pa, :lon)'
            , [':u'=>$u,  ':qq' => $qq, ':pa' => $pa, 'lon'=>$lon.$ax]
            ,function($row,$pp,$lp){ 
        
               if ($row['IS_CONFIRMED_U'] == '1')
               {
                   $row['CONFIRM_CHG'] = 'clear';
                   $row['CONFIRM_MINUS'] = (1*$row['CNT_CONFIRMED']-1);
                   $row['CONFIRM_PLUS'] = $row['CNT_CONFIRMED'];
               } else {
                   //$row['IS_CONFIRMED_U'] = null;
                   $row['CONFIRM_CHG'] = 'exposure_plus_1';
                   $row['CONFIRM_MINUS'] = $row['CNT_CONFIRMED'];
                   $row['CONFIRM_PLUS'] = (1*$row['CNT_CONFIRMED']+1);
               }
            
               $u=$pp[':u'];
               $pa=$pp[':pa'];
               $qq=$pp[':qq'];
               $ct=$row['ID_CERTIFICATE'];
               $df =  $lp['dir0']. "/data.poll/$qq/$pa/$ct/$u/";

               if (file_exists($df)){
                  $aa= directoryToArray($df, false);
               } else $aa=array();

               if (count($aa) > 0) $row['FILE_ATTACHED'] = 1; else  $row['FILE_ATTACHED'] = null;
        
        
                if (!isset($row['NAME_ROLE_VECHE'])) $row['NAME_ROLE_VECHE'] = 'Гость';
        
                $row['NO'] = null;
                $row['YES'] = null;
                $row['CHECK'] = null;
                if ($row['ID_KIND_ANSWER'] == 2) $row['CHECK'] = 1;
                if ($row['ID_KIND_ANSWER'] == 1) $row['YES'] = 1;
                if ($row['ID_KIND_ANSWER'] == -1) $row['NO'] = 1;
                if ($row['U_ADMIN'] == $row['U_DELEGATE']) $row['HAS_DELEGATE']=null; else $row['HAS_DELEGATE']=1;
                return $row; 
            },['dir0'=>$dir0]
            );
    $r0 = new db(DB_POBEDIM, 'select * from W2_POLL_ANSWER where ID_ANSWER = :pa',[':pa'=>$pa]);

//------------------------------------------------------------
if ($ax === 'getfile'){
try
{
	$name =  '/derjava/spisok_golosov.ots';
	$fn = dirname( __DIR__ ) . $name ;
	$fn = str_ireplace('\\','/',$fn);
	$fn = 'file:///'.str_ireplace('//','/',$fn);

	$f = new ods();
	$f->init2($fn);
        $sheet=$f->sheets->getByIndex(0); //$sheet=$bt->sheets->getByName('faktura');

        ods_setstring($sheet,'C2', "https://pobedim.su/derjava/poll_list_c.php?qq=$qq&pa=$pa&ax=getfile" );
        ods_setstring($sheet,'A3', $r7->length() );
        ods_setstring($sheet,'B3', $r0->row('NAME_ANSWER') );
        
    $j = 5;
    foreach($r7->rows as $row){
       
        
        $j++;
        ods_setstring($sheet,'A'.$j, $j-5);
        ods_setstring($sheet,'B'.$j, $row['NAME_CERTIFICATE']);
       // if ($row['NAME_CERTIFICATE'] !== $row['NAME_U_DELEGATE']) {
            ods_setstring($sheet,'C'.$j, $row['CONTACT_CERTIFICATE']
                    .' '.$row['NAME_U_DELEGATE'].' '.$row['CONTACT_U_DELEGATE']
                    );
       // }
        ods_setstring($sheet,'D'.$j, $row['TS_SYS']);
        ods_setstring($sheet,'E'.$j, $row['ID_KIND_ANSWER']);
        ods_setstring($sheet,'F'.$j, $row['NAME_ROLE_VECHE']);
        ods_setstring($sheet,'H'.$j, $row['ID_CERTIFICATE']);
        ods_setstring($sheet,'I'.$j, $row['MD5_CERTIFICATE']);
        ods_setstring($sheet,'K'.$j,  $row['NAME_U_ADMIN'].' '.$row['CONTACT_U_ADMIN'] );
    }


	$mysave = $f->newprop();
	$mysave->Name="FilterName";
	$mysave->Value="MS Excel 97";
	$varArr = array($mysave);
	
	$fn = 'file:///'.dirname(__DIR__).'/tmp/'.$f->fn ;
	$fn = str_ireplace('\\','/',$fn);
	
	$f->oodoc->storeAsURL($fn,$varArr);
	$f->Free();

	unset( $f);

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=spisok_golosov.xls");
	readfile($fn);
	unlink($fn);
	//echo '<a href="./tmp/'.$f->fn.'">'.$f->fn.'</a>';
}
  
catch (Exception $e) {
	print_r( array('R'=>'Caught exception: ' . iconv('Windows-1251','UTF-8//TRANSLIT', $e->getMessage()) ));
}
    exit();
}
//------------------------------------------------------------





$ht = new html('/derjava/poll_list_c.html');
$menu= menu_user($ht, '');

$data0 = $r0->rows;
$data0[0]['ID_POLL'] = $qq;
$data0[0]['ID_ANSWER'] = $pa;
$data0[0]['CNT_ANSWER'] = $r7->length();

if ($lon == 'user')  $data0[0]['VISIBLE_SHOWALL'] = 1; else $data0[0]['VISIBLE_SHOWALL'] = null;


$ht->data('data0', $data0 );
$ht->data('data7', $r7->rows );

echo $ht->build('',true);

 