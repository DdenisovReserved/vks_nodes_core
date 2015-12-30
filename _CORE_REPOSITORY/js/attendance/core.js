$(document).ready(function() {
//    -----------------------------------------------------------------
    //скрыть НЕ корневые элементы и добавить признак свернутости
    //regExps = $.parseJSON(regExps);




//
//    -----------------------------------------------------------------
    //обработчик нажатия на плашку, показывает всех детей
      $(document).on("click",".closed-image", function (e) {
//          alert("t");
          e.stopPropagation();
          var $this = $(this);
          var $this_id = $this.data('my_id');
         //проверить закрыт или открыт элемент
          if ($this.attr('data-closed')==1) { //если закрыт, найти детей и показать
              $(".elem[data-parent_id='"+$this_id+"']").show();

              //установить элемент открыт
              $this.attr("data-closed",0).attr("src","images/icons/minus.png");
          } else {
              $this.attr("data-closed",1).attr("src","images/icons/plus.png");
              $this.parent().parent().find(".elem").not($this.parent()).hide().attr("data-closed",1);
          }
      });


//    -----------------------------------------------------------------

    $(document).on("click",".branch-radio", function (e) {
        e.stopPropagation();
    });
 //    -----------------------------------------------------------------






    $(document).on("click","#add-ip-phone",function(e) {
            $(".attendance-chooser").hide();
            $(".attendance-phones").hide();
            $(".add-ip-phone-container").removeClass("hidden")
    }); //click end
/*
обработчик переключателя телефоны/дерево на форме выбора участников
 */
    $(document).on("click",".chooser-changer",function(e) {
        var state = $(this).attr("data-state");
        $("#select-points").show();

        if (state == 0) {
            $(".attendance-chooser").hide();
            $(".attendance-phones").show();
            $(".btn-success").show()
            $(this).attr("data-state",1).html('К выбору точек');

        } else {
            $(".attendance-chooser").show();
            $(".attendance-phones").hide();
            $(".btn-success").show()
            $(this).attr("data-state",0).html('Внести телефоны');
        }
    }); //click end


    //$(document).on("click",".phone-tmpl-select-me",function(e) {
    //    $(".errors-cnt").html("").hide();
    //    $(".phone-field-hidden").removeClass("hidden");
    //    var myTpl = $(this).data('phone_tpl');
    //    $(".phone-inputer").mask(myTpl);
    //    $(".phone-inputer").attr("disabled",false);
    //    $(".phone-inputer").focus();
    //}); //click end



}); //main end

function textInp (re, str) {
//    console.log(re);
//    console.log(str);
//    re = $(re).replace("","");
    if (re.test(str))  {
//        console.log('ok');
        return true;
    } else {
//        console.log('nope');
        return false;

    }

}