<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';

require_once $dir0 . '/derjava/fn.php';



$ax = val_rq('ax');
$ic = val_rq('ic', 0);

$fu = current_fu();
$u = current_iu();
$bx = val_rq('bx');

if ($bx === 'update_cert') {
    $r = new db(DB_POBEDIM, 'select * from U2_CERTIFICATE_IU2(:u,:ic,:tv, :n1c,:n2c,:n3c,:dbc,:bpc,:cc,:kv)'
            ,[':u'=>$u,':ic'=>$ic,':tv'=>val_rq('tv'), ':n1c'=>val_rq('n1c'),':n2c'=>val_rq('n2c'),':n3c'=>val_rq('n3c')
                ,':dbc'=>val_rq('dbc'),':bpc'=>val_rq('pbc'),':cc'=>val_rq('cc')
                ,':kv'=>val_rq('kv')]);
}



$ht = new html('/derjava/person_confirm.html');
$menu= menu_user($ht, '');

$data1=array();

    $r = new db(DB_POBEDIM,<<<SQL
select cu.* from u2_certificate cu  where cu.ID_CERTIFICATE=:ic
SQL
            ,[':ic'=>$ic]);
    $s1 = '';
    $s2 = '';
    foreach($r->rows as $row){
        if ( ($row['U']+0) === ($u+0) ) { $data0[0] = $row; }
        else  {
            $row['NAME_U'] = get_username($row['U']);
            array_push ($data1,$row);
            }
    }
    



$ht->data('data0', [] );
$ht->data('data1', $data1 );
 

echo $ht->build('',true);

 
exit;


//---------------------
     




function form_edit_u_cert_validation($row){
    /*
    U                    
    ID_CERTIFICATE           
    ID_KIND_VALIDATION       
    */
    
    $r2 = new db(DB_POBEDIM, 'select * from W2_USER_VALIDATION_KIND');
    $r2->filter = function ($row){
        return tag_option( $row['NAME_KIND_VALIDATION'], $row['ID_KIND_VALIDATION']);
    };

   // $u = current_iu();
    $e = empty($row['NAME3_CERTIFICATE'] || $row['NAME1_CERTIFICATE'] || $row['NAME2_CERTIFICATE']);
    return 
        tag_p('Вы подтверждаете следующие данные о человеке: ')
        .tag_form('?bx=update_cert&ic='.$row['ID_CERTIFICATE'] ,   
                   tag_table(
                            tag_tr(
                                    tag_td('Фамилия')
                                    . tag_td( iif($e , tag_input0('n3c', $row['NAME3_CERTIFICATE'] , a_size(50)) , $row['NAME3_CERTIFICATE'] ))
                            )
                            .tag_tr(
                                    tag_td('Имя')
                                    . tag_td( iif($e, tag_input0('n1c', $row['NAME1_CERTIFICATE'] , a_size(50))  ,$row['NAME1_CERTIFICATE']  ))
                            )
                           .tag_tr(
                                    tag_td('Отчество')
                                   . tag_td( iif($e, tag_input0('n2c', $row['NAME2_CERTIFICATE'], a_size(50)) , $row['NAME2_CERTIFICATE']  ))
                            )
                     //      .tag_tr( tag_td('Дата рождения'). tag_td( tag_input0('dbc', $row['D_BIRTH_CERTIFICATE']) ) )
                     //      .tag_tr( tag_td('Место рождения'). tag_td( tag_input0('pbc', $row['PLACE_BIRTH_CERTIFICATE'], a_size(50)) ) )                            
                     //      .tag_tr( tag_td('Контактная информация'). tag_td( tag_input0('cc', $row['CONTACT_CERTIFICATE'] , a_size(50)) ) )
                     //      .tag_tr( tag_td('эл почта'). tag_td( tag_input0('ec', $row['EMAIL_CERTIFICATE'] , a_size(50)) ) )  
                           .tag_tr( tag_td('Примечание'). tag_td( tag_input0('tv', $row['TEXT_VALIDATION'] , a_size(50)) ) )            
                           .tag_tr( tag_td('Основание'). tag_td(tag_select('kv', $row['ID_KIND_VALIDATION'] , tag_option('', 0). $r2->printf() ) ) )
                     //      .tag_tr( tag_td('Фото'). tag_td( '[здесь будет список фото и возможность загрузить новое]' ) )            
                           
                        )
                      );
}




    if (empty($s1)) {
        $r0 = new db(DB_POBEDIM,'select first 1 * from u2_certificate_s1(:ic)',[':ic'=>$ic]);
        $row = $r0->r['DS'][0]; 
        $row['U'] = $u;
        $row['ID_CERTIFICATE'] = $ic;
        $s1 = form_edit_u_cert_validation( $row ); 
    }
