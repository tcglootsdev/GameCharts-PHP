<?php

    ini_set('display_errors',false);
    date_default_timezone_set("Europe/Madrid");
    $dataURL = './data/';
    $ahora = time();
    $hoy = date("dmy");

    //	CARGA DE PARAMETROS
    if (isset($_GET['dataURL']) && !empty($_GET['dataURL'])) {
        $dataURL = $_GET['dataURL'];
    }
    else {

        $appid = '0';
        if (isset($_GET['appid']) && !empty($_GET['appid'])) {
            $appid = $_GET['appid'];
        }

        $source = 'default';
        if (isset($_GET['source']) && !empty($_GET['source'])) {
            $source = $_GET['source'];
        }
        $dataURL .= $source."/".$appid."/";
    }

    $fechaOrigen = '1900-01-01';
    if (isset($_GET['inicio']) && !empty($_GET['inicio'])) {
        $fechaOrigen = $_GET['inicio'];
    }

    $fechaFinal = '3000-01-01';
    if (isset($_GET['final']) && !empty($_GET['final'])) {
        $fechaFinal = $_GET['final'];
    }

    // OBTENEMOS LOS DATOS DEL JUEGO
//    $gameinfo = file_get_contents($dataURL."gameinfo.json");
//    $gameinfo_aux = json_decode($gameinfo);
//
//    $gamedata = file_get_contents($dataURL."gamedata.json");
//    $gamedata_aux = json_decode($gamedata);

    $game_info = file_get_contents($dataURL . "gameinfo.json");
    $game_info = json_decode($game_info, true);
    $game_info = $game_info[0];

    $game_data = file_get_contents($dataURL . "gamedata.json");
    $game_data = json_decode($game_data, true);
    $game_data = $game_data[0];

    // OBTENEMOS LOS DATOS DE FULLDATA, TODAY, YESTERDAY, WEEKAGO
    $fulldata = str_replace(',[0000,0]','',file_get_contents($dataURL."fulldata.json"));
    $fulldata_aux = json_decode($fulldata,true);

    $today = str_replace(',[0000,0]','',file_get_contents($dataURL."today.json"));
    $today_aux = json_decode($today,true);

    $yesterday = str_replace(',[0000,0]','',file_get_contents($dataURL."yesterday.json"));
    $yesterday_aux = json_decode($yesterday,true);

    $weekago = str_replace(',[0000,0]','',file_get_contents($dataURL."weekago.json"));
    $weekago_aux = json_decode($weekago,true);

    $fullaverage = str_replace(',[0000,0]','',file_get_contents($dataURL."fullaverage.json"));
    $fullaverage_aux = json_decode($fullaverage,true);



    // TRANSFORMAMOS CADA FICHERO EN FORMATO EPOCH

    // FULLDATA
    if (isset($fulldata_aux) && !empty($fulldata_aux)) {
        $fileResult = array();
        foreach ($fulldata_aux as $data)
        {
            $aux = array();
            $aux2 = explode(' ',$data['DateTime']);
            $auxDate = explode('/',$aux2[0]);
            $auxHour = explode(':',$aux2[1]);

            $aux[] = (int)(mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2])."000");
            $aux[] = (int)$data['Ccu'];

            $fileResult[] = $aux;
        }
        $fulldata_aux = $fileResult;
    }

    // TODAY Y UNIMOS EL FICHERO DE HOY A FULLDATA
    if (isset($today_aux) && !empty($today_aux)) {
        $fileResult = array();
        foreach ($today_aux as $data)
        {
            $aux = array();
            $aux2 = explode(' ',$data['DateTime']);
            $auxDate = explode('/',$aux2[0]);
            $auxHour = explode(':',$aux2[1]);

            $aux[] = (int)(mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2])."000");
            $aux[] = (int)$data['Ccu'];

            $fileResult[] = $aux;
        }
        $fulldata_result = array_merge($fulldata_aux, $fileResult);
        $fulldata_aux = $fulldata_result;
        $today_aux = $fileResult;
    }

    // YESTERDAY
    if (isset($yesterday_aux) && !empty($yesterday_aux)) {
        $fileResult = array();
        foreach ($yesterday_aux as $data)
        {
            $aux = array();
            $aux2 = explode(' ',$data['DateTime']);
            $auxDate = explode('/',$aux2[0]);
            $auxHour = explode(':',$aux2[1]);

            $aux[] = ((mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2]))."000") + 86400000;
            $aux[] = (int)$data['Ccu'];

            $fileResult[] = $aux;
        }
        $yesterday_aux = $fileResult;
    }

    // WEEK AGO
    if (isset($weekago_aux) && !empty($weekago_aux)) {
        $fileResult = array();
        foreach ($weekago_aux as $data)
        {
            $aux = array();
            $aux2 = explode(' ',$data['DateTime']);
            $auxDate = explode('/',$aux2[0]);
            $auxHour = explode(':',$aux2[1]);

            $aux[] = ((mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2]))."000") + 604800000;
            $aux[] = (int)$data['Ccu'];

            $fileResult[] = $aux;
        }
        $weekago_aux = $fileResult;
    }

    // AVERAGE
    if (isset($fullaverage_aux) && !empty($fullaverage_aux)) {
        $fileResult = array();
        foreach ($fullaverage_aux as $data)
        {
            $aux = array();
            $auxDate = explode('/',$data['Date']);

            $aux[] = (int)(mktime('0','0','0',$auxDate[1],$auxDate[0],$auxDate[2])."000");
            $aux[] = (int)$data['Average'];

            $fileResult[] = $aux;
        }
        $fullaverage_aux = $fileResult;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Game Charts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A premium admin dashboard template by mannatthemes" name="description"/>
    <meta content="Mannatthemes" name="author"/>

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

</head>
<style>
    .center {
        text-align: center;
    }

    .text-green{
        color:green;
        font-weight:900;
    }

    .text-red{
        color:red;
        font-weight:800;
    }

    .text-right{
        text-align: right;
    }
</style>

<body class="boxed">

<!-- Top Bar Start -->
<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">

        <!-- LOGO -->
        <div class="topbar-left">
            <a href="/" class="logo">
                        <span>
<!--                            <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">-->
                        </span>
                <span style="font-size: 25px;color: grey; font-weight: 500">
<!--                            <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg">-->
                            gamecharts.org
                        </span>
            </a>
        </div>


        <ul class="list-unstyled topbar-nav mb-0">
            <li class="hide-phone app-search">
                <form role="search" class="">
                    <input type="text" placeholder="Search..." class="form-control">
                    <a href=""><i class="fas fa-search"></i></a>
                </form>
            </li>

        </ul>

    </nav>
    <!-- end navbar-->
</div>
<!-- Top Bar End -->
<br>
<br>
<br>
<div class="page-wrapper">
    <br>
    <!--end page-wrapper-inner -->
    <!-- Page Content-->
    <div class="page-content" style="width:90%;margin-left:auto;margin-right:auto">
        <div class="container-fluid">
            <div>
                <a href="<?php echo $game_info['Store']?$game_info['Store']:'#'?>"><h2 style="color: #505050;"><?php echo $game_info['Name']?></h2></a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?php echo $game_info['Store']?$game_info['Store']:'#'?>">
                                <img src="<?php echo $game_info['Splash']?($game_info['Splash'].'.jpg'):'assets/images/cs_ic.png'?>" style="width: 200px">
                            </a>
                        </div>

                        <div class="col-md-2 center">
                             <span style="font-size: 18px">
                                <?php echo number_format($game_data['TopCcuToday']); ?> ccu/
                                 <br>
                                 <?php echo number_format($game_data['MaxAvgToday']); ?> average
                            </span>
                            <p style="font-size: 15px;color: grey">TODAY</p>
                        </div>
                        <div class="col-md-2 center">
                             <span style="font-size: 18px">
                                <?php echo number_format($game_data['TopCcu24h']); ?> ccu/
                                 <br>
                                 <?php echo number_format($game_data['MaxAvg24h']); ?> average
                            </span>
                            <p style="font-size: 15px;color: grey">LAST 24h</p>
                        </div>
                        <div class="col-md-2 center">
                              <span style="font-size: 18px">
                                 <?php echo number_format($game_data['TopCcu30d']); ?> ccu/
                                  <br>
                                  <?php echo number_format($game_data['MaxAvg30d']); ?> average
                            </span>
                            <p style="font-size: 15px;color: grey">LAST 30d</p>
                        </div>
                        <div class="col-md-2 center">
                             <span style="font-size: 18px">
                                 <?php echo number_format($game_data['TopCcu']); ?> ccu/
                                 <br>
                                 <?php echo number_format($game_data['MaxAvg']); ?> average
                            </span>
                            <p style="font-size: 15px;color: grey">ALL-TIME PEAK</p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <div id="global" style="height: 200px; min-width: 310px; max-width: 1024px; border: 2px solid black; padding:10px; margin:10px;"></div>

                    <div id="compare" style="height: 200px; min-width: 310px; max-width: 1024px; border: 2px solid black; padding:10px; margin:10px;"></div>

                    <div id="average" style="height: 200px; min-width: 310px; max-width: 1024px; border: 2px solid black; padding:10px; margin:10px;"></div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-centered table-striped mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Month</th>
                            <th class="text-right">Avg.Players</th>
                            <th class="text-right">Gain</th>
                            <th class="text-right">% Gain</th>
                            <th class="text-right">Peak Players</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Last 30 Days</td>
                                <td class="text-right">516,156.7</td>
                                <td class="text-green text-right">+14,960.7</td>
                                <td class="text-green text-right">+2.99%</td>
                                <td class="text-right">901,305</td>
                            </tr>
                            <tr>
                                <td>January 2020</td>
                                <td class="text-right">501,196.0</td>
                                <td class="text-green text-right">+44,494.4</td>
                                <td class="text-green text-right">+9.74%</td>
                                <td class="text-right">817,229</td>
                            </tr>
                            <tr>
                                <td>December 2019</td>
                                <td class="text-right">456,701.6</td>
                                <td class="text-green text-right">+30,620.8</td>
                                <td class="text-green text-right">+7.19%</td>
                                <td class="text-right">767,060</td>
                            </tr>
                            <tr>
                                <td>November 2019</td>
                                <td class="text-right">408,995.3</td>
                                <td class="text-red text-right">-1,930.3</td>
                                <td class="text-red text-right">-0.47%</td>
                                <td class="text-right">747,937</td>
                            </tr>
                            <tr>
                                <td>October 2019</td>
                                <td class="text-right">415,097.3</td>
                                <td class="text-green text-right">+14,960.7</td>
                                <td class="text-green text-right">+2.99%</td>
                                <td class="text-right">901,305</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>


        </div><!-- container -->

        <footer class="footer text-center text-sm-left">
            &copy; 2020 Game Charts
        </footer>
    </div>
    <!-- end page content -->
</div>
<!-- end page-wrapper -->

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/waves.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>

<script src="assets/plugins/moment/moment.js"></script>



<!-- App js -->
<script src="assets/js/app.js"></script>

<script src="assets/js/highstock.js"></script>
<script src="assets/js/data.js"></script>
<script src="assets/js/exporting.js"></script>
<script src="assets/js/export-data.js"></script>
<script src="assets/js/jquery-1.11.3.js"></script>


<script>

    function changeMinData() {
        $("input[name='min']").val("<?php echo($fechaOrigen); ?>");
        $("input[name='min']").trigger("change");
    }

    function changeMaxData() {
        $("input[name='max']").val("<?php echo($fechaFinal); ?>");
        $("input[name='max']").trigger("change");
    }


    $( document ).ready(function() {

        Highcharts.stockChart('global', {


            rangeSelector: {
                selected: 1
            },

            /*
            title: {
                text: 'Game CCU'
            },
            */

            navigation: {
                buttonOptions: {
                    enabled: false
                }
            },

            scrollbar: { enabled: false },

            series: [{
                name: 'CCU',
                data: <?php echo(json_encode($fulldata_aux)); ?>,
                marker: {
                    enabled: true,
                    radius: 3
                },
                shadow: true,
                tooltip: {
                    valueDecimals: 2
                }
            }],

            xAxis: {
                type: 'datetime',
                ordinal: true


            },

            rangeSelector: {
                allButtonsEnabled: true,
                buttons: [{
                    type: 'hour',
                    count: 12,
                    text: '12h'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1d'
                }, {
                    type: 'day',
                    count: 7,
                    text: '7d'
                }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                }, {
                    type: 'month',
                    count: 3,
                    text: '3m'
                }, {
                    type: 'month',
                    count: 6,
                    text: '6m'
                }, {
                    type: 'year',
                    count: 1,
                    text: '1y'
                }, {
                    type: 'all',
                    text: 'ALL'
                }],
                selected: 0
            },


        });


        Highcharts.stockChart('average', {


            rangeSelector: {
                selected: 1
            },

            /*
            title: {
                text: 'Game CCU'
            },
            */

            navigation: {
                buttonOptions: {
                    enabled: false
                }
            },

            scrollbar: { enabled: false },

            series: [{
                name: 'Average CCU',
                data: <?php echo(json_encode($fullaverage_aux)); ?>,
                marker: {
                    enabled: true,
                    radius: 3
                },
                shadow: true,
                tooltip: {
                    valueDecimals: 2
                }
            }],

            xAxis: {
                type: 'datetime',
                ordinal: true


            },

            rangeSelector: {
                allButtonsEnabled: true,
                buttons: [{
                    type: 'day',
                    count: 7,
                    text: '7d'
                }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                }, {
                    type: 'month',
                    count: 3,
                    text: '3m'
                }, {
                    type: 'month',
                    count: 6,
                    text: '6m'
                }, {
                    type: 'year',
                    count: 1,
                    text: '1y'
                }, {
                    type: 'all',
                    text: 'ALL'
                }],
                selected: 0
            },


        });

        successToday();
        successYesterday();
        successWeekago();

        changeMinData();
        changeMaxData();

    });





    var seriesOptions = [],
        seriesCounter = 0,
        names = ['TODAY', 'YESTERDAY', 'WEEK AGO'];

    function createChart(folder) {

        Highcharts.stockChart(folder, {

            rangeSelector: {
                enabled: false
            },

            navigator: {
                enabled: false
            },

            navigation: {
                buttonOptions: {
                    enabled: false
                }
            },

            scrollbar: { enabled: false },

            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                valueDecimals: 2,
                split: true
            },

            series: seriesOptions
        });
    }

    function success(name) {
        var name = this.url.match(/(today|yesterday|weekago)/)[0].toUpperCase();
        var i = names.indexOf(name);
        seriesOptions[i] = {
            name: name,
            data: data
        };

        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart();
        }
    }
    function successToday() {
        var name = 'TODAY'
        var i = 0;
        seriesOptions[i] = {
            name: name,
            data: <?php echo(json_encode($today_aux)); ?>,
        };

        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart('compare');
        }
    }
    function successYesterday() {
        var name = 'YESTERDAY';
        var i = 1;
        seriesOptions[i] = {
            name: name,
            data: <?php echo(json_encode($yesterday_aux)); ?>,
        };

        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart('compare');
        }
    }
    function successWeekago() {
        var name = 'WEEKAGO';
        var i = 2;
        seriesOptions[i] = {
            name: name,
            data: <?php echo(json_encode($weekago_aux)); ?>,
        };

        seriesCounter += 1;

        if (seriesCounter === names.length) {
            createChart('compare');
        }
    }


</script>

</body>
</html>
