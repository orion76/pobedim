   function getW(div){
       var dm=document.querySelector(div); 
       return dm.clientWidth;
   }
   
   function setW(div, w){
       var d=document.querySelector(div);
       d.style.width = w + 'px';  
   }
   
   function doResized(){
       var dm=document.querySelector('.div-main'); 
       var ww = window.innerWidth - dm.clientWidth - 50;
       var d=document.querySelector('.div-ajax');
       if (d.offsetTop > dm.clientHeight - 20 ) 
       { if (window.innerWidth < dm.clientWidth * 2.5 +50 )
          {  d.style.width = (window.innerWidth- 50) + 'px';} 
         else {  d.style.width = ww + 'px';} 
       }
        else {
       d.style.width = ''+ ww +'px';
        }
   }
        
    function tt_loaded(event){ window.onresize = doResized; doResized(); }