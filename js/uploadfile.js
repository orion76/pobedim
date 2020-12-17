/*
  
требуется такой код на странице, где предполагается загрузка 

<!-- $uploadfile$ -->

или код 

<div style="display:none" id="uploader">
    <form style="display:none" method="post" enctype="multipart/form-data" id="form_uploader" >
        <input type="file" id="input_uploader" name="userfile" onchange="chosen_uploader(event)">
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
    </form>
</div>

*/

function uploader_file_sent(event){
  var r = event.target;
 // alert(r.name);
  r.onload = null; 
  var m=r.getAttribute('data-id');
  var n = document.getElementById('upploader-li-'+m);
  if (n){
    var t = r.contentDocument.body.innerHTML;
    var rz= JSON.parse(t);
    if (rz['ERR'] == '')  n.innerHTML = rz['NF']; else n.innerHTML = rz['MSG'];
    n.removeAttribute("id");
  }
  r.removeAttribute("name");
 // var uu = document.getElementById("uploader");
 // uu.removeChild(r);
}

function chosen_uploader(event){
    var t = event.target;
    var m=t.getAttribute('data-id');
    var an=t.getAttribute('data-action');
    
    //var uu = document.getElementById("uploader");
    var f = document.getElementById("form_uploader");
    f.removeAttribute('target');
    var uu = f.parentNode;
    var nn="iframe_"+ (" "+Math.random()).substring(4,8)+"-"+m;
    
    var r = null;
    //var r = document.createElement('iframe'); 
    // r.setAttribute('name',nn);
    // r.setAttribute('src',"javascript:false;")
        var div = document.createElement('div');
        div.innerHTML = '<iframe src="javascript:false;" name="' + nn + '" />';
        r = div.firstChild;
        div.removeChild(r);
    // --- конец финта ушами создания фрейма
    
    uu.insertBefore(r,f);

    r.setAttribute('data-id',m);
    r.onload = uploader_file_sent; 

    var ul = document.getElementById("fm"+m);
    if (ul){
        var n = document.createElement('li');
        n.innerHTML = 'Идёт загрузка файла ..';
        n.setAttribute('id', 'upploader-li-'+m)
        ul.appendChild(n);
    }
    f.setAttribute('target',nn);
    f.setAttribute('action',an);
    f.submit();
}
