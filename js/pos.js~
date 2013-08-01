
// 说明：用 Javascript 获取指定页面元素的位置
// 整理：http://www.codebit.cn
// 来源：YUI DOM
 
function getElementPos(elementId) {
 
    var ua = navigator.userAgent.toLowerCase();
    var isOpera = (ua.indexOf('opera') != -1);
    var isIE = (ua.indexOf('msie') != -1 && !isOpera); // not opera spoof
 
    var el = document.getElementById(elementId);
 
    if(el.parentNode === null || el.style.display == 'none')
    {
        return false;
    }
 
    var parent = null;
    var pos = [];
    var box;
 
    if(el.getBoundingClientRect)    //IE
    {
        box = el.getBoundingClientRect();
        var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
        var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
 
        return {x:box.left + scrollLeft, y:box.top + scrollTop};
    }
    else if(document.getBoxObjectFor)   // gecko
    {
        box = document.getBoxObjectFor(el);
 
        var borderLeft = (el.style.borderLeftWidth)?parseInt(el.style.borderLeftWidth):0;
        var borderTop = (el.style.borderTopWidth)?parseInt(el.style.borderTopWidth):0;
 
        pos = [box.x - borderLeft, box.y - borderTop];
    }
    else    // safari & opera
    {
        pos = [el.offsetLeft, el.offsetTop];
        parent = el.offsetParent;
        if (parent != el) {
            while (parent) {
                pos[0] += parent.offsetLeft;
                pos[1] += parent.offsetTop;
                parent = parent.offsetParent;
            }
        }
        if (ua.indexOf('opera') != -1
            || ( ua.indexOf('safari') != -1 && el.style.position == 'absolute' ))
        {
                pos[0] -= document.body.offsetLeft;
                pos[1] -= document.body.offsetTop;
        }
    }
 
    if (el.parentNode) { parent = el.parentNode; }
    else { parent = null; }
 
    while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML')
    { // account for any scrolled ancestors
        pos[0] -= parent.scrollLeft;
        pos[1] -= parent.scrollTop;
 
        if (parent.parentNode) { parent = parent.parentNode; }
        else { parent = null; }
    }
    return {x:pos[0], y:pos[1]};
}

function getMousePos(e){
	var posx = 0, posy = 0,
   e = e || window.event;
           
   if (e.pageX || e.pageY) {
      posx = e.pageX;
      posy = e.pageY;
   } 
   else if (e.clientX || e.clientY) {
      posx = e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
      posy = e.clientY + document.documentElement.scrollTop + document.body.scrollTop;
   }
	
	return {x:posx, y:posy};
	
}

function getElementStyle(id , type){
    var typeface = '';
    elem=document.getElementById(id);
    if(elem.currentStyle)
        typeface = elem.currentStyle[type];
    else if(window.getComputedStyle)
        typeface = window.getComputedStyle(elem , null)[type];
    return typeface;        
}

function getElementSize(id){
	var element=document.getElementById(id);

	var pos=getElementPos(id);
	var minx=pos.x;
	var miny=pos.y;	
	var maxx=minx+element.offsetWidth;
	var maxy=miny+element.offsetHeight;
	
	return {minx:minx,miny:miny,maxx:maxx,maxy:maxy};
}