<?php

require_once ('ut.php');


$a = 1;

$tt = '';
for($i = 1; $i < 40; $i++){
    $sz = $i .'pt';
    $sz1 = $i .'vh';
    $sz2 = $i .'vw';
    $tt .= "<p><span style='font-size:$sz;'>$i</span><span style='font-size:$sz;font-weight:bold;'>$i</span><span style='font-size:$sz1;'>$i</span><span style='font-size:$sz2;'>$i</span></p>";
}


$t = <<<EOT
<!DOCTYPE html>
<html>
   <head>
        <link rel="stylesheet" type="text/css" href="/css/font.css" />
   <style> 
       
input[type="checkbox"] {
    display:none;
}
input[type="checkbox"] + label span {
    display:inline-block;
    width:19px;
    height:19px;
    margin:-1px 4px 0 0;
    vertical-align:middle;
    background:url(/css/sprite_check.png) left top no-repeat;
    cursor:pointer;
}
        
input[type="checkbox"]:checked + label span {
    background:url(/css/sprite_check.png) -19px top no-repeat;
}

   
input[type="radio"] {
    display:none;
}
        
input[type="radio"] + label span {
    display:inline-block;
    width:19px;
    height:19px;
    margin:-1px 4px 0 0;
    vertical-align:middle;
    background:url(/css/sprite_check.png) -38px top no-repeat;
    cursor:pointer;
}
        
input[type="radio"]:checked + label span {
    background:url(/css/sprite_check.png) -57px top no-repeat;
}
        
        
   </style>
   </head>
        
<body>     
     
<p class="ft0">   ft0  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
<p class="ft1">   ft1  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
<p class="ft2">   ft2  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
<p class="ft3">   ft3  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
<p class="ft4">   ft4  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
<p class="ft5">   ft5  asdfghhjklPOIUYTREWQzxcvbnm,.
</p>
   
<div>
        <input type="checkbox" id="c1" name="cc" />
        <label for="c1"><span></span>Check Box 1</label>
        <input type="radio" id="r1" name="rx" />
        <label for="r1"><span></span>radio 1</label>
        <input type="radio" id="r2" name="rx" />
        <label for="r2"><span></span>radio 2</label>
        
   $tt
</div>       
</body>
</html>
        
EOT;
    
echo var_dump($_SERVER) .  $t;