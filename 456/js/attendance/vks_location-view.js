//этот файл каждый раз загружается при октрытии модального окна с выбором места проведения вкс
$(".elem").each(function(){
    var $this = $(this);
    if ($this.data('parent_id') != '0') {
        $this.hide();
    }
}) //each end

/*
 если окно открывается повторно, нам нужно отобрать выбранные плашки и
 загрузить их в правую часть окна выбора участников,
 то есть список должен снова доступен на редактирование
 */
$(".vks_location-display").find(".point").each(function() {
    var text = $(this).text();
//        console.log(text);
    //создать элемент и присоединить
    $("<div data-my_name='"+text+"' data-my_id='"+$(this).attr("data-my_id")+"'>"+text+"<sup><span class='del-point'>[X]</span></sup></div>").addClass('point').appendTo(".selected-point-vks_location")
})
