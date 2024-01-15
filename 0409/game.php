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
		$dataURL .= $source."/games/".$appid."/";
	}
	
	$fechaOrigen = '1900-01-01';
	if (isset($_GET['inicio']) && !empty($_GET['inicio'])) {
		$fechaOrigen = $_GET['inicio'];
	}
    $stores = json_decode(file_get_contents('http://gamecharts.local/data/store.json'));
	$fechaFinal = '3000-01-01';
	if (isset($_GET['final']) && !empty($_GET['final'])) {
		$fechaFinal = $_GET['final'];
	}
    function get_top_games($platform_name){
        $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
        return array_slice($top_games, 0, 5, true);
    }
	// OBTENEMOS LOS DATOS DEL JUEGO
	$gameinfo = file_get_contents($dataURL."gameinfo.json");
	$gameinfo_aux = json_decode($gameinfo);
	
	$gamedata = file_get_contents($dataURL."gamedata.json");
	$gamedata_aux = json_decode($gamedata);
	
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
	
	$monthaverage = str_replace(',[0000,0]','',file_get_contents($dataURL."monthaverage.json"));
	$monthaverage_aux = json_decode($monthaverage,true);
	
	$months = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'Agoust','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
	
	$table_aux = array();
	// CREAMOS LA ESTRUCTURA DE DATOS PARA LA TABLA DE AVERAGE PEAK
	foreach ($fulldata_aux as $data) {
		
		$time = explode(' ',$data['DateTime']);
		$date = explode('/',$time[0]);
		$fecha = $months[$date[1]].' 20'.$date[2];
		
		if (!isset($table_aux[$fecha])) {
			$table_aux[$fecha]['peak'] = $data['Ccu'];
		}
		else {
			if ($table_aux[$fecha]['peak'] < $data['Ccu']) {
				$table_aux[$fecha]['peak'] = $data['Ccu'];
			}
		}
		
	}
	foreach ($monthaverage_aux as $data) 
	{
		if (isset($data['MonthYear'])) {
			$table_aux[$data['MonthYear']]['avg'] = (int)$data['Average'];
		}
	}
	$lastMonth=array();
	foreach ($table_aux as $key => $data) {
	
		if (isset($lastMonth['avg'])) {
			$table_aux[$key]['inc'] = $data['avg'] - $lastMonth['avg'];
			$table_aux[$key]['pinc'] = number_format(((($data['avg']/$lastMonth['avg']) - 1) * 100),2) . "%";
		}
		else {
			$table_aux[$key]['inc'] = '---';
			$table_aux[$key]['pinc'] = '---';
		}
		$lastMonth['avg']=$data['avg'];
	}
	
	$reversed = array_reverse($table_aux);
	$table_aux = $reversed;
	
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
<html>

<head>
	<meta charset="utf-8" />
    <title>Game Charts Detail</title>
	<script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <!-- App favicon -->
		<link rel="shortcut icon" href="http://gamecharts.local/assets/images/favicon.ico">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="http://gamecharts.local/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="http://gamecharts.local/assets/css/style.css" rel="stylesheet" type="text/css" />
		<link href="http://gamecharts.local/assets/css/custom-highcharts.css" rel="stylesheet" type="text/css" />
</head>

<body style="overflow-x: hidden">

<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>


<nav class="navbar navbar-expand-lg navbar-light bg-gradient-green fixed-top">
    <a href="http://gamecharts.local"><img src="http://gamecharts.local/assets/images/logo-1.png" class="logoGameCharts" alt="Game Charts logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link game-subject" href="#">Realtime game analysis and charts</a>
            </li>
        </ul>
        <ul class="list-unstyled topbar-nav navbar-search">
            <li class="hide-phone app-search">
                <form role="search" class="">
                    <input type="text" id="searchBox" placeholder="Search..." class="form-control bg-light-gray">
                    <a href=""><i class="fas fa-search" style="margin-top:2em"></i></a>
                </form>
                <div id="searched_game">
                    <div class="item"> Not Games Found </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="row game-platforms">
        <span class="game-platforms-menu" style="margin-left: 0">
             <?php
             foreach($stores as $store) {
                 if ($store->Store == $source){
                     echo('<li><a href="http://gamecharts.local/'.$store->Store.'"><img src="'.$store->Splash.'"/></a> </li>');
                 }
             }
             ?>
        </span>
    <div class="route-top">
        <a href="http://gamecharts.local">GameCharts</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;
        <a href="http://gamecharts.local/<?php echo $source?>"><?php echo $source?></a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo($gameinfo_aux[0]->Name); ?></a>
    </div>
</div>

<div id="menu" class="game-menu d-flex flex-md-row flex-column">
	<div class="d-flex game-img">
		<img src="<?php echo($gameinfo_aux[0]->Splash); ?>"/>	
	</div>
	<div class="d-flex flex-column justify-content-center game-info">
		<div class="d-flex flex-sm-row flex-column justify-content-between  align-items-center mt-md-0 mt-3 mx-5 mb-sm-0 mb-3">
			<div class="app-stat">
				<h1 class="display-4 font-weight-900 text-uppercase text-shadow"><?php echo($gameinfo_aux[0]->Name); ?></h1>
			</div>	
			<div class="app-stat">
				<a href="<?php echo($gameinfo_aux[0]->Store); ?>" target="_new" class="btn btn-success"> Store in <?php echo($source); ?> </a>
			</div>		
		</div>
		<div class="line bg-gradient-green mx-5"></div>
		<div class="d-flex flex-sm-row flex-column justify-content-between mt-2 mx-5">
			<div class="app-stat d-flex flex-column justify-content-baseline align-items-center mr-sm-3 mr-0">
				<div class="h6 text-center text-uppercase font-weight-bold text-shadow mb-1">
					<span class="num"><?php echo($gamedata_aux[0]->CurrentCcu); ?> ccu</span>		
				</div>
				<span class="h4 font-secondary text-center text-shadow mt-0">CURRENT</span>
			</div>
			<div class="app-stat d-flex flex-column justify-content-baseline align-items-center mr-sm-3 mr-0"> 
				<div class="h6 text-center text-uppercase font-weight-bold text-shadow mb-1">
					<span class="num"><?php echo($gamedata_aux[0]->TopCcu24h); ?> ccu</span> / 
					<span class="num"><?php echo($gamedata_aux[0]->MaxAvg24h); ?> average</span>		
				</div>
				<span class="h4 font-secondary text-center text-shadow mt-0">LAST 24h</span>
			</div>
			<div class="app-stat d-flex flex-column justify-content-baseline align-items-center mr-sm-3 mr-0">
				<div class="h6 text-center text-uppercase font-weight-bold text-shadow mb-1">
					<span class="num"><?php echo($gamedata_aux[0]->TopCcu30d); ?> ccu</span> / 
					<span class="num"><?php echo($gamedata_aux[0]->MaxAvg30d); ?> average</span>
				</div>
				<span class="h4 font-secondary text-center text-shadow mt-0">LAST 30d</span>
			</div>
			<div class="app-stat d-flex flex-column justify-content-baseline align-items-center">
				<div class="h6 text-center text-uppercase font-weight-bold text-shadow mb-1">
					<span class="num"><?php echo($gamedata_aux[0]->TopCcu); ?> ccu</span> / 
					<span class="num"><?php echo($gamedata_aux[0]->MaxAvg); ?> average</span>
				</div>
				<span class="h4 font-secondary text-center text-shadow mt-0">ALL-TIME PEAK</span>
			</div>	
		</div>
	</div>
</div>

<div class="container-fluid game-screen bg-light-gray pt-4 pb-5">
	<div class="row justify-content-center">
        <div class="col-lg-2 col-xs-6">
            <div class="add-2">
                <span class="h4 text-white text-center p-3"> Add 2: 600x 300px</span>
            </div>
        </div>
		<div class="col-lg-8 col-xs-12 align-items-center px-2">
			<div id="global" class="game-card card p-3"></div>
			<div id="compare" class="game-card card p-3"></div>
			<div id="average" class="game-card card p-3"></div>
            <div class="align-items-center row justify-content-center mb-4">
                <div class="add-4">
                    <span class="h4 text-white text-center mt-0"> Add 4: 728x 90px</span>
                </div>
            </div>
			<div class="card p-0">
				<div class="table-responsive">
					<table class="table table-centered mb-0">
						<thead class="thead-light">
							<tr>
								<th></th>
								<th>Month</th>
								<th class="text-center">Avg. Players</th>
								<th class="text-center">Gain</th>
								<th class="text-center">% Gain</th>
								<th class="text-center">Peak Players</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if(count($table_aux)) {

							foreach ($table_aux as $key => $data) {
							?>
							<tr>
								<td></td>
								<td><?php echo($key)?></td>
								<td class="text-center"><?php echo number_format($data['avg']); ?></td>
								<td class="text-center"><?php echo number_format($data['inc']); ?></td>
								<td class="text-center"><?php echo ($data['pinc']); ?></td>
								<td class="text-center"><?php echo number_format($data['peak']); ?></td>
							</tr>
							<?php 
							}
						}
						else 
						{
						?>
							<tr><td colspan="5"> Not Games Found </td></tr>
						<?php 
						}
						?>
						</tbody>
					</table>
				</div>					
			</div>
		</div>
        <div class="col-lg-2 col-xs-6">
            <div class="add-3">
                <span class="h4 text-white text-center p-3"> Add 3: 600x 300px</span>
            </div>
        </div>
	</div>
</div>

<footer class="section footer-classic context-dark bg-image" style="background: #2d3246;">
    <div class="container" style="padding: 3em;">
        <div class="row row-30">
            <div class="col-md-3 col-xs-6 text-white footer-text">
                &copy; 2019-<?php echo(date('Y')); ?> Game Charts
            </div>
            <div class="col-md-3 col-xs-6 text-white footer-item">
                <li style="list-style-type: none;">Supported Platforms
                    <ul style="padding-top: 10px;">
                        <?php foreach($stores as $store):?>
                        <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>"><?php echo $store->Store?></a>
                            <?php endforeach;?>
                    </ul>
                </li>
            </div>
            <div class="col-md-6 col-xs-6 row">
                <?php foreach($stores as $store):?>
                    <div class="footer-item col-md-6 col-xs-6">
                        <li style="list-style-type: none;"><a href="http://gamecharts.local/<?php echo $store->Store?>/top">Top <?php echo $store->Store?> Games</a>
                            <ul style="padding-top: 10px;">
                                <?php $platform_top_games = get_top_games($store->Store); foreach ($platform_top_games as $platform_top_game):?>
                                    <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>/<?php echo $platform_top_game->AppID?>"><?php echo $platform_top_game->Name?></a></li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</footer>
<script src="http://gamecharts.local/assets/js/jquery.min.js"></script>
<script src="http://gamecharts.local/assets/js/bootstrap.bundle.min.js"></script>
<script src="http://gamecharts.local/assets/js/waves.min.js"></script>
<script src="http://gamecharts.local/assets/js/jquery.slimscroll.min.js"></script>
<script src="http://gamecharts.local/assets/plugins/moment/moment.js"></script>
<script src="http://gamecharts.local/assets/plugins/apexcharts/apexcharts.min.js"></script>
<script src="http://gamecharts.local/assets/pages/jquery.apexcharts.init.js"></script>
<script src="http://gamecharts.local/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="http://gamecharts.local/assets/pages/jquery.charts-sparkline.js"></script>
<script src="http://gamecharts.local/assets/js/app.js"></script>
<script src="http://gamecharts.local/assets/js/searchbox.js"></script>
</body>

<script>

$( document ).ready(function() {
  
	Highcharts.setOptions({
		time: {
			timezoneOffset: - 1 * 60
		}
	});
  
  
	Highcharts.stockChart('global', {


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
                valueDecimals: 0
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
		  }],
		  selected: 0,
		  buttonTheme: {
              width: 60
          },
		},
    });

	Highcharts.stockChart('average', {

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
                valueDecimals: 0
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
  		  }],
  		  selected: 1,
  		  buttonTheme: {
                width: 60
            },
  		},
    });
	
	successToday();
	successYesterday();
	successWeekago();
	
	//changeMinData();
	//changeMaxData();

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
            valueDecimals: 0,
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
function changeMinData() {
	$("input[name='min']").val("<?php echo($fechaOrigen); ?>");
	$("input[name='min']").trigger("change");
}

function changeMaxData() {
	$("input[name='max']").val("<?php echo($fechaFinal); ?>");
	$("input[name='max']").trigger("change");
}

function openNav() {
    document.getElementById("mySidenav").style.width = "380px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}
</script>


</html>
