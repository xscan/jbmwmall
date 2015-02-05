$('[data=senduserlist]>.list-group-item').click(function() {
    $(this).remove();
//    $('[data=userlist]').append("<a href='#' class='list-group-item' data-value='"+$(this).attr('data-value')+"'>"+$(this).html()+"<\/a>");
})
