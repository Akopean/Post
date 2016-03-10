$(function () {
    $('[data-toggle="popover"]').popover({html:true})
});


$(function(){

    $('#rating_id').on('rating.change', function(event, value, caption) {
        $.ajax({
            type: "POST",
            url: "update-rating",
            data: "post_id="+$('#post_id').val()+"&rating="+value,
            success: function(msg) {
                if (msg != null){
                    $('#rating_id').rating('update', msg);
                    $('#rating_id').rating('refresh', {disabled: true,showClear: false});
                }

            }
        });
    });
});
