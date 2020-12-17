<?php
    $dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
    include $dir0 . '/derjava/home.php';


/*



require_once $dir0 . '/ut.php';



require_once $dir0 . '/html.php';

$ht = new html('');


$ht->data('body', ['BODY' =>  'test '. tag_a('/derjava/poll_search.php', 'ГОЛОСОВАНИЯ')] );



echo $ht->build('',true);

exit();

*/