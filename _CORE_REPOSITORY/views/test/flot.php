<?php
include_once(CORE_REPOSITORY_REAL_PATH . "views/main.php");
ST::setUserJs('flot/jquery.flot.min.js');
ST::setUserJs('flot/jquery.flot.time.js');
ST::setUserJs('flot/jquery.flot.selection.js');
ST::setUserJs('flot/jquery.flot.threshold.js');

$plotTicks = array();
$randomData = array();
$threshold = array();
$thresholdCounter = 100;
$dateCur = date_create()->setTime(8,0);
$dateEnd = date_create()->setTime(20,0);
$c = 0;
while($dateCur <= $dateEnd) {
    if (in_array($dateCur->format('i'),['00','30']) ) {
        $plotTicks[] = array($c,$dateCur->format('H:i'));
    } else {
        $plotTicks[] = array($c, '');
    }

    $randomData[] = array($c, rand(0, 120));
    $threshold[] =  array($c, $thresholdCounter);
    $c++;
    $dateCur->modify("+15 minutes");
}

?>



    <div class="demo-container"><button class="btn btn-default pull-right" id="reset" type="button">Сброс</button>
        <div id="placeholder" class="demo-placeholder" style="width:960px;height:450px"></div>
    </div>




<script type='text/javascript'>
    <?php

    echo "var plotTicks = ". json_encode($plotTicks) . ";\n";
    echo "var randomData = ". json_encode($randomData) . ";\n";
    echo "var croppedData = ". json_encode(array_slice($randomData,10,20)) . ";\n";
    echo "var thresholdCou = ". json_encode($threshold) . ";\n";

    ?>
</script>

<script>
    $(function () {
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
            label: 'Нагрузка на сервер',
            data: randomData,
            points: {show: true},
            lines: {show: true, fill: true},
        };
        var limit = {
            label: 'Лимит производительности',
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
                max: 130
            },
            selection: {
                mode: "x"
            }


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

</script>