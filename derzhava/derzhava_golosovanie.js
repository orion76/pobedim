/*
function ajax_a_likelist_click(event){
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    var n = getParentNode('A', event.target); 
    var href = n.getAttribute('href');
    var r = ajax_get(href);
    var rz = json_parse(r);
    var z = document.getElementById('list_like');
    z.innerHTML = rz['HTML'];
}

function ajax_a_like_click(event){
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    var n = getParentNode('A', event.target); 
    var href = n.getAttribute('href');
    var r = ajax_get(href);
    var rz = json_parse(r);
    var z1 = document.getElementById('cnt_like');
    var z2 = document.getElementById('cnt_dislike');
    z1.innerHTML = rz['CNT_LIKE'];
    z2.innerHTML = rz['CNT_DISLIKE'];
}
*/


function ajax_a_like_click2(event){
    //if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    preventDefault(event);
    var n = getParentNode('A', event.target); 
    var href = n.getAttribute('href');
    var r = ajax_get(href);
    var rz = json_parse(r);
    n = getParentNode('DIV', event.target); 
    var z1 = n.querySelector('span[data-name="cnt_like"]');
    var z2 = n.querySelector('span[data-name="cnt_dislike"]');
    z1.innerHTML = rz['CNT_LIKE'];
    z2.innerHTML = rz['CNT_DISLIKE'];
}


 
function ajax_a_likelist_click2(event){
    //if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    preventDefault(event);
    
    var n = getParentNode('A', event.target); 
    var href = n.getAttribute('href');
    var r = ajax_get(href);
    var rz = json_parse(r);
    
    n = getParentNode('DIV', event.target); 
    var z = n.querySelector('table[data-name="list_like"]');
    z.innerHTML = rz['HTML'];
}
 

function ajax_btn_after_verify_person( btn, text_response){
    btn.style.display = 'none';
    var n = getParentNode('TD',btn);
    var na = n.querySelector('a');
    var cnt = btn.getAttribute('data-cntv')*1 + 1;
    na.innerHTML =  cnt+'.';
    return false; // resultJS_nFunction( theButton, text_response) => false 
}
 

function publish_golosovanie(event){
    var fm = getParentNode('FORM',event.target);
    var an = fm.getAttribute('action')+'&pn=1';
    fm.setAttribute('action',an);
    fm.submit();
}

/*
function get_uploader_pic_poll(qq){
    return get_uploader_file1('file-uploader-'+qq,'/derzhava/derzhava_upload.php?ax=ge_main_pic&qq='+qq
            , function (id, fileName, responseJSON) {  
                  if (responseJSON.success) {
                      var img = getElementById("picqq"+qq);
                      img.setAttribute("src",responseJSON.img);
                      img.style.display = '';
                    }
               }
            , 1 );
}
*/

function get_uploader_pic_answer(qq,pa){
    return get_uploader_file1('file-uploader-'+pa,'/derzhava/derzhava_upload.php?ax=ge_pa_pic&qq='+qq+'&pa='+pa
            , function (id, fileName, responseJSON) {  
                  if (responseJSON.success) {
                      var img = document.getElementById("picpa"+pa);
                      img.setAttribute("src",responseJSON.img);
                    }
               } ,1);
}

function create_answer(event){
  preventDefault(event);  
  var t = event.target;  // button
  var tr = t.parentNode.parentNode;
  var tb = tr.parentNode;
  var trn = document.createElement('tr');
  tb.insertBefore(trn,tr);
  var qq = t.getAttribute('data-qq');
  var url = "/derzhava/derzhava_ajax.php?ax=create_answer&qq="+qq;
  var r = ajax_get(url ); 
  var aa = JSON.parse(r);
  trn.innerHTML = aa['tr'];
  var pa = aa['pa'];
  var uploader = get_uploader_pic_answer(qq,pa);
}




function set_veche_answer_result(event){
    var t = event.target; 
    var v = t.getAttribute('v');
    var qq = t.getAttribute('data-qq');
    var z = t.value;
    var r = ajax_get('/derjava/ajax.php?ax=set_poll_answer_result&iv='+v+'&pa='+z+'&qq='+qq );
    if (r !== '') { alert(r); }
}

function fav_in_ajax_btn_after(btn,txt,i){
    btn.style.display ='none';
    var e = document.getElementById('fav_out');
    e.style.display = '';
    btn.disabled = false;
    return false; 
}

function fav_out_ajax_btn_after(btn,txt,i){
    btn.style.display ='none';
    var e = document.getElementById('fav_in');
    e.style.display = '';
    btn.disabled = false;
    return false; 
}


 
function btn_golosovanie_more_(event){
   var t = event.target; 
   var f = t.parentNode.getElementsByTagName('form')[0];
   f.style.display = 'block';
   t.style.display = 'none';
}


function select_territory1(event) {
       var t = event.target;
       var v = t.value;
       var t2 = document.getElementById('t2');
       var s2 = ajax_get('/derzhava/derzhava_ajax.php?ax=territory&tp='+v);
       t2.innerHTML = s2; 
       var t3 = document.getElementById('t3');                    
       t3.innerHTML = '';             
       var t4 = document.getElementById('t4');                    
       t4.innerHTML = '';             
   }

function select_territory2(event){
       var t = event.target;
       var v = t.value;
       var t3 = document.getElementById('t3');
       var s3 = ajax_get('/derzhava/derzhava_ajax.php?ax=territory&tp='+v);
       t3.innerHTML =  s3;
   }
                    
function select_territory3(event){
       var t = event.target;
       var v = t.value;
       var t4 = document.getElementById('t4');
       var s4 = ajax_get('/derzhava/derzhava_ajax.php?ax=territory&tp='+v);
       t4.innerHTML =  s4;
   }
            
function select_territory4(event){}
                    

function golosovanie_role_u_change(event){
                var n = event.target;
                var href = n.getAttribute('href')+n.value;
                return ajax_get(href );
    }