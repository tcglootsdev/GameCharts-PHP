<?php

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

$_BACKGROUNDCOLOR = array('100' => '#07399c',
                           '90' => '#3a5988',
                           '80' => '#3a5f91',
                           '70' => '#3a6ca4',
                           '60' => '#4582bb',
                           '50' => '#4988bf',
                           '40' => '#6baad3',
                           '30' => '#86bcdd',
                           '20' => '#a9cfe5',
                           '10' => '#c2dbec',
                            '0' => '#dbe8f5');
$_COLOR = array('100' => '#fff',
                '90' => '#fff',
                '80' => '#fff',
                '70' => '#fff',
                '60' => '#fff',
                '50' => '#fff',
                '40' => '#000',
                '30' => '#000',
                '20' => '#000',
                '10' => '#000',
                '0' => '#000');


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

#########################################################################


#########################################################################
# GET RESUME GAME DATA
    $resumeGameData = statsLoginFecha($appid,$fechaOrigen,$fechaFinal);
    $fieldName = 'tLoginFecha';

/*
# CHECK UTM_SOURCE
if (isset($source) && !empty($source)) {
    
    foreach ($resumeGameData[$fieldName] as $key => $value) {
        
        if ($value->UTM_SOUCE != $source) {
            unset($resumeGameData[$fieldName][$key]);
        }
        
    }
}
*/

# SUM TOTALS
$totales = array();
$totales['Dias'] = array();
$totales['Semanas'] = array();
$totales['Meses'] = array();
$totalNUSERS = '0';
foreach ($resumeGameData[$fieldName] as $key => $value) {

    foreach ($value->Dias as $key2 => $value2) {
        
        $totales['Dias'][$key2] = $totales['Dias'][$key2] + $value2;
    }
    
    foreach ($value->Semanas as $key2 => $value2) {
        
        $totales['Semanas'][$key2] = $totales['Semanas'][$key2] + $value2;
    }
    
    foreach ($value->Meses as $key2 => $value2) {
        
        $totales['Meses'][$key2] = $totales['Meses'][$key2] + $value2;
    }
    $totalNUSERS = $totalNUSERS + $value->NUSERS;
}

// FULLDATA FOR CHART
$fullfecha_aux = array();
$fullFW_aux = array();
$fullFM_aux = array();
$fullNM_aux = array();
// FULLDATA
if (isset($resumeGameData['tLoginFecha'])) {
    
    $temporal = (array)$resumeGameData['tLoginFecha'];
    $fileResult = array();
    foreach ($temporal as $data)
    {
        $fullfecha_aux[] = $data->FECHA;
        $fullFW_aux[] = $data->PRIMERASEMANA;
        $fullFM_aux[] = $data->PRIMERMES;
        $fullNM_aux[] = $data->MASMES;
    }
    $fullfecha = json_encode($fullfecha_aux);
    $fullFW = json_encode($fullFW_aux);
    $fullFM = json_encode($fullFM_aux);
    $fullNM = json_encode($fullNM_aux);
}
#########################################################################

#########################################################################
# WEBSERVICES FUNTCIONS
#########################################################################
function statsLoginFecha($appid,$fechaOrigen,$fechaFinal,$groupby='1'){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $iAgrupado = urlencode ($groupby);
    
    $funcion = "StatsLoginFecha";
    $params = array(
        'cLogin'=>'',
        'cPassword'=>'',
        'iIDJuego'=>$appid,
        'cFechaInicio'=>$cFechaIni,
        'cFechaFin'=>$cFechaFin,
        'iAgrupado'=>$iAgrupado,
    );
    
    $res = AppServer($funcion,$params,'unistats');

    $retorno['result'] = $res->result;
    if (isset($res->tLoginFecha->tLoginFechaRow) && is_array($res->tLoginFecha->tLoginFechaRow)) {
        $retorno['tLoginFecha'] = (array)$res->tLoginFecha->tLoginFechaRow;
    }
    else {
        $retorno['tLoginFecha'][0] = $res->tLoginFecha->tLoginFechaRow;
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
	                
	                <strong><?php echo("From: ".$fechaOrigen."<br/>To: ".$fechaFinal); ?></strong>
	                
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

<!--  GRAFICA  -->
<div class="row">

	<div class="col-sm-12">
		<div id="global" style="height: 400px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	
</div>

<!--  TABLA -->
<div class="row">
	<div class="col-sm-12">	
	
		<table id="dtDropOff" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
		  <thead>
		  
		  	<tr>
		  		<th></th>
		  		<th>Day 0</th>
		  		<th>Day 1</th>
		  		<th>Day 2</th>
		  		<th>Day 3</th>
		  		<th>Day 4</th>
		  		<th>Day 5</th>
		  		<th>Day 6</th>
		  		<th>Week 2</th>
		  		<th>Week 3</th>
		  		<th>Week 4</th>
		  		<th>Month 2</th>
		  		<th>Month 3</th>
		  		<th>Month 4</th>
		  		<th>Month 5</th>
		  		<th>Month 6</th>
		  		
		  	</tr>
		  	
		  	<?php 
  		  	
		  	if (isset($resumeGameData[$fieldName]) && count($resumeGameData[$fieldName])) {
  		  	    
  		  	?>
		  	
		  	<tr>
		  		<th></th>
		  		<th><?php echo(number_format(($totales['Dias'][0]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][0].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][1]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][1].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][2]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][2].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][3]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][3].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][4]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][4].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][5]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][5].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Dias'][6]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Dias'][6].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Semanas'][1]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Semanas'][1].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Semanas'][2]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Semanas'][2].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Semanas'][3]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Semanas'][3].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Meses'][1]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Meses'][1].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Meses'][2]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Meses'][2].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Meses'][3]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Meses'][3].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Meses'][4]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Meses'][4].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales['Meses'][5]/$totalNUSERS) * 100,2).'% <br/><span style="font-size:9px">('.$totales['Meses'][5].' users)</span>'); ?></th>
		  	</tr>
		  
		  	<?php } ?>
		   
		  </thead>
  		  <tbody>

  		  	<?php 
  		  	
  		  	if (isset($resumeGameData[$fieldName]) && count($resumeGameData[$fieldName])) {
  		  	    
  		  	    $today = mktime (0,0,0,date('m'),date('d'),date("Y"));
  		  	    foreach ($resumeGameData[$fieldName] as $key => $value) {
  		  	        
    	  	        echo('<tr>');
    		
    	  	            echo('<td>'.$value->FECHA.'<br/><span style="font-size:9px">('.$value->NUSERS.' users)</span><br/></td>');
    	  	            
    	  	            $i=0;
    	  	            foreach ($value->Dias as $key2 => $value2) {
    	  	                
    	  	                $valor = number_format(($value2 / $value->NUSERS)*100,2);
    	  	                
    	  	                if (isset($value2)) {
    	  	                    foreach ($_COLOR as $keyColor => $valueColor) {
    	  	                        if ($valor >= $keyColor) {
    	  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
    	  	                            $color = $valueColor;
    	  	                            break;
    	  	                        }
    	  	                    }
    	  	                    echo ('<td style="background-color: '.$background_color.';color: '.$color.'">');
    	  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' users)</span></td>');
    	  	                }
    	  	                
    	  	                $i++;
    	  	                if ($i>6) {
    	  	                    break;
    	  	                }
    	  	            }
    	  	            
    	  	            $i=0;
    	  	            foreach ($value->Semanas as $key2 => $value2) {
    	  	                
    	  	                if ($i==0) { 
    	  	                    $i++;
    	  	                    continue; 
    	  	                }
    	  	                
    	  	                $valor = number_format(($value2 / $value->NUSERS)*100,2);
    	  	                
    	  	                if (isset($value2)) {
    	  	                    foreach ($_COLOR as $keyColor => $valueColor) {
    	  	                        if ($valor >= $keyColor) {
    	  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
    	  	                            $color = $valueColor;
    	  	                            break;
    	  	                        }
    	  	                    }
    	  	                    echo ('<td style="background-color: '.$background_color.';color: '.$color.'">');
    	  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' users)</span></td>');
    	  	                }
    	  	                
    	  	                $i++;
    	  	                if ($i>3) {
    	  	                    break;
    	  	                }
    	  	            }
    	  	            
    	  	            $i=0;
    	  	            foreach ($value->Meses as $key2 => $value2) {
    	  	                
    	  	                if ($i==0) { 
    	  	                    $i++;
    	  	                    continue; 
    	  	                }
    	  	                
    	  	                $valor = number_format(($value2 / $value->NUSERS)*100,2);
    	  	                
    	  	                if (isset($value2)) {
    	  	                    foreach ($_COLOR as $keyColor => $valueColor) {
    	  	                        if ($valor >= $keyColor) {
    	  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
    	  	                            $color = $valueColor;
    	  	                            break;
    	  	                        }
    	  	                    }
    	  	                    echo ('<td style="background-color: '.$background_color.';color: '.$color.'">');
    	  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' users)</span></td>');
    	  	                }
    	  	                
    	  	                $i++;
    	  	                if ($i>5) {
    	  	                    break;
    	  	                }
    	  	            }
    	  	            
    				echo('</tr>');
    
    			}
  		  	}
  		  	else {
  		  	    echo("<tr><td colspan='9' text-align='center'> No data with those parameters </td></tr>");
  		  	}
			?>
		  </tbody>
		</table>	
	
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

<!--  HIGHCHARTS -->
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

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

			/*
			// SI LA FECHA HA CAMBIADO, SUBMITE EL FORM -> CONSTRUIDO ASÃ� PARA QUE NO SE GENERE UN BUCLE
			if( start.format('DD-MM-YYYY') != '12-03-2020' || end.format('DD-MM-YYYY') != '12-03-2020' ){
				$( "form" ).submit();
			}
			*/
			
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
		scope = $('#selectpicker_scope').val();
		appid = $('#selectpicker_appid').val();

		if ((pais == null) || (pais == 'XX')) {
			pais = '';
		}
		if (fecha == today) {
			fecha = '';
		}

		window.location.href = "./logines.php?appid="+appid+"&from="+fechaDesde+"&to="+fechaHasta;
		
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


    // GRAPHIC
	Highcharts.chart('global', {

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
		
        chart: {
            type: 'column'
        },
        title: {
            text: 'Login of users in a date based on their installation date'
        },
        xAxis: {
            categories: <?php echo($fullfecha); ?>
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Login'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'More than a Month',
            data: <?php echo($fullNM); ?>        
        }, {
            name: 'First Month',
            data: <?php echo($fullFM); ?>
        }, {
            name: 'First Week',
            data: <?php echo($fullFW); ?>
        }]
    });

	// LOAD TABLE
	var table = $('#dtDropOff').DataTable({"bFilter": false,"bPaginate": false,"bInfo": false});
	
	
});
</script>

</html>
