
function poll_publish_click(event){
    var t = event.target;
    var qq = t.getAttribute('data-qq');
    var href='/derjava/ajax.php?ax=poll_publish&qq='+qq;
    location.assign(href);
}

function poll_option_click(event){
    var btn = document.getElementById('btn_publish_poll');
    var qq = btn.getAttribute('data-qq');
    var lon = '';
  
/*
   var ww = getElementById('checkboxWW');
if (ww){
    if ( ww.checked ) { lon = lon+'WW';};
}
*/
var vv = document.getElementById('checkboxVV');
if (vv){
    if ( vv.checked ) { lon = lon+'VV';};
}
var pp = document.getElementById('checkboxPP');
if (pp){
    if ( pp.checked ) { lon = lon+'PP';};
}
    
var aa = document.getElementById('checkboxAA');
if ( aa.checked ) { lon = lon+'AA';};
    
ajax_get('/derjava/ajax.php?ax=poll_options_edit&lon='+lon+'&qq='+qq);
    
}

 function poll_text_change(event){
    preventDefault(event);
    var btn = getElementById('btn_publish_poll');
    var qq = btn.getAttribute('data-qq');
    var t = event.target;
    var tx =t.value;
    //ajax_get('/derjava/ajax.php?ax=poll_text&qq='+qq+'&tp='+encodeURIComponent(t.value));
    ajax_post('/derjava/ajax.php?ax=poll_text&qq='+qq , 'tp='+encodeURIComponent(tx));
}


function change_answer(event){
  var t = event.target; 
  var qq = t.getAttribute('data-qq');
  var pa = t.getAttribute('data-pa');
  var npa = encodeURIComponent(t.value);
  var url = "/derjava/ajax.php?ax=update_answer&qq="+qq+"&pa="+pa;
  var r = ajax_post( url , "npa="+npa);
  if (r !== 'OK') { alert(r); }
}


 function poll_name_change(event){
    var btn = getElementById('btn_publish_poll');
    var qq = btn.getAttribute('data-qq');
    ajax_get('/derjava/ajax.php?ax=poll_name&qq='+qq+'&np='+encodeURIComponent(event.target.value));
}

 function poll_ep_change(event){
    var btn = getElementById('btn_publish_poll');
    var qq = btn.getAttribute('data-qq');
    var t = event.target;
    var ep = t.value;
    var it = null;
    if (t.value != "0"){
        it = getElementById('poll_endperiod');
        ep = it.value;
    }
   ep=ajax_get('/derjava/ajax.php?ax=poll_ep&qq='+qq+'&ep='+ep);
   if (it != null){ it.value = ep; }
   if (t.value != "0"){
          var e =  getElementById('poll_endperiod_check');
          e.checked = true;
   }          
}

