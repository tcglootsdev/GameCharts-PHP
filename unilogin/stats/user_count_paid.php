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
    $resumeGameData = statsPagosLoginFechaAmount($appid,$fechaOrigen,$fechaFinal);
    $fieldName = 'tPagosLoginFechaRow';

# SUM TOTALS
// >> None Offer ####################################################
$totales_cnt = array();
$totales_cnt['DIAS'] = array();
$totales_cnt['SEMANAS'] = array();
$totales_cnt['MESES'] = array();
$totalPAGOS_cnt = 0;
foreach ($resumeGameData[$fieldName] as $key => $value) {

    foreach ($value->DIAS as $key2 => $value2) {
        
        $totales_cnt['DIAS'][$key2] = $totales_cnt['DIAS'][$key2]*1 + $value2*1;
    }
    
    foreach ($value->SEMANAS as $key2 => $value2) {
        
        $totales_cnt['SEMANAS'][$key2] = $totales_cnt['SEMANAS'][$key2]*1 + $value2*1;
    }
    
    foreach ($value->MESES as $key2 => $value2) {
        
        $totales_cnt['MESES'][$key2] = $totales_cnt['MESES'][$key2]*1 + $value2*1;
    }
    $totalPAGOS_cnt = $totalPAGOS_cnt*1 + $value->PRIMERASEMANA*1
    			+ $value->PRIMERMES*1 + $value->MASMES*1;
}
/* <<== End of None Offer*/

/* ==>> Start of Offer Section */
$totales_cnt_offer = array();
$totales_cnt_offer['OFFER_DIAS'] = array();
$totales_cnt_offer['OFFER_SEMANAS'] = array();
$totales_cnt_offer['OFFER_MESES'] = array();
$totalPAGOS_cnt_offer = 0;
foreach ($resumeGameData[$fieldName] as $key => $value) {

    foreach ($value->OFFER_DIAS as $key2 => $value2) {
        
        $totales_cnt_offer['OFFER_DIAS'][$key2] = $totales_cnt_offer['OFFER_DIAS'][$key2]*1 + $value2*1;
    }
    
    foreach ($value->OFFER_SEMANAS as $key2 => $value2) {
        
        $totales_cnt_offer['OFFER_SEMANAS'][$key2] = $totales_cnt_offer['OFFER_SEMANAS'][$key2]*1 + $value2*1;
    }
    
    foreach ($value->OFFER_MESES as $key2 => $value2) {
        
        $totales_cnt_offer['OFFER_MESES'][$key2] = $totales_cnt_offer['OFFER_MESES'][$key2]*1 + $value2*1;
    }
    $totalPAGOS_cnt_offer = $totalPAGOS_cnt_offer*1 + $value->OFFER_PRIMERASEMANA*1
    			+ $value->PAGOS_OFFER_PRIMERMES*1 + $value->OFFER_MASMES*1;
}
/* <<== End of Offer Section*/


// FULLDATA FOR CHART
$fullfecha_aux_cnt = array();
$fullFW_aux_cnt = array();
$fullFM_aux_cnt = array();
$fullNM_aux_cnt = array();

$fullFW_offer_aux_cnt = array();
$fullFM_offer_aux_cnt = array();
$fullNM_offer_aux_cnt = array();

// FULLDATA
if (isset($resumeGameData['tPagosLoginFechaRow'])) {
    
    $temporal_cnt = (array)$resumeGameData['tPagosLoginFechaRow'];
    $fileResult_cnt = array();
    foreach ($temporal_cnt as $data)
    {
        $fullfecha_aux_cnt[] = $data->FECHA;
        $fullFW_aux_cnt[] = $data->PRIMERASEMANA*1;
        $fullFM_aux_cnt[] = $data->PRIMERMES*1;
        $fullNM_aux_cnt[] = $data->MASMES*1;

        $fullFW_offer_aux_cnt[] = $data->OFFER_PRIMERASEMANA*1;
        $fullFM_offer_aux_cnt[] = $data->OFFER_PRIMERMES*1;
        $fullNM_offer_aux_cnt[] = $data->OFFER_MASMES*1;
    }
    $fullfecha_cnt = json_encode($fullfecha_aux_cnt);
    $fullFW_cnt = json_encode($fullFW_aux_cnt);
    $fullFM_cnt = json_encode($fullFM_aux_cnt);
    $fullNM_cnt = json_encode($fullNM_aux_cnt);
    
    $fullFW_Offer_cnt = json_encode($fullFW_offer_aux_cnt);
    $fullFM_Offer_cnt = json_encode($fullFM_offer_aux_cnt);
    $fullNM_Offer_cnt = json_encode($fullNM_offer_aux_cnt);
}
#########################################################################

#########################################################################
# WEBSERVICES FUNTCIONS
#########################################################################
function statsPagosLoginFechaAmount($appid,$fechaOrigen,$fechaFinal,$groupby='1'){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $iAgrupado = urlencode ($groupby);
    
    $funcion = "StatsPagosLoginFecha";
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
    if (isset($res->tPagosLoginFecha->tPagosLoginFechaRow) && is_array($res->tPagosLoginFecha->tPagosLoginFechaRow)) {
        $retorno['tPagosLoginFechaRow'] = (array)$res->tPagosLoginFecha->tPagosLoginFechaRow;
    }
    else {
        $retorno['tPagosLoginFechaRow'][0] = $res->tPagosLoginFecha->tPagosLoginFechaRow;
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

<!-- >>  User Count Graph  -->
<div class="row">

	<div class="col-sm-12">
		<div id="global-cnt" style="height: 400px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	
</div>
<!-- << end of User Count Graph -->

<!--  User Count Table -->
<div class="row">
	<div class="col-sm-12">	
	
		<table id="dtDropOff-cnt" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
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
		  		
		  		$totalPAGOS = $totalPAGOS*1 == 0 ? 1 : $totalPAGOS;
		  		$totalPAGOS_offer = $totalPAGOS_offer*1 == 0 ? 1 : $totalPAGOS_offer;  
  		  	?>
		  	
		  	<tr>

		  		<th>
		  			<div>NOffer</div>
		  			<div class="boder">Offer</div>
		  		</th>

		  		<?php for($i=0; $i<7; $i++){?>
		  		<th>
		  			<div>
		  			<?php echo(number_format(($totales_cnt['DIAS'][$i]/$totalPAGOS_cnt) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt['DIAS'][$i].' Users)</span>'); ?>
		  			</div>
		  			<div class="boder">
		  			<?php echo(number_format(($totales_cnt_offer['DIAS'][$i]/$totalPAGOS_cnt_offer) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt_offer['DIAS'][$i].' Users)</span>'); ?>
		  			</div>
		  		</th>		  			
		  		<?php }?>	

          <?php for($i=1; $i<=3; $i++){ ?>
		  		<th>
		  			<div>
		  			<?php echo(number_format(($totales_cnt['SEMANAS'][$i]/$totalPAGOS_cnt) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt['SEMANAS'][$i].' Users)</span>'); ?>
		  			</div>
		  			<div class="boder">
		  			<?php echo(number_format(($totales_cnt_offer['SEMANAS'][$i]/$totalPAGOS_cnt_offer) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt_offer['SEMANAS'][$i].' Users)</span>'); ?>
		  			</div>
		  		</th>
          <?php } ?>

		  		<?php for($i=1; $i<6; $i++){?>
		  		<th>
		  			<div>
		  			<?php echo(number_format(($totales_cnt['MESES'][$i]/$totalPAGOS_cnt) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt['MESES'][$i].' Users)</span>'); ?>	  				
		  			</div>
		  			<div class="boder">
		  			<?php echo(number_format(($totales_cnt_offer['MESES'][$i]/$totalPAGOS_cnt_offer) * 100,2).'% <br/><span style="font-size:9px">('.$totales_cnt_offer['MESES'][$i].' Users)</span>'); ?>		  				
		  			</div>
		  		</th>		  			
		  		<?php }?>		  		
		  	</tr>
		  
		  	<?php } ?>
		   
		  </thead>
  		  <tbody>
	  	<?php 
	  	
	  	if (isset($resumeGameData[$fieldName]) && count($resumeGameData[$fieldName])) {
	  	    
	  	    $today = mktime (0,0,0,date('m'),date('d'),date("Y"));
	  	    foreach ($resumeGameData[$fieldName] as $key => $value) {
	  	        
  	        echo('<tr>');

				// $day_total = $value->NPAGOS*1;
				$day_total = $value->PRIMERASEMANA*1 + $value->PRIMERMES*1 + $value->MASMES*1;

				// $day_total_offer = $value->NOFERTAS*1;
				$day_total_offer = $value->OFFER_PRIMERASEMANA*1 + $value->OFFER_PRIMERMES*1 + $value->OFFER_MASMES*1;

  	            echo('<td>'.$value->FECHA.'<br/><div class="boder"><span style="font-size:9px">'.$day_total.' Users </div><div class="boder"><span style="font-size:9px">'.$day_total_offer.' Users</span></div></td>');
  	            
  	            $i=0;
  	            foreach ($value->DIAS as $key2 => $value2) {
  	                
  	                $valor = $day_total*1 == 0 ? 0 : number_format(($value2*1 / $day_total)*100,2);

  	                $value2_offer = $value->OFFER_DIAS[$key2]*1;

  	                $valor_offer = $day_total_offer*1 == 0 ? 0 : number_format(($value2_offer / $day_total_offer)*100,2);
  	                
  	                if (isset($value2)) {
  	                    foreach ($_COLOR as $keyColor => $valueColor) {
  	                        if ($valor >= $keyColor) {
  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
  	                            $color = $valueColor;
  	                            break;
  	                        }
  	                    }
  	                    echo('<td>');
  	                    echo ('<div style="background-color: '.$background_color.';color: '.$color.'">');
  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' Users)</span></div>');
  	                }

  	                if(isset($value2_offer)){
  	                	foreach ($_COLOR as $keyColor => $valueColor) {
  	                	    if ($valor_offer >= $keyColor) {
  	                	        $background_color_offer = $_BACKGROUNDCOLOR[$keyColor];
  	                	        $color_offer = $valueColor;
  	                	        break;
  	                	    }
  	                	}
  	                    echo ('<div class="boder" style="background-color: '.$background_color_offer.';color: '.$color_offer.'">');
  	                    echo($valor_offer.' % <br/><span style="font-size:9px">('.$value2_offer.' Users)</span></div>');  	                    
  	                    echo('</td>');
  	                }
  	                
  	                $i++;
  	                if ($i>6) {
  	                    break;
  	                }
  	            }
  	            
  	            $i=0;
  	            foreach ($value->SEMANAS as $key2 => $value2) {
  	                
  	                if ($i==0) { 
  	                    $i++;
  	                    continue; 
  	                }
  	                
  	                $valor = $day_total*1 == 0 ? 0 : number_format(($value2*1 / $day_total)*100,2);

  	                $value2_offer = $value->OFFER_SEMANAS[$key2]*1;

  	                $valor_offer = $day_total_offer*1 == 0 ? 0 : number_format(($value2_offer / $day_total_offer)*100,2);
  	                
  	                if (isset($value2)) {
  	                    foreach ($_COLOR as $keyColor => $valueColor) {
  	                        if ($valor >= $keyColor) {
  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
  	                            $color = $valueColor;
  	                            break;
  	                        }
  	                    }
  	                    echo('<td>');
  	                    echo ('<div style="background-color: '.$background_color.';color: '.$color.'">');
  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' Users)</span></div>');
  	                }

  	                if(isset($value2_offer)){
  	                	foreach ($_COLOR as $keyColor => $valueColor) {
  	                	    if ($valor_offer >= $keyColor) {
  	                	        $background_color_offer = $_BACKGROUNDCOLOR[$keyColor];
  	                	        $color_offer = $valueColor;
  	                	        break;
  	                	    }
  	                	}
  	                    echo ('<div class="boder" style="background-color: '.$background_color_offer.';color: '.$color_offer.'">');
  	                    echo($valor_offer.' % <br/><span style="font-size:9px">('.$value2_offer.' Users)</span></div>');  	                    
  	                    echo('</td>');
  	                }
  	                
  	                $i++;
  	                if ($i>3) {
  	                    break;
  	                }
  	            }
  	            
  	            $i=0;
  	            foreach ($value->MESES as $key2 => $value2) {
  	                
  	                if ($i==0) { 
  	                    $i++;
  	                    continue; 
  	                }
  	                
  	                $valor = $day_total*1 == 0 ? 0 : number_format(($value2*1 / $day_total)*100,2);

  	                $value2_offer = $value->OFFER_MESES[$key2]*1;

  	                $valor_offer = $day_total_offer*1 == 0 ? 0 : number_format(($value2_offer / $day_total_offer)*100,2);
  	                
  	                if (isset($value2)) {
  	                    foreach ($_COLOR as $keyColor => $valueColor) {
  	                        if ($valor >= $keyColor) {
  	                            $background_color = $_BACKGROUNDCOLOR[$keyColor];
  	                            $color = $valueColor;
  	                            break;
  	                        }
  	                    }
  	                    echo('<td>');
  	                    echo ('<div style="background-color: '.$background_color.';color: '.$color.'">');
  	                    echo($valor.' % <br/><span style="font-size:9px">('.$value2.' Users)</span></div>');
  	                }

  	                if(isset($value2_offer)){
  	                	foreach ($_COLOR as $keyColor => $valueColor) {
  	                	    if ($valor_offer >= $keyColor) {
  	                	        $background_color_offer = $_BACKGROUNDCOLOR[$keyColor];
  	                	        $color_offer = $valueColor;
  	                	        break;
  	                	    }
  	                	}
  	                    echo ('<div class="boder" style="background-color: '.$background_color_offer.';color: '.$color_offer.'">');
  	                    echo($valor_offer.' % <br/><span style="font-size:9px">('.$value2_offer.' Users)</span></div>');  	                    
  	                    echo('</td>');
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

		window.location.href = "./user_payamount_paid.php?appid="+appid+"&from="+fechaDesde+"&to="+fechaHasta;
		
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


  // User count Graph
	Highcharts.chart('global-cnt', {

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
            text: 'Count of Users Graph Based on they paid date'
        },
        xAxis: {
            categories: <?php echo($fullfecha_cnt); ?>
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count of Users'
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
        series: [
            {
                name: 'More than a Month (Noffer)',
                data: <?php echo($fullNM_cnt); ?>,
                stack: "noffer"       
            }, {
                name: 'First Month (Noffer)',
                data: <?php echo($fullFM_cnt); ?>,
                stack: "noffer" 
            }, {
                name: 'First Week (Noffer)',
                data: <?php echo($fullFW_cnt); ?>,
                stack: "noffer" 
            },
            {
                name: 'More than a Month (Offer)',
                data: <?php echo($fullNM_Offer_cnt); ?>,
                stack: "offer"       
            }, {
                name: 'First Month (Offer)',
                data: <?php echo($fullFM_Offer_cnt); ?>,
                stack: "offer" 
            }, {
                name: 'First Week (Offer)',
                data: <?php echo($fullFW_Offer_cnt); ?>,
                stack: "offer" 
            }
        ]
        

    });

	// LOAD TABLE
	var table = $('#dtDropOff-cnt').DataTable({"bFilter": false,"bPaginate": false,"bInfo": false});
	
	
});
</script>
<style type="text/css">
	.boder{
		border-top: 2px solid #dddddd;
	}
</style>

</html>
