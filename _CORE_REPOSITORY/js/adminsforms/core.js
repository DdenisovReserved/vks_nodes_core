$(document).ready(function() {
    $(document).on("click",".check-codes",function(){
        $(".check-codes-report-container").html("");
        var retObj = {"checkCodes": true};
        $("input[name*='conn_code']").each(function(){
           var $this = $(this);
           retObj[$this.attr('name')]= $this.val();
        });
        //дата проведения вкс
        retObj['vks_date'] = $(".vks_date").attr('value');
        $.ajax({
            beforeSend: function() {
                $(".check-codes-report-container").html("<h4>Проверка кодов, ожидайте...</h4>");
            },
            type: 'POST',
            cache: false,
            url: appHttpPath+'?r=controllers/controller_ajax',
            data: retObj,
            success: function(e) {
              $(".check-codes-report-container").html("");
                if (e != 0){
                    //таблицу для вывода!
                    $(".check-codes-report-container").append("<div class='alert alert-warning'>Внимание! Коды использутся</div>")
                    $(".check-codes-report-container").append("<table class='vks-busy-table'></table>")
                    $(".vks-busy-table").append("<th>Код</th><th>Значение</th><th>Исп. в ВКС</th>");
                    var getUsage = $.parseJSON(e);

                    $.each(getUsage,function(i,item){
//                        console.log(item.code_id)
                        $(".vks-busy-table").append("<tr>");
                        var retStr = "<td>"+item.code_id+"</td>";
                        retStr += "<td>"+item.code_val+"</td>";
                        retStr += "<td><a href='?r=views/adminforms/vks_simple_view&id="+item.used_in+"' target='_blank'>#"+item.used_in+"</a></td>";

                            $(".vks-busy-table").find('tr').last().append(retStr);

                    })
                } else {
                    $(".check-codes-report-container").html("<div class='alert alert-success'>Коды свободны</div> ");
                }
            }
        })

    }) //click end
//    $(document).on("click",".add-adm-direct", function(e) {
//        e.preventDefault();
//        var collect = $("input, textarea");
//        validateElements(collect);
//
//
//    })
})