

function golosovanie_answer_click2(event){ 
   preventDefault(event);
   var t = event.target; 
   var pa = t.getAttribute('pa');
   var qq = t.getAttribute('qq'); 
   var checked = t.getAttribute('checked'); 
   if (checked === 'checked') { pa = null; }
   var r= ajax_get("/derzhava/derzhava_ajax.php?ax=set_answer&pa="+pa+"&qq="+qq);
   if (r === '')
       { location.reload();}
   else { location.assign(r); } 
              
    
  }

 
 