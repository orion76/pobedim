function tk_make_MoreButtons1(tk){ return '';}


function ltk_events_raised(){
if (timerOn === 0) return;
   timerOn = 0;    
  ltk_load_li();
  timerOn = 1;
}

function jsTalkingNew(event){
   SendRequestGET('/tk/tk.php?ax=9209', function(data){} ,true); 
   alert('Требуется авторизация');
}

function jsPTalkingSave(event){
 
}

function jsTalkingSave(event){
 
}

function jsTalkingYesNo(event_button, tt) { 
    SendRequestGET('/tk/tk.php?ax=9209', function(data){} ,true); 
    alert('Требуется авторизация');
}
