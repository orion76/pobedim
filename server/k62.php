<?php

 

function srv_database($db) {
    $r['dbpw']='password';
    $srv = '127.0.0.1:';

    if ($db === 0)
    {
        $r['db']= $srv.'c:\\db\\user.fdb';
    } else
    if ($db === 2)
    {
        $r['db']= $srv.'c:\\db\\pobedim.fdb';
    } else  if ($db === 3)
    {
        $r['db']= $srv.'c:\\db\\koop.fdb';
    } else {
        $r['db']=$srv.'c:\\db\\itogo.db3';
    }
    return $r;
}



?>