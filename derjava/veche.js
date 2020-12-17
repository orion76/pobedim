function veche_create(event){
    var kv = document.querySelector('input[name="kv"]:checked');
    var t = event.target;
    var href=t.getAttribute('href');
    var nsx=document.getElementById("veche_search");  
    setLocation(href+'&kv='+kv.value+'&sx='+encodeURIComponent(nsx.value));
}


function load_veche0(event) {
    var n0 = document.getElementById('select0');
    var nf = getParentNode('FORM', n0.parentNode);
    var kv = nf.getAttribute('data-kv');
    var s0 = ajax_get('/derjava/ajax.php?ax=select_veche0&vp='+kv+'&kv='+kv);
    n0.innerHTML = s0;
}

function select_veche0(event) {
       var n = event_target(event);
       var np = n.parentNode;
       var nf = getParentNode('FORM', np.parentNode);
       var ni = n.getAttribute('id');
       var v = n.value;
       var kv = nf.getAttribute('data-kv');


       var nv = document.getElementById('veche_name'); 
       var sx = nv.value;
       
       nv = document.getElementById('selected_veche');
       var vz = v;

       var btn = document.getElementById('submit');
       var b = v == 0 && ni=='select0';
       
        if (kv == 1 ){
           if ( v < 20 ) b = true;
           if ( v > 100 && v < 200 ) b = true;
       }
       
       if (b)
           btn.style.display='none';
         else {
             btn.style.display='';
             if (v == 0) {
                 var npz = np.parentNode;
                 n = npz.querySelector('select');
                 vz = n.value;
             }
         }
       nv.value = vz;

       var d = np.querySelector('div');
       var s0 = ajax_get('/derjava/ajax.php?ax=select_veche0&vp='+v+'&kv='+kv+'&sx='+encodeURIComponent(sx));
       if (s0 != '') {
           var t = document.getElementById('veche_treenode');
           d.innerHTML = t.innerHTML;
        //   d.setAttribute('data-ivp',v);
        //   d.setAttribute('data-kv',kv)
           n = d.querySelector('select');
           n.innerHTML = s0;
       } else { d.innerHTML = ''; }
}







function veche_u_btn_more(event){
    alert(1);
}




function veche_search_kind_click(event){
    var f = getParentNode('FORM',event.target);
    f.submit();
}

function veche_role_u_change(event){
            var n = event.target;
            var href = n.getAttribute('href')+n.value;
            return ajax_get(href );
}

function veche_join_member_click(event){
    var t = event.target;
     t.style.display = 'none';
    var tbl = document.getElementById("tblJoinMember");
    tbl.style.display = '';
}



function select_veche1(event) {
       var t = event.target;
       var v = t.value;
       var t2 = document.getElementById('t2');
       var s2 = ajax_get('/derjava/ajax.php?ax=select_veche&n=1&tp='+v);
       t2.innerHTML = s2; 
       t2.style.display = '';
       var t3 = document.getElementById('t3');     
       if (t3)  t3.innerHTML = '';             
       var t4 = document.getElementById('t4');                    
       if (t4) t4.innerHTML = '';             
   }

function select_veche2(event){
       var t = event.target;
       var v = t.value;
       var t3 = document.getElementById('t3');
       var s3 = ajax_get('/derjava/ajax.php?ax=select_veche&n=2&tp='+v);
       t3.innerHTML =  s3;
       t3.style.display = '';
   }
                    
function select_veche3(event){
       var t = event.target;
       var v = t.value;
       var t4 = document.getElementById('t4');
       if(t4){
            var s4 = ajax_get('/derjava/ajax.php?ax=select_veche&n=3&tp='+v);
            t4.innerHTML =  s4;
            t4.style.display = '';
        }
   }
            
function select_veche4(event){}
                    
