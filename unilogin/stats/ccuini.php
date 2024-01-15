<?php

$timeControl = array();
$timeControl['init'] = time();

#########################################################################
# LOAD INITIAL PARAMETERS
ini_set('display_errors',false);
date_default_timezone_set("Europe/Madrid");
include "../AppServer.php";
$dataURL = './data/';
$ahora = time();
$hoy = date("dmy");

$field_lenght = '16%';
$field_lenght_second = '16%';
$header_lenght = '16%';
$main_field_lenght = '20%';
#########################################################################

$timeControl['load_params'] = time();

#########################################################################
# LOAD INCOMMING PARAMETERS
if (isset($_GET['dataURL']) && !empty($_GET['dataURL'])) {
    $dataURL = $_GET['dataURL'];
}
else {
    
    $appid = '0';
    if (isset($_GET['appid']) && !empty($_GET['appid'])) {
        $appid = $_GET['appid'];
    }
}

$monthAgo = mktime(0,0,0,date("m"),date("d")-30);
$weekAgo = mktime(0,0,0,date("m"),date("d")-8);
$weekAgoNext = mktime(0,0,0,date("m"),date("d")-7);
$yesterday = mktime(0,0,0,date("m"),date("d")-1);
$today = mktime(0,0,0,date("m"),date("d"));

$fechaOrigen = date('d-m-Y', $monthAgo);
$fromData = date('m-d-Y', $monthAgo); 
if (isset($_GET['from']) && !empty($_GET['from'])) {
    $fechaOrigen = $_GET['from'];
    // m-d-Y format
    $aux = explode('-',$fechaOrigen);
    $fromData = $aux[1].'/'.$aux[0].'/'.$aux[2];
    $fromData_time = mktime(0,0,0,$aux[1],$aux[0],$aux[2]);
}


$fechaFinal = date('d-m-Y');
$toData = date('m-d-Y');
if (isset($_GET['to']) && !empty($_GET['to'])) {
    $fechaFinal = $_GET['to'];
    // m-d-Y format
    $aux = explode('-',$fechaFinal);
    $toData = $aux[1].'/'.$aux[0].'/'.$aux[2];
    $toData_time = mktime(0,0,0,$aux[1],$aux[0],$aux[2]);
}

$dataURL = "http://gamecharts.local/data/idcgames/games/".$appid."/";
#########################################################################

$timeControl['params'] = time();

#########################################################################
# GET RESUME GAME DATA
$gamedata = file_get_contents($dataURL."gamedata.json");
$gamedata_aux = json_decode($gamedata);

// OBTENEMOS LOS DATOS DE FULLDATA, TODAY, YESTERDAY, WEEKAGO
$fulldata = str_replace(',[0000,0]','',file_get_contents($dataURL."fulldata.json"));
$fulldata_aux = json_decode($fulldata,true);

$today = str_replace(',[0000,0]','',file_get_contents($dataURL."today.json"));
$today_aux = json_decode($today,true);

$fullaverage = str_replace(',[0000,0]','',file_get_contents($dataURL."fullaverage.json"));
$fullaverage_aux = json_decode($fullaverage,true);

$monthaverage = str_replace(',[0000,0]','',file_get_contents($dataURL."monthaverage.json"));
$monthaverage_aux = json_decode($monthaverage,true);

$months = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'Agoust','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
#########################################################################

$timeControl['json'] = time();

#########################################################################
# AVERAGE PEAK TABLE
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
#########################################################################
	
$timeControl['average_peak'] = time();

#########################################################################
# EPOCH TRANSFORM
#########################################################################
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
#########################################################################

$timeControl['epoch'] = time();

#########################################################################
# GET RESUME GAME DATA
#########################################################################
$gamedataWS = statsCCUGeneral($fechaOrigen,$fechaFinal,$appid);

$timeControl['appserver'] = time();

// TODAY YESTERDAY WEEKAGO
if (isset($gamedataWS['tJuegoMaxCcuDetalle'])) {
    $temporal = (array)$gamedataWS['tJuegoMaxCcuDetalle'];
    $fileResult = array();
    $today_aux = array();
    $yesterday_aux = array();
    $weekago_aux = array();
    
    $monthAgo = mktime(0,0,0,date("m"),date("d")-30);
    $weekAgo = mktime(0,0,0,date("m"),date("d")-7);
    $weekAgoNext = mktime(0,0,0,date("m"),date("d")-6);
    $yesterday = mktime(0,0,0,date("m"),date("d")-1);
    $today = mktime(0,0,0,date("m"),date("d"));
    
    foreach ($temporal as $data)
    {
        
        $auxDate = explode('-',$data->FECHA);
        $thisDate = (int)(mktime($data->HORA,$data->MINUTO,'0',$auxDate[1],$auxDate[2],$auxDate[0]));
        
        $aux = array();
        
        // TODAY
        if ($thisDate >= $today) {
            $aux[] = (int)(mktime($data->HORA,$data->MINUTO,'0',$auxDate[1],$auxDate[2],$auxDate[0])."000");
            $aux[] = (int)$data->MAXIMO_CCU;
            $today_aux[] = $aux;
            continue;
        }
        
        // YESTERDAY
        if (($thisDate >= $yesterday) && ($thisDate < $today)) {
            $aux[] = (int)(mktime($data->HORA,$data->MINUTO,'0',$auxDate[1],$auxDate[2]+1,$auxDate[0])."000");
            $aux[] = (int)$data->MAXIMO_CCU;
            $yesterday_aux[] = $aux;
            continue;
        }
        
        // WEEKAGO
        if (($thisDate >= $weekAgo) && ($thisDate < $weekAgoNext)) {
            $aux[] = (int)(mktime($data->HORA,$data->MINUTO,'0',$auxDate[1],$auxDate[2]+7,$auxDate[0])."000");
            $aux[] = (int)$data->MAXIMO_CCU;
            $weekago_aux[] = $aux;
            continue;
        }
        
    }
}

#########################################################################

$timeControl['gamedata'] = time();

#########################################################################
# WEBSERVICES FUNTCIONS
#########################################################################
function statsCCUGeneral($cFechaIni,$cFechaFin,$iIDJuego){
    
    $cFechaIni = urlencode ($cFechaIni);
    $cFechaFin = urlencode ($cFechaFin);
    $iIDJuego = (int)$iIDJuego;
    
    $funcion = "StatsCCU_TYW";
    $params = array(
        'iIDJuego'=>$iIDJuego,
    );
    
    $res = AppServer($funcion,$params,'unistats');

    $retorno['result'] = $res->result;
    $retorno['tJuegoMaxCcuDetalle'] = $res->tJuegoMaxCcuDetalle->tJuegoMaxCcuDetalleRow;
    return $retorno;
}
#########################################################################
?>
<html lang="en">

<head>
	<script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1,shrink-to-fit=no">
	
</head>

<body>


<!--  MENU DE ORDENACION SUPERIOR -->

<div class="row">
	<div class="col-sm-12">&nbsp;</div>
</div>


<div class="row">
	
	<div class="col-sm-2">
	</div>
	
	<div class="col-sm-3">
		<button type="button" class="btn btn-primary btn-lg" id="search" name="search">UPDATE FILTERS</button>
	</div>
	
	<!-- APPID -->
	<div class="col-sm-2">
	
		<div class="form-group" id="appid_filter" <?php if (isset($_REQUEST['appid']) && !empty($_REQUEST['appid'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($appid); ?></strong>
	                
	                <button type="button" class="close" id="appid_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="appid_select" <?php if (isset($_REQUEST['appid']) && !empty($_REQUEST['appid'])) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			<div class="form-group">
  				<label for="usr">APP ID:</label>
  				<input type="text" class="form-control" id="selectpicker_appid" value="<?php echo($appid); ?>">
			</div>
		</div>
	
	</div>
</div>

<!-- MENU DATA -->
<div id="menu" class="row">
	<div class="app-stat col-sm-3"> 
		<span class="num"><?php echo($gamedata_aux[0]->CurrentCcu); ?> ccu</span></span>
		<br>CURRENT
	</div>
	<div class="app-stat col-sm-3"> 
		<span class="num"><?php echo($gamedata_aux[0]->TopCcu24h); ?> ccu</span> / <span class="num"><?php echo((int)$gamedata_aux[0]->MaxAvg24h); ?> average</span>
		<br>LAST 24h
	</div>
	<div class="app-stat col-sm-3"> 
		<span class="num"><?php echo($gamedata_aux[0]->TopCcu30d); ?> ccu</span> / <span class="num"><?php echo((int)$gamedata_aux[0]->MaxAvg30d); ?> average</span>
		<br>LAST 30d
	</div>
	<div class="app-stat col-sm-3"> 
		<span class="num"><?php echo($gamedata_aux[0]->TopCcu); ?> ccu</span> / <span class="num"><?php echo((int)$gamedata_aux[0]->MaxAvg); ?> average</span>
		<br>ALL-TIME PEAK
	</div>
</div>

<?php $timeControl['menu'] = time(); ?>

<!--  GRAFICA  -->
<div class="row">

	<div class="col-sm-12">
		<div id="global" style="height: 250px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
		<div id="compare" style="height: 250px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
		<div id="average" style="height: 250px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	
</div>

<?php $timeControl['grafica'] = time(); ?>

<!--  TABLA -->
<div class="row">
	<div class="col-sm-12">	
		
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

<?php $timeControl['tabla'] = time(); ?>

</body>

<!-- JAVASCRIPT -->

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- BOOTSTRAP -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- POPPER -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

<!-- SELECT COUNTRY -->
<script src="./js/bootstrap-select-country.min.js"></script>

<!-- DATE RANGE PICKET -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- MOMENT -->
<script src="./js/moment.min.js"></script>
<script src="./js/moment-timezone-with-data.min.js"></script>

<!--  HIGHCHARTS -->
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

<!-- DATATABLES -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

<?php $timeControl['javascript_load'] = time(); ?>

<!--  STYLESHEET -->

<!-- BOOSTRAP - JQUERY - FONT AWESOME-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
<!-- HIGH SELECTS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/xcode.min.css" />	
	
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" />	

<!-- COUNTRY SELECT -->
<link rel="stylesheet" href="css/bootstrap-select-country.min.css" />

<!-- DATE RANGE PICKER -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- CUSTOM STYLE -->
<style>
.row {
    margin: 0px;
}

.table>tbody>tr>td,
.table>tbody>tr>th,
.table>tfoot>tr>td,
.table>tfoot>tr>th,
.table>thead>tr>td,
.table>thead>tr>th {
    padding: 5px;
    font-size: 12px;
}

.highcharts-credits {
    color: white;
    fill: white;
}

.dropdown-menu {
    
    max-height: 250px !important;

}
.app-stat {
    font-family: 'Exo', sans-serif;
    text-align: center;
    padding: 12px 0;
}
#menu {
    background-color: #d6d6ec;
    padding: 0 14px;
    margin-bottom: 20px;
    overflow: hidden;
	color: #000;
}
</style>

<?php $timeControl['css_load'] = time(); ?>

<!-- CUSTOM FUNCTIONS -->
<script>

var order = '<?php echo($order); ?>';
var appid = '<?php echo($appid); ?>';
var pais = '<?php echo($pais); ?>';
var fecha = '<?php echo($fecha_param); ?>';
var today = '<?php echo(date('m/d/Y')); ?>';


$( document ).ready(function() {

	Highcharts.setOptions({

		time: {
	     	getTimezoneOffset: function (timestamp) {
	            	var zone = 'Europe/Madrid',
	                timezoneOffset = -moment.tz(timestamp, zone).utcOffset();
	            return timezoneOffset;
	        }
	    }
		
		/*
		time: {
			timezoneOffset: - 1 * 60
		}
		*/
	});

	// SEARCH BUTTON
	$('#search').click(function() {

		fechaDesde = $('#input_01').val(); 
		fechaHasta = $('#input_02').val();
		pais = $('#selectpicker_country').val();
		source = $('#selectpicker_source').val();
		medium = $('#selectpicker_medium').val();
		scope = $('#selectpicker_scope').val();
		appid = $('#selectpicker_appid').val();

		if ((pais == null) || (pais == 'XX')) {
			pais = '';
		}
		if (fecha == today) {
			fecha = '';
		}

		window.location.href = "./ccu.php?appid="+appid;
		
	});


	// CLOSE FILTERS  
    $('#daterange_close_filter').click(function(){
		$('#daterange_filter').hide();
		$('#reportrange').show();
    });

    $('#country_close_filter').click(function(){
		$('#country_filter').hide();
		$('#country_select').show();
		$('#selectpicker_country').val('');
    });

    $('#medium_close_filter').click(function(){
		$('#medium_filter').hide();
		$('#medium_select').show();
    });

    $('#source_close_filter').click(function(){
		$('#source_filter').hide();
		$('#source_select').show();
    });

    $('#medium_close_filter').click(function(){
		$('#medium_filter').hide();
		$('#medium_select').show();
    });

    $('#scope_close_filter').click(function(){
		$('#scope_filter').hide();
		$('#scope_select').show();
    });

    $('#appid_close_filter').click(function(){
		$('#appid_filter').hide();
		$('#appid_select').show();
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
		title: {
            text: 'Historic Concurrent Users'
        },
		rangeSelector: {
		  allButtonsEnabled: true,
		  buttons: [{
			type: 'hour',
			count: 168,
			text: '7d',
			dataGrouping: {
                forced: true,
                units: [['hour', [1]]]
            }
		  }, {
			type: 'month',
			count: 1,
			text: '1m',
			dataGrouping: {
                forced: true,
                units: [['day', [1]]]
            }
		  }, {
			type: 'month',
			count: 3,
			text: '3m',
			dataGrouping: {
                forced: true,
                units: [['week', [1]]]
            }
		  }, {
			type: 'month',
			count: 6,
			text: '6m',
			dataGrouping: {
                forced: true,
                units: [['month', [1]]]
            }
		  }, {
			type: 'year',
			count: 1,
			text: '1y',
			dataGrouping: {
                forced: true,
                units: [['month', [1]]]
            }
		  }],
		  buttonTheme: {
              width: 60
          },
          selected: 0
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
        title: {
            text: 'Historic Average of Users'
        },
        rangeSelector: {
  		  allButtonsEnabled: true,
  		  buttons: [{
  			type: 'hour',
  			count: 168,
  			text: '7d',
  			dataGrouping: {
                  forced: true,
                  units: [['hour', [1]]]
              }
  		  }, {
  			type: 'month',
  			count: 1,
  			text: '1m',
  			dataGrouping: {
                  forced: true,
                  units: [['day', [1]]]
              }
  		  }, {
  			type: 'month',
  			count: 3,
  			text: '3m',
  			dataGrouping: {
                  forced: true,
                  units: [['week', [1]]]
              }
  		  }, {
  			type: 'month',
  			count: 6,
  			text: '6m',
  			dataGrouping: {
                  forced: true,
                  units: [['month', [1]]]
              }
  		  }, {
  			type: 'year',
  			count: 1,
  			text: '1y',
  			dataGrouping: {
                  forced: true,
                  units: [['month', [1]]]
              }
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
});


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
	title: {
        text: 'Today/Yesterday/Week Ago Concurrent Users'
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
</script>

<?php $timeControl['custom'] = time(); ?>

<div class="row">
	<div class="col-sm-12">	
		Tiempo total de carga: <?php echo($timeControl['custom'] - $timeControl['init']); ?>s<br/>
		-------------<br/>
		PHP INICIAL<br/>
		-------------<br/>
		Configuracion inicial: <?php echo($timeControl['load_params'] - $timeControl['init']); ?>s<br/>
		Carga de par&aacute;metros GET: <?php echo($timeControl['params'] - $timeControl['load_params']); ?>s<br/>
		Carga de JSONS: <?php echo($timeControl['json'] - $timeControl['params']); ?>s<br/>
		Datos de tabla Average: <?php echo($timeControl['average_peak'] - $timeControl['json']); ?>s<br/>
		Datos de tabla Epoch: <?php echo($timeControl['epoch'] - $timeControl['average_peak']); ?>s<br/>
		Llamada webservice: <?php echo($timeControl['appserver'] - $timeControl['epoch']); ?>s<br/>
		Datos de tabla comparaci&oacute;n: <?php echo($timeControl['gamedata'] - $timeControl['appserver']); ?>s<br/>
		-------------<br/>
		HTML<br/>
		-------------<br/>
		Menu superior: <?php echo($timeControl['menu'] - $timeControl['gamedata']); ?>s<br/>
		Graficas: <?php echo($timeControl['grafica'] - $timeControl['menu']); ?>s<br/>
		Tabla: <?php echo($timeControl['tabla'] - $timeControl['grafica']); ?>s<br/>
		-------------<br/>
		JAVASCRIPTS<br/>
		-------------<br/>
		librerias javascript: <?php echo($timeControl['javascript_load'] - $timeControl['tabla']); ?>s<br/>
		librerias stylesheet: <?php echo($timeControl['css_load'] - $timeControl['javascript_load']); ?>s<br/>
		custom javascript: <?php echo($timeControl['custom'] - $timeControl['css_load']); ?>s<br/>

</html>
