function copy2clipboard(node,text){
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

function url2clipboard(event){
    var n = event_target(event);
    copy2clipboard(n,location.href);
}

function href2clipboard(event){
    var n = event_target(event);
    var t = n.getAttribute('data-href');
    if (!t) t = n.getAttribute('href');
    t = getLocation(t);
    copy2clipboard(n,t);
} 
