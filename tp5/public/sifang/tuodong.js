_MoveObj = null;//全部变量，用来表示当前div 
z_index = 0;//z方向 
jQuery.fn.myDrag=function(){ 
_IsMove = 0; //是否移动 1.移动 
_MouseLeft = 0; //div left坐标 
_MouseTop = 0; //div top坐标 
$(document).bind("mousemove",function(e){ 
if(_IsMove==1){ 
$(_MoveObj).offset({top:e.pageY-_MouseLeft,left:e.pageX-_MouseTop}); 
} 
}).bind("mouseup",function(){ 
_IsMove=0; 
$(_MoveObj).removeClass("downMouse"); 
}); 
return $(this).bind("mousedown",function(e){ 
_IsMove=1; 
_MoveObj = this; 
var offset =$(this).offset(); 
_MouseLeft = e.pageX - offset.left; 
_MouseTop = e.pageY - offset.top; 
z_index++; 
_MoveObj.style.zIndex=z_index; 
$(_MoveObj).addClass("downMouse"); 
}); 
} 