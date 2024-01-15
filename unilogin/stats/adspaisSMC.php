<?php

#########################################################################
# LOAD INITIAL PARAMETERS
ini_set('display_errors',false);
date_default_timezone_set("Europe/Madrid");
include "../AppServer.php";
$dataURL = './data/';
$ahora = time();
$hoy = date("dmy");

$_SOURCES = utf8_encode(file_get_contents("./js/source_group.json"));
$_SOURCES_aux = json_decode($_SOURCES,true);
$_SOURCES_keys = array_keys($_SOURCES_aux);
$_SOURCES = array();
foreach ($_SOURCES_aux as $key => $sources) {
    foreach ($sources as $source) {
        $_SOURCES[$source] = $key;
    }
}

$_ISOcountry_json = utf8_encode(file_get_contents("./js/country.json"));
$_ISOcountry_aux = json_decode($_ISOcountry_json,true);
$_ISOcountry = array();
foreach ($_ISOcountry_aux as $data) {
    $_ISOcountry[$data["alpha-2"]] = $data;
}
#########################################################################

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
    
    $dataURL .= $source."/".$appid."/";
}


$monthAgo = mktime(0,0,0,date("m"),date("d")-30);
$fechaOrigen = date('d-m-Y', $monthAgo);
$fromData = date('m-d-Y', $monthAgo); 
if (isset($_GET['from']) && !empty($_GET['from'])) {
    $fechaOrigen = $_GET['from'];
    // m-d-Y format
    $aux = explode('-',$fechaOrigen);
    $fromData = $aux[1] . '/' . $aux[0] . '/' . $aux[2];
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

$_DATES = array();
for ($i = $fromData_time; $i <= $toData_time; $i=$i+86400) {
    $_DATES[date('d-m-Y', $i)] = array();
    foreach ($_SOURCES_keys as $sourceKey) {
        $_DATES[date('d-m-Y', $i)][$sourceKey] = '0';
    }
    $_DATES[date('d-m-Y', $i)]['Others'] = '0';
}

$source='';
if (isset($_GET['source']) && !empty($_GET['source'])) {
    $source = $_GET['source'];
}
$medium='';
if (isset($_GET['medium']) && !empty($_GET['medium'])) {
    $medium = $_GET['medium'];
}
$campaign='';
if (isset($_GET['campaign']) && !empty($_GET['campaign'])) {
    $campaign = $_GET['campaign'];
}
#########################################################################

#########################################################################
# GET RESUME GAME DATA
    $gameData = statsAdsSMCFecha($appid,$fechaOrigen,$fechaFinal,'','','');
    
    if (!isset($_GET['source']) || empty($_GET['source'])) {
        $resumeGameData_aux = $gameData;
    } else {
        $resumeGameData_aux = statsAdsSMCFecha($appid,$fechaOrigen,$fechaFinal,$source,$medium,$campaign);
    }
    $fieldName = 'tRed';
#########################################################################


#########################################################################
# FULLDATA FOR PIE CHART
#########################################################################
$fullDataPie = array();
$totalPie = '0';
if (isset($resumeGameData_aux[$fieldName])) {
    
    $temporal = (array)$resumeGameData_aux[$fieldName];
    foreach ($temporal as $data)
    {
        
        if (isset($fullDataPie[strtolower($data->Pais)])) {
            
            $fullDataPie[strtolower($data->Pais)][1] = $fullDataPie[strtolower($data->Pais)][1] + $data->total_end_install;
        }
        else {
            
            $fullDataPie[strtolower($data->Pais)] = array();
            $fullDataPie[strtolower($data->Pais)][0] = $_ISOcountry[strtoupper($data->Pais)]['name'];
            $fullDataPie[strtolower($data->Pais)][1] = $data->total_end_install;
        }
        $totalPie = $totalPie + $data->total_end_install;
    }
}
$fullDataPie = json_encode(array_values($fullDataPie));
$fullDataMedium = json_encode(array_values($fullDataMedium));
$fullDataCampaign = json_encode(array_values($fullDataCampaign));
#########################################################################
    
#########################################################################
# FULLDATA FOR BARS
#########################################################################
$fullDataBars = array();
$keyDataBars = array();
$totalBar = '0';
foreach ($_DATES as $key=>$value) {
    
    $keyDataBars[] = $key;
    
    $gameData_bar = statsAdsSMCFecha($appid,$key,$key,'','','');
    
    foreach ($gameData_bar['tRed'] as $dataValue) {
        
        if (isset($dataValue) && isset($dataValue->Utm_Source)) {
            
            $key2 = $_SOURCES[$dataValue->Utm_Source];
            if (empty($key2)) {
                $key2 = 'Others';
            }
            
            
            $_DATES[$key][$key2] = $_DATES[$key][$key2] + $dataValue->total_end_install;
        }
        $totalBar = $totalBar + $dataValue->total_end_install;
    }
}
$keyDataBars = str_replace('"',"'",json_encode(array_reverse($keyDataBars)));

$_DATES = array_reverse($_DATES);

$aux = array();
foreach ($_DATES as $dateValue) {
    foreach ($dateValue as $key=>$value) {
        
        if (!isset($aux[$key])) {
            $aux[$key] = array();
            $aux[$key][] = (int)$value;
        }
        else {
            $aux[$key][] = (int)$value;
        }
        
    }
}

$serieDataBars = array();
foreach($aux as $key=>$value) {
    $serieDataBars[] = array(
        "name" => $key,
        "data" => $value
    );
}
$serieDataBars = json_encode($serieDataBars);
#########################################################################


#########################################################################
# WEBSERVICES FUNTCIONS
#########################################################################
function statsAdsSMCFecha($appid,$fechaOrigen,$fechaFinal,$source='',$medium='',$campaign=''){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $iIDJuego = (int)$appid;
    $cUtm_Source = urlencode ($source);
    $cUtm_Medium = urlencode ($medium);
    $cUtm_Campaign = urlencode ($campaign);
    
    $funcion = "statsAdsSMCFecha";
    $params = array(
        'cFechaInicio'=>$cFechaIni,
        'cFechaFin'=>$cFechaFin,
        'iIDJuego'=>$iIDJuego,
        'cUtm_Source'=>$cUtm_Source,
        'cUtm_Medium'=>$cUtm_Medium,
        'cUtm_Campaign'=>$cUtm_Campaign,
    );
    
    $res = AppServer($funcion,$params,'unistats');
    
    $retorno['result'] = $res->result;
    if (isset($res->tRed->tRedRow) && is_array($res->tRed->tRedRow)) {
        $retorno['tRed'] = (array)$res->tRed->tRedRow;
    }
    else {
        $retorno['tRed'][0] = $res->tRed->tRedRow;
    }
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

<?php if (!isset($_GET['source']) || empty($_GET['source'])) { ?>

    <!--  MENU DE ORDENACION SUPERIOR -->
    
    <div class="row">
    	<div class="col-sm-12">&nbsp;</div>
    </div>
    
    
    <div class="row">
    	
    	<div class="col-sm-2">
    	</div>
    	
    	<div class="col-sm-2">
    		<button type="button" class="btn btn-primary btn-lg" id="search" name="search">UPDATE FILTERS</button>
    	</div>
    	
    	<!--  DATE RANGE PICKER -->
    	<div class="col-sm-4">
    		<div class="form-group" id="daterange_filter" <?php if (isset($_REQUEST['from']) && !empty($_REQUEST['from'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
    	        <div class="row">
    	            <div class="col-md-12 alert alert-success">
    	                
    	                <strong><?php echo("From: ".$fechaOrigen." - To: ".$fechaFinal); ?></strong>
    	                
    	                <button type="button" class="close" id="daterange_close_filter" aria-label="Close">
    					  <span aria-hidden="true">&times;</span>
    					</button>
    	                
    	            </div>
    	        </div>
    	    </div>
    	
    		<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; max-width: 300px; <?php if (isset($_REQUEST['from']) && !empty($_REQUEST['from'])) { echo('display:none;'); }else{ echo('display:block'); } ?>" >
    			<label for="reportrange">Date Range:</label><br/>
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>
            <input value="<?php echo($fechaOrigen);?>" id="input_01" name="dateFrom" type="hidden">
            <input value="<?php echo($fechaFinal);?>" id="input_02" name="dateTo" type="hidden">
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
 
 	<!-- GRAFICA (BARRAS) -->
	<div class="row">
		<div class="col-sm-12">
			<div id="barras_source" style="border: 2px solid black; padding:10px; margin:10px;"></div>
		</div>
	</div>
	

	<!-- TABLAS -->
	<div class="row">
    	<div class="col-sm-12">	
        	
    		<table id="installs" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    		  <thead>
    		  
    		    <tr>
				  <th class="th-sm">Source</th>
                  <th class="th-sm">Medium</th>
                  <th class="th-sm">Campaign</th>
	   		      <th class="th-sm">Installed</th>
    		      <th class="th-sm">% of Total (<?php echo(number_format($totalBar,0))?> Installs)</th>
    		    </tr>
    		  </thead>
      		  <tbody>
    
      		  	<?php 
      		  	
      		  	foreach($resumeGameData_aux[$fieldName] as $data) {

      		  	    echo('<tr class="f16">');
      		  	    echo('<td onclick="filterSource('."'".$data->Utm_Source."','".$data->utm_Medium."','".$data->utm_campaign."'".');" style="cursor:pointer;"><a href="#" data-toggle="modal" data-target="#myModal">'.$data->Utm_Source.'</a></td>');
    				echo('<td style="cursor:pointer;"><a href="#" data-toggle="modal" data-target="#myModal">'.$data->utm_Medium.'</a></td>');
    				echo('<td style="cursor:pointer;"><a href="#" data-toggle="modal" data-target="#myModal">'.$data->utm_campaign.'</a></td>');
    				echo('<td style="cursor:pointer;"><a href="#" data-toggle="modal" data-target="#myModal">'.number_format($data->total_end_install,0).'</a></td>');
    				echo('<td style="cursor:pointer;"><a href="#" data-toggle="modal" data-target="#myModal">'.number_format(($data->total_end_install/$totalBar)*100,2).'%</a></td>');
    				echo('</tr>');
    
    			}
    			?>
    		  </tbody>
    		</table>	
        	
		</div>
	</div>
	
<?php } else { ?>  	

    <!-- GRAFICA (QUESO) -->
	<div class="row">
		<div class="col-sm-12">
			<div id="queso_country" style="border: 2px solid black; padding:10px; margin:10px;"></div>
		</div>
	</div>
	
	<!-- TABLAS -->
	<div class="row">
    	<div class="col-sm-12">	
        	
    		<table id="installs_country" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    		  <thead>
    		  
    		    <tr>
    		      <th class="th-sm">ISO</th>
				  <th class="th-sm">Country</th>
                  <th class="th-sm">Installed</th>
    		      <th class="th-sm">% of Total (<?php echo(number_format($totalPie,0))?> Installs)</th>
    		    </tr>
    		  </thead>
      		  <tbody>
    
      		  	<?php 
      		  	
      		  	foreach($resumeGameData_aux[$fieldName] as $data) {

      		  	    echo('<tr class="f16">');
      		  	    echo('<td><span class="flag '.strtolower(trim($data->Pais)).'" style="padding-left: 20px;">'.$data->Pais.'</span>');
      		  	    echo('<td>'.$_ISOcountry[strtoupper(trim($data->Pais))]['name'].'</td>');
    				echo('<td>'.number_format($data->total_end_install,0).'</td>');
    				echo('<td>'.number_format(($data->total_end_install/$totalPie)*100,2).'%</td>');
    				echo('</tr>');
    
    			}
    			?>
    		  </tbody>
    		</table>	
        	
		</div>
	</div>
	
	
<?php } ?>

    <div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modal-title">Event</h4>
                </div>
                <div class="modal-body" id="modal-body">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>

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

<!-- MOMENT -->
<script src="./js/moment.min.js"></script>

<!-- DATE RANGE PICKET -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!--  HIGHMAPS -->
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/asia.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/africa.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/europe.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/oceania.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/north-america.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/south-america.js"></script>

<!-- DATATABLES -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>



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
</style>

<!-- CUSTOM FUNCTIONS -->
<script>

var order = '<?php echo($order); ?>';
var appid = '<?php echo($appid); ?>';
var pais = '<?php echo($pais); ?>';
var fecha = '<?php echo($fecha_param); ?>';
var today = '<?php echo(date('m/d/Y')); ?>';


$( document ).ready(function() {

	// DATE RANGE PICKER
	$(function() {

	    var start = moment(<?php echo($fromData_time)?>000);
	    var end = moment(<?php echo($toData_time)?>000);

	    function cb(start, end) {
	        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			$('#input_01').val( start.format('DD-MM-YYYY') );
			$('#input_02').val( end.format('DD-MM-YYYY') );
	    }

	    $('#reportrange').daterangepicker({
	        startDate: start,
	        endDate: end,
	        showISOWeekNumbers: true,
	        ranges: {
	           'Today': [moment(), moment()],
	           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	           'This Month': [moment().startOf('month'), moment().endOf('month')],
	           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        }
	    }, cb);

	    cb(start, end);

	});

	// SEARCH BUTTON
	$('#search').click(function() {

		fechaDesde = $('#input_01').val(); 
		fechaHasta = $('#input_02').val();
		pais = $('#selectpicker_country').val();
		source = $('#selectpicker_source').val();
		medium = $('#selectpicker_medium').val();
		campaign = $('#selectpicker_campaign').val();
		scope = $('#selectpicker_scope').val();
		appid = $('#selectpicker_appid').val();

		if ((pais == null) || (pais == 'XX')) {
			pais = '';
		}
		if (fecha == today) {
			fecha = '';
		}

		window.location.href = "./adspaisSMC.php?appid="+appid+"&from="+fechaDesde+"&to="+fechaHasta;
		
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
		filterCountry('');
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
    
    $('#campaign_close_filter').click(function(){
		$('#campaign_filter').hide();
		$('#campaign_select').show();
    });

    $('#scope_close_filter').click(function(){
		$('#scope_filter').hide();
		$('#scope_select').show();
    });

    $('#appid_close_filter').click(function(){
		$('#appid_filter').hide();
		$('#appid_select').show();
    });

   	<?php if (!isset($_GET['source']) || empty($_GET['source'])) { ?>

	Highcharts.chart('barras_source', {
    	    chart: {
    	        type: 'bar'
    	    },
    	    title: {
    	        text: 'Sources of Installs'
    	    },
    	    subtitle: {
    	        text: 'clasified by groups and days'
    	    },
    	    xAxis: {
    	        categories: <?php echo($keyDataBars); ?>,
    	        title: {
    	            text: 'Sources Groups'
    	        }
    	    },
    	    yAxis: {
    	        min: 0,
    	        title: {
    	            text: 'Installations',
    	            align: 'high'
    	        },
    	        labels: {
    	            overflow: 'justify'
    	        }
    	    },
    	    tooltip: {
    	        valueSuffix: ' installations'
    	    },
    	    plotOptions: {
    	        bar: {
    	            dataLabels: {
    	                enabled: true
    	            }
    	        }
    	    },
    	    plotOptions: {
    	        series: {
    	            lineWidth: 5
    	        }
    	    },
    	    legend: {
    	        layout: 'vertical',
    	        align: 'right',
    	        verticalAlign: 'top',
    	        x: -40,
    	        y: 80,
    	        floating: true,
    	        borderWidth: 10,
    	        backgroundColor:
    	            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
    	        shadow: true
    	    },
    	    credits: {
    	        enabled: false
    	    },
    	    series: <?php echo($serieDataBars); ?>
			/*
        	[{
    	        name: 'Google/Youtube',
    	        data: [107, 31, 635, 203, 2]
    	    }, {
    	        name: 'Facebook/Instagram',
    	        data: [133, 156, 947, 408, 6]
    	    }, {
    	        name: 'Twitch',
    	        data: [814, 841, 3714, 727, 31]
    	    }, {
    	        name: 'Influencer Mkt',
    	        data: [1216, 1001, 4436, 738, 40]
    	    }, {
    	        name: 'Native Ads',
    	        data: [1216, 1001, 4436, 738, 40]
    	    }, {
    	        name: 'Organic',
    	        data: [1216, 1001, 4436, 738, 40]
    	    }, {
    	        name: 'Others',
    	        data: [1216, 1001, 4436, 738, 40]
    	    }]
        	*/
    	});

	var table = $('#installs').DataTable({"bInfo": false, "order": [[ 3, "desc" ]]});
	
	<?php } else { ?>

	var data_source = <?php echo($fullDataPie); ?>;
   	
	//Create Pie chart
	Highcharts.chart('queso_country', {
         chart: {
             plotBackgroundColor: null,
             plotBorderWidth: null,
             plotShadow: false,
             type: 'pie'
         },
         title: {
             text: 'Sources of Installs'
         },
         tooltip: {
             pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
         },
         accessibility: {
             point: {
                 valueSuffix: '%'
             }
         },
         plotOptions: {
             pie: {
                 allowPointSelect: true,
                 cursor: 'pointer',
                 dataLabels: {
                     enabled: true,
                     format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                 }
             }
         },
         series: [{
             name: 'Countries',
             colorByPoint: true,
             data: data_source,
         }],
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
     });

	var table = $('#installs_country').DataTable({"bInfo": false, "order": [[ 2, "desc" ]]});
	
    <?php } ?>
     
	 
 
});
    
	// TABLE FILTER
	function filterSource(source,medium,campaign) {

		fechaDesde = '<?php echo($fechaOrigen); ?>';
		fechaHasta = '<?php echo($fechaFinal); ?>';
		appid = $('#selectpicker_appid').val();

		console.log('Loading Source: '+source+'|'+medium+'|'+campaign);
		
		$('#modal-title').html('Source: '+source + '<br/>Medium: '+medium+'<br/>Campaign: '+campaign);
		$('#modal-body').html('<p>Loading...</p>');

		$('#modal-body').load("./adspaisSMC.php?appid="+appid+"&from="+fechaDesde+"&to="+fechaHasta+"&source="+source+"&medium="+medium+"&campaign="+campaign);
		
	}
	
</script>

</html>
