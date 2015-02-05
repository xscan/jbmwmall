$('[data=userlist]>.list-group-item').click(function() {
    $(this).remove();
     $('[data=senduserlist]').append("<a href='#' class='list-group-item' data-value='"+$(this).attr('data-value')+ "'' data-toggle='tooltip' data-placement='right' title='点击移除发送人'>"+$(this).html()+"<\/a>");
    //alert('message')

    var oHead = document.getElementsByTagName('HEAD').item(0); 
    var oScript= document.createElement("script"); 
    oScript.type = "text/javascript"; 
    oScript.src="/jbmwmall/Public/senduserlist.js"; 
    oHead.appendChild( oScript); 
});
