function tk_make_MoreButtons1(tk){
    return '';
    
    
    return '<button onclick="jsTalkingMore(event)" type="button">...</button>'
            +'<span style="display:none" class="tool">'
            +'<a href="https://pobedim.su/tk/1001/-11146.php" target="tk_hlp"><img src="/32/bulb32.png" ></a>'
            +'<button value="4" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button"># тема, объект</button>'
            +'<button value="5" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button">@ субъект, автор</button>'
            +'<button value="3" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button">: время, место, проект</button>'
            +'<button value="2" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button">&lt; издатель</button>'
            +'<button value="10" tk="'+tk+'" onclick="jsTalkingNew(event)" title="опубликован в группе FB" type="button">fb$</button>'
            +'<button value="11" tk="'+tk+'" onclick="jsTalkingNew(event)" title="опубликован в группе VK"  type="button">vk$</button>'
            +'<button value="7" tk="'+tk+'" onclick="jsTalkingNew(event)" title="параграф"  type="button">-&gt;</button>'
            +'</span>';
}




function jsTalkingMore(event){
    var n = event_target(event);
    var np= n.parentNode;
    var tl = np.querySelector('.tool');
    tl.removeAttribute('style');
    n.setAttribute('hidden','hidden');
    var ul_talk = np.querySelector('ul.ltg');
    for(i = 0; i < ul_talk.childNodes.length; i++){
        var nx = ul_talk.childNodes[i];
        if (nx.attributes == null) {continue;}
        var ttg = nx.getAttribute('ttg');
        if ( ttg > 1 ){
            //nx.setAttribute('style','display:none;');
             nx.removeAttribute('style');
        }
    }
}



var times = -1;
var timer9207 = 1;
var timer9208 = 1;
function ltk_events_raised(){
   if (timerOn == 0) return;
   timerOn = 0;
   times++;
   var dv = document.querySelector('.ltk_timer');  
   dv.innerHTML = times +'| ';
   
 // новые публикации
   if ( (times % timer9207) === 0 ) {
        timer9207 += 4;
        if (timer9207 > 100) {timer9207 = 5;}
        SendRequestGET('tk/tk.php?ax=9207'
                        ,function(data){
                            var rz =  json_parse(data.response);
                            var dv = document.querySelector('.ltk_new'); 
                            if (rz['CNT']){
                                dv.innerHTML = rz['CNT']; 
                            } else { dv.innerHTML = '0';}
                         }
                    );    
   }

// новые комментарии
   if ( (times % timer9208) === 0 ) {
        timer9208 += 1;
        if (timer9208 > 100) {timer9208 = 5;}
        SendRequestGET('tk/tk.php?ax=9208'
                        ,function(data){
                            var rz =  json_parse(data.response);
                            var dv = document.querySelector('.ltg_new'); 
                            if (rz['CNT']){
                                dv.innerHTML = rz['CNT']; 
                            } else { dv.innerHTML = '0';}
                         }
                    );    
   }    
    
    
   ltk_load_li();
   timerOn = 1;
}

function jsTalkingNew(event){
     var n = event_target(event);
     var tk = n.getAttribute("tk"); 
     var tt = n.getAttribute("value"); 
     SendRequestGET('tk/db_talk.php?ax=9103&tk='+tk+'&tt='+tt
        ,function(data){
               var rz =  json_parse(data.response);
               var id_ul = 'ul_tk'+tk;
               var ul = document.getElementById(id_ul);
               var uls = "li[tg="+rz.tg+"]";
               var etx = ul.querySelectorAll("li");
               var et1 = null;
               for(i = 0; i < etx.length; i++){
                   if (etx[i].getAttribute('tg')==rz.tg){ et1 = etx[i]; break; }
               }
                if (et1 === null){
                  et1 =  document.createElement("li"); 
                  et1.setAttribute('tg',rz.tg);
                  if(ul.childNodes.length > 0){
                      ul.insertBefore(et1,ul.childNodes[0]);
                  } else {ul.appendChild(et1);}
                 }
                et1.outerHTML =  rz.htm; 
                et1.setAttribute('tt',rz.tt);                    
            });
}


function jsPTalkingSave(event){
    var n = event_target(event);
    var np = getParentNode('LI',n);
    var tg = np.getAttribute("tg"); 
    var v = n.value; 
    SendRequestGET('tk/db_talk.php?ax=9109&tg='+tg+'&p='+encodeURI(v)
        ,function(data){
               var rz =  json_parse(data.response);
               if (rz.error !== 0) {
                    var et1 = document.createElement("span");   
                    et1.innerHTML = 'error';
                    n.parentNode.appendChild(et1);
                }
            });
}

function jsTalkingSave(event){
    var n = event_target(event);
    var np = getParentNode('LI',n);
    var tg = np.getAttribute("tg"); 
    var v = n.innerText; 
    SendRequestGET('tk/db_talk.php?ax=9102&tg='+tg+'&t='+encodeURI(v)
        ,function(data){
               var rz =  json_parse(data.response);
               if (rz.error !== 0) {
                    var et1 = document.createElement("span");   
                    et1.innerHTML = 'error';
                    n.parentNode.appendChild(et1);
                }
            });
}


function jsTalkingYesNo(event_button, tt) { 
    var n = event_target(event_button);
    var n0 = getParentNode("li", n);
    var tg = n0.getAttribute("tg"); 
    var tk = n0.getAttribute("tk"); 
    var ul = null;
    var uls = n0.getElementsByTagName('ul');
    if (uls.length == 0 ) {
        ul = document.createElement("ul");
        n0.appendChild(ul);
        } else { ul = uls[0];}
    
    SendRequestGET('tk/db_talk.php?ax=9101&tg='+tg+'&tt='+tt+'&tk='+tk
        ,function(data){
               var rz =  json_parse(data.response);
               var etx = ul.querySelectorAll("li");
               var et1 = null;
               for(i = 0; i < etx.length; i++){
                   if (etx[i].getAttribute('tg')==rz.tg){ et1 = etx[i]; break; }
               }
                if (!et1){
                  et1 =  document.createElement("li"); 
                  et1.setAttribute('tg',rz.tg);
                  if (tt == 1) et1.setAttribute('class','tg_yes');
                  if (tt == -1) et1.setAttribute('class','tg_no');
                  ul.appendChild(et1);
                 }
                et1.innerHTML =  rz.htm; 
            });
}




