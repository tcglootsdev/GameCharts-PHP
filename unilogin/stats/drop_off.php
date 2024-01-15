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

$_BACKGROUNDCOLOR = array('100' => '#00aa00',
    '90' => '#00b400',
    '80' => '#00c900',
    '70' => '#00db00',
    
    '60' => '#00e600',
    '50' => '#00f000',
    '40' => '#3eff3e',
    '30' => '#65ff65',
    
    '25' => '#f03d02',
    '20' => '#fd4102',
    '15' => '#fe602b',
    '10' => '#fe8c67',
    '5' => '#febba5',

    '4' => '#ff4747',
    '3' => '#ff2424',
    '2' => '#f20000',
    '1' => '#e10000',
    '0' => '#c20000',
    );
$_COLOR = array('100' => '#fff',
    '90' => '#fff',
    '80' => '#fff',
    '70' => '#fff',
    '60' => '#000',
    '50' => '#000',
    '40' => '#000',
    '30' => '#000',
    
    '25' => '#fff',
    '20' => '#fff',
    '15' => '#000',
    '10' => '#000',
    '5' => '#000',
    
    '4' => '#000',
    '3' => '#000',
    '2' => '#fff',
    '1' => '#fff',
    '0' => '#fff');
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
    
    $source = '';
    if (isset($_GET['source']) && !empty($_GET['source'])) {
        $source = $_GET['source'];
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

$country = '';
if (isset($_GET['country']) && !empty($_GET['country'])) {
    $country = $_GET['country'];
}

$scope = 'daily';
if (isset($_GET['scope']) && !empty($_GET['scope'])) {
    $scope = $_GET['scope'];
}

$groupby = 'daily';
if (isset($_GET['groupby']) && !empty($_GET['groupby'])) {
    $groupby = $_GET['groupby'];
}
#########################################################################


#########################################################################
# GET RESUME GAME DATA

# USING GROUPBY TO GET DATA
if (!isset($groupby) || empty($groupby) || $groupby=='daily') {
    $resumeGameData = statsUserDropOffFecha($appid,$fechaOrigen,$fechaFinal,$country);
    $fieldName = 'tDropOffFecha';
}
else if ($groupby=='weekly') {
    $resumeGameData = statsUserDropOffSemana($appid,$fechaOrigen,$fechaFinal,$country);
    $fieldName = 'tDropOffSemana';
}
else if ($groupby=='monthly') {
    $resumeGameData = statsUserDropOffMes($appid,$fechaOrigen,$fechaFinal,$country);
    $fieldName = 'tDropOffMes';
}

# USING SCOPE TO KNOW WICH DATA GET
if (!isset($scope) || empty($scope) || $scope=='daily') {
    $dropObject = 'TOTAL_DROPOFF_FECHA';
    $chartKey = 'Day';
}
else if ($scope=='weekly') {
    $dropObject = 'TOTAL_DROPOFF_SEMANA';
    $chartKey = 'Week';
}
else {
    $dropObject = 'TOTAL_DROPOFF_MES';
    $chartKey = 'Month';
}

# SUM TOTALS
$totales = array();
foreach ($resumeGameData[$fieldName] as $key => $value) {
    foreach ($value->$dropObject as $key2 => $value2) {
        $totales[$key2] = $totales[$key2] + $value2;
    }
}

// FULLDATA FOR CHART
$count = 0;
if (isset($totales)) {
    
    $fileResult = array();
    foreach ($totales as $key => $data)
    {
        
        $aux = array();
        if (!isset($_GET['scope']) || empty($_GET['scope']) || empty($scope) || ($scope == 'daily')) {
            $aux[] = 'Day '.$key;
        } else if ($scope == 'weekly') {
            $aux[] = 'Week '.$key;
        } else if ($scope == 'monthly') {
            $aux[] = 'Month '.$key;
        }
        $aux[] = (int)$data;
        $fileResult[] = $aux;
        $count++;
        if ($count > 15) {
            break;
        }
    }
    $fulldata_aux = $fileResult;
}
#########################################################################

#########################################################################
# WEBSERVICES FUNTCIONS
#########################################################################
function statsUserDropOffFecha($appid,$fechaOrigen,$fechaFinal,$country='ES'){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $cPais = urlencode ($country);
    
    $funcion = "StatsUserDropOffFecha";
    $params = array(
        'cLogin'=>'',
        'cPassword'=>'',
        'iIDJuego'=>$appid,
        'cFechaInicio'=>$cFechaIni,
        'cFechaFin'=>$cFechaFin,
        'cPais'=>$cPais,
        'cUtm_Source'=>'',
        'cUtm_Medium'=>'',
        'cUtm_Campaign'=>''        
    );
    
    $res = AppServer($funcion,$params,'unistats');

    $retorno['result'] = $res->result;
    if (isset($res->tDropOffFecha->tDropOffFechaRow) && is_array($res->tDropOffFecha->tDropOffFechaRow)) {
        $retorno['tDropOffFecha'] = (array)$res->tDropOffFecha->tDropOffFechaRow;
    }
    else {
        $retorno['tDropOffFecha'][0] = $res->tDropOffFecha->tDropOffFechaRow;
    }
    return $retorno;
}

function statsUserDropOffSemana($appid,$fechaOrigen,$fechaFinal,$country='ES'){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $cPais = urlencode ($country);
    
    $funcion = "StatsUserDropOffSemana";
    $params = array(
        'cLogin'=>'',
        'cPassword'=>'',
        'iIDJuego'=>$appid,
        'cFechaInicio'=>$cFechaIni,
        'cFechaFin'=>$cFechaFin,
        'cPais'=>$cPais,
        'cUtm_Source'=>'',
        'cUtm_Medium'=>'',
        'cUtm_Campaign'=>''
    );
    
    $res = AppServer($funcion,$params,'unistats');
    
    $retorno['result'] = $res->result;
    if (isset($res->tDropOffSemana->tDropOffSemanaRow) && is_array($res->tDropOffSemana->tDropOffSemanaRow)) {
        $retorno['tDropOffSemana'] = (array)$res->tDropOffSemana->tDropOffSemanaRow;
    }
    else {
        $retorno['tDropOffSemana'][0] = $res->tDropOffSemana->tDropOffSemanaRow;
    }
    return $retorno;
}

function statsUserDropOffMes($appid,$fechaOrigen,$fechaFinal,$country='ES'){
    
    $cFechaIni = urlencode ($fechaOrigen);
    $cFechaFin = urlencode ($fechaFinal);
    $cPais = urlencode ($country);
    
    $funcion = "StatsUserDropOffMes";
    $params = array(
        'cLogin'=>'',
        'cPassword'=>'',
        'iIDJuego'=>$appid,
        'cFechaInicio'=>$cFechaIni,
        'cFechaFin'=>$cFechaFin,
        'cPais'=>$cPais,
        'cUtm_Source'=>'',
        'cUtm_Medium'=>'',
        'cUtm_Campaign'=>''
    );
    
    $res = AppServer($funcion,$params,'unistats');
    
    $retorno['result'] = $res->result;
    if (isset($res->tDropOffMes->tDropOffMesRow) && is_array($res->tDropOffMes->tDropOffMesRow)) {
        $retorno['tDropOffMes'] = (array)$res->tDropOffMes->tDropOffMesRow;
    }
    else {
        $retorno['tDropOffMes'][0] = $res->tDropOffMes->tDropOffMesRow;
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
		<button type="button" class="btn btn-primary btn-lg" id="search" name="search">UPDATE FILTERS</button>
	</div>
	
	<!--  DATE RANGE PICKER -->
	<div class="col-sm-3">
	
	
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
	
	<!-- PAISES -->
	<div class="col-sm-2">
	
		<div class="form-group" id="country_filter" <?php if (isset($_REQUEST['country']) && !empty($_REQUEST['country']) && ($pais != 'XX')) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($country); ?></strong>
	                
	                <button type="button" class="close" id="country_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="country_select" <?php if (isset($_REQUEST['country']) && !empty($_REQUEST['country']) && ($pais != 'XX')) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			<label for="usr">Country:</label><br/>
			<select id="selectpicker_country" class="selectpicker countrypicker" data-flag="true" data-default="ES"></select>
		</div>
	
	</div>
	
	<!-- APPID -->
	<div class="col-sm-1">
	
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
	
	
	<!-- SOURCE -->
	<div class="col-sm-1">
	
		<div class="form-group" id="source_filter" <?php if (isset($_REQUEST['source']) && !empty($_REQUEST['source'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($source); ?></strong>
	                
	                <button type="button" class="close" id="source_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="source_select" <?php if (isset($_REQUEST['source']) && !empty($_REQUEST['source'])) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			<div class="form-group">
  				<label for="usr">Source:</label>
  				<input type="text" class="form-control" id="selectpicker_source" value="<?php echo($source); ?>">
			</div>
		</div>
	
	</div>
	
    <!-- MEDIUM -->
	<div class="col-sm-1">
	
		<div class="form-group" id="medium_filter" <?php if (isset($_REQUEST['medium']) && !empty($_REQUEST['medium'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($medium); ?></strong>
	                
	                <button type="button" class="close" id="medium_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="medium_select" <?php if (isset($_REQUEST['medium']) && !empty($_REQUEST['medium'])) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			<div class="form-group">
  				<label for="usr">Medium:</label>
  				<input type="text" class="form-control" id="selectpicker_medium" value="<?php echo($medium); ?>">
			</div>
		</div>
	
	</div>
	
	<!-- SCOPE -->
	<div class="col-sm-1">
	
		<div class="form-group" id="scope_filter" <?php if (isset($_REQUEST['scope']) && !empty($_REQUEST['scope'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($scope); ?></strong>
	                
	                <button type="button" class="close" id="scope_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="scope_select" <?php if (isset($_REQUEST['scope']) && !empty($_REQUEST['scope'])) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			
			<div class="form-group">
  				<label for="usr">Scope:</label>
  				<select class="form-control" id="selectpicker_scope">
                  <option value="daily" <?php if($scope=='daily'){echo('selected=');} ?>>daily</option>
                  <option value="weekly" <?php if($scope=='weekly'){echo('selected=');} ?>>weekly</option>
                  <option value="monthly" <?php if($scope=='monthly'){echo('selected=');} ?>>monthly</option>
                </select>
			</div>
			
		</div>
	
	</div>
	
	<!-- GROUP BY -->
	<div class="col-sm-1">
	
		<div class="form-group" id="groupby_filter" <?php if (isset($_REQUEST['groupby']) && !empty($_REQUEST['groupby'])) { echo('style="display:block"'); }else{ echo('style="display:none"'); } ?>>
	        <div class="row">
	            <div class="col-md-12 alert alert-success">
	                
	                <strong><?php echo($groupby); ?></strong>
	                
	                <button type="button" class="close" id="groupby_close_filter" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
	                
	            </div>
	        </div>
	    </div>
	
		<div id="groupby_select" <?php if (isset($_REQUEST['groupby']) && !empty($_REQUEST['groupby'])) { echo('style="display:none"'); }else{ echo('style="display:block"'); } ?>>
			
			<div class="form-group">
  				<label for="usr">Group by:</label>
  				<select class="form-control" id="selectpicker_groupby">
                  <option value="daily" <?php if($groupby=='daily'){echo('selected=');} ?>>daily</option>
                  <option value="weekly" <?php if($groupby=='weekly'){echo('selected=');} ?>>weekly</option>
                  <option value="monthly" <?php if($groupby=='monthly'){echo('selected=');} ?>>monthly</option>
                </select>
			</div>
			
		</div>
	
	</div>
	
	
</div>

<!--  GRAFICA  -->
<div class="row">

	<div class="col-sm-12">
		<div id="global" style="height: 200px; min-width: 310px; border: 2px solid black; padding:10px; margin:10px;"></div>
	</div>
	
</div>

<!--  TABLA -->
<div class="row">
	<div class="col-sm-12">	
	
		<table id="dtDropOff" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
		  <thead>
		  
		  	<tr>
		  		<th></th>
		  		<th style="min-width: 75px;"><?php echo($chartKey); ?> 0</th>
		  		<?php 
		  		for ($i = 1; $i < 16; $i++) {
                    echo('<th>'.$chartKey.' '.$i.'</th>');    
		  		}
		  		?>
		  	</tr>
		  	
		  	<?php 
  		  	
		  	if (isset($resumeGameData[$fieldName]) && count($resumeGameData[$fieldName])) {
  		  	    
  		  	?>
		  	
		  	<tr>
		  		<th></th>
		  		<th><?php echo(number_format(($totales[0]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[0].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[1]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[1].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[2]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[2].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[3]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[3].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[4]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[4].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[5]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[5].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[6]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[6].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[7]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[7].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[8]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[8].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[9]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[9].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[10]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[10].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[11]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[11].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[12]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[12].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[13]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[13].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[14]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[14].' users)</span>'); ?></th>
		  		<th><?php echo(number_format(($totales[15]/$totales[0]) * 100,2).'% <br/><span style="font-size:9px">('.$totales[15].' users)</span>'); ?></th>
		  	</tr>
		  
		  	<?php } ?>
		   
		  </thead>
  		  <tbody>

  		  	<?php 
  		  	
  		  	if (isset($resumeGameData[$fieldName]) && count($resumeGameData[$fieldName])) {
  		  	    
  		  	    $today = mktime (0,0,0,date('m'),date('d'),date("Y"));
  		  	    foreach ($resumeGameData[$fieldName] as $key => $value) {
  		  	        
  		  	        if (!isset($_GET['groupby']) || empty($_GET['groupby']) || empty($groupby) || ($groupby == 'daily')) {
    	  	            $aux = explode('-',$value->FECHA);
  		  	        } else if ($groupby=='weekly') {
  		  	            $aux = explode('-',$value->DESDE);
  		  	        }
  		  	        else {
  		  	            $aux = array($value->ANYO,$value->MES,'1');
  		  	        }
  		  	        $valor = array();
    	  	        foreach ($value->$dropObject as $key2 => $value2) {
    	  	            if (!isset($_GET['scope']) || empty($_GET['scope']) || empty($scope) || ($scope == 'daily')) {
    	  	                $auxtime = mktime (0,0,0,$aux[1],$aux[2]+$key2,$aux[0]);
    	  	            } else if ($scope == 'weekly') {
    	  	                $auxtime = mktime (0,0,0,$aux[1],$aux[2]+(7*$key2),$aux[0]);
    	  	            } else {
    	  	                $auxtime = mktime (0,0,0,$aux[1]+$key2,$aux[2],$aux[0]);
    	  	            }
    	  	            if ($auxtime > $today) {
    	  	                $valor[$key2] = '';
    	  	            }
    	  	            else
    	  	            {
    	  	                $valor[$key2] = number_format(($value->$dropObject[$key2]/$value->TOTAL_INSTALADOS) * 100,2);
    	  	            }
    	  	        }
    	  	        
    	  	        
    	  	        echo('<tr>');
    		
    	  	            if (!isset($_GET['groupby']) || empty($_GET['groupby']) || empty($groupby) || ($groupby == 'daily')) {
    				        echo('<td>'.$value->FECHA.'<br/><span style="font-size:9px">('.$value->TOTAL_INSTALADOS.' new users)</span><br/></td>');
    	  	            } else if ($groupby == 'weekly') {
    	  	                echo('<td>'.$value->DESDE.'<br/>'.$value->HASTA.'<br/><span style="font-size:9px">('.$value->TOTAL_INSTALADOS.' new users)</span><br/></td>');
    	  	            } else if ($groupby == 'monthly') {
    	  	                echo('<td>'.$value->MES.'-'.$value->ANYO.'<br/><span style="font-size:9px">('.$value->TOTAL_INSTALADOS.' new users)</span><br/></td>');
    	  	            }
    	  	            
    				    
    				    
    				    $contador = '0';

    				    foreach ($valor as $key2 => $value2) {
    				        
    				        if (isset($value)) {
    				            if (empty($value2)) {
    				                echo ('<td></td>');
    				            }
    				            else {
        				            foreach ($_COLOR as $keyColor => $valueColor) {
        				                if ($value2 >= $keyColor) {
        				                    $background_color = $_BACKGROUNDCOLOR[$keyColor];
        				                    $color = $valueColor;
        				                    break;
        				                }
        				            }
        				            echo ('<td style="background-color: '.$background_color.';color: '.$color.'">');
        				            echo($valor[$key2].' % <br/><span style="font-size:9px">('.$value->$dropObject[$key2].' users)</span></td>');
    				            }
    				        }
    				        
    				        $contador ++;
    				        if ($contador > 15) {
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
		groupby = $('#selectpicker_groupby').val();
		appid = $('#selectpicker_appid').val();

		if ((pais == null) || (pais == 'XX')) {
			pais = '';
		}
		if (fecha == today) {
			fecha = '';
		}

		window.location.href = "./drop_off.php?appid="+appid+"&from="+fechaDesde+"&to="+fechaHasta+"&country="+pais+"&source="+source+"&medium="+medium+"&scope="+scope+"&groupby="+groupby;
		
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

    $('#groupby_close_filter').click(function(){
		$('#groupby_filter').hide();
		$('#groupby_select').show();
    });

    $('#appid_close_filter').click(function(){
		$('#appid_filter').hide();
		$('#appid_select').show();
    });


    // GRAPHIC
	Highcharts.stockChart('global', {


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
		
        series: [{
            name: 'Login',
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
			type: 'lineal',
			labels: {
                format: '<?php echo($chartKey); ?> {value}'
            },
          },
    });

	// LOAD TABLE
	var table = $('#dtDropOff').DataTable({"bFilter": false,"bPaginate": false,"bInfo": false});
	
	
});
</script>

</html>
