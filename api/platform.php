<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

ini_set('display_errors',false);

$source = 'default';
if (isset($_GET['source']) && !empty($_GET['source'])) {
    $source = strtolower($_GET['source']);
}

$trending = array();
$trending_average = array();
$topdata = array();
$topdata_average = array();
$stores = array();
function get_top_games($platform_name){
    $top_games = json_decode(file_get_contents('../data/' . $platform_name . '/top/topccu.json'));
    return array_slice($top_games, 0, 5, true);
}
//echo $source; die();
$baseURL = '../data/'.$source . "/";

$dataURL = $baseURL.'trending/trendingccu.json';
$trending = json_decode(file_get_contents($dataURL));
$trending_arr = [];
foreach($trending as $data)
{
    if($data->YesterdayCcu == 0){
        $change = $data->CurrentCcu;
    }else{
        $change = ($data->CurrentCcu / (float)$data->YesterdayCcu - 1) * 100;
    }
    $todayURL = $baseURL.'games_seo/'.$data->NameSEO.'/today.json';

    $today_data = json_decode(file_get_contents($todayURL));
    $hisArr = [];
    foreach ($today_data as $h) {
        $hisArr[] = (int)$h->Ccu;
    }

    $info = array(
        'name'      =>  $data->Name,
        'change'    =>  '+'.number_format($change, 1).'%',
        'ccu'       =>  number_format($data->CurrentCcu),
        'hist'      =>  $hisArr,
        'app_id'    =>  $data->NameSEO,
        'store'		=>	$source
    );
    $trending_arr[] = $info;
}
$trending = $trending_arr;

for ($i = 0; $i < count($trending); $i++) {
    $i_game = json_decode(file_get_contents('../data/' . $source . '/games_seo/' . $trending[$i]['app_id'] . '/gameinfo.json'));
    $trending[$i]['i_game'] = $i_game;
}

$dataURL = $baseURL.'trending/trendingavg.json';
$trending_average = json_decode(file_get_contents($dataURL));
$trending_arr = [];
foreach($trending_average as $data)
{
    if($data->YesterdayAverage == 0){
        $change = $data->CurrentAverage;
    }else{
        $change = ((float)$data->CurrentAverage / (float)$data->YesterdayAverage - 1) * 100;
    }
    $today_data = json_decode(file_get_contents($todayURL));
    $hisArr = [];
    foreach ($today_data as $h) {
        $hisArr[] = (int)$h->Ccu;
    }

    $info = array(
        'name'      =>  $data->Name,
        'change'    =>  '+'.number_format((float)$change, 1).'%',
        'ccu'       =>  number_format((float)$data->CurrentAverage),
        'hist'      =>  $hisArr,
        'app_id'    =>  $data->NameSEO,
        'store'		=>	$source
    );
    $trending_arr[] = $info;
}
$trending_average = $trending_arr;

for ($i = 0; $i < count($trending_average); $i++) {
    $i_game = json_decode(file_get_contents('../data/' . $source . '/games_seo/' . $trending_average[$i]['app_id'] . '/gameinfo.json'));
    $trending_average[$i]['i_game'] = $i_game;
}

$dataURL = $baseURL.'top/topccu.json';
$topdata = json_decode(file_get_contents($dataURL));

for ($i = 0; $i < count($topdata); $i++) {
    $i_game = json_decode(file_get_contents('../data/' . $source . '/games_seo/' . $topdata[$i]->NameSEO . '/gameinfo.json'));
    $topdata[$i]->i_game = $i_game;
}

$dataURL = $baseURL.'top/maxavg.json';
$topdata_average = json_decode(file_get_contents($dataURL));

for ($i = 0; $i < count($topdata_average); $i++) {
    $i_game = json_decode(file_get_contents('../data/' . $source . '/games_seo/' . $topdata_average[$i]->NameSEO . '/gameinfo.json'));
    $topdata_average[$i]->i_game = $i_game;
}

$dataURL = $baseURL.'../store.json';
$stores = json_decode(file_get_contents($dataURL));

$aux = array();
foreach($stores as $store) {
    $aux[$store->Store] = $store;
}
$stores = $aux;

echo json_encode([
    'trending' => $trending,
    'trending_average' => $trending_average,
    'stores' => $stores,
    'topdata' => $topdata,
    'topdata_average' => $topdata_average,
]);
?>
