 function doResize_home(event){
   var wl = getW('.div-left');
   var wc = getW('.div-content');
   var wt = wl - wc - 10;
   if (wt < 100) { wt = wl; }
   setW('.div-text', wt);
} 


function doResize_index(event){
     var ww = window.innerWidth -80;
     setW('.div-right',0);
     setW('.div-left',ww);
}

