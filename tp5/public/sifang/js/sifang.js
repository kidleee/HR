
//下面是公告层
function getJsonLength(jsonData){
 var jsonLength = 0;
 for(var item in jsonData){
  jsonLength++;
 }
 return jsonLength;
}


$.ajax({
        url:"http://www.runjianli.com/index.php/session/session_use/getimg",
        type:"post",
        dataType: "text",
        success: function(data){//如果调用php成功   
           	if(data == "请先登录")
            {   
                $("#img").hide();
                $("#login").show();   
            }
            else
            {   
                $("#login").hide();
                $("#imgurl").attr("src",data);
                $("#img").show();
            }
        }
});
function signout()
{
    $.ajax({  
            type: "post",  
            url: "http://www.runjianli.com/index.php/session/session_use/signout",
			dataType: "text", 			
            success: function(data){ 
                $("#img").hide();
                $("#login").show(); 
            },
			error: function(jqXHR, textStatus, errorThrown){  
                alert(jqXHR.responseText);
				alert(jqXHR.status);
				alert(jqXHR.readyState);
				alert(jqXHR.statusText);
				alert(textStatus);
				alert(errorThrown); 
			}
        })  	
}
