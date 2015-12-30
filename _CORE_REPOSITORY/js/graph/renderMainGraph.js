$(function () {

    $('#btn_another_date').click(function ()
    {
        $('#dp_another_date').datepicker();
        $('#dp_another_date').datepicker("show");
    });

    $(document).on('change','#dp_another_date', function() {
        location.href = '?route=Load/showJsLoadGraph/'+$(this).val();
    })

    $("<div id='tooltip'></div>").css({
        position: "absolute",
        display: "none",
        border: "1px solid #fdd",
        padding: "2px",
        "background-color": "#fee",
        opacity: 0.80
    }).appendTo("body");

//        console.log(typeof plotTicks);
//        console.log(randomData);
    var d2 = {
        label: 'Нагрузка',
        data: randomData,
        points: {show: true},
        lines: {show: true, fill: true},

    };
    var limit = {
        label: 'Лимит',
        data: thresholdCou,
        color: 'red'
    };

    var options = {
        series: {
            color: "rgb(100, 140, 232)",
        },
        grid: {
            hoverable: true,
            clickable: true
        },
        xaxis: {
            min: 0,
            show: true,
            ticks: plotTicks,
//                tickSize: 2,
        },

        yaxis: {
            min: 0,
            max: thresholdCou[0][1]+30
        },
        selection: {
            mode: "x"
        },
        crosshair: {
            mode: "x"
        },


    };
    var plot = $.plot("#placeholder", [d2,limit], options);

    $("#placeholder").bind("plothover", function (event, pos, item) {

        if ($("#enablePosition:checked").length > 0) {
            var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
            $("#hoverdata").text(str);
        }


        if (item) {
            var x = item.datapoint[0].toFixed(2),
                y = item.datapoint[1].toFixed(2);

            $("#tooltip").html(Number(y))
                .css({top: item.pageY+5, left: item.pageX+5})
                .fadeIn(200);
        } else {
            $("#tooltip").hide();
        }

    });


    $(document).on('click', '#reset', function() {
        var plot = $.plot("#placeholder", [d2,limit], options);
    });


    $("#placeholder").bind("plotclick", function (event, pos, item) {
//            console.log(item);
        if (item) {
            $("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
            plot.highlight(item.series, item.datapoint);
        }
    });

    $("#placeholder").bind("plotselected", function (event, ranges) {

        // clamp the zooming to prevent eternal zoom

        if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) {
            ranges.xaxis.to = ranges.xaxis.from + 0.00001;
        }
        // do the zooming
        plot = $.plot("#placeholder", [d2,limit],
            $.extend(true, {}, options, {
                xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
            })
        );
    });

});