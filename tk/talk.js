
function tk_ltalking1(pn,tk){

    SendRequestGET('/tk/db_talk.php?ax=9106&tk='+tk
                        ,function(data){
                            var rz =  json_parse(data.response);
                            var tk = rz.tk;
                            var ht = null;
                             ht = pn.querySelector('.tk_ot');
                             ht.innerText = rz.ht4;
                             ht = pn.querySelector('.tk_st');
                             ht.innerText = rz.ht5;
                             ht = pn.querySelector('.tk_rg');
                             ht.innerText = rz.ht3;
                             ht = pn.querySelector('.tk_pr');
                             ht.innerText = rz.ht2;
                             
                             /*  20191117
                              * 
                             ht = pn.querySelector('button .tkYes');
                             ht.innerText = ' '+rz.ht1;
                             ht = pn.querySelector('button .tkNo');
                             ht.innerText = ' '+rz.ht0;
                             var id = 'ul_tk'+tk;   
                             var ul_talk = getElementById(id);
                             ul_talk.innerHTML = rz.htm; 
                             
                             for(i = 0; i < ul_talk.childNodes.length; i++){
                                 var nx = ul_talk.childNodes[i];
                                 var ttg = nx.getAttribute('ttg');
                                 if ( ttg > 1 ){
                                     nx.setAttribute('style','display:none;');
                                 }
                             }
                             
                              
                             var lu = rz.lu;
                             var hd = document.querySelector('head');
                             if (lu.length>0){
                                 var lcss = hd.querySelectorAll('link[type="text/css"]');
                                 for(j = 0; j<lu.length; j++){
                                    var u = lu[j]; if (u == null) { u = 0;}
                                    for(i = 0; i<lcss.length; i++){
                                       var f = lcss[i].getAttribute('href');     
                                       if (f == '/u/x'+u+'.css') { break;}
                                       f  = '';
                                    }
                                    if (f == '') { 
                                        var et = document.createElement("link"); 
                                        et.setAttribute('href','/u/x'+u+'.css');
                                        et.setAttribute('type','text/css');
                                        et.setAttribute('rel','stylesheet');
                                        hd.appendChild(et);
                                    }
                                }
                             }
                             */
                            
                        } , false);
}


function tk_copy2clipboard(node,text){
    var by = node.parentNode;
    var et = document.createElement("input");
    et.setAttribute('type','text');
    et.setAttribute('value',text);
    by.parentNode.insertBefore(et,by);
    et.focus();
    et.select();
    document.execCommand('copy');
    by.parentNode.removeChild(et);
}

function tg_url2clipboard(event){
    var n = event_target(event);
    n = getParentNode('LI',n);
    var h = 'pobedim.su'; //location.host
    var href = location.protocol+'//'+ h +'/'+n.getAttribute('tg');  //  '/tg/?+n.getAttribute('id');
    tk_copy2clipboard(n,href);
}



 function tk_body_loaded(event){
    var pn = document.querySelector(".tk_pn");
    var tk = pn.getAttribute('tk');
    tk_make_tags1(pn,tk);
    tk_ltalking1(pn, tk);
    tk_load_youtube( pn );
    var h = window.location.hash;
    if (h != '') { 
      window.location.hash = '';
      window.location.hash = h;
    }
}

function droplist_click0(event) {
    var t = event_target(event);
    t = getParentNode('LI', t);
    var c = t.querySelectorAll('.v');
    var v = t.innerHTML;
    var ul = getParentNode('UL', t);
    var name_input = ul.getAttribute('name');
    var n =  document.querySelector('input[name=' + name_input + ']');
    if (n.value != '') { n.value =  n.value +'; '+v; } else { n.value = v;}
    n.focus();
}


function tku_body_loaded(event){
    var pn = document.querySelector(".tk_pn");
    tk_load_youtube( pn ); 
 }
 
function tk_load_youtube( div_tk_pn ){
    if (!div_tk_pn) return false;
    var lyt = div_tk_pn.getElementsByTagName('youtube');
    
    for (var j = 0; j < lyt.length ;j++){
       var  yt = lyt[j];
       var iyt = yt.getAttribute('key');
       if (iyt){
            yt.innerHTML = '<img src="https://img.youtube.com/vi/'
                        +iyt +'/hqdefault.jpg" onclick="tk_youtube_img_click(event)" style="cursor:pointer;" >'
                +yt.innerHTML;
        }
    }
}



var current_tk_loading = 0;
var current_n_li_tk = null;
function ltk_print_li(n_li, jsonData){
    n_li.innerHTML = jsonData['HTM'];
    n_li.setAttribute('class','loaded');
    if ( n_li.innerHTML !== '') {
        var tk = jsonData['ID_TALK'];
        var pn = n_li.querySelector('.tk_pn');
        tk_make_tags1(pn,tk);
        tk_ltalking1(pn,tk);
        tk_load_youtube(pn);
    } else { n_li.parentNode.removeChild(n_li); }
}

var ltk_load_count = 0;
function ltk_load_li(){
    var lsr_loading = document.querySelectorAll('ul.ltalk .loading');
    if (lsr_loading.length > 0) {return;}

    var lsr = document.querySelectorAll('ul.ltalk .unloaded'); 

    if (lsr.length === 0) return;
    ltk_load_count = 0;
    for(i = 0; i <lsr.length; i++ ){
        if ( lsr[i].getAttribute('class') !== 'unloaded' ) { continue; }
        if (!isVisible0(lsr[i])) continue;
        var tk = lsr[i].getAttribute('tk');
        if (tk) {
            ltk_load_count++;
            current_tk_loading = tk;
            current_n_li_tk = lsr[i];
            current_n_li_tk.setAttribute('class','loading');

            SendRequestGET1('/tk/db_talk.php?ax=9105&tk='+tk
                ,function(data){
                        var rz =  json_parse(data.response);
                        current_n_li_tk = this.params.n_li_tk;
                        ltk_print_li(current_n_li_tk, rz);
                        current_n_li_tk = null;
                        current_tk_loading = 0;
                }
                ,function(data){
                    current_n_li_tk.innerHTML = 'ajax error';
                    current_n_li_tk.setAttribute('class','loaded');
                    current_tk_loading = 0;
                    current_n_li_tk = null;
                }         
                ,{ tk:tk , n_li_tk: lsr[i] }
            );

            if (ltk_load_count > 0)
            {
                break; // загружаем только две публикации, нам спешить некуда
            }
        }
    }
}


function ltk_hashtag_click(event){
    var n= event_target(event);
    var i = n.innerHTML.indexOf('<sup>');
    var s = n.innerHTML.substr(0,i);
    var ni = document.getElementById('search');
    var v = ' '+ni.value+' ';
    i = v.indexOf(s+' ');  
    if (i > 0) {
        i = " +-#<@#^:;!|".indexOf(v.substr(i-1,1));
    }
    if ( i < 0) {
        if ( n.innerHTML.indexOf('<sup>0</sup>') > 0  ) {ni.value = ' +'+s;} else { ni.value += ' +'+s;}
        var f = document.querySelector('form.ltk_search');
        f.submit();
    }
}

var timerOn = 1;
function ltk_window_scroll_init(){
    window.onscroll = function() {
        ltk_load_li();
    };
    var lsr = document.querySelectorAll('.lhashtag li');
    for(i=0; i<lsr.length; i++){
        lsr[i].addEventListener("click",ltk_hashtag_click);
    }
    
    ltk_events_raised();
    setInterval(ltk_events_raised, 2500);
}


function jsTalkCounts(tk){
     SendRequestGET('/tk/db_talk.php?ax=9106&tk='+tk
        ,function(data){
               var rz =  json_parse(data.response);
               
            });
}




function jsTalkingYes(event)
{
    jsTalkingYesNo(event,1);
}
function jsTalkingNo(event)
{
    jsTalkingYesNo(event,-1);
}



function tk_make_tags1(pn,tk){
    if (pn == null) {return;}
    var btn = null;
    var a = null;
    var mn = pn.parentNode;
    var url = null;
    var li =  getParentNode('LI',pn); 
    var iu = null;
    if (li) iu = li.getAttribute('iu');
     
//---------------
/*
    url = location.protocol+'//'+location.host + '/'+tk;  //   '/tk?'
    a = document.createElement("a");
    a.setAttribute('target','tk');
    a.setAttribute('href',"http://www.facebook.com/sharer/sharer.php?u="+url);
    btn = document.createElement("img");
    btn.setAttribute('src','/32/up2fb.png'); 
    btn.setAttribute('title','опубликовать на Facebook '+tk);
    btn.setAttribute('width','32px');
    a.appendChild(btn);
    mn.insertBefore(a, pn);
*/    
//---------------
if (li) {
    a = document.createElement("a");
    a.setAttribute('target','tk');
    if (iu) { a.setAttribute('href','/tk/'+iu+'/'+tk+'.php'); }
        else { 
    a.setAttribute('href','/'+tk);  // '/tk/?'
        }
    btn = document.createElement("img");
    btn.setAttribute('src','/32/newwdw32.png'); 
    btn.setAttribute('title','Открыть в новом окне '+tk);
    btn.setAttribute('width','32px');
    a.appendChild(btn);
    mn.insertBefore(a, pn);
} else {
    //---------------
    a = document.createElement("a");
    a.setAttribute('target','tk');
    a.setAttribute('href','https://pobedim.su/pobedim/pobedim.php'); 
    btn = document.createElement("img");
    btn.setAttribute('src','/32/star.png'); 
    btn.setAttribute('title',' Народное государство: время объединяться! ');
    btn.setAttribute('width','32px');
    a.appendChild(btn);
    mn.insertBefore(a, pn);
    
}

//---------------
    btn = document.createElement("img");
    btn.setAttribute('src','/32/copylink32.png'); 
    btn.setAttribute('title','скопирывать URL в буфер обмена '+tk);
    btn.setAttribute('width','32px');
    btn.setAttribute('onclick','tk_btn_url2clipboard('+tk+')');
    mn.insertBefore(btn, pn);
//---------------
    btn = document.createElement("img");
    btn.setAttribute('title','Скрыть текст '+tk);
    btn.setAttribute('src','/32/cps.png'); 
    btn.setAttribute('onclick','tk_btn_pn_text_hide(event)');
    mn.insertBefore(btn, pn);
//---------------
    var node_pn_tk = pn;
    var iframes = node_pn_tk.querySelectorAll('iframe');
    for(i=0; i< iframes.length;i++){
        var fm = iframes[i];
        var dv = fm.parentNode;
        if (dv.getAttribute('class') != 'tk_fm'){
             fm = document.createElement("div");
             fm.setAttribute('class','tk_fm');
             fm.appendChild(iframes[i]);
             dv.appendChild(fm);
        }
    }
    
    
    var lht = node_pn_tk.querySelector('.tk_hashtag');
    if (!lht){
        var tl = node_pn_tk.querySelector('.tk_tl');
        lht = document.createElement("div");
        lht.setAttribute('class','tk_hashtag');
        //node_pn_tk.appendChild(lht);
        node_pn_tk.insertBefore(lht,tl);
        lht.innerHTML = '<div class="tk_ot" title="тема, предмет"></div>'
                +'<div class="tk_st" title="автор высказывания"></div>'
                +'<div class="tk_rg" title="проект, эпоха, время"></div>'
                +'<div class="tk_pr" title="издание, телепередача"></div>';
    }
 /*   
    var talk = node_pn_tk.querySelector('.talk');
    if (!talk){
        talk = document.createElement("div");
        talk.setAttribute('class','talk');
        talk.setAttribute('tk',tk);
        node_pn_tk.appendChild(talk);
        talk.innerHTML = '<button value="1" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button">+ Согласен<sup class="tkYes"></sup></button>'
            +'<button value="-1" tk="'+tk+'" onclick="jsTalkingNew(event)" type="button">- Возражаю<sup class="tkNo"></sup></button>'
            +tk_make_MoreButtons1(tk)
            +'<ul id="ul_tk'+tk+'" class="ltg"> </ul>';
    }
*/    
}


function tk_youtube_img_click(event){
    var yt0 = event_target(event);
    var yt = getParentNode('youtube',yt0);
    var id = yt.getAttribute("key");
    yt.innerHTML = '<div  class="tk_fm" >'
      +'<iframe  src="https://youtube.com/embed/'+id+'"  frameborder="0" allowfullscreen></iframe>'
      +'</div>';  
}



function showElementsSrc_(le , cnt){
    for (var i = 0; i < le.length; i++) { 
        var f = le[i];
         if (f.hasAttribute("src_") && isVisible0(f)) {
            var src_ = f.getAttribute("src_");
             f.removeAttribute("src_");                     
             
            if (src_.indexOf('md=') > 0){
               SendRequestGET( src_   // read file.md
                        ,function(data){
                              f.parentNode.innerHTML = data.response;
                            });
             } 
             else { 
               SendRequestGET('dummy.php?'
                   ,function(data){
                           f.setAttribute ("src",src_);
                         //  var et1 = document.createElement("span");   
                         //  et1.innerHTML = 'OK';
                         //  f.parentNode.appendChild(et1);
                       });
            }
            cnt--;
            if ( cnt <= 0 ) { return true; }
         }
       }
     return false;
}


    /**
     * Проверяет элемент на попадание в видимую часть экрана.
     * Для попадания достаточно, чтобы верхняя или нижняя границы элемента были видны.
     */
    function isVisible0(elem) {
      var coords = elem.getBoundingClientRect();
      var windowHeight = document.documentElement.clientHeight;
      // верхняя граница elem в пределах видимости ИЛИ нижняя граница видима
      var topVisible = coords.top > 0 && coords.top < windowHeight;
      var bottomVisible = coords.bottom < windowHeight && coords.bottom > 0;
      return topVisible || bottomVisible;
    }
    /**
    Вариант проверки, считающий элемент видимым,
    если он не более чем -1 страница назад или +1 страница вперед
   */
    function isVisible1(elem) {
      var coords = elem.getBoundingClientRect();
      var windowHeight = document.documentElement.clientHeight;
      var extendedTop = -windowHeight;
      var extendedBottom = 2 * windowHeight;
      // top visible || bottom visible
      var topVisible = coords.top > extendedTop && coords.top < extendedBottom;
      var bottomVisible = coords.bottom < extendedBottom && coords.bottom > extendedTop;
      return topVisible || bottomVisible;
    }



function tk_btn_pn_text_hide(event){ 
    
    var n = event_target(event);
    var dv = n.parentNode.querySelector('.tk_text');
    if (dv.hasAttribute('hidden')) {
        dv.removeAttribute('hidden');
        //n.innerHTML = 'Скрыть текст';
        n.setAttribute('title','Скрыть текст');
        n.setAttribute('src','/32/cps.png');         
    } else
    {
        dv.setAttribute('hidden','hidden'); 
        //n.innerHTML = 'Показть текст';
        n.setAttribute('title','Показть текст');
        n.setAttribute('src','/32/dcps.png'); 
    }
}


function tk_btn_url2clipboard(tk){
    var n = event_target(event);
    var by = n.parentNode;
    var zhref = location.protocol+'//'+location.host+ '/'+tk; // + '/tk?'+tk; 
    var et = document.createElement("input");
    et.setAttribute('type','text');
    et.setAttribute('value',zhref);
    by.parentNode.insertBefore(et,by);
    et.focus();
    et.select();
    document.execCommand('copy');
    by.parentNode.removeChild(et);
}

