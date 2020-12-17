

function poll_answer_click2(event){ 
   preventDefault(event);
   var t = getParentNode('BUTTON', event.target); 
   //if (t.tagName != 'BUTTON') { t = getParentNode('BUTTON', t); }
   var pa = t.getAttribute('data-pa');
   var qq = t.getAttribute('data-qq'); 
   var cu = t.getAttribute('data-cu'); 
   var checked = t.getAttribute('data-checked'); 
   
   if (checked === 'checked') checked = 0;  else checked = 1;
   
   var r= ajax_get("/derjava/ajax.php?ax=set_answer0&pa="+pa+"&qq="+qq+"&cu="+cu+"&ck="+checked);
   if (r === '')
       { location.reload();}
   else { location.assign(r); } 
   
}

function btn_poll_check_click(event){
   preventDefault(event);
   var t = getParentNode('BUTTON', event.target); 
}

function poll_answer_adminu_click2(event){ 
   preventDefault(event);
   var t = getParentNode('BUTTON', event.target); 
   //if (t.tagName != 'BUTTON') { t = getParentNode('BUTTON', t); }
   var pa = t.getAttribute('data-pa');
   var qq = t.getAttribute('data-qq'); 
   
   var r= ajax_get("/derjava/ajax.php?ax=set_answer&pa="+pa+"&qq="+qq+"&ck=adminu");
   if (r === '')
       { location.reload();}
   else { location.assign(r); } 
   
}



 
function btn_poll_statute_show(event){
   var z=getElementById("tr_poll_statute"); 
   z.style.display="";
   var t=event.target; t.style.display="none";
}