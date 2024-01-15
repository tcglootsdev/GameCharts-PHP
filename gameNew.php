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
		//if (isset($_GET['appid']) && !empty($_GET['appid'])) {
	if (isset($_GET['nameseo']) && !empty($_GET['nameseo'])) {
		$appid = $_GET['nameseo'];
	}

	$source = 'default';
	if (isset($_GET['source']) && !empty($_GET['source'])) {
		$source = $_GET['source'];
	}
	$dataURL .= $source."/games_seo/".$appid."/";

}

$fechaOrigen = '1900-01-01';
if (isset($_GET['inicio']) && !empty($_GET['inicio'])) {
	$fechaOrigen = $_GET['inicio'];
}
$stores = json_decode(file_get_contents('https://gamecharts.org/data/store.json'));
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

$months = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');

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
	
	if (isset($lastMonth['avg']) && $lastMonth['avg'] > 0) {
		$table_aux[$key]['inc'] = $data['avg'] - $lastMonth['avg'];
		$table_aux[$key]['pinc'] = number_format(((($data['avg']/$lastMonth['avg']) - 1) * 100),2) . "%";
		$lastMonth['avg']=$data['avg'];
	}
	else {
		$table_aux[$key]['inc'] = '---';
		$table_aux[$key]['pinc'] = '---';
	}
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
	<title>Game Charts Detail : <?php echo($gameinfo_aux[0]->Name); ?></title>
	<meta content="Game Detail Page. The name of this game is <?php echo($gameinfo_aux[0]->Name);?>  You will see more detailed info about this game in this page." name="description" />
	<meta name = "gamename" content = "<?php echo($gameinfo_aux[0]->Name); ?>" />
	<meta name="keywards" content="plyer, game, chart, average, current, playerunknowns, 24-hours, platform, name, steam, idcgames, xbox, current players, average players, game chart, <?php echo($gameinfo_aux[0]->Name);?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- App favicon -->
	<link rel="shortcut icon" href="https://gamecharts.org/assets/images/favicon.ico">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://gamecharts.org/assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="https://gamecharts.org/assets/css/styleNew.css" rel="stylesheet" type="text/css" />
	<link href="https://gamecharts.org/assets/css/custom-highcharts.css" rel="stylesheet" type="text/css" />
	<link rel="canonical" href="https://gamecharts.org/<?php echo "$source/" . $gameinfo_aux[0]->NameSEO;?>" />
	<meta name = "twitter:title" content="Game Charts Detail : <?php echo($gameinfo_aux[0]->Name); ?>">
	<meta name = "twitter:card" content="summary">
	<meta name = "twitter:site" content="@gamecharts">
	<meta name = "twitter:creator" content="@gamecharts">
	<meta name = "twitter:description" content="Game Detail Page. The name of this game is <?php echo($gameinfo_aux[0]->Name);?>  You will see more detailed info about this game in this page."/>
	<meta name = "twitter:image" content="https://gamecharts.org/assets/images/logo-1.png"/>
	<meta property = "og:type" content="website" />
	<meta property = "og:url" content="https://gamecharts.org"/>
	<meta property = "og:image" content="https://gamecharts.org/assets/images/logo-1.png"/>
	<meta property = "og:site_name" content="Gamecharts"/>
	<meta property = "og:title" content="Game Charts Detail : <?php echo($gameinfo_aux[0]->Name); ?>" />
	<meta property = "og:description" content="Game Detail Page. The name of this game is <?php echo($gameinfo_aux[0]->Name);?>  You will see more detailed info about this game in this page."/>


	<link rel="preconnect dns-prefetch" href="https://www.googletagmanager.com">
	<link rel="preconnect dns-prefetch" href="https://cdn.jsdelivr.net">
	<link rel="preconnect dns-prefetch" href="https://gamecharts.com">
	<link rel="preconnect dns-prefetch" href="https://cdnjs.cloudflare.com">
	<link rel="preconnect dns-prefetch" href="https://kit.fontawesome.com">
	<link rel="preconnect dns-prefetch" href="code.jquery.com">
	<link rel="preconnect dns-prefetch" href="code.highcharts.com">
	<link rel="preconnect dns-prefetch" href="https://steamcdn-a.akamaihd.net">



	<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/data.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>


	<script src="https://gamecharts.org/assets/js/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
	<script src="https://gamecharts.org/assets/js/bootstrap.bundle.min.js"></script>
	<script src="https://gamecharts.org/assets/js/waves.min.js"></script>
	<script src="https://gamecharts.org/assets/js/jquery.slimscroll.min.js"></script>
	<script src="https://gamecharts.org/assets/plugins/moment/moment.js"></script>
	<script src="https://gamecharts.org/assets/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="https://gamecharts.org/assets/pages/jquery.apexcharts.init.js"></script>
	<script src="https://gamecharts.org/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
	<script src="https://gamecharts.org/assets/pages/jquery.charts-sparkline.js"></script>
	<script src="https://gamecharts.org/assets/js/checkCookie.js"></script>
	<script src="https://gamecharts.org/assets/js/app.js"></script>
	<script src="https://gamecharts.org/assets/js/searchbox.js"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43282477-5"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-43282477-5');
	</script>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>

<body style="overflow-x: hidden">

	<?php include('navigation.php'); ?>

	<div class="row game-platforms">
		<span class="game-platforms-menu" style="margin-left: 0">
			<?php
			foreach($stores as $store) {
				if ($store->Store == $source){
					echo('<li><a href="https://gamecharts.org/'.$store->Store.'"><img alt="'.$store->Store.'" src="'.$store->Splash.'"/></a> </li>');
				}
			}
			?>
		</span>
		<div class="route-top">
			<a href="https://gamecharts.org">GameCharts</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;
			<a href="https://gamecharts.org/<?php echo $source?>"><?php echo ucfirst ($source)?></a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo($gameinfo_aux[0]->Name); ?></a>
		</div>
	</div>

	<div id="menu" class="game-menu d-flex flex-md-row flex-column">
		<div class="d-flex game-img">
			<img src="<?php echo($gameinfo_aux[0]->Splash); ?>" alt="<?php echo($gameinfo_aux[0]->Name); ?>"/>
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

	<div class="container-fluid-add game-screen-add bg-light-gray">
		<!--desktop-->
    <!--
    <div class="row desktop-screen justify-content-center">
    -->
    <div class="row-add desktop-screen">
    	<!--<div class="col-lg-8 col-xs-12 align-items-center px-2">-->
    		<div class="align-items-center">

    			<div class="desktop-ads-column-left">
    				<table class="desktop-ads-table">
    					<tr>
    						<td>
    							<ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
    							data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
    						</ins>
    						<script>
    							(adsbygoogle = window.adsbygoogle || []).push({});
    						</script>
    					</td>
    				</tr>
    				<tr>
    					<td class="desktop-ads-bottom">
    						<ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
    						data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
    					</ins>
    					<script>
    						(adsbygoogle = window.adsbygoogle || []).push({});
    					</script>
    				</td>
    			</tr>
    		</table>
    	</div>
    	<div class="desktop-ads-column-right">
    		<table class="desktop-ads-table">
    			<tr>
    				<td>
    					<ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
    					data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
    				</ins>
    				<script>
    					(adsbygoogle = window.adsbygoogle || []).push({});
    				</script>
    			</td>
    		</tr>
    		<tr>
    			<td class="desktop-ads-bottom">
    				<ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
    				data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
    			</ins>
    			<script>
    				(adsbygoogle = window.adsbygoogle || []).push({});
    			</script>
    		</td>
    	</tr>
    </table>
</div>
<div class="content-column">
	<div id="global" class="game-card card p-3"></div>
	<div id="compare" class="game-card card p-3"></div>
	<div id="average" class="game-card card p-3"></div>


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
								<td class="text-center">
									<?php
									if (isset($data['avg'])) {
										echo number_format((float)$data['avg']);
									} else {
										echo "0";
									}

									?>
								</td>
								<td class="text-center">
									<?php
									if (isset($data['inc'])) {
										echo number_format((float)$data['inc']);
									} else {
										echo "0";
									}

									?>
								</td>
								<td class="text-center">
									<?php
									if (isset($data['pinc'])) {
										echo number_format((float)$data['pinc']);
									} else {
										echo "0";
									}

									?>
								</td>
								<td class="text-center">
									<?php 
									if (isset($data['peak'])) {
										echo number_format((float)$data['peak']);
									} else {
										echo "0";
									}

									?>
								</td>
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
	<div class="row">
		<div class="col-12">
			<div id="top_game_slider" class="top_game_slider">
				<div class="row">
					<h2>About <?php echo ($gameinfo_aux[0]->Name);?></h2>

					<div style="display: block;padding: 0; overflow-y: scroll;" class="col-sm-12 col-md-12">
						<?php if(isset($gameinfo_aux[0]->AboutGame)):?>
							<?php echo ($gameinfo_aux[0]->AboutGame);?>
							<div class="" style="margin-left: 3px; margin-right: 3px">
							</div>
						<?php else:?>
							<h3>Description is not added yet.</h3>
						<?php endif;?>
					</div>
				</div>

			</div>
		</div><!--end col-->
	</div>
</td>

</div>
</div>
</div>

</div>


</div>

<?php include('footer.php'); ?>

</body>

<script>

	$( document ).ready(function() {

		Highcharts.setOptions({
			time: {
				timezoneOffset: - 1 * 60
			}
		});


		Highcharts.stockChart('global', {
			chart: {
				styledMode: true
			},
			title:{
				text: 'Top concurrent users',
				align: 'left',
			},
			navigation: {

				buttonOptions: {
					enabled: false
				}
			},
			navigator: {
				series: {
					label: {
						enabled: false
					}
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
			chart: {
				styledMode: true
			},
			title:{
				text: 'Daily average users',
				align: 'left',
			},

			navigation: {
				buttonOptions: {
					enabled: false
				}
			},
			navigator: {
				series: {
					label: {
						enabled: false
					}
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
        /*$('.mobile-screen').hide();
        if($(window).width() < 992){
            $(".desktop-screen").hide();
            $('.mobile-screen').show();
        }*/
	});


	var seriesOptions = [],
	seriesCounter = 0,
	names = ['TODAY', 'YESTERDAY', 'WEEK AGO'];

	function createChart(folder) {

		Highcharts.stockChart(folder, {

			chart: {
				styledMode: true
			},
			title:{
				text: 'Top concurrent users comparison',
				align: 'left',
			},

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
		var name = 'WEEK AGO';
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


</script>


</html>

