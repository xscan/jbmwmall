$('[data=senduserlist]>.list-group-item').click(function() {
    $(this).remove();
 // $('[data=userlist]').append("<li class='list-group-item' data-value="+$(this).attr('data-value')+">"+$(this).html()+"<\/li>");
 
  /*var oHead = document.getElementsByTagName('HEAD').item(0); 
    var oScript= document.createElement("script"); 
    oScript.type = "text/javascript"; 
    oScript.src="/jbmwmall/Public/userlist.js"; 
    oHead.appendChild( oScript); 
    */
})
