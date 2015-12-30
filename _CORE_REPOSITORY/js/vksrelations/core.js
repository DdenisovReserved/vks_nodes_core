$(document).ready(function() {
    $(document).on("click",".relation-approve",function(){
        $.get("?route=VksRelations/approve/"+$(this).data('relation'),function(data) {
            $.fancybox({
                'width': 560,
                'content': data
            });
        });
    });

    $(document).on("click",".relation-decline",function(){

        if  (confirm("Уверены что желаете отказаться от участия в конференции?")) {

            document.location.href = "?route=VksRelations/destroy/"+$(this).data('relation');

        }

    });

}); //ready end