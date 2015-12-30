$(document).ready(function () {

    //init query stack
    var stack = 0;
    $(document).on("keyup", "#user-search-field", function() {
        $("#founded-table").children().remove();
        if (stack>0) return;
        var $this = $(this);
        if ($this.val().length > 1) {
            var timer = setTimeout(
                function()
                {
                    $.post('?route=User/find/'+$this.val()+'/humanized', function(data) {
                        //clear container
                        $("#founded-table").children().remove();
                        data = $.parseJSON(data);
                        //create element
                        var $c = 1;
                        $("#user-table").hide();
                        if (data.length > 0) {
                            $(data).each(function(i,element) {
                                var $element = "<tr>";
                                $element += "<td>"+$c+"</td>";
                                $element += "<td>"+element.id+"</td>";
                                $element += "<td>"+element.login+"</td>";
                                $element += "<td>"+element.humanized.role+"</td>";
                                $element += "<td>"+element.humanized.status+"</td>";
                                $element += "<td>"+element.fio+"</td>";
                                $element += "<td>"+element.phone+"</td>";
                                $element += "<td>"+element.humanized.created_at+"</td>";
                                $element += "<td>"+element.humanized.last_visit+"</td>";

                                $element += "<td class='col-md-2'>";

                                if (element.status != 3){
                                    $element += "<a class='btn btn-default btn-sm' title='Забанить' href='?route=User/ban/"+element.id+"'><span class='glyphicon glyphicon-ban-circle text-danger'></span></a>";
                                } else {
                                    $element += "<a class='btn btn-default btn-sm' title='Разбанить' href='?route=User/unban/"+element.id+"'><span class = 'glyphicon glyphicon-check text-success'></span></a>";
                                }


                                if (!element.status)
                                $element +="<a class='btn btn-default btn-sm' title='Подтвердить' href='?route=User/approve/"+element.id+"')><span class='glyphicon glyphicon-check text-success' ></span> </a>";
                                $element +="<a class='btn btn-default btn-sm' title='Редактировать' href='?route=User/edit/"+element.id+"'><span class='glyphicon glyphicon-edit text-info'></span></a>";

                                $element +="<a class='btn btn-default btn-sm' title='Удалить из системы' href='?route=User/destroy/"+element.id+"'><span class='glyphicon glyphicon-remove text-warning' ></span> </a>";

                                $element += "</tr>";
                                $("#founded-table").append($element);
                                $c++;
                            });
                        } else {
                            $("#founded-table").append("<tr><td class='text-center text-primary' colspan='4'>Ничего не найдено, уточните запрос. Поиск осуществляется по логину пользователя</td>")
                        }


                    }) //ajax end
                    stack--;
                }, 200);
            stack++;

        } else {
            $("#founded-table").children().remove();
            $("#user-table").show();
        }
    })

}); //main end