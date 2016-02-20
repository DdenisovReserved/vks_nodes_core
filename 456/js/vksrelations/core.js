$(document).ready(function() {
    $(document).on("click",".relation-approve",function(){
        $.get("?route=VksRelations/approve/"+$(this).data('relation'),function(data) {
            $.fancybox({
                'width': "50%",
                'height': $(window).height(),
                'autoSize': false,
                'type': 'iframe',
                'content': data,
                'fitToView': false
            });
        });
    });

    $(document).on("click",".relation-decline",function(){

        if  (confirm("Уверены что желаете отказаться от участия в конференции?")) {

            document.location.href = "?route=VksRelations/destroy/"+$(this).data('relation');

        }

    });

}); //ready end