$(document).ready(function () {
    $(document).on("click", "#add", function () {
        var countTr = Number($(".copy-me").attr('data-codes'));
        $(".copy-me").clone().removeClass('copy-me').removeAttr('data-codes').appendTo("table.table-striped");
        $("table.table-striped").find('tr').last().find('input').each(function () {
            $(this).val('');
            $(this).parent().parent().find(".all").text('');
            $(this).attr('name', $(this).attr('name').replace("1", countTr + 1))
        })
        $(".copy-me").attr('data-codes', countTr + 1);

    })
    $(document).on("click", ".remove-inp", function () {
        var countTr = Number($(".copy-me").attr('data-codes'));
        if (confirm('Точно удалить?')) {
            if ($("table.table-striped").find('tr').length-1 <= 1) {
                alert("Удаление последнего элемента сломает логику, так нельзя")
            } else {
                $(this).parent().parent().remove();
            }
        }
    })
})