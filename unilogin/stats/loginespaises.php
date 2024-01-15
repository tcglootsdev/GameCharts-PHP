<?php

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
	
	
	$weekAgo = mktime(0,0,0,date("m"),date("d")-8);
	$fechaOrigen = date('d-m-Y', $weekAgo);
	if (isset($_GET['inicio']) && !empty($_GET['inicio'])) {
		$fechaOrigen = $_GET['inicio'];
	}
	
	$fechaFinal = date('d-m-Y');
	if (isset($_GET['final']) && !empty($_GET['final'])) {
		$fechaFinal = $_GET['final'];
	}

	// OBTENEMOS LOS DATOS DEL JUEGO
	$gamedata = statsLoginFechaPais($appid,$fechaOrigen,$fechaFinal);
	
	$fullfecha_aux = array();
	$fullFW_aux = array();
	$fullFM_aux = array();
	$fullNM_aux = array();
	
	
	// FULLDATA
	if (isset($gamedata['tLoginFecha'])) {
	
		$temporal = (array)$gamedata['tLoginFecha'];
		$fileResult = array();
		foreach ($temporal as $data)
		{
			if (!in_array($data->FECHA,$fullfecha_aux)) {
				$fullfecha_aux[] = $data->FECHA;
			}
		
			if (!isset($data->PAIS) || empty($data->PAIS)) {
				$data->PAIS = 'ALL';
			}
			$fullFW_aux[$data->PAIS][$data->FECHA] = $data->PRIMERASEMANA;
			$fullFM_aux[$data->PAIS][$data->FECHA] = $data->PRIMERMES;
			$fullNM_aux[$data->PAIS][$data->FECHA] = $data->MASMES;
		}
		
		
		// REVISAMOS QUE ESTÉN TODAS LAS FECHAS
		foreach($fullFW_aux as $key=>$value) {
			foreach($fullfecha_aux as $fecha) {
				if (!isset($value[$fecha])) {
					$fullFW_aux[$key][$fecha] = 0;
				}
			}
			
			$aux = $fullFW_aux[$key];
			ksort($aux);
			$fullFW_aux[$key] = array_values($aux);
		}
		foreach($fullFM_aux as $key=>$value) {
			foreach($fullfecha_aux as $fecha) {
				if (!isset($value[$fecha])) {
					$fullFM_aux[$key][$fecha] = 0;
				}
			}
				
			$aux = $fullFM_aux[$key];
			ksort($aux);
			$fullFM_aux[$key] = array_values($aux);
		}
		foreach($fullNM_aux as $key=>$value) {
			foreach($fullfecha_aux as $fecha) {
				if (!isset($value[$fecha])) {
					$fullNM_aux[$key][$fecha] = 0;
				}
			}
				
			$aux = $fullNM_aux[$key];
			ksort($aux);
			$fullNM_aux[$key] = array_values($aux);
		}
		
		
		
		// NOS QUEDAMOS CON LOS 20 VALORES MÁS IMPORTANTES
		
		// FIRST WEEK
		$cont_aux = array();
		foreach ($fullFW_aux as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $data) {
					$cont_aux[$key] = $cont_aux[$key] + $data; 
				}
			}
		}
		asort($cont_aux);
		$cont_ordered = array_reverse($cont_aux);
		$i = 0;
		$resultAUX = array();
		foreach ($cont_ordered as $key => $value) {
			if ($i >= '10') {
				foreach ($fullFW_aux[$key] as $vkey => $data) {
					if (isset($resultAUX['others'][$vkey])) 
					{
						$resultAUX['others'][$vkey] = $resultAUX['others'][$vkey] + $data;
					}
					else {
						$resultAUX['others'][$vkey] = $data;
					}
				}
			}
			else {
				$resultAUX[$key] = $fullFW_aux[$key];
			}
			$i++;
		}
		$fullFW_aux = $resultAUX;
		
		// FIRST MONTH
		$cont_aux = array();
		foreach ($fullFM_aux as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $data) {
					$cont_aux[$key] = $cont_aux[$key] + $data;
				}
			}
		}
		asort($cont_aux);
		$cont_ordered = array_reverse($cont_aux);
		$i = 0;
		$resultAUX = array();
		foreach ($cont_ordered as $key => $value) {
			if ($i >= '10') {
				foreach ($fullFM_aux[$key] as $vkey => $data) {
					if (isset($resultAUX['others'][$vkey]))
					{
						$resultAUX['others'][$vkey] = $resultAUX['others'][$vkey] + $data;
					}
					else {
						$resultAUX['others'][$vkey] = $data;
					}
				}
			}
			else {
				$resultAUX[$key] = $fullFM_aux[$key];
			}
			$i++;
		}
		$fullFM_aux = $resultAUX;
		
		// NEXT MONTH
		$cont_aux = array();
		foreach ($fullNM_aux as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $data) {
					$cont_aux[$key] = $cont_aux[$key] + $data;
				}
			}
		}
		asort($cont_aux);
		$cont_ordered = array_reverse($cont_aux);
		$i = 0;
		$resultAUX = array();
		
		foreach ($cont_ordered as $key => $value) 
		{
			
			if ($i >= '10') {
				foreach ($fullNM_aux[$key] as $vkey => $data) 
				{
					if (isset($resultAUX['others'][$vkey]))
					{
						$resultAUX['others'][$vkey] = $resultAUX['others'][$vkey] + $data;
					}
					else {
						$resultAUX['others'][$vkey] = $data;
					}
				}
			}
			else 
			{
				$resultAUX[$key] = $fullNM_aux[$key];
			}
			$i++;
		}
		$fullNM_aux = $resultAUX;
		
		$fullfecha = json_encode($fullfecha_aux);
		foreach ($fullFW_aux as $key=>$value) {
			$fullFW[$key] = json_encode($value);
		}
		foreach ($fullFM_aux as $key=>$value) {
			$fullFM[$key] = json_encode($value);
		}
		foreach ($fullNM_aux as $key=>$value) {
			$fullNM[$key] = json_encode($value);
		}
	}
	
	
	
	// GAMEDATA
	$order = 'fecha|pais';
	$order_aux = explode('|',$order);
	
	$gamedata_aux = array();
	$gamedata_second_aux = array();
	
	$gamedata_inverter_aux = array();
	$gamedata_second_inverter_aux = array();
	
	$sumar = false;
	$sumar_second = false;
	
	foreach ($gamedata['tLoginFecha'] as $valor) {
		
		if ($order_aux[0] == 'pais') {
			$index = strtoupper($valor->PAIS);
			$index2 = strtoupper($valor->FECHA);
		} else {
			$index = strtoupper($valor->FECHA);
			$index2 = strtoupper($valor->PAIS);
		}
		
		if (isset($gamedata_aux[$index])) {
		
			$sumar = true;
			if (isset($gamedata_second_aux[$index][$index2])) 
			{
				$sumar_second = true;
			}
			else 
			{
				$sumar_second = false;
				$gamedata_second_aux[$index][$index2] = (array)$valor;
			}
		}
		else 
		{
			$sumar = false;
			$sumar_second = false;
			
			$gamedata_aux[$index] = (array)$valor;
		
			$gamedata_second_aux[$index] = array();
			$gamedata_second_aux[$index][$index2] = (array)$valor;
			
		}
		
		if (isset($gamedata_inverter_aux[$index2])) {
		
			$sumar_inverter = true;
		
			if (isset($gamedata_second_inverter_aux[$index2][$index]))
			{
				$sumar_second_inverter = true;
			}
			else
			{
				$sumar_second_inverter = false;
				$gamedata_second_inverter_aux[$index2][$index] = (array)$valor;
			}
			
		}
		else
		{
			$sumar_inverter = false;
			$sumar_second_inverter = false;
			
			$gamedata_inverter_aux[$index2] = (array)$valor;
		
			$gamedata_second_inverter_aux[$index2] = array();
			$gamedata_second_inverter_aux[$index2][$index] = (array)$valor;
		}
		
		
		
		if ($sumar) {
			
			$gamedata_aux[$index]['PRIMERASEMANA'] += $valor->PRIMERASEMANA;
			$gamedata_aux[$index]['PRIMERMES'] += $valor->PRIMERMES;
			$gamedata_aux[$index]['MASMES'] += $valor->MASMES;
			$gamedata_aux[$index]['NUSERS'] += $valor->NUSERS;
			
			if ($sumar_second) {
				
				$gamedata_second_aux[$index][$index2]['PRIMERASEMANA'] += $valor->PRIMERASEMANA;
				$gamedata_second_aux[$index][$index2]['PRIMERMES'] += $valor->PRIMERMES;
				$gamedata_second_aux[$index][$index2]['MASMES'] += $valor->MASMES;
				$gamedata_second_aux[$index][$index2]['NUSERS'] += $valor->NUSERS;
								
				$sumar_second = false;
			}
			$sumar = false;
		}
		
		if ($sumar_inverter) {
		
			$gamedata_inverter_aux[$index2]['PRIMERASEMANA'] += $valor->PRIMERASEMANA;
			$gamedata_inverter_aux[$index2]['PRIMERMES'] += $valor->PRIMERMES;
			$gamedata_inverter_aux[$index2]['MASMES'] += $valor->MASMES;
			$gamedata_inverter_aux[$index2]['NUSERS'] += $valor->NUSERS;
		
			if ($sumar_second_inverter) {
				$gamedata_second_inverter_aux[$index2][$index]['PRIMERASEMANA'] += $valor->PRIMERASEMANA;
				$gamedata_second_inverter_aux[$index2][$index]['PRIMERMES'] += $valor->PRIMERMES;
				$gamedata_second_inverter_aux[$index2][$index]['MASMES'] += $valor->MASMES;
				$gamedata_second_inverter_aux[$index2][$index]['NUSERS'] += $valor->NUSERS;
			}
			$sumar_inverter = false;
		}
	}
	
	

	
	$gamedata_first = json_encode($gamedata_aux);
	$gamedata_second = json_encode($gamedata_second_aux);
	
	$gamedata_first_inverter = json_encode($gamedata_inverter_aux);
	$gamedata_second_inverter = json_encode($gamedata_second_inverter_aux);
	
?>
<html>

<head><?php /*?><script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><?php */ ?></head>

<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" />


<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>

<script src="./js/moment.min.js"></script>
<script src="./js/jquery.sortable.min.js"></script>

<div class="row">
	<div class="col-md-12">&nbsp;</div>
</div>

<div class="row">

	<div class="col-md-4">
		<div id="firstweek" style="width:100%; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	<div class="col-md-4">
		<div id="firstmonth" style="width:100%; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	<div class="col-md-4">
		<div id="aftermonth" style="width:100%; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">&nbsp;</div>
</div>

<div class="row" style="margin-bottom: 150px;">
	<div class="col-md-6">

		<table id="dtInstallations" class="table table-striped table-bordered table-sm display compact nowrap w-100" cellspacing="0" width="100%">
		  <thead>
		    <tr>
		      <th></th>
		      <th style="display:none;"></th>
		      <?php if ($order_aux[0] == 'pais') { ?>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Country</th>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Date</th>
		      <?php } else { ?>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Date</th>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Country</th>
		      <?php } ?>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">Total</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">A Week Ago</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">A Month Ago</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">More than a Month</th>
		    </tr>
		  </thead>
  		  <tbody>

  		  	<?php 
  		  	
  		  	foreach($gamedata_aux as $data) {

				echo('<tr>');
				echo('<td class=" details-control" style="width:50px"></td>');
				echo('<td style="display:none;">'.rand('0','60000').'</td>');
				if ($order_aux[0] == 'pais') { 
					echo('<td>'.$data['PAIS'].'</td>');
					echo('<td style="width:'.$field_lenght.'"> --- </td>');
				}
				else {
					echo('<td>'.$data['FECHA'].'</td>');
					echo('<td style="width:'.$field_lenght.'"> --- </td>');
				}
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERASEMANA']+$data['PRIMERMES']+$data['MASMES']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERASEMANA']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERMES']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['MASMES']).'</td>');
				echo('</tr>');

			}
			?>
		  </tbody>
		</table>

	</div>
	
	<div class="col-md-6">

		<table id="dtInstallations2" class="table table-striped table-bordered table-sm display compact nowrap w-100" cellspacing="0" width="100%">
		  <thead>
		    <tr>
		      <th></th>
		      <th style="display:none;"></th>
		      <?php if ($order_aux[1] == 'pais') { ?>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Country</th>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Date</th>
		      <?php } else { ?>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Date</th>
		      	<th class="th-sm" style="width:<?php echo($header_lenght); ?>">Country</th>
		      <?php } ?>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">Total</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">A Week Ago</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">A Month Ago</th>
		      <th class="th-sm" style="width:<?php echo($header_lenght); ?>">More than a Month</th>
		    </tr>
		  </thead>
  		  <tbody>

  		  	<?php 
  		  	
  		  	foreach($gamedata_inverter_aux as $data) {

				echo('<tr>');
				echo('<td class=" details-control" style="width:50px"></td>');
				echo('<td style="display:none;">'.rand('0','60000').'</td>');
				if ($order_aux[1] == 'pais') { 
					echo('<td>'.$data['PAIS'].'</td>');
					echo('<td style="width:200px"> --- </td>');
				}
				else {
					echo('<td>'.$data['FECHA'].'</td>');
					echo('<td style="width:'.$field_lenght.'"> --- </td>');
				}
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERASEMANA']+$data['PRIMERMES']+$data['MASMES']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERASEMANA']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['PRIMERMES']).'</td>');
				echo('<td style="width:'.$field_lenght.'">'.number_format($data['MASMES']).'</td>');
				echo('</tr>');

			}
			?>
		  </tbody>
		</table>

	</div>
	
</div>
	
</body>

<script>

var order = '<?php echo($order); ?>';
var appid = '<?php echo($appid); ?>';
var second = <?php echo($gamedata_second); ?>;
var second_inverter = <?php echo($gamedata_second_inverter); ?>;

$( document ).ready(function() {
	var table = $('#dtInstallations').DataTable();
	var table2 = $('#dtInstallations2').DataTable();

	Highcharts.chart('firstweek', {
	    chart: {
	        type: 'column'
	    },
	    title: {
	        text: 'LOGIN FOR USER REGISTERED A WEEK AGO'
	    },
	    xAxis: {
	        categories: <?php echo($fullfecha); ?>
	    },
	    yAxis: {
	        min: 0,
	        title: {
	            text: 'Total Drop Off'
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
		navigation: {
			buttonOptions: {
				enabled: false
			}
		},
		
		scrollbar: { enabled: false },
	    legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 0,
			floating: false,
			backgroundColor:
				Highcharts.defaultOptions.legend.backgroundColor || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false,
			itemStyle: {
				fontSize: '8px'
			}
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
	    series: [
			<?php 
			foreach ($fullFW as $key=>$value) {
	    		echo("{
	    	    name: '".$key."',
	    	    data: ".$value.",
	    	    },");
			}			
	    	?>
	    ]
	});

	Highcharts.chart('firstmonth', {
		chart: {
	    	type: 'column'
		},
		title: {
			text: 'LOGIN FOR USER REGISTERED A MONTH AGO'
		},
		xAxis: {
			categories: <?php echo($fullfecha); ?>
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total Drop Off'
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
		navigation: {
			buttonOptions: {
				enabled: false
			}
		},
		
		scrollbar: { enabled: false },
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 0,
			floating: false,
			backgroundColor:
				Highcharts.defaultOptions.legend.backgroundColor || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false,
			itemStyle: {
				fontSize: '8px'
			}
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
		series: [
			<?php 
			foreach ($fullFM as $key=>$value) {
			echo("{
				name: '".$key."',
				data: ".$value.",
				},");
			}			
			?>
		]
	});
	
	Highcharts.chart('aftermonth', {
		chart: {
	    	type: 'column'
		},
		title: {
			text: 'LOGIN FOR USER REGISTERED MORE THAN A MONTH AGO'
		},
		xAxis: {
			categories: <?php echo($fullfecha); ?>,
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total Drop Off'
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
		navigation: {
			buttonOptions: {
				enabled: false
			}
		},
		
		scrollbar: { enabled: false },
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 0,
			floating: false,
			backgroundColor:
				Highcharts.defaultOptions.legend.backgroundColor || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false,
			itemStyle: {
				fontSize: '8px'
			}
		},
		tooltip: {
			headerFormat: '<b>{point.x}</b><br/>',
			pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal} {point.percent}%'
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true
				}
			}
		},
		series: [
			<?php 
			foreach ($fullNM as $key=>$value) {
			echo("{
				name: '".$key."',
				data: ".$value.",
				},");
			}			
			?>
		]
	});

	$('#dtInstallations tbody').on('click', 'td.details-control', function () {
		    var tr = $(this).closest('tr');
		    var row = table.row(tr);
		    var rowData = row.data();

		   	if (row.child.isShown()) {
		        // This row is already open - close it
		        row.child.hide();
		        tr.removeClass('shown');
		
		        // Destroy the Child Datatable
		        $('#cl' + rowData[1]).DataTable().destroy();
		    }
		    else {
		        // Open this row
		        row.child(format(rowData)).show();
		        var id = rowData[1];
		        var firstID = rowData[2].toUpperCase();
				var salida = '<table id="'+id+'">';
				var data = second[firstID];

				console.log(id);
				console.log(firstID);
				console.log(data);
				console.log(second);
				

				jQuery.each(data, function (index, value) {
					salida = salida + '<tr>';
					salida = salida + '<td style="width:50px"></td>';
					salida = salida + '<td>&nbsp;</td>';
					salida = salida + '<td style="display:none;">' + id + '-2</th>';
					//salida = salida + '<td>' + rowData[2] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + index.toUpperCase() + '</td>';

					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + (value['PRIMERASEMANA']+value['PRIMERMES']+value['MASMES']) + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['PRIMERASEMANA'] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['PRIMERMES'] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['MASMES'] + '</td>';
					salida = salida + '</tr>';
					
				});
				salida = salida + '</table>';
		        $('#cl' + id).html(salida);
		        tr.addClass('shown');
		    }
		});

		// Return table with id generated from row's name field
	    function format(rowData) {
	        var childTable = '<table id="cl' + rowData[1] + '" class="display compact nowrap w-100" width="100%">' +
	            '<thead style="display:none"></thead >' +
	            '</table>';
	        return $(childTable).toArray();
	    }



	    $('#dtInstallations2 tbody').on('click', 'td.details-control', function () {
		    var tr = $(this).closest('tr');
		    var row = table2.row(tr);
		    var rowData = row.data();

		    if (row.child.isShown()) {
		        // This row is already open - close it
		        row.child.hide();
		        tr.removeClass('shown');
		
		        // Destroy the Child Datatable
		        $('#cl' + rowData[1]).DataTable().destroy();
		    }
		    else {
		        // Open this row
		        row.child(format(rowData)).show();
		        var id = rowData[1];
		        var firstID = rowData[2].toUpperCase();
				var salida = '<table id="'+id+'">';
				var data = second_inverter[firstID];

				jQuery.each(data, function (index, value) {

					console.log(index);
					console.log(value);
					
					salida = salida + '<tr>';
					salida = salida + '<td style="width:50px"></td>';
					salida = salida + '<td></td>';
					salida = salida + '<td style="display:none;">' + id + '-2</th>';
					//salida = salida + '<td>' + rowData[2] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + index.toUpperCase() + '</td>';

					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + (value['PRIMERASEMANA']+value['PRIMERMES']+value['MASMES']) + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['PRIMERASEMANA'] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['PRIMERMES'] + '</td>';
					salida = salida + '<td style="width:<?php echo($field_lenght_second); ?>">' + value['MASMES'] + '</td>';
					salida = salida + '</tr>';
					
				});
				salida = salida + '</table>';
		        $('#cl' + id).html(salida);
		        tr.addClass('shown');
		    }
		});

		// Return table with id generated from row's name field
	    function format(rowData) {
	        var childTable = '<table id="cl' + rowData[1] + '" class="display compact nowrap w-100" width="100%">' +
	            '<thead style="display:none"></thead >' +
	            '</table>';
	        return $(childTable).toArray();
	    }
	    
});


</script>


<style>
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
.row {
    width: 100%;
    margin-right: 0px;
    margin-left: 0px;
}
#container {
    height: 400px; 
}

.highcharts-figure, .highcharts-data-table table {
    min-width: 310px; 
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
.highcharts-legend-item {
	font-size:8px !important;
}



ul.source, ul.target {
  min-height: 50px;
  margin: 0px 25px 10px 0px;
  padding: 2px;
  border-width: 1px;
  border-style: solid;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  list-style-type: none;
  list-style-position: inside;
}
ul.source {
  border-color: #f8e0b1;
}
ul.target {
  border-color: #add38d;
}
.source li, .target li {
  margin: 5px;
  padding: 5px;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}
.source li {
  background-color: #fcf8e3;
  border: 1px solid #fbeed5;
  color: #c09853;
}
.target li {
  background-color: #ebf5e6;
  border: 1px solid #d6e9c6;
  color: #468847;
}
.sortable-dragging {
  border-color: #ccc !important;
  background-color: #fafafa !important;
  color: #bbb !important;
}
.sortable-placeholder {
  height: 40px;
}
.source .sortable-placeholder {
  border: 2px dashed #f8e0b1 !important;
  background-color: #fefcf5 !important;
}
.target .sortable-placeholder {
  border: 2px dashed #add38d !important;
  background-color: #f6fbf4 !important;
}


    table.dataTable.compact tbody th, table.dataTable.compact tbody td, table.dataTable.compact tfoot td {
        padding: 0px 0px 0px 0px;
    }

    td.details-control {
        background: url(https://www.datatables.net/examples/resources/details_open.png) no-repeat center center;
        cursor: pointer;
        width: 30px;
        transition: .5s;
    }

    tr.shown td.details-control {
        background: url(https://www.datatables.net/examples/resources/details_close.png) no-repeat center center;
        width: 30px;
        transition: .5s;
    }

    td.details-control1 {
        background: url(https://www.datatables.net/examples/resources/details_open.png) no-repeat center center;
        cursor: pointer;
        width: 40px;
        transition: .5s;
    }

    tr.shown td.details-control1 {
        background: url(https://www.datatables.net/examples/resources/details_close.png) no-repeat center center;
        width: 40px;
        transition: .5s;
    }

    td.details-control2 {
        background: url(https://www.datatables.net/examples/resources/details_open.png) no-repeat center center;
        cursor: pointer;
        width: 50px;
        transition: .5s;
    }

    tr.shown td.details-control2 {
        background: url(https://www.datatables.net/examples/resources/details_close.png) no-repeat center center;
        width: 50px;
        transition: .5s;
    }

    .fee-col {
        text-align: right;
    }

    .label-col {
        text-align: left;
    }

    tr.shown td {
        background-color: lightgrey !important;
        transition: .5s;
    }
    td.invoice-date {
        background-color: rgba(237, 205, 255, .2);
    }
    td.invoice-author {
        background-color: rgba(237, 205, 255, .2);
    }
    td.invoice-notes {
        background-color: rgba(237, 205, 255, .2);
    }




</style>

</html>

<?php 

function statsLoginFechaPais($iIDJuego,$cFechaIni,$cFechaFin){

	$cFechaIni = urlencode ($cFechaIni);
	$cFechaFin = urlencode ($cFechaFin);
	
	$funcion = "StatsLoginFechaPais";
	$params = array(
			'cLogin'=>'',
			'cPassword'=>'',
			'iIDJuego'=>$iIDJuego,
			'cFechaInicio'=>$cFechaIni,
			'cFechaFin'=>$cFechaFin,
	);

	$res = AppServer($funcion,$params,'unistats');
	
	$retorno['result'] = $res->result;
	$retorno['tLoginFecha'] = $res->tLoginFecha->tLoginFechaRow;
	return $retorno;
}

?>
