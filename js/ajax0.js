function getLocation(url){
  if (url.indexOf('http') != 0) {
     var ht = location.protocol+'//'+ location.host;
     if (url.indexOf('/') == 0) { url = ht + url; }
  }
  return url;
}
function setLocation(url){
    // <button onclick="setLocation('/xxxx/xx/xxxx.php')">xxxx</button>
    location.assign(getLocation(url));
}


function OnStateChange() 
{
    if (OnStateChange.httpRequest.readyState == 0 || OnStateChange.httpRequest.readyState == 4) 
    {
     if (IsRequestSuccessful (OnStateChange.httpRequest)) 
     {    // defined in ajax.js
        OnStateChange.fn(OnStateChange.httpRequest);
     }
     else {
           OnStateChange.fn_fail(OnStateChange.httpRequest);
          }
    }
}

function SendRequestGET (url, fn , async ){
    SendRequestGET0 (url, fn , function(data){
         alert ("AJAX operation failed.");
        }, async );
}

// onsubmit= ajax_form_submit_custom(event)  { var r = ajax_form_submit(event); далее делает свои дела }
function ajax_form_submit(event){
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    var n = event.target;  if (!n) { n = event.srcElement; }
    var href = n.getAttribute('action');
    var params = GetPOST(n);
    return ajax_get(href + params);
}

function ajax_fill_divs(r){
    var rz = json_parse(r);
    for(var index in rz) { 
    var v = rz[index]; 
    var d = document.querySelector(index);
    if (d !== null) { d.innerHTML = v; }
    }
     doResized();
}

/* получаем результат с сервера и раскладываем по селекторам, например ".div-text" */
function ajax_a_click(event){
    if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    var n = event.target;  if (!n) { n = event.srcElement; }
    var href = n.getAttribute('href');
    if (!href) href = n.getAttribute('data-href');
    var r = ajax_get(href);
    var rz = json_parse(r);
    for(var index in rz) { 
        var v = rz[index]; 
        var d = document.querySelector(index);
        if (d !== null) { d.innerHTML = v; }
    }
}

//  ut.php: tag_button_ajax использует эту процу
function ajax_btn_before(btn,i){ return ''; }
function ajax_btn_after(btn,txt,i){ return false; }  // false -- NO LOOP , true -- DO LOOP

function ajax_btn_redirect(event){ 
   var n = event.target;  if (!n) { n = event.srcElement; }
   n.disabled = true;
   var an = n.getAttribute('action');
   var href = n.getAttribute('href');
   var r = ajax_get(an); 
   if (r) alert(r);
   setLocation(href);
}

function ajax_btn_click(event){ 
   var n = event.target;  if (!n) { n = event.srcElement; }
   n.disabled = true;
   var fn_before = n.getAttribute('fn_before');
   var fn_after = n.getAttribute('fn_after');
   var href = n.getAttribute('href');
   var i = -1;
   var params = '';
   do 
   { 
     i++;
     params = window[fn_before](n,i);
     if (params === '') { if (i===0) {ajax_get(href+params);  window[fn_after](n,'',0); }    break; }
     var r = ajax_get(href+params);
   } while ( (true===window[fn_after](n,r,i))  &&  (i < 50 )); // (i < 50 ) is proof 
}

function ajax_btn_click_and_hide(event){
   var n = event.target;  if (!n) { n = event.srcElement; }
   n = getParentNode('BUTTON',n);
   n.style.display = 'none';
   var href = n.getAttribute('href');
   if (href == null) href = n.getAttribute('data-href');
   var r=ajax_get(href);
   if (r != '') alert(r);
}


function ajax_text_change(event){
    var t = event.target;
    var href = t.getAttribute('data-action');
    ajax_get(href +'&v='+encodeURIComponent(t.value));
}

function ajax_textarea_change(event){
    var t = event.target;
    var href = t.getAttribute('data-action');
    var s= ajax_post(href , 'v='+encodeURIComponent(t.value)); // innerHTML
}

function ajax_input_change(event){ 
   var n = event.target;  if (!n) { n = event.srcElement; }
   var href = n.getAttribute('href');
   if (href==null || !href) href = n.getAttribute('data-action');
   if (href==null || !href) href = n.getAttribute('action');
   var t = n.getAttribute('type');
   var v = n.value;
   var nv = n.getAttribute('name');
   if (t === 'checkbox' || t === 'radio' ) {  if (n.checked === false) { v = null; }  }
   var ck = n.checked;
   var r = ajax_get(href+'&checked='+ck+'&v='+ encodeURIComponent( v )+'&n='+ encodeURIComponent( nv ));
}

function ajax_get( url ){   if (url == null) return 'ERR: invalid url ';
   var httpRequest = CreateHTTPRequestObject (); 
   if (httpRequest) 
   {          
        if (url.indexOf('http') != 0) {
           var ht = location.protocol+'//'+ location.host;
           if (url.indexOf('/') == 0) { url = ht + url; }
        }
    httpRequest.open ("GET", url, false);    // async
    httpRequest.onreadystatechange = null;
    httpRequest.send (null);   // urlencoded params когда open POST
   }
   return httpRequest.response;   
}


function ajax_post(url, data_POST){
    if (url.indexOf('http') != 0) {
        var ht = location.protocol+'//'+ location.host;
        if (url.indexOf('/') == 0) { url = ht + url; }
    }
    
    var xmlhttp = getXmlHttp();
    xmlhttp.open("POST",  url, false);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xmlhttp.send(data_POST);
    xmlhttp.onreadystatechange = function(){ 
            if (xmlhttp.readyState == 4) {
                if(xmlhttp.status == 200) {
                    alert(xmlhttp.responseText);
                } else {
                    alert(xmlhttp.status );
                }
            }
        };
  return xmlhttp.responseText;   
}


function SendRequestGET0 (url, fn , fn_fail, async ) {
    return SendRequestGET1 (url, fn , fn_fail, null,  async ) ;
}

function SendRequestGET1 (url, fn , fn_fail, params_, async ) 
{
  if (async == undefined) {async = true;}
  
  httpRequest = CreateHTTPRequestObject ();   // defined in ajax.js

  OnStateChange.params = params_;
  OnStateChange.fn = fn;
  OnStateChange.fn_fail = fn_fail;
  OnStateChange.httpRequest = httpRequest;


  if (url.indexOf('http') != 0) {
     var ht = location.protocol+'//'+ location.host;
     if (url.indexOf('/') == 0) { url = ht + url; }
  }

   if (httpRequest) 
   {          
    // The requested file must be in the same domain that the page is served from.
    httpRequest.open ("GET", url, async);    // async
    httpRequest.onreadystatechange = OnStateChange;
    httpRequest.send (null);   // urlencoded params когда open POST
   }
   return httpRequest;
}


 //---------------------------------------- AJAX ---------------------------------------
// returns whether the HTTP request was successful
function IsRequestSuccessful (httpRequest) {
    // IE: sometimes 1223 instead of 204
    var success = (httpRequest.status == 0 || 
        (httpRequest.status >= 200 && httpRequest.status < 300) || 
        httpRequest.status == 304 || httpRequest.status == 1223);
    
    return success;
}

function CreateHTTPRequestObject ()
{
    // although IE supports the XMLHttpRequest object, but it does not work on local files.
    var forceActiveX = (window.ActiveXObject && location.protocol === "file:");
    if (window.XMLHttpRequest && !forceActiveX) 
    {
        return new XMLHttpRequest();
    }
    else {
        try {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {}
    }
    alert ("Your browser doesn't support XML handling!");
    return null;
}


function getXmlHttp(){
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

// -- utils



// create HTTP request body form form data
function GetPOST (form) 
{
    var data = "";
    for (var i = 0; i < form.elements.length; i++) 
    {
        var elem = form.elements[i];
        if (elem.name) {
            var nodeName = elem.nodeName.toLowerCase ();
            var type = elem.type ? elem.type.toLowerCase () : "";

                // if an input:checked or input:radio is not checked, skip it
            if (nodeName === "input" && (type === "checkbox" || type === "radio")) 
	    {
                if (!elem.checked) 
		{
                    continue;
                }
            }

            var param = "";
                // select element is special, if no value is specified the text must be sent
            if (nodeName === "select") 
	    {
                for (var j = 0; j < elem.options.length; j++) 
		{
                    var option = elem.options[j];
                    if (option.selected) {
                        var valueAttr = option.getAttributeNode ("value");
                        var value = (valueAttr && valueAttr.specified) ? option.value : option.text;
                        if (param != "") {
                            param += "&";
                        }
                        param += encodeURIComponent (elem.name) + "=" + encodeURIComponent (value);
                    }
                }
            }
            else 
            {
                param = encodeURIComponent (elem.name) + "=" + encodeURIComponent (elem.value);
            }

            if (data != "") 
	    {
                data += "&";
            }
            data += param;                  
        }
    }
    return data;
}
