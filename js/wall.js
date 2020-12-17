
function wall_fileup(event){
    var t = getParentNode('BUTTON',event.target);
    var m=t.getAttribute('data-id');
    var bf = document.getElementById("input_uploader");
    bf.setAttribute('data-id',m);
    bf.setAttribute('data-action','/derjava/wall_upload.php?ax=w_file&m='+m);
    bf.click();
}



function wall_nextpage(event){
   var n = event.target;  if (!n) { n = event.srcElement; }
   var ts = n.getAttribute('data-ts');
   var mx = n.getAttribute('data-mx');
   var sx = n.getAttribute('data-sx');
   var w = n.getAttribute('data-w');
   var r = ajax_get('/derjava/wall_ext.php?ts='+ts+'&w='+w+'&mx='+mx+'&sx_msg='+encodeURIComponent(sx)); 
   var z = n.parentNode;
   z.outerHTML  = r;
}


function wall_ajax_btn_send(event){ 
   var n = event.target;  if (!n) { n = event.srcElement; }
   n.disabled = true;
   var an = n.getAttribute('action');
   var href = n.getAttribute('href');
   var r = ajax_get(an); 
   if (r) alert(r);
   setLocation(href);
}

function wall_msg_showfull(event){
    var p = getParentNode('DIV',event.target);
    var d = p.querySelector('DIV.original_text');
    p.innerHTML = d.innerHTML;
}


function wall_edit_close(event){
    var ed = getElementById('wall_editor');
    ed.style.display = 'none';
    var m = ed.getAttribute('data-id');
    location.href='#m'+m;
}


function wall_msg_reply(event) {
    var t = getParentNode('BUTTON',event.target);
    var an=t.getAttribute('data-action');
    
    var ed = getElementById('wall_editor');
    ed.setAttribute('data-id',0);
    ed.setAttribute('data-action',an);
    ed.style.display = '';
    location.href='#wall_editor';
}

function wall_edit_click(event){
    var t = getParentNode('BUTTON',event.target);
    var m = t.getAttribute('data-id');
    var ed = getElementById('wall_editor');
    ed.style.display = '';
    ed.setAttribute('data-id',m);
    var an=t.getAttribute('data-action');
    ed.setAttribute('data-action',an);
    var txt = ed.querySelector('textarea');
    txt.value = ajax_get('/derjava/ajax.php?ax=msg&bx=get&m='+m);
    location.href='#wall_editor';
}

function wall_save_click(event){
    var ed = getElementById('wall_editor');
    var muj = ed.querySelector('input');
    var m= ed.getAttribute('data-id');
    var an=ed.getAttribute('data-action');
    var txt = ed.querySelector('textarea');
    ed.style.display = 'none';
    var url=ajax_post( an,'muj='+muj.value+'&ln='+encodeURIComponent(location.pathname+'?'+location.search)
                        +'&txt='+encodeURIComponent(txt.value));
    if (url !== ''){
       location.replace(url);
    }
    if (m != 0) {
        location.hash='';
        location.reload();  
        location.hash='m'+m;
    }
}

function wall_show_entire(event){
   var h = location.href;
   h = h.replace('m=','x=');
   location.href = h;
}



function get_uploader_wall(fn_xSubmit,fn_xProgress){
//    var ed = getElementById('wall_editor');
//    var m = ed.getAttribute('data-id');
    return get_uploader_file1('file-uploader-w-0','/derjava/wall_upload.php?ax=w_file&m='
            , function (id, fileName, responseJSON) {  
                  if (responseJSON.success) {
                      //var img = document.getElementById("picpa"+pa);
                      //img.setAttribute("src",responseJSON.img);
                      return [{success:1}];
                    }
               } , 0 ,fn_xSubmit, fn_xProgress );
}





function veche_add_comment(event){
    preventDefault(event);
    alert('!');
}


function wall_do_show_read(event){ajax_input_change(event);location.href="#DO_SHOW_READ"; location.reload();}

function wall_msg_flag_click(event){
    preventDefault(event);
    var n = getParentNode('BUTTON', event.target);
    var href =  n.getAttribute('href');
    var r =  ajax_get( href );
    var a = n.getAttribute('data-flag');
    if (a == "check") {
     n = getParentNode('MAIN', n);   
     var l = n.querySelectorAll('button[data-flag="check"]');
     for(i = 0; i < l.length; i++){
            l[i].style.display = 'none';
       }
    } else 
        n.style.display = 'none';
}



function wall_msg_flag(event){
    preventDefault(event);
    var n = getParentNode('BUTTON', event.target);
    var href =  n.getAttribute('href');
    var flag =  n.getAttribute('data-flag');
    var trig =  n.getAttribute('data-target');
    var p = getParentNode('SPAN',n); if (!p) p = getParentNode('LI',n);
    var i = p.querySelector('[data-flag="'+trig+'"]'); 
    if (i !== null){
        i.style.display = '';
        n.style.display = 'none';
        var r =  ajax_get( href );
    }
    /*
    if (flag == 'like') {
     var s = n.querySelector('small');
     var cnt = (s.innerHTML)*1 + 1;
     s.innerHTML = cnt;
    }
     */

}

function veche_wall_user_change(event){
    var n = event.target;
    var href = n.getAttribute('action')+n.value;
    return ajax_get(href );
}

