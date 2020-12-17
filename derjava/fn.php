<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/html_parse.php';



function get_youtubekey($s){
    $k = strpos($s, '<youtube ');
    if ($k !== false){
        $i = strpos($s, '"', $k);
        $j = strpos($s, '"', $i+1);
        $k = substr($s,$i, $j-$i);
    }
    return $k;
}



// возвращаем ссылку на картинку, если ссылка не может быть картинкой, то возвращаем пусто
function veche_get_imagehref($href){
  $r = ht_tag_iframe($href);
  $key = $r['key_youtube']; 
  //$i = strpos($key, '?'); if ($i !== false) $key = substr ($key,0,$i);
  $r['onclick'] = '';
  if (!empty($key)) {
      
    $r['imgsrc'] = "https://img.youtube.com/vi/$key/hqdefault.jpg";
    $r['onclick'] = 'youtube_img_click(event)';
    
  } else {
    $r['imgsrc'] = $href;
  }
  
  if(strpos($r['imgsrc'], '.jpg')===false  &&
     strpos($r['imgsrc'], '.gif')===false  &&
     strpos($r['imgsrc'], '.png')===false) { $r = '';}
  
  return $r;   
}




function rows_kind_veche($kv='',$on='',$onclick=' '){
    $r = new db(DB_POBEDIM, <<<SQL
            select * from W2_VECHE_KIND where 1=1 
SQL
 .iif($kv=='6' && $on !='+6',' and ID_KIND_VECHE = 6 ',' and SORTING_KIND_VECHE is not null ')
 .iif($on =='+6',' or ID_KIND_VECHE = 6 ')            
 .iif($kv=='5',' and ID_KIND_VECHE = 5 ',' and SORTING_KIND_VECHE is not null ')  
 .iif( $on === '!6' && $kv!=='6' ,  ' and ID_KIND_VECHE <> 6 ')
.<<<SQL
                 order by SORTING_KIND_VECHE
SQL
            ,[]
                ,function($row,$pa,$lp) {
                    $row['CHECKED'] = iif($lp['kv']==$row['ID_KIND_VECHE'],' checked="checked" ');
                    
                    $row['HREF_KIND_VECHE'] =  null;
                    switch ($row['ID_KIND_VECHE']){
                      case '1':
                         $row['HREF_KIND_VECHE']= 'https://www.youtube.com/watch?v=EdkuihIQmYo&feature=emb_logo';
                         $row['TREF_KIND_VECHE']= 'видеоролик о создании советов';
                         break;
                      case '6':
                         $row['HREF_KIND_VECHE']= '/derjava/v6.php';
                         $row['TREF_KIND_VECHE']= 'пояснение';
                         break;
                    }
                    $row['ONCLICK']=$lp['onclick'];
                    return $row;
                }
                ,['kv'=>$kv, 'onclick'=>$onclick]
            );
    return $r->rows;
}
    
function menu_user(&$ht, $mi_active = ''){
   
    
    
   if ( checkAddressPort('192.168.1.67_',5443) )
   {$menu['OPENMEETINGS_ON'] = 1;  $menu['OPENMEETINGS_OFF'] = null;}
   else 
   {$menu['OPENMEETINGS_ON'] = null;  $menu['OPENMEETINGS_OFF'] = 1;}
   
   $menu['RX']='';
   /*
   $menu['ACTIVE1'] = ' '; 
   $menu['ACTIVE2'] = ' '; 
   $menu['ACTIVE3'] = ' '; 
   $menu['ACTIVE4'] = ' '; 
   $menu['ACTIVE5'] = ' '; 
   $menu['ACTIVE6'] = ' '; 
   */
   for($i = 1; $i<20; $i++){ $menu['ACTIVE'.$i] = ' '; }
   if ( !empty( trim( $mi_active) )) { $menu[$mi_active] = 'main-list-active'; }   
   $_SESSION['derjava'] = 1;
   
   if (isUserLoggedOn() ) 
    {
       $u = current_iu();
       
       $r = new db(DB_USER,'select * from U0_SESSION_S (:u,:h)',[':u'=>$u, ':h'=>$_SERVER['REMOTE_ADDR'] ] );
       
       $menu['IS_USER_ON'] = $u;
       $menu['IS_USER_OFF'] = null;
       $menu['IMGSRC_USER_ON'] = "/photo_u.php?u=$u&h=64&w=64"; // tag_user_imgsrc($menu['IS_USER_ON']);
       $r = new db(DB_POBEDIM,'select count(1) as CNT_MSG from W2_WALL_MSG_NEW_L4(:u)',[':u'=>$menu['IS_USER_ON']]);
       $menu['CNT_MSG_WALL_U'] = $r->row('CNT_MSG');
       unset($r);
       $r = new db(DB_POBEDIM, 'select count(1) as CNT_MSG from W2_WALL_MSG_OTHERS_L4(:u)' , [':u'=>$menu['IS_USER_ON']]);
       $menu['CNT_MSG_WALL_OTHER'] = $r->row('CNT_MSG');
       unset($r);

       $r = new db(DB_POBEDIM, 'select count(1) as CNT_MSG from W2_WALL_MSG_CAST_L4(:u,10)' , [':u'=>$menu['IS_USER_ON']]);
       $menu['CNT_MSG_CAST'] = $r->row('CNT_MSG');
       unset($r);
       
    unset($r);      $r = new db(DB_POBEDIM,<<<SQL
         select count(1) as CNT_
            from w2_user u
                inner join w2_veche_certificates vc on vc.id_certificate = u.id_certificate_u and
                                                       vc.id_role_veche in (1,2) 
                left outer join w2_veche_certificates vc2 on vc2.veche = vc.veche 
                where u.u = :u and
                      vc2.id_role_veche is null  
SQL
             ,[':u'=>$u]
       );
       $menu['CNT_INFO_VECHE_U'] = $r->row('CNT_');
       unset($r);
       
    unset($r);      $r = new db(DB_USER,<<<SQL
         select 
               sum( case when c1.TS > u0.TS_VIEW and c1.T_TEXT_CONVERSE = 4 then 1 ELSE 0 end ) as CNT4_NOT_VIEWED
             , sum( case when c1.TS > u0.TS_VIEW and c1.T_TEXT_CONVERSE = 3 then 1 ELSE 0 end ) as CNT3_NOT_VIEWED
                 from U2_converse c0
                     inner join U2_CONVERSE c1 on c1.ID_CONVERSE = c0.ID_CONVERSE and
                                                  c1.U <> c0.U
                     inner join U0_USER u0 on u0.u = c0.U and
                                              u0.U_USER = c1.U
                     left outer join U0_USER u1 on u1.u = c1.U and
                                              u1.U_USER = c0.U        
                 where c0.u = :u
SQL
             ,[':u'=>$u]
       );
       
    $_SESSION['CNT4_NOT_VIEWED'] = $r->row('CNT4_NOT_VIEWED');
    $_SESSION['CNT3_NOT_VIEWED'] = $r->row('CNT3_NOT_VIEWED');
    $_SESSION['CNT_NOT_VIEWED'] = intval($_SESSION['CNT3_NOT_VIEWED'])+intval($_SESSION['CNT4_NOT_VIEWED']);
    if ($_SESSION['CNT4_NOT_VIEWED'] == 0) $_SESSION['CNT4_NOT_VIEWED'] = null; else if ($_SESSION['CNT4_NOT_VIEWED'] >100) $_SESSION['CNT4_NOT_VIEWED'] ='99+';
    if ($_SESSION['CNT3_NOT_VIEWED'] == 0) $_SESSION['CNT3_NOT_VIEWED'] = null; else if ($_SESSION['CNT3_NOT_VIEWED'] >100) $_SESSION['CNT3_NOT_VIEWED'] ='99+';
    if ($_SESSION['CNT_NOT_VIEWED'] == 0) $_SESSION['CNT_NOT_VIEWED'] = null;  else if ($_SESSION['CNT_NOT_VIEWED'] >100) $_SESSION['CNT_NOT_VIEWED'] ='99+';
    
    
    // количество изменившихся опросов
    unset($r);      $r = new db(DB_POBEDIM,<<<SQL
       
   SELECT count(distinct PU.ID_POLL) as CNT5_NOT_VIEWED
        FROM U2_POLL PU
        INNER JOIN W2_POLL_ANSWERS PAA ON PAA.ID_POLL = PU.ID_POLL AND
                                          PAA.TS_INSERTED > PU.TS_READ_POLL + 0.05
    WHERE PU.U = :u
   

SQL
             ,[':u'=>$u]
       );
       
    $_SESSION['CNT5_NOT_VIEWED'] = $r->row('CNT5_NOT_VIEWED');
    unset($r);   
    
    } 
    else 
    {
       $menu['CNT_MSG_WALL_U'] = null;
       $menu['IS_USER_ON'] = null;
       $menu['IS_USER_OFF'] = 1;
       $menu['IMGSRC_USER_ON'] = null;
    }
   
$srv = $_SERVER['SERVER_NAME'];
if ($srv === 'localhost' ||  ('1110' == current_iu() ) ) $menu['IS_LOCAL'] = 1; else $menu['IS_LOCAL'] = null;
    
   $ht->data('menu_u', $menu );     
   return $menu;
}


function select_veche4(&$ht, $iv, $level = 4 , $disabled = false) {
   
    if ($iv < 1) { $iv = 10; }
    

    $r0 = new db(DB_POBEDIM, <<<TXT
            SELECT V.VECHE       AS VECHE4
                , V.VECHE_PARENT AS VECHE3
                , V.NAME_VECHE
                , V.ID_KIND_VECHE
                , V2.VECHE_PARENT AS VECHE2
                , V1.VECHE_PARENT AS VECHE1
                 FROM W2_VECHE V
                   LEFT OUTER JOIN W2_VECHE V2 ON V2.VECHE=V.VECHE_PARENT
                   LEFT OUTER JOIN W2_VECHE V1 ON V1.VECHE=V2.VECHE_PARENT
      WHERE V.VECHE =:iv
TXT
            , [':iv' => $iv]);

    $v1 = $r0->row('VECHE1') + 0;
    $v2 = $r0->row('VECHE2') + 0;
    $v3 = $r0->row('VECHE3') + 0;
    $v4 = $r0->row('VECHE4');

    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = $v4;
        $v4 = 0;
    }
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = 0;
        $v4 = 0;
    }
    
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = 0;
        $v3 = 0;
        $v4 = 0;
    }

    if ($v1 < 10) {
        $v1 = 0;
    }
    
    if ($v3 < 10) {
        $v3 = 0;
    }
    
    if ( $v4 == 0 || $level < 4 ) {
          $v4 = 0;
    }
    
    function options($rows, $v, $disabled){
        if ($disabled) return $rows;
        
        $a[0]= ['VECHE'=>0
            , 'NAME_VECHE'=>'...' 
            , 'SELECTED' => iif( $v=='0', ' selected="selected" ', ' ')
            ];
        $a= array_merge($a,$rows);
        return $a;
    }
    
    $fx=function($row,$pa,$lp){
            if ($row['VECHE']==$lp['v']){ $row['SELECTED']=' selected="selected" ';} else {$row['SELECTED']='';} 
            if ($lp['d'] && $row['VECHE'] != $lp['v']) return null;
            return $row; 
        };
        
   
    $r1 = new db(DB_POBEDIM, "select VECHE,NAME_VECHE,VECHE_PARENT from w2_veche where veche_parent=1 order by SORTING_VECHE" ,[],$fx,['v'=>$v1,'d'=>$disabled]);
    $r2 = new db(DB_POBEDIM, "select VECHE,NAME_VECHE,VECHE_PARENT from w2_veche where veche_parent=:v1 and veche_parent <> 1 and veche < 900000 order by SORTING_VECHE", [':v1' => $v1],$fx,['v'=>$v2,'d'=>$disabled]);
    $r3 = new db(DB_POBEDIM, "select VECHE,NAME_VECHE,VECHE_PARENT from w2_veche where veche_parent=:v2 and veche < 900000 order by SORTING_VECHE", [':v2' => $v2],$fx,['v'=>$v3,'d'=>$disabled]);
    $r4 = new db(DB_POBEDIM, "select VECHE,NAME_VECHE,VECHE_PARENT from w2_veche where veche_parent=:v3 order by SORTING_VECHE", [':v3' => $v3],$fx,['v'=>$v4,'d'=>$disabled]);
    
    
    $data[0] =
         [
           'READONLY' => iif($disabled , '1', null )  
          ,'VECHE' =>$iv 
          ,'NAME_VECHE' => $r0->rows[0]['NAME_VECHE']             
          ,'VECHE1'=>options($r1->rows,$v1, $disabled)
          ,'VECHE2'=>options($r2->rows,$v2, $disabled)
          ,'VECHE3'=>options($r3->rows,$v3, $disabled)
          ,'VECHE4'=>options($r4->rows,$v4, $disabled)
          ,'SELECT2' => iif( $r2->length === 0 || ($disabled && $v2==0) , null, $r2->length )                        
          ,'SELECT3' => iif( $r3->length === 0 || ($disabled && $v3==0) , null, $r3->length )            
          ,'SELECT4' => iif( $r4->length === 0 || ($disabled && $v4==0) , null, $r4->length )
          ,'DISPLAY_SELECT2' => iif( $v1>0 , '', 'display:none;' )                        
          ,'DISPLAY_SELECT3' => iif( $v2>0 , '', 'display:none;' )            
          ,'DISPLAY_SELECT4' => iif( $v3>0 , '', 'display:none;' )
          ,'SELECT4' => iif($level < 4, null, 1)
        ];    
         
   $ht->data('tree_veche',$data); 
}



function select_veche3(&$ht, $iv, $level = 4) {
    if ($iv < 1) { $iv = 1; }
    

    $r0 = new db(DB_POBEDIM, <<<TXT
            SELECT V.VECHE       AS VECHE4
                , V.VECHE_PARENT AS VECHE3
                , V.NAME_VECHE
                , V.ID_KIND_VECHE
                , V2.VECHE_PARENT AS VECHE2
                , V1.VECHE_PARENT AS VECHE1
                 FROM W2_VECHE V
                   LEFT OUTER JOIN W2_VECHE V2 ON V2.VECHE=V.VECHE_PARENT
                   LEFT OUTER JOIN W2_VECHE V1 ON V1.VECHE=V2.VECHE_PARENT
      WHERE V.VECHE =:iv
TXT
            , [':iv' => $iv]);

    $v1 = $r0->row('VECHE1') + 0;
    $v2 = $r0->row('VECHE2') + 0;
    $v3 = $r0->row('VECHE3') + 0;
    $v4 = $r0->row('VECHE4');

    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = $v4;
        $v4 = 0;
    }
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v3;
        $v3 = 0;
        $v4 = 0;
    }
    
    if ($v1 < 10) {
        $v1 = $v2;
        $v2 = $v0;
        $v3 = 0;
        $v4 = 0;
    }

    if ($v3 == 0) {
        $ht->subst('select3',' ');
    }
    
    if ($v4 == 0 || $level < 4) {
        $v4 = 0;
        $ht->subst('select4',' ');
    }

    
    $fx=function($row,$pa,$lp){ if ($row['VECHE']==$lp['v']){ $row['SELECTED']=' selected="selected" ';} else {$row['SELECTED']='';} return $row; };
    
    $r1 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=1 order by SORTING_VECHE" ,[],$fx,['v'=>$v1]);
    $ht->data('data-ta1',$r1->rows);
    
    $r2 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v1 order by SORTING_VECHE", [':v1' => $v1],$fx,['v'=>$v2]);
    $ht->data('data-ta2',$r2->rows);

    $r3 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v2  order by SORTING_VECHE", [':v2' => $v2],$fx,['v'=>$v3]);
    $ht->data('data-ta3',$r3->rows);
    
    $r4 = new db(DB_POBEDIM, "select * from w2_veche where veche_parent=:v3 order by SORTING_VECHE", [':v3' => $v3],$fx,['v'=>$v4]);
    $ht->data('data-ta4',$r4->rows);
    
}