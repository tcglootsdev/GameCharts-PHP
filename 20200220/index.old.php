<?php

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

	
	// REVISAMOS FICHEROS EN LA CARPETA
	$ficheros = scandir($dataURL);
	$join_ficheros = array();
	foreach ($ficheros as $nombre_fichero) {
	
		if ( ($nombre_fichero != ".") && ($nombre_fichero != "..") && ($nombre_fichero != $hoy.".json") && ($nombre_fichero != "fulldata.json") ) {

			$nombre = str_replace('.json','',$nombre_fichero);
			$join_ficheros[] = $nombre;
		
		}
	}
	
	
	// OBTENEMOS LOS DATOS DE FULLDATA
	$fulldata = str_replace(',[0000,0]','',file_get_contents($dataURL."fulldata.json"));
	$fulldata_aux = json_decode($fulldata,true);
	$url = $dataURL."fulldata.json";
	if (!isset($fulldata_aux) || empty($fulldata_aux)) {
		$fulldata_aux = array();
	}
	
	echo("<pre>");
		var_dump($fulldata);
		var_dump($fulldata_aux);
	echo("</pre>");
	die;
	
	
	// SI HAY MAS FICHEROS LOS UNIMOS A FULLDATA
	if (count($join_ficheros)) {
		foreach ($join_ficheros as $fichero) {
		
			
			$file = file_get_contents($dataURL.$fichero.".json");
			$fileData = json_decode($file);
			$fileResult = array();
			foreach ($fileData as $data) {
			
				$aux = array();
				$aux2 = explode(' ',$data->DateTime);
				$auxDate = explode('/',$aux2[0]);
				$auxHour = explode(':',$aux2[1]);
				
				$aux[] = (int)mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2]).'000';
				$aux[] = (int)$data->Ccu;
				
				$fileResult[] = $aux;
			}
		
			$fulldata_result = array_merge($fulldata_aux, $fileResult);
			
			$fulldata_aux = $fulldata_result;
			//unlink($dataURL.$fichero.".json");
		}
		$file = fopen($url, 'w+');
		$data = str_replace('"','',json_encode($fulldata_aux));
		fwrite ($file, $data);
	}
	
	// UNIMOS EL FICHERO DE HOY A FULLDATA
	$fileHoy = file_get_contents($dataURL.$hoy.".json");
	$fileData = json_decode($fileHoy);
	if (isset($fileData) && !empty($fileData)) {
		$fileResult = array();
		foreach ($fileData as $data) 
		{
			$aux = array();
			$aux2 = explode(' ',$data->DateTime);
			$auxDate = explode('/',$aux2[0]);
			$auxHour = explode(':',$aux2[1]);
			
			$aux[] = (int)mktime($auxHour[0],'0','0',$auxDate[1],$auxDate[0],$auxDate[2]).'000';
			$aux[] = (int)$data->Ccu;
			
			$fileResult[] = $aux;
		}
		$fulldata_result = array_merge($fulldata_aux, $fileResult);
		$fulldata_aux = $fulldata_result;
		$file = fopen($url, 'w+');
		$data = str_replace('"','',json_encode($fulldata_aux));
		fwrite ($file, $data);
	}
	
	$url = $dataURL."temp.json";
	$file = fopen($url, 'w+');
	$data = str_replace('"','',json_encode($fulldata_aux));
	fwrite ($file, $data);
	
	
?>


<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>

<div id="global" style="height: 400px; min-width: 310px; border: 2px solid black;"></div>



<script>

function changeMinData() {
	$("input[name='min']").val("<?php echo($fechaOrigen); ?>");
	$("input[name='min']").trigger("change");
}

function changeMaxData() {
	$("input[name='max']").val("<?php echo($fechaFinal); ?>");
	$("input[name='max']").trigger("change");
}

Highcharts.getJSON('<?php echo($url); ?>', function (data) {
    // Create the chart
    Highcharts.stockChart('container', {


        rangeSelector: {
            selected: 1
        },

        title: {
            text: 'Game CCU'
        },

        series: [{
            name: 'CCU',
            data: data,
			marker: {
                enabled: true,
                radius: 3
            },
            shadow: true,
            tooltip: {
                valueDecimals: 2
            }
        }],
		
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
	
	changeMinData();
	changeMaxData();
	
});



/*
var seriesOptions = [],
    seriesCounter = 0,
    names = ['MSFT', 'AAPL', 'GOOG'];

function createChart() {

    Highcharts.stockChart('container', {

        rangeSelector: {
            selected: 4
        },

        yAxis: {
            labels: {
                formatter: function () {
                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                }
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },

        plotOptions: {
            series: {
                compare: 'percent',
                showInNavigator: true
            }
        },

        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
            valueDecimals: 2,
            split: true
        },

        series: seriesOptions
    });
}

function success(data) {
    var name = this.url.match(/(msft|aapl|goog)/)[0].toUpperCase();
    var i = names.indexOf(name);
    seriesOptions[i] = {
        name: name,
        data: data
    };

    // As we're loading the data asynchronously, we don't know what order it
    // will arrive. So we keep a counter and create the chart when all the data is loaded.
    seriesCounter += 1;

    if (seriesCounter === names.length) {
        createChart();
    }
}

Highcharts.getJSON(
    'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/msft-c.json',
    success
);
Highcharts.getJSON(
    'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/aapl-c.json',
    success
);
Highcharts.getJSON(
    'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/goog-c.json',
    success
);

*/


</script>