<?php

function file_thumb_src($dir, $f){
    $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
    $td = $dir0.'/tmp/thumb64-';
    $ext = '.jpg';
    $rn = '/tmp/thumb64-'.$f.$ext;
    $nf = $td.$f.$ext;
    if (file_exists($nf)) return $rn;
              
    try {
          $tf = $dir.'/'.$f;

            $im = new Imagick_();
            if ($im->pingImage($tf)) {
                $im->readImage($tf);
                $im->thumbnailImage(64, 64, true);
                $im->writeImage($nf);
                return $rn;
            } else {
                $err = 'Формат файла не поддерживается';
                return null;
            }
      } catch (Exception $e) {
        $err = 'Ошибка обработки изображения';
    }
    return null;
}
    

function getWall4(&$data0, $u, $u_w='0', $iv = '0' , $qq='0' , $rx=''
            , $w = null
            , $msg_last=null // пост по которому измерять время
            , $m=null   // пост который нужно отобразить как единственный
            , $mx=null  // пост, который отображать не надо
){
    if (!isset($data0[0]['WALL_SEARCH'])) $data0[0]['WALL_SEARCH'] = null;
    $sx= val_rq('sxw');
    return getWall5($data0, $u, $u_w, $iv, $qq , $rx
            , $w 
            , $msg_last // пост по которому измерять время
            , $m   // пост который нужно отобразить как единственный
            , $mx  // пост, который отображать не надо
            , $data0[0]['WALL_SEARCH']
            , $sx );
}


function setWall_U_text($users,&$row){
    foreach ($users as $row1){
        if ($row1['U'] == $row['U']) { $row['TEXT_U']=$row1['TEXT_U']; return; }
    }
    $row['TEXT_U']= '';
}


function setWallAttached($m,$cnt){
    $r = new db(DB_POBEDIM,'update w2_wall_msg set cnt_attached=:cnt where id_msg_wall=:m and cnt_attached is distinct from :cnt0'
                ,[':m'=>$m,':cnt'=>$cnt,':cnt0'=>$cnt]);
    unset($r);
}

function getWall5(&$data0, $u, $u_w='0', $iv = '0' , $qq='0' , $rx=''
            , $w = null
            , $msg_last=null // пост по которому измерять время
            , $m=null   // пост который нужно отобразить как единственный
            , $mx=null  // пост, который отображать не надо
            , $search_w='' // сайт поиска action
            , $sx='' //строка поиска
){
    $data0[0]['WALL_SEARCH'] = $search_w;
    $data0[0]['WALL_SEARCHED'] = $sx;    
   
    if ( !isset($w) || $w == '0' || empty($w) ) {
        unset($r);  $r = new db(DB_POBEDIM, 'select ID_WALL from W2_WALL_GET2(:u,:v,:p)',[':u'=>$u_w,':v'=>$iv,':p'=>$qq]);
        $w = $r->row('ID_WALL');
    }
    if ($w == null  && $u_w != 0) return null;

    // массив пользователей стены
    $r5 = new db(DB_POBEDIM,'select * from W2_WALL_USERS (:w)',[':w'=>$w] );
    
    //if ($w == '-1') $w = null;
    
    if (isUserLoggedOn() && $u != '1000') {
    $r = new db(DB_POBEDIM, 'select * from W2_WALL_OPTIONS( :u , :w )' ,[':u'=>$u, ':w'=>$w] );
        $can_user_add_post = $r->row('CAN_USER_ADD_POST');
        $can_user_add_comment = $r->row('CAN_USER_ADD_COMMENT'); 
        $data0[0]['TEXT_RIGHTS_WALL'] = $r->row('TEXT_RIGHTS_WALL'); 
        $do_show_read = val_sn('DO_SHOW_READ',0); if (empty($do_show_read)) $do_show_read = 0;
        $cnt_rows = ' first 8 ';
    } else {
        $data0[0]['TEXT_RIGHTS_WALL'] = 'Комментировать могут только авторизованные пользователи';
        $can_user_add_post = null;
        $can_user_add_comment = null;
        $_SESSION['DO_SHOW_READ'] = '1';
        $do_show_read = 1;
        $u = '1000';
        $cnt_rows = '';
    }
    
    $data0[0]['CAN_USER_ADD_POST'] = $can_user_add_post; 
    $data0[0]['CAN_USER_ADD_COMMENT'] = $can_user_add_comment; 

 //$cnt_rows = '';

    $r1 = new db(DB_POBEDIM,'SELECT ID_SEARCH_WALL ,CNT_SEARCH_WALL FROM  W2_WALL_SEARCH_FILTER(:w, :sx)'
            ,[':w'=>$w,':sx'=>$sx]);
    $csw = $r1->row('CNT_SEARCH_WALL');
    $isw = $r1->row('ID_SEARCH_WALL');
    unset($r1);
    
    $r8= new db(DB_POBEDIM,
'select '. $cnt_rows.
<<<SQL
 m.*
    from W2_WALL_MSG_L11(:u, null, :w, :m0, :m1, :do_show_read,null,:isw) m 
    where m.ID_MSG_WALL is distinct from :mx
        ORDER BY
               M.SORTING_MSG_WALL NULLS LAST
            ,  M.TS_MSG_WALL DESC
            ,  M.TS_READ DESC NULLS FIRST  
SQL
            ,[  ':u'=>$u , ':qq'=>$qq ,  ':w'=>$w, ':m0'=>$msg_last ,':m1'=>$m,':mx'=>$mx
                ,':do_show_read'=>$do_show_read
                ,':isw'=> $isw
            ]
        ,function($row,$pa,$lp){
            setWall_U_text($lp['users'],$row);
                
            
            $row['CAN_WALL_CAST'] = 1;
                        if ($row['IS_CASTED'] == '1'){
                            $row['DISPLAY_CAST'] = 'display:none';
                            $row['DISPLAY_CASTED'] = '';
                        } else {
                            $row['DISPLAY_CASTED'] = 'display:none';
                            $row['DISPLAY_CAST'] = '';
                        }
            
            
            $row['CAN_USER_ADD_POST'] = $lp['can_user_add_post'];
            $row['CAN_USER_ADD_COMMENT'] = $lp['can_user_add_comment'];
    
            $row['RX_MSG']=$lp['rx'];
            $imgsrc = '';
            if ($row['IS_CLAIMED'] == '1'){

            }
            
                        if ($row['IS_LIKED'] == '1'){
                            $row['DISPLAY_LIKE'] = 'display:none';
                            $row['DISPLAY_LIKED'] = '';
                            $row['CNT_LIKED'] = $row['CNT_LIKE'];
                            $row['CNT_LIKE'] = $row['CNT_LIKE']-1;
                        } else {
                            $row['DISPLAY_LIKED'] = 'display:none';
                            $row['DISPLAY_LIKE'] = '';
                            $row['CNT_LIKED'] = $row['CNT_LIKE']+1;                            
                        }

            $dir0 = f_root();            
            $f = $dir0 ."/tk/".$row['U'].'/'.$row['ID_MSG_WALL'].'.php';
            $row['MSG_IS_PUB'] = file_exists( $f);
                        
            $row['DISPLAY_CHECK'] = iif($row['IS_READ'] == '1', 'display:none','');
            
            $row['IMGSRC_USER'] = user_imgsrc($row['U']);
            
            $row['NAME_U'] = get_username($row['U']);
            $row['NAME_USER'] = $row['NAME_U'];
            $row['IMGSRC_MSG_STATE']= $imgsrc;
            $row['TEXT_MSG_STATE']='';
            
            $s = $row['BLOB_MSG_WALL'];
            if (strlen($s)> 1500) {
                $s = substr($s,0, 1000); $i = strrpos($s, ' ');
                $row['TEXT_MSG_WALL_200'] = md_parse( substr($s,0, $i) );
            } else { $row['TEXT_MSG_WALL_200'] = null; }
            
            $row['TEXT_MSG_WALL'] = md_parse($row['BLOB_MSG_WALL']);
            
            $data10 = array();
            
            $s = str_replace(['\r','\n','  ','"',"'"], ' ', $row['TEXT_MSG_WALL']);
            // находим в комментарии все ссылки и заполняем массив $data10
            
            $i = strpos($s, 'http');
            $j = 1;
            $a = array();
            while($i !== false && $j !== false){
                $j = strpos($s, ' ',$i);
                if ($j === false) {$sx = substr($s, $i); } else { $sx = substr($s, $i, $j-$i) ; }
                $l = strpos($sx,'<'); if ($l !== false) $sx = substr($sx,0,$l);
                    
                if ( array_search($sx, $a) === false ){
                    array_push($a,$sx);
                    $r = veche_get_imagehref( $sx ); 
                    $r['t2'] = " ";
                    if (!empty( va($r,'imgsrc'))){
                        if (empty(va($r,'onclick'))) { $imgstyle = null; } else { $imgstyle= 'cursor: pointer'; }
                        array_push($data10,['IMGSRC_MSG_WALL' => trim($r['imgsrc'])
                                          , 'IMG_CLICK'=>$r['onclick']
                                          , 'IMG_STYLE'=>$imgstyle 
                                          , 'TITLE'=>$r['title']
                                          , 'KEY_YOUTUBE'=>$r['key_youtube']
                                          , 'T_YOUTUBE'=> va($r,'t')
                                          , 'IMG'=>0
                                          , 'IFRAME'=>null
                                ]); 
                    }
                }
                $i = strpos($s, 'http',$i+5);
            }
            
            if ( count($data10) > 0) {$row['rows10'] = $data10;} else {$row['rows10'] = [];}
            
            
            
            $data11 = array();
            $dir = f_root().'/w/'.$row['ID_MSG_WALL'];
            $a = directoryToArray($dir, false);
            foreach ($a as $f){
                if (strrpos($f, '.php') === false) {
                    $thumbsrc =  file_thumb_src($dir, $f);
                    if (empty($thumbsrc)) $thumbsrc=null;
                    array_push($data11,['FILE_WALL'=>$f, 'IMGSRC_FILE_WALL'=>$thumbsrc]);
                }
            }
            if ( count($data11) > 0) {$row['rows11'] = $data11;} else {$row['rows11'] = [];}
            setWallAttached($row['ID_MSG_WALL'], count($data11));
            
/*
    select m.* 
        from W2_WALL_MSG_L8(:u, :mp , :w, null, null,1) m 
             left outer join W2_WALL_MSG m0 on m0.ID_MSG_WALL = m.ID_MSG_WALL_REPLY
        order by 
            case when m.ID_MSG_WALL_REPLY is not null then m0.TS_MSG_WALL else m.TS_MSG_WALL end 
           ,m.TS_MSG_WALL

 * 
 *  */
          
if ($pa[':u'] != '1000'){
            $r9 = new db(DB_POBEDIM,
<<<SQL
                    
WITH RECURSIVE MT AS (

SELECT  0 as L , cast( '/' || i_ts_diff(m0.TS_MSG_WALL) as VCH7K) as W
        , m0.*
    FROM W2_WALL_MSG_L11(:u0, :mp0 , :w0, null, null,1,null,null) M0
    WHERE  m0.ID_MSG_WALL_REPLY is NULL

union all

SELECT  p.L+1 as L,  p.w || '/' || i_ts_diff(m1.TS_MSG_WALL) as W
        , m1.*
    FROM W2_WALL_MSG_L11(:u, :mp , :w, null, null,1,P.ID_MSG_WALL,null) M1
        JOIN MT P ON M1.ID_MSG_WALL_REPLY = P.ID_MSG_WALL
)
   select * from mt order by w

SQL
                    ,[
                        ':mp'=>$row['ID_MSG_WALL'],  ':u'=>$pa[':u'] , ':w'=>$row['ID_WALL']
                      ,':mp0'=>$row['ID_MSG_WALL'],  ':u0'=>$pa[':u'] , ':w0'=>$row['ID_WALL']
                    ]
                    , function($row,$pa,$lp){
                        setWall_U_text($lp['users'],$row);
                        $row['IMGSRC_USER'] = tag_user_imgsrc($row['U']);
                        $row['NAME_U'] = get_username($row['U']);
                        $row['NAME_USER'] = $row['NAME_U'];
                        
                        //if ($row['U'] == $pa[':u']){ $row['CAN_EDIT_MSG'] = 1; } else { $row['CAN_EDIT_MSG'] = null; }
                        
                    if ($pa[':u']=='1000'){                        
                        $row['CAN_EDIT_MSG'] = 0;
                        $row['CAN_REPLY_MSG'] = 0;
                    } else {
                        $row['CAN_EDIT_MSG'] = $row['CAN_WALL_EDIT'];
                        $row['CAN_REPLY_MSG'] = $row['CAN_MSG_WALL_REPLY'];
                    }
                    
                        if ($row['IS_CLAIMED'] == '1'){

                        }
                        if ($row['IS_LIKED'] == '1'){
                            $row['DISPLAY_LIKE'] = 'display:none';
                            $row['DISPLAY_LIKED'] = '';
                            $row['CNT_LIKED'] = $row['CNT_LIKE'];
                            $row['CNT_LIKE'] = $row['CNT_LIKE']-1;
                        } else {
                            $row['DISPLAY_LIKED'] = 'display:none';
                            $row['DISPLAY_LIKE'] = '';
                            $row['CNT_LIKED'] = $row['CNT_LIKE']+1;                            
                        }
                        if ($row['IS_MARKED'] == '1'){
                            $row['DISPLAY_MARK'] = 'display:none';
                            $row['DISPLAY_MARKED'] = 'color:black';
                        } else {
                            $row['DISPLAY_MARKED'] = 'display:none';
                            $row['DISPLAY_MARK'] = 'color:yellow';
                        }
                        
                        $row['IMGSRC_MSG_STATE']='';
                        $row['TEXT_MSG_STATE']='';
                        
                        $s = $row['BLOB_MSG_WALL'];
                        if (strlen($s)> 1500) {
                            $s = substr($s,0, 1000); $i = strrpos($s, ' ');
                            $row['TEXT_MSG_WALL_200'] = md_parse( substr($s,0, $i) );
                        } else { $row['TEXT_MSG_WALL_200'] = null; }
                        $row['TEXT_MSG_WALL'] = md_parse($row['BLOB_MSG_WALL']);

                        $data12 = array();            
                            $dir = f_root().'/w/'.$row['ID_MSG_WALL'];
                            $a = directoryToArray($dir, false);
                            foreach ($a as $f){
                                if (strrpos($f, '.php') === false) {
                                    $thumbsrc =  file_thumb_src($dir, $f);
                                    if (empty($thumbsrc)) $thumbsrc=null;
                                    array_push($data12,['FILE_WALL'=>$f, 'IMGSRC_FILE_WALL'=>$thumbsrc]);
                                }
                            }                        
                        if ( count($data12) > 0) {$row['rows12'] = $data12;} else {$row['rows12'] = [];}                
                        setWallAttached($row['ID_MSG_WALL'], count($data12));
                        return $row;
                    }
                    ,$lp
                    );
                    
            if ($r9->length > 0) {$row['rows9'] = $r9->rows;} else {$row['rows9'] = [];}
        } else {
   $row['rows9'] = null;         
}
            return $row;
        },['rx'=>$rx
           , 'can_user_add_post'=>$can_user_add_post
           , 'can_user_add_comment'=>$can_user_add_comment
           , 'users'=>$r5->rows     
                ]    
    );
        

if ($m == null) {    

    $ts_ = 'Z';
    $x_ = 0;
    foreach ($r8->rows as $row){
        $x = $row['TS_MSG_WALL'];
        if ($ts_ > $x ) {$ts_ = $x; $x_ = $row['ID_MSG_WALL'];}
    }     
    if ($ts_ == 'Z') $ts_ = null;
    
    $r00 = new db(DB_POBEDIM, <<<SQL
 select count(*) as CNT
     from W2_WALL_MSG m
           INNER JOIN W2_WALL_SEARCHED SX ON SX.ID_SEARCH_WALL = :isw AND
                                       SX.ID_MSG_WALL = M.ID_MSG_WALL
     where m.ID_MSG_WALL_PARENT is null and 
           m.TS_MSG_WALL < coalesce( :ts, current_timestamp) and
           m.ID_MSG_WALL is distinct from :mx and
           (m.ID_WALL =:w or coalesce(:w0,0)=0)
SQL
            ,[':ts'=>$ts_, ':w'=>$w, ':w0'=>$w, ':mx'=>iif($mx==null,0,$mx) , ':isw'=>$isw ]);
    $m8=$x_;    
} else {
  $x_ = null;
  $m8=$r8->row('ID_MSG_WALL');    
  $r00 = new db(DB_POBEDIM, <<<SQL
 select count(*) as CNT
     from W2_WALL_MSG m
           INNER JOIN W2_WALL_SEARCHED SX ON SX.ID_SEARCH_WALL = :isw AND
                                       SX.ID_MSG_WALL = M.ID_MSG_WALL
     where m.ID_MSG_WALL_PARENT is null and 
           m.ID_MSG_WALL is distinct from :m and
           m.ID_MSG_WALL is distinct from :mx and
           (m.ID_WALL =:w or coalesce(:w0,0)=0)
SQL
            ,[':m'=>$m, ':w'=>$w, ':w0'=>$w, ':mx'=>iif($mx==null,0,$mx)  , ':isw'=>$isw] );
}
   $r8->r = array('ID_MSG_WALL'=>$x_ , 'ID_MSG_WALL_EX'=>$m8 , 'ID_WALL' =>$w , 'CNT_WALL_LAST'=>$r00->row('CNT') );
  
  $data0[0]['ID_MSG_WALL'] = $m;
  if ($m != null){
    $data0[0]['ID_MSG_WALL_LAST'] = $m;
    $data0[0]['ID_MSG_WALL_EX'] = null;
    $data0[0]['CNT_WALL_LAST'] = 0;
    $data0[0]['ID_WALL'] = $w;
  } else {
    $data0[0]['ID_MSG_WALL_LAST'] = $r8->r['ID_MSG_WALL'];
    $data0[0]['ID_MSG_WALL_EX'] = $r8->r['ID_MSG_WALL_EX'];
    $data0[0]['CNT_WALL_LAST'] = $r8->r['CNT_WALL_LAST'];
    $data0[0]['ID_WALL'] = $r8->r['ID_WALL'];
  }
  
  $r = new db(DB_POBEDIM, 'select * from u2_wall where u=:u and id_wall=:w',[':u'=>$u, ':w'=>$w] );
  $data0[0]['CHECKED_DO_GET_CAST']= iif( $r->row('DO_GET_CAST')==='0','',' checked="checked" ');
  $data0[0]['CHECKED_DO_SHOW_READ']= iif( val_sn('DO_SHOW_READ')=='1',' checked="checked" ','');
  unset($r);
  return $r8;
}
 
// https://youtu.be/Wzn6W9_d2I4