 function DetectBrowser () {
            var agent = navigator.userAgent.toLowerCase ();

            var browser = "Unknown browser";
            if (agent.search ("msie") > -1) {
                browser = "Internet Explorer";
		return 1;
            }
            else {
                if (agent.search ("firefox") > -1) {
                    browser = "Firefox";
		    return 2;
                } 
                else {
                    if (agent.search ("opera") > -1) {
                        browser = "Opera";
			return 3;
                    } 
                    else {
                        if (agent.search ("safari") > -1) {
                            if (agent.search ("chrome") > -1) {
                                browser = "Google Chrome";
				return 4;
                            } 
                            else {
                                browser = "Safari";
				return 5;
                            }
                        }
                    }
                }
            }
            
//            alert (browser);
        }



//  thx to dottor.com


//---------------------------------------- AJAX ---------------------------------------
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

function CreateMSXMLDocumentObject () 
{
    if (typeof (ActiveXObject) != "undefined") 
    {
        var progIDs = [
                        "Msxml2.DOMDocument.6.0", 
                        "Msxml2.DOMDocument.5.0", 
                        "Msxml2.DOMDocument.4.0", 
                        "Msxml2.DOMDocument.3.0", 
                        "MSXML2.DOMDocument", 
                        "MSXML.DOMDocument"
                      ];
        for (var i = 0; i < progIDs.length; i++) {
            try { 
                return new ActiveXObject(progIDs[i]); 
            } catch(e) {};
        }
    }
    return null;
}

function CreateXMLDocumentObject (rootName) 
{
    if (!rootName) { rootName = ""; }

    var xmlDoc = CreateMSXMLDocumentObject ();
    if (xmlDoc) {
        if (rootName) {
            var rootNode = xmlDoc.createElement (rootName);
            xmlDoc.appendChild (rootNode);
        }
    }
    else {
        if (document.implementation.createDocument) {
            xmlDoc = document.implementation.createDocument ("", rootName, null);
        }
    }
    
    return xmlDoc;
}

function ParseHTTPResponse (httpRequest) 
{
    var xmlDoc = httpRequest.responseXML;

    // if responseXML is not valid, try to create the XML document from the responseText property
    if (!xmlDoc || !xmlDoc.documentElement) {
        if (window.DOMParser) {
            var parser = new DOMParser();
            try {
                xmlDoc = parser.parseFromString (httpRequest.responseText, "text/xml");
            } catch (e) {
                alert ("XML parsing error");
                return null;
            };
        }
        else {
            xmlDoc = CreateMSXMLDocumentObject ();
            if (!xmlDoc) {
                return null;
            }
            xmlDoc.loadXML (httpRequest.responseText);

        }
    }

            // if there was an error while parsing the XML document
    var errorMsg = null;
    if (xmlDoc.parseError && xmlDoc.parseError.errorCode != 0) {
        errorMsg = "XML Parsing Error: " + xmlDoc.parseError.reason
                  + " at line " + xmlDoc.parseError.line
                  + " at position " + xmlDoc.parseError.linepos;
    }
    else {
        if (xmlDoc.documentElement) {
            if (xmlDoc.documentElement.nodeName == "parsererror") {
                errorMsg = xmlDoc.documentElement.childNodes[0].nodeValue;
            }
        }
    }
    if (errorMsg) {
        alert (errorMsg);
        return null;
    }

        // ok, the XML document is valid
    return xmlDoc;
}



    // returns whether the HTTP request was successful
function IsRequestSuccessful (httpRequest) {
        // IE: sometimes 1223 instead of 204
    var success = (httpRequest.status == 0 || 
        (httpRequest.status >= 200 && httpRequest.status < 300) || 
        httpRequest.status == 304 || httpRequest.status == 1223);
    
    return success;
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
  return SendRequestGET0 (url, fn , function(data){
         alert ("AJAX operation failed.");
        }, async );
}



function SendRequestGET1 (url, fn , fn_fail, params,  async ) 
{
  if (async == undefined) {async = true;}
  
  httpRequest = CreateHTTPRequestObject ();   // defined in ajax.js

  
  OnStateChange.fn = fn;
  OnStateChange.fn_fail = fn_fail;
  OnStateChange.httpRequest = httpRequest;

   if (httpRequest) 
   {          
    // The requested file must be in the same domain that the page is served from.
    httpRequest.open ("GET", url, async);    // async
    httpRequest.onreadystatechange = OnStateChange;
    httpRequest.send (null);
   }
   return httpRequest;
}


	function gotXmlContentStr(httpRequest)  
        {
            var xmlDoc = ParseHTTPResponse (httpRequest);   // defined in ajax.js
            if (!xmlDoc)  return;

            if (window.XMLSerializer) { // all browsers, except IE before version 9
                var serializer = new XMLSerializer();
                    // the serializeToString method raises an exception in IE9
                try
		{
                    var str = serializer.serializeToString (xmlDoc.documentElement);
		    gotXmlContent.gotXML(str);
                    return true;
                }
                catch (e) {  }
            }

            if ('xml' in xmlDoc)
	    {  // Internet Explorer
               gotXmlContent.gotXML (xmlDoc.xml);
	       return true;
            }
            alert("Cannot display the contents of the XML document as a string.");
        }


	function gotXmlContent(httpRequest)  
        {
            var xmlDoc = ParseHTTPResponse (httpRequest);   // defined in ajax.js
            if (!xmlDoc) return false;

	    gotXmlContent.gotXML(xmlDoc);
            return true;
        }


function  GetXMLstring (url, f_gotXMLstring ) 
{
  var zXmlContent = gotXmlContentStr;
  zXmlContent.gotXML = f_gotXMLstring;
  SendRequestGET( url, zXmlContent);
}

function  GetXML (url, f_gotXML ) 
{
  var zXmlContent = gotXmlContent;
  zXmlContent.gotXML = f_gotXML;
  SendRequestGET( url, zXmlContent);
}

//=========================================== AJAX ========================================

function XMLtBody ( xmlDoc, tbodyID, tableID ) 
{
   var browser=DetectBrowser();

   var errorMsg = null;
   if (xmlDoc.parseError && xmlDoc.parseError.errorCode != 0) 
   {
     errorMsg = "XML Parsing Error: " + xmlDoc.parseError.reason
              + " at line " + xmlDoc.parseError.line
              + " at position " + xmlDoc.parseError.linepos;
   }
   else {
          if (xmlDoc.documentElement) 
  	  {
	        if (xmlDoc.documentElement.nodeName == "parsererror") 
		{
                        errorMsg = xmlDoc.documentElement.childNodes[0].nodeValue;
                }
          }
        }
        if (errorMsg) 
	{
          alert (errorMsg);
          return null;
        }

        var resTable = document.getElementById ( tbodyID );

        var zTable = document.getElementById ( tableID );

// *****************************************************************************
	resTable.rowClick = function (e){ /* alert( e.innerHTML ); */ } ;

        var xmlNodes = new Array();  


	var TblHead = zTable.tHead;

	var bIdx=0;

if (browser==1) // IE ?
{
	for(j = 0; j<TblHead.rows[0].cells.length; j++)
	{
	  var a = TblHead.rows[0].cells[j].attributes["dataFld"];
	  if (a != "null" && a.value != "null") { xmlNodes.push(a.value); }
		else {if (j==0) {bIdx=1;}}
	}
} else {
	for(j = 0; j<TblHead.rows[0].cells.length; j++)
	{
	  var a = TblHead.rows[0].cells[j].getAttribute("dataFld");
	  if (a != null) { xmlNodes.push(a); }
		else {if (j==0) {bIdx=1;}}
	}
}


        var itemTags = xmlDoc.getElementsByTagName ("r");
//	var itemTags = xmlDoc.DocumentElement.childNodes("x")[0];
//	var itemTags = xmlDoc.childNodes("r");
	

        for (i = 0; i < itemTags.length; i++) 
	{
          resTable.insertRow (i);

          for (j = 0; j < xmlNodes.length; j++) 
	  {

//console.log( xmlNodes[j] );
//console.log( recAttr );

	    if (browser==1) // IE ?
            {  
		    var recAttr = itemTags[i].attributes[xmlNodes[j].value];
		    if (recAttr == null) 	
		    {
		       var recordNodes = itemTags[i].getElementsByTagName (xmlNodes[j]);
		       if (recordNodes.length == 0) continue;
	               var recordNode = recordNodes[0];
                       if ( recordNode != null) 
		       {		
        	         resTable.rows[i].insertCell (j);
	                 resTable.rows[i].cells[j].innerHTML = recordNode.text;
                        }
		    } else {
		        	resTable.rows[i].insertCell (j);
		                resTable.rows[i].cells[j].innerHTML = recAttr.value;
		    }
	    } else
	    {
		    var recAttr = itemTags[i].getAttribute(xmlNodes[j]);
		    if (recAttr == null) 
		    {	
			    var recordNodes = itemTags[i].getElementsByTagName (xmlNodes[j]);
        		    if (recordNodes.length == 0) continue;
	        	    var recordNode = recordNodes[0];
			    if (recordNode != "undefind" && recordNode != "null" ) 
			    {	
		        	resTable.rows[i].insertCell (j);
		                resTable.rows[i].cells[j].innerHTML = recordNode.textContent;
			    }
		    } else 
		    {
		        	resTable.rows[i].insertCell (j);
		                resTable.rows[i].cells[j].innerHTML = recAttr;
		    }
	    }		
          }
	  if (bIdx==1) 
	  {
            resTable.rows[i].insertCell(0);
	    resTable.rows[i].cells(0).innerHTML = i+1;
	  }
          resTable.rows[i].onclick= function(){ resTable.rowClick(this); }  
        }
}                                  





function AssignXMLtoFields(xmldoc)
{

 var browser=DetectBrowser();
 if (browser==1) // IE ?
	alert("IE is not supported");
else 
{	
//   var panel1 = document.getElementById("panel1");

   var tags=xmldoc.getElementsByTagName("i");
   for (i=0; i < tags.length; i++) 
   {
       var zca = tags[i].attributes["n"];
        if (zca != null) 
 	{
	 var zcn = zca.value;
	// var zc = panel1.childNodes[zcn];
	 var zc = document.getElementById(zcn);

 	 if (typeof(zc) != "undefined" && zc != null) 
	 {
		zc.value= tags[i].attributes["v"].value;
  	 }
        }
   }
}
}


function mkGUID() 
{
 var guid = "";
 for (var i = 0; i < 32; i++)
  guid += parseInt(Math.random() * 16).toString(16);
 return guid;
}






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
