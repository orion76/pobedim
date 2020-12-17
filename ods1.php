<?php

@session_start();
require_once ('ut.php'); 

ini_set('max_execution_time','300');

/*

DCOMCNFG.exe -  Служба компонентов/Компьютер/Настройка DCOM/LibrOffice -> контекстное меню Свойства
идинтификация(удостоверение) должна быть такойже как
 и на сайте IIS (Начальная страница/IIS/Проверка подлинности: Анонимная проверка подлинности\Указанный пользователь:admin), например admin.  
Главное, чтобы хотя бы 1 раз пользователь уже запускал LibrOffice

com.sun.star.ServiceManager
--norestore --invisible --nologo --headless  

{82154420-0FBF-11d4-8313-005004526AB4}
LibreOffice Service Manager (Ver 1.0)

--headless  

требуется установить  libroffice 32bit , если php 32bit !
 
[PHP_COM_DOT_NET]
extension=php_com_dotnet.dll


в IIS  должен быть определён тип MIME
// https://wp-kama.ru/id_8643/spisok-rasshirenij-fajlov-i-ih-mime-tipov.html 
.ots=application/vnd.oasis.opendocument.spreadsheet-template
.ods=application/vnd.oasis.opendocument.spreadsheet
*/

function ods_loadfile($nf, $sheetname=0 , $id_import = 0 ){
    // fn == function ($row, $col, $value )
    $r = false;
    $bt = new ods();
    $bt->init2($nf);
    //$sheet=$f->sheets->getByName('УПД');
    $sheet=$bt->sheets->getByIndex($sheetname);

    $bt->sheet_activated($sheet);
    $nEndColumn = $bt->nEndColumn;
    $nEndRow = $bt->nEndRow;
    $range = $sheet->getCellRangeByPosition(0, 0, $nEndColumn, $nEndRow);
    $a = $range->getDataArray();
    $bt->Free();

    $row = 0;
    foreach( $a as $a0 )
    {
        $col = 0;
        foreach( $a0 as $a1 )
        {
            $sx = iconv( "WINDOWS-1251","UTF-8", $a1);
            if ($sx != '' && $sx != null) 
            {
                $q = "select * from S1_IMPORTDATA_I ( :ID_SESSION, :ID_IMPORTDATA, :ROW_DATA, :COL_DATA, :CELLVALUE)";
                $pa = array( ':ID_SESSION' => null
                            ,':ID_IMPORTDATA' => $id_import
                            ,':ROW_DATA' => $row
                            ,':COL_DATA' => $col
                            ,':CELLVALUE' => $sx
                        );
                $r = db_get($q, $pa);
            }
            $col = $col+1;	
        }
        $row = $row+1;  
    }
    return $r;
}


function ods_setValue($xSheet,$xCellName,$xValue,$xNumberFormat=10000)
{
    $r = $xSheet->getCellRangeByName($xCellName)->getCellByPosition(0, 0);
    $r-> NumberFormat = $xNumberFormat;
    $r->SetValue($xValue);
	return $r;
}

function ods_cell($xSheet,$xCellName)
{
    
	return $xSheet->getCellRangeByName($xCellName)->getCellByPosition(0, 0);
}

function ods_setstring($xSheet,$xCellName,$xString)
{
	$c = $xSheet->getCellRangeByName($xCellName)->getCellByPosition(0, 0);
	if ($xString == null) $xString = '';
	$c->SetString ( iconv( "UTF-8","WINDOWS-1251",$xString ) );
}

function ods_rowsInsert($xSheet,$xRow,$xCnt)
{
  $tr = $xSheet->getRows();
  $tr->insertByIndex($xRow, $xCnt);
}

function ods_rowsRemove($xSheet,$xRow,$xCnt)
{
	$tr = $xSheet->getRows();
	$tr->removeByIndex($xRow, $xCnt);
}

function ods_writecol_ix($xSheet,$xRowStarting,$xCol,$xCount ){
    $a = array();
    for ($i=1; $i<=$xCount; $i++){
        array_push($a, array( ''.$i ) ); 
    }
    $r = $xSheet->getCellRangeByPosition($xCol,$xRowStarting,$xCol,$xRowStarting+count($a)-1);
    $r->setDataArray($a);
}

function ods_writecol($xSheet,$xRowStarting,$xCol,$xArray, $xArrayKey, $mode=0 ,$current_ods = null)
{
    $NumberFormat1 = 108;
    if ($current_ods != null){
        $locale = $current_ods->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
        $locale->Country = 'US';
        $locale->Language = 'en';
        $NumberFormats=$current_ods->oodoc->getNumberFormats();
        $NumberFormat1 = $NumberFormats->getStandardFormat( 16, $locale);
    }

    $rownb = 0;
    $aa= array_column($xArray,$xArrayKey);
    $a = array();
    
    foreach( $aa as $aa1 )
    {		
        if ($mode == 1) {
            $r = $xSheet->getCellByPosition($xCol,$xRowStarting+$rownb);
            $r->SetValue( $aa1 );
            $r-> NumberFormat = $NumberFormat1;
        } else {
            if ($mode == 2) $v = $aa1;
            else $v = iconv( "UTF-8","WINDOWS-1251", $aa1);
            array_push($a, array( $v ) );
        }
        $rownb++;
	}
    
/*
if ( $current_ods != null) {
$local = $current_ods->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
            $local->Country = 'US';
            $local->Language = 'en';
        $NumberFormats=$current_ods->oodoc->getNumberFormats();
        $NumberFormat1 = $NumberFormats->getStandardFormat( 16, $local);
        //$NumberFormats->queryKey("### ### ##0.00", $local, 1);
        // $NumberFormat1 = $NumberFormats->getFormatForLocale(108,$local);
        $c = $r-> getCellByPosition(0,0);
        $c -> NumberFormat = $NumberFormat1;
        $c->cellBackColor = 255;
    }
*/
    if ($mode != 1) {
        $r = $xSheet->getCellRangeByPosition($xCol,$xRowStarting,$xCol,$xRowStarting+count($a)-1);
        $r->setDataArray($a);
    }
}



class ods 
{
   var $fn;
   var $zfn;
   var $oo = null;
   var $desk;
   var $sheets;
   var $sheet;
   var $oodoc;
   var $localeEN; // англ локаль нужна для числовых полей с разделителем "."
   var $localeRU; // в русской локале разделитель ","
   var $NumberFormats;

   var $nEndColumn;
   var $nEndRow;
   
   function setCellValue($xoCell,$xValue,$xNumberFormat=10000){
       $xNumberFormat = $this->NumberFormatFloatEn;
       $xoCell-> NumberFormat = $xNumberFormat;
       if ($xValue==null) $xValue='';
       try{
           if (is_float( 0.00+$xValue+0.00)) $xoCell->SetValue( 0+$xValue+0 );
           else $xoCell->SetString($xValue);
       }
       catch (Exception $e) {
           $xoCell->SetString($xValue); 
       }
   }

   function setValue($xCellName,$xValue,$xNumberFormat=10000,$xSheet = null)
   {
       $xSheet = $this->sheet;
       $r = $xSheet->getCellRangeByName($xCellName)->getCellByPosition(0, 0);
       $this->setCellValue($r,$xValue,$xNumberFormat);
       return $r;
   }

   function writeArrayCol($xRowStarting,$xCol,$xArray, $xArrayKey, $method=0,$xSheet = null)
   {
       $xSheet = $this->sheet;
//       $NumberFormat1 = 108;
       $NumberFormat1 = $this->NumberFormats->getStandardFormat( 16, $this->localeEN);

       $rownb = 0;
       $aa= array_column($xArray,$xArrayKey);
       $a = array();
       
       foreach( $aa as $aa1 )
       {	
           //if (is_callable($method)) {}
           if ($method == 1) {
               $r = $xSheet->getCellByPosition($xCol,$xRowStarting+$rownb);
               $this->setCellValue($r,$aa1,$NumberFormat1);
           } else {
               if ($method == 2) $v = $aa1;
               else $v = iconv( "UTF-8","WINDOWS-1251", $aa1);
               array_push($a, array( $v ) );
           }
           $rownb++;
       }
       
       /*
       if ( $current_ods != null) {
       $local = $current_ods->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
       $local->Country = 'US';
       $local->Language = 'en';
       $NumberFormats=$current_ods->oodoc->getNumberFormats();
       $NumberFormat1 = $NumberFormats->getStandardFormat( 16, $local);
       //$NumberFormats->queryKey("### ### ##0.00", $local, 1);
       // $NumberFormat1 = $NumberFormats->getFormatForLocale(108,$local);
       $c = $r-> getCellByPosition(0,0);
       $c -> NumberFormat = $NumberFormat1;
       $c->cellBackColor = 255;
       }
        */
       if ($method != 1) {
           $r = $xSheet->getCellRangeByPosition($xCol,$xRowStarting,$xCol,$xRowStarting+count($a)-1);
           $r->setDataArray($a);
       }
   }

 
   function __construct() 
   {
	   $this->fn = 'a'.rand(100,999) .'b'.rand(100,999) .'c'.rand(100,999) .'.xls';
	   if ($_SERVER['SERVER_NAME'] == "localhost" )
	   {
		   $this->zfn =   dirname(__FILE__).$this->fn;
	   } else 
	   {
		   $this->zfn =   dirname(__FILE__).'/tmp/'.$this->fn;
	   }
   }


   
   /*
   function init()
   {
	   $varArr = array();
	   $this->oo = new COM('com.sun.star.ServiceManager');  // Создаем новый COM-объект сервис манеджер
	   $this->desk = $this->oo->CreateInstance("com.sun.star.frame.Desktop");  // Создаем новый  фрэйм для загрузки документа	   
	   
	   $fw = '_blank';
	   $fn = 'private:factory/scalc';
	   $this->oodoc = $this->desk->LoadComponentFromURL($fn, $fw,0,$varArr); // запуск приложения
	   $this->sheets = $this->oodoc->getSheets();
		//$SheetNames = $Sheets.getElementNames(); $SheetNames[0]	   
   }
   */
   function init2($fn2)
   {
	   $varArr = array(); // http://www.openoffice.org/api/docs/common/ref/com/sun/star/document/MediaDescriptor.html#FilterName
	   $this->oo = new COM('com.sun.star.ServiceManager');  // Создаем новый COM-объект сервис манеджер
	   $this->desk = $this->oo->CreateInstance("com.sun.star.frame.Desktop");  // Создаем новый  фрэйм для загрузки документа	   
	
	   $fw ='_blank';// '_default';
	   //if ($fn == null) {$fn = 'private:factory/scalc';}
	   $fn2 = str_ireplace('\\','/',$fn2);

    try{ 
       $desk = $this->desk;
	   $this->oodoc = $desk->LoadComponentFromURL($fn2, $fw,0,$varArr); // запуск приложения
       if ($this->oodoc == null) return false;
	  
       }
    catch (Exception $e) {
        print_r( 'Caught exception: ' . iconv( "WINDOWS-1251","UTF-8", $e->getMessage()) );
        return false;
    }
       
       $this->sheets = $this->oodoc->getSheets();
       $this->sheet_activate(0);

       $this->localeEN = $this->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
       $this->localeEN->Country = 'US';
       $this->localeEN->Language = 'en';
       $this->localeRU = $this->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
       $this->localeRU->Country = 'RU';
       $this->localeRU->Language = 'ru';
       $this->NumberFormats=$this->oodoc->getNumberFormats();
       $this->NumberFormatFloatEn = $this->NumberFormats->getStandardFormat( 16, $this->localeEN);
		//$SheetNames = $Sheets.getElementNames(); $SheetNames[0]	   
	   
	   /*
		$mysave = $oo->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
		$mysave->Name="Hidden";
		$mysave->Value=true;
		$varArr2=array();
		$varArr2[0] = $mysave;
	   */
       return true;
   }
  
   function sheet_activated($sheet)
   {
	   $oCurs = $sheet->createCursor();
	   $oCurs->gotoEndOfUsedArea(true);
	   $ra = $oCurs->getRangeAddress();
	   $this->nEndColumn = $ra->EndColumn;
	   $this->nEndRow = $ra->EndRow;
   }
   
   function sheet_activate($i)
   {
	   $this->sheet = $this->sheets->getByIndex(0);
	   $this->sheet_activated($this->sheet);
   }
   

   function NewProp($xName=null,$xValue=null)
   {
	   $p = $this->oo->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
       if ($xName != null) $p->Name = $xName;
       if ($xValue != null) $p->Value = $xValue;
       return $p;
   }
   

			
   function Free()
   {
	   $this->oodoc->close(true);
	   $this->oodoc = null;
	   $this->desk->terminate();
	   $this->desk = null;
   }	
   
   /*    
    function NewProp1()
    {
    return $this->oo->Bridge_GetStruct("com.sun.star.sheet.CellInsertMode.DOWN");
    }

   function makefile()
   {
	   try{
		   $sheet=$this->sheets->getByName('Лист1');
		   $Cell=$sheet->getCellByPosition(0,0);          //получаем ссылку на ячейку
		   $Cell->SetString("Множитель");                 //вносим текст
		   $Cell=$sheet->getCellByPosition(1,0);
		   $Cell->SetString("Множитель");
		   $Cell=$sheet->getCellByPosition(2,0);
		   $Cell->SetString("Произведение");
		   $Cell=$sheet->getCellByPosition(0,1);          //получаем ссылку на ячейку
		   $Cell->SetValue(78);                           //вносим число
		   $Cell=$sheet->getCellByPosition(1,1);
		   $Cell->SetValue(11);
		   $Cell=$sheet->getCellByPosition(2,1);          //получаем ссылку на ячейку
		   $Cell->SetFormula("=A2*B2");                   //вносим формулу
		   
		   $mysave = $this->oo->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
		   $mysave->Name="FilterName";
		   $mysave->Value="MS Excel 97";
		   $varArr = array($mysave);
		   //$varArr[0]=$mysave;
		   $this->oodoc->storeAsURL("file:///".$this->zfn,$varArr);
		   echo 'start8';
		    
           //$this->oodoc->close(true);
           //$this->oodoc = null;
           //$this->desk->terminate();
           //$this->desk = null;
		    
		   $this->Free();
	   }
	   catch (Exception $e) {
		   echo 'Caught exception: ',  $e->getMessage(), "\n";
	   }
   }
 */  
    /*
    $local = $bt->oo->Bridge_GetStruct("com.sun.star.lang.Locale");
    $local->Country = 'US';
    $local->Language = 'en';
    $NumberFormats=$bt->oodoc->getNumberFormats();
    // $f = $bt->NewProp(); $f->Value = "### ### ##0.00";
    // $t = $bt->NewProp(); $t->Value = true;
    //$NumberFormat1 = $NumberFormats->getStandardFormat( 16, $local);
    //$NumberFormats->queryKey("### ### ##0.00", $local, 1);
    //$NumberFormat1 = $NumberFormats->getFormatForLocale(108,$local);
    //$r1->setPropertyValue( "NumberFormat", $NumberFormat1);
    //$r2->setPropertyValue( "NumberFormat", $NumberFormat1);
    
     */

    /*
    for($i=18;$i<18+count($rs);$i++)
    {
    ods_cell($sheet,'A'.$i)->SetValue($i-17);
    $rx=$sheet->getCellRangeByName('D'.$i.':G'.$i);
    $rx->merge(true);
    $c = ods_cell($sheet,'D'.$i);
    $c->HoriJustify =1;
    }
    
    ods_rowsRemove($sheet,17+count($rs),5);
    ods_rowsRemove($sheet,0,1);
     */

}



?>