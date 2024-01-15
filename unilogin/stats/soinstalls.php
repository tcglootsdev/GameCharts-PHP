<?php

	ini_set('display_errors',false);
	date_default_timezone_set("Europe/Madrid");
	include "../AppServer.php";
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
	
	
	$weekAgo = mktime(0,0,0,date("m"),date("d")-8);
	$fechaOrigen = date('d-m-Y', $weekAgo);
	if (isset($_GET['from']) && !empty($_GET['from'])) {
		$fechaOrigen = $_GET['from'];
	}
	
	$fechaFinal = date('d-m-Y');
	if (isset($_GET['to']) && !empty($_GET['to'])) {
		$fechaFinal = $_GET['to'];
	}

	// OBTENEMOS LOS DATOS DEL JUEGO
	$gamedata = statsInstalacionesPreFecha($fechaOrigen,$fechaFinal);
	
?>
<html>

<head><script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script></head>

<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>


		<table id="dtInstallations" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
		  <thead>
		    <tr>
		      <th class="th-sm">Operating System</th>
		      <th class="th-sm">INNO Init</th>
		      <th class="th-sm">INNO End</th>
		      <th class="th-sm">Updater Init</th>
		      <th class="th-sm">Updater End</th>
		    </tr>
		  </thead>
  		  <tbody>

  		  	<?php 
  		  	
  		  	foreach($gamedata['tSO'] as $data) {

				echo('<tr>');
				echo('<th>'.$data->SO.'</th>');
				echo('<td>'.$data->INNOINI.'</th>');
				echo('<td>'.$data->INNOFIN.'</th>');
				echo('<td>'.$data->UPDATERINI.'</th>');
				echo('<td>'.$data->UPDATERFIN.'</th>');
				echo('</tr>');

			}
			?>
		  </tbody>
		</table>	
	
</body>

<script>

$( document ).ready(function() {
	$('#dtInstallations').DataTable();
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
</style>

</html>

<?php 

function statsInstalacionesPreFecha($cFechaIni,$cFechaFin){

	$cFechaIni = urlencode ($cFechaIni);
	$cFechaFin = urlencode ($cFechaFin);
	
	$funcion = "StatsInstalacionesPreFecha";
	$params = array(
			'cFechaIni'=>$cFechaIni,
			'cFechaFin'=>$cFechaFin,
	);

	$res = AppServer($funcion,$params,'unistats');

	$retorno['result'] = $res->result;
	$retorno['tSO'] = $res->tSO->tSORow;
	return $retorno;
}

?>
