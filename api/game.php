<?php
ini_set('display_errors',false);

date_default_timezone_set("America/New_York");
$dataURL = '../data/';
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
    $dataURLGame = $dataURL;

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
    $top_games = json_decode(file_get_contents('../data/' . $platform_name . '/top/topccu.json'));
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

$month_data_array = array();
// CREAMOS LA ESTRUCTURA DE DATOS PARA LA TABLA DE AVERAGE PEAK
foreach ($fulldata_aux as $data) {

    $time = explode(' ',$data['DateTime']);
    $date = explode('/',$time[0]);
    $fecha = $months[$date[1]].' 20'.$date[2];

    if (!isset($month_data_array[$fecha])) {
        $month_data_array[$fecha]['peak'] = $data['Ccu'];
    }
    else {
        if ($month_data_array[$fecha]['peak'] < $data['Ccu']) {
            $month_data_array[$fecha]['peak'] = $data['Ccu'];
        }
    }

}
foreach ($monthaverage_aux as $data)
{
    if (isset($data['MonthYear'])) {
        $month_data_array[$data['MonthYear']]['avg'] = (int)$data['Average'];
    }
}

$lastMonth=array();
foreach ($month_data_array as $key => $data) {

    //if (isset($lastMonth['avg']) && $lastMonth['avg'] > 0) {
    if (isset($lastMonth['avg'])){
        $month_data_array[$key]['inc'] = number_format ($data['avg'] - $lastMonth['avg']);
        if ($lastMonth['avg'] > 0){
            $month_data_array[$key]['pinc'] = floatval(number_format(((($data['avg']/$lastMonth['avg']) - 1) * 100),2)) . "%";
        }
        else {
            $month_data_array[$key]['pinc'] = '---';
        }
    }
    else {
        $month_data_array[$key]['inc'] = '---';
        $month_data_array[$key]['pinc'] = '---';
    }
    $lastMonth['avg']=$data['avg'];
}

$reversed = array_reverse($month_data_array);
$month_data_array = $reversed;

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

echo json_encode([
    'gameinfo_aux' => $gameinfo_aux,
    'gamedata_aux' => $gamedata_aux,
    'stores' => $stores,
    'month_data_array' => $month_data_array,
    'dataURL' => $dataURL,
    'fulldata_aux' => $fulldata_aux,
    'fullaverage_aux' => $fullaverage_aux,
    'today_aux' => $today_aux,
    'yesterday_aux' => $yesterday_aux,
    'weekago_aux' => $weekago_aux,
]);
?>



