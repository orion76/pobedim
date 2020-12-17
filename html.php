<?php

/*

    part[ n_part ][i][rows_sqlds]
    part[ n_part ][i][t_template]

  
   
   <!-- %part  --> 
__VAR__
 
 "any text##__VAR__"  => "__VAR__" -- текст будет удалён, предполагается ссылка - значение аттрибута
   <!-- part% -->
 
  <!-- %remove --> удаляемый <!-- remove% -->
*/


function mk_tpl($container, $dir_includes, $includes , $rebuild = true){
/* создаёт шаблон из файла контейнера и вложений, этот шаблон отправляется в html->build */    
    $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
    $s = @file_get_contents( $dir0 . $container);
    foreach ($includes as $f){
        $sx = @file_get_contents( $dir0 .$dir_includes. $f );
        $s = str_replace("$$".$f."$$", $sx, $s);
    }
    return $s;
}


class html{
    public $part = array('remove'=>'','session'=> array('0'=> array('session'=>1))
                        ,'head'=> array( '0'=> array('TITLE'=>'Держава - система самоуправления народа'
                                                    ,'DESCRIPTION'=>'Деражава - место для сбора народа вместе'
                                                    ,'TITLE_OG'=>''
                                                    ,'IMG_OG'=>''
                                                )
                            
                                        )
                    );  
    public $replace = array('remove'=>''); // удаляемые блоки из шаблона
    public $tt=''; // загруженный шаблон
    

    function make_include_head($tt,$t){
               
        if (strpos($tt,'</title>') !== false ){
            $i = strpos($t,'<title>');
            $j = strpos($t,'</title>');
            $t = substr($t, 0, $i).  substr($t, $j+8);
        }
        
        if (strpos($tt,'="description"') !== false ){
            $k = strpos($t,'="description"');
            $i = strpos($t,'<meta',$k-20);
            $j = strpos($t,'>',$k);
            $t = substr($t, 0, $i).  substr($t, $j+1);
        }
        
        return $t;
    }
    
    
    
    function subst($n_part, $text){
        if( !array_key_exists($n_part, $this->replace) )
        {        
            $this->replace[$n_part]=$text;
            $this->data($n_part, null);
        }
    }
    
    function data($n_part, $o){
        $rows = null;

        if (is_a($o,'db'))
                $rows = $o->rows;
        else if (is_array($o)) $rows = $o;

        if (!isset($this->part[$n_part])) {$this->part[$n_part]=[];}
        array_push( $this->part[$n_part], $rows);
    }


    function fill_clip($c, &$row){
         $sf = array_keys($row);    
         if (strpos($c, "__") !== false){
             foreach ($sf as $f) {
                 $v = "__".$f."__";
                 $j = strpos($c,$v); 
                 if ( $j !== false) {
                     if ($j > 2 && substr($c, $j-2, 2) === '##'){
                         $i = strrpos($c, '"', $j-2- strlen($c));
                         $c = substr($c, 0, $i+1).substr($c, $j);
                     }
                     $c = str_replace($v, $row[$f], $c); 
                 }    
             }
         }
         return trim( $c );     
    }

    function make_clip_row1($t_clip, $row, &$subs ){
      
        $c0 = trim( $t_clip );
        if (empty($c0)) return '';
            
//file_put_contents('c:/tmp/debug0.txt' , $c0   );
        
        $c4 = '';
        while (true){
          $sub = count($subs) -1;
          if( $sub < 0 ) $nv = ''; else $nv = $subs[$sub];
//        $nvp = ''; if ( $sub>1 ) $nvp = $subs[$sub-1];
/*
              if ( $nv == 'CAN_USER_ADD_COMMENT'){
                   $b = -1;
               }
*/
          
            $i = strpos($c0, '<!--');
            if ($i === false ) {break;}
            $c1 = substr($c0,0,$i); // этот кусок до нового вложенного блока            
            
            $j = strpos($c0, '-->', $i); // конец определения нового вложенного блока
            if ($j !== false) {
                $s = substr($c0, $i+4,  $j-$i-4);  // фрагмент c наименованием нового блока
                $c2 = substr($c0,$j+3); // это кусок остаткак кода     
            } else {
                $c0 = $c1; $c1 = ''; $c2 = ''; break;
            }
/*            
            file_put_contents('c:/tmp/debug.txt'
                    , $c1
                    . chr(10). chr(13).'------------------------------------------'. chr(10). chr(13)
                    . $s 
                    . chr(10). chr(13).'------------------------------------------'. chr(10). chr(13)
                    . $c2);
*/            
            
            if (strlen($s) > 50 || strpos($s,'__')===false) {
                $c0 = $c1 .' '. $c2;
                continue;
            }            
            
            $k = ''; // наименование тестируемой переменной 
            $sf = array_keys($row);    
            $zk = 0;
            if (!empty($s)){ foreach ($sf as $xk){ $zk = strpos($s, '__'.$xk.'__'); if ( $zk !== false ) {$k = $xk; break; }}}


if($k == 'rows8' || $k == 'rows9'){
   $zz = 0;
}
         
            if ($nv === $k ){
                    if (isset($row[$k])) $v = $row[$k]; else $v = '';
                    if (empty($v) || $v =='0') {  $row[$k] = null; $v =null; }
             // найден конец блока для переменной
                    $b = isset($v); 
                    if ($zk>0 && substr($s, $zk-1,1) === '!') {
                        $b = !$b;
                    }
                    if (!$b) {
                       $c1 = ''; // если не отображать, то удалить блок
                    } else {
                                                
                         if (!is_array($row[$k])){
                                $c1 = $this->fill_clip($c1, $row); // заполним кусочек до новой вставки 
                         }
                    }
                    array_pop($subs);
            } else {
               // if (array_key_exists($k, $row)) ...
               
                // найден новый блок с новой переменной
            
$b = true;

                $i = strpos($c2, '__'.$k);
                $c4 = substr($c2, $i-1,1);
                if ($c4 === '!'){
                    $j = 0;
                }
                if (isset($row[$k])) $v = $row[$k]; else $v='';
                if (empty($v) || $v =='0') {  $row[$k] = null; $v =null; }
                if (  ( $v == null && $c4 !== '!' )
                      ||( $v != null && $c4 === '!' )  
                   ) {
                    if ($i !== false) {
                        $j = strpos($c2, '-->', $i+strlen($k));
                        if ($j !== false) {
                            $j = $j+3;
                            $c2 = substr($c2, $j);
                            $b = false;
                        }
                    }
                }
                
            if (is_a($row[$k],'db')) {
                $aa = $row[$k]->rows;
                $row[$k] = $aa;
            }

            if ($b) {
                    if (!is_array($row[$k])){
                        array_push($subs,$k);    
                        $c2 = $this->make_clip_row1($c2, $row, $subs);
                    } else {
                                 //-- переменная - массив
                                 //
                                 //
                                  //array_push($subs,$k);    
                                  $c4 ='';
                                  $i = strpos($c2, '__'.$k.'__');
                                  $j = strpos($c2, '-->', $i);
                                  $c5 = substr($c2, 0, $i);
                                  $c2 = substr($c2, $j+3);

                                  foreach ($row[$k] as $r){
                                        // $aa = [ 0=>$k];
                                        $aa = [];
                                        $c3 = $this->make_clip_row1($c5, $r, $aa ); // ,k
                                        $c4 .= $c3;
                                      }
                                  $c1 = $c1 . $c4;
                             }
                }
            }
            $c0 = $c1 . $c2;
        }
        $r = $this->fill_clip($c0, $row); // вложенных более нет заполняем и выходим
//        array_push($clips,$r);
//        file_put_contents('c:/tmp/debug1.txt', $r);
        return $r;
    }

       
    function make_clip($n_clip,$t_clip){
       
        if( array_key_exists($n_clip, $this->replace) ) return $this->replace[$n_clip];
        
        $i = strpos($t_clip, '-->');
        $j = strpos($t_clip, '<!--');
        $t_clip = substr($t_clip, $i+3, $j-$i-4);
        $tt = ''; 

        //----- массив переменных для расширения $row
        $vv = array();
        
        if (strpos($t_clip,'__SESSION_') !== false ) {
             $va = array_keys($_SESSION); 
             foreach ($va as $v){
                 $vn = 'SESSION_'. strtoupper($v);
                 if (strpos($t_clip,'__'.$vn.'__') !== false ) {
                     $vv[$vn] = $_SESSION[$v];
                 }
             }
        }
        if (strpos($t_clip,'__REQUEST_') !== false ) {
             $va = array_keys($_REQUEST); 
             foreach ($va as $v){
                 $vn = 'REQUEST_'. strtoupper($v);
                 if (strpos($t_clip,'__'.$vn.'__') !== false ) {
                     $vv[$vn] = $_REQUEST[$v];
                 }
             }
        }                        
        //-----
        
        if ( isset( $this->part[$n_clip] ) || $n_clip=='session'){
            foreach ($this->part[$n_clip] as $rows)
            {        
                if (is_array($rows)){
                    if (count($rows) === 0) {
                        continue;
                    }
                    if (!array_key_exists(0,$rows)) { $rows = array($rows); }
                } else                    
                    continue;
                    
                if ($n_clip == 'session') {
                    $z = 0;
                }
                
                foreach ($rows as $row){
                    if (count($row) === 0) $row = $vv;
                    else {
                        if (is_array($vv)){
                            $row = array_merge($row, $vv); // дополним массив переменных 
                        }
                    }

                    $subs = [];
                        
                    $c = $this->make_clip_row1($t_clip, $row, $subs);
                    // file_put_contents('c:/tmp/tmp.c.txt', $c);
                     if (strpos($c,'__') !== false){
                         $c = $this->fill_clip($c, $row);
                         
                     }
                     //array_push($clips,$c);
                     //file_put_contents('c:/tmp/tmp.c1.txt', $c);
                     $tt .= $c;
                     //file_put_contents('c:/tmp/tmp.tt.txt', $tt);
                }
            }
        }
        return $tt;
   }
    
    // на входе скомпилированный текст шаблона или пусто для использования нескомпилированного шаблона
    function build($tt = '', $do_recompile = false ){
    
        if (empty($tt)){
            $tf = $_SERVER['SCRIPT_FILENAME'].'.html-template';
            $u = current_iu();
            if (!file_exists('c:/tmp/u/'.$u)) mkdir('c:/tmp/u/'.$u ,0777);
            $tf = "c:/tmp/u/$u/". basename($tf);
            $b = file_exists($tf);
            if ($do_recompile === true) 
            {
                //if($b) unlink($tf); 
                $b = false; 
            } 
            if ($b !== false) {
                 $s = @file_get_contents($tf);
                 if (strlen($s) > 20) { $this->tt = $s; } else { $b = false; }
            }
        } else {
            $b = true;
            $this->tt = $tt;
        }
        
        
        $i = stripos($this->tt, '<!DOCTYPE');
            if ($i > 0) {
                $this->tt = substr($this->tt, $i);
            }
            
            
/* вырезаем все комментарии из шаблона и заменяем вставками %n_clip% */
//-------
/*
     <!--  любой текст   %varname%  любой текст комментария --> 
    все  комментарии уничтожаются  и при наличии  переменной  %varname%
    вставляется соответствующее значение вместо блока <!-- --> .
    в блоке <!-- -->  должна быть только одна переменная
 * 
     <!-- %varname --> любой текст <!-- varname% -->    
     при выводе всё между блоками  <!-- %varname --> и  <!-- varname% --> 
   заменяется на значение переменной
 */        
        $k = array_keys($this->part);
        $i = strpos($this->tt, '<!--');
        $j = 0;
        $tt = '';
        
        while ( $i !== false ) {
            $tt .= substr($this->tt, $j,$i-$j);
            $j = strpos($this->tt, '-->',$i+4);
            if ($j === false) { $j=$i;  break;}
            $j = $j+3;
            $s = substr($this->tt, $i,$j-$i);
            
            $p1 = strpos($s, '$');
            if ($p1 !== false){
                $p2 = strpos($s, '$', $p1+1);    
                if ($p2 !== false){
                    // это .html-clip ?!
                    $nx = substr($s, $p1+1, $p2-$p1-1);
                    $nf = dirname($_SERVER['SCRIPT_FILENAME']).'/'.$nx.'.html-clip';
                    $nf = f_root() .'/_html-clip/'.  basename($nf);
                    if (file_exists($nf)) {
                        $s =  @file_get_contents($nf);
                        if ($nx === 'head')
                        {
                            $s = $this->make_include_head ( $tt, $s);
                        }
                        $this->tt = '  '. $s . substr($this->tt, $j);
                        $i = strpos($this->tt, '<!--' );
                        $j=0;
                        continue;
                    }
                }
            } 

            
            foreach ($k as $n_clip) {
                    $p1 = strpos($s, "%$n_clip");
                    $p2 = strpos($this->tt, "$n_clip%" ,$i+4);
                    if ( $p1 !== false && $p2 !== false) {
                        $j = strpos($this->tt, '-->',$p2);
                        $j = $j+3;
                        $t_clip = substr($this->tt, $i,$j-$i);
                        
//file_put_contents('c:/tmp/out.txt', $t_clip);                        
                        
            if ($b !== false){
                    $html_clip = $this->make_clip($n_clip,$t_clip);
            } else {
                if( array_key_exists($n_clip, $this->replace) ) {$html_clip = $this->replace[$n_clip];}
                else { $html_clip = $t_clip; }
            }
                $tt .= $html_clip; 
                break;
                    }
                }
                
            //}
            //file_put_contents('c:/tmp/out.txt', $tt);
            if ($j < $i) { $j=$i;  break;}
            $i = strpos($this->tt, '<!--', $j);
        }
        $tt .= substr($this->tt, $j);            
        if ($b === false){
            $b = file_put_contents($tf, $tt);
            if ($b > 20) { $tt = $this->build( $tt ); }
        }
        return $tt;    
    }

    function __construct($nf_template = ''){
        if (empty($nf_template)){
            $this->tt = <<<'TXT'
<!DOCTYPE html><html><head><!-- %head --> __HEAD__ <!-- head% --></head><body>  <!-- %body --> __BODY__ <!-- body% --></body></html>
TXT
;
        } else
        {
            $nf = f_root() . $nf_template;
            $this->tt = @file_get_contents( $nf );
            if ( $this->tt === false ) $this->tt = $nf_template;
        }
    }
}