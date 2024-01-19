<?php
ini_set('display_errors', false);
function get_top_games($platform_name){
    $top_games = json_decode(file_get_contents('../data/' . $platform_name . '/top/topccu.json'));
    return array_slice($top_games, 0, 5, true);
}

date_default_timezone_set("America/New_York");
$searched_string = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searched_string = strtolower($_GET['search']);
}

$filename = substr($searched_string,0,2);

$baseURL = '../data/search/';
$dataURL = $baseURL . $filename.'.json';
$file = file_get_contents($dataURL);
$all_data = json_decode($file);
$matching_data = array();
foreach ($all_data as $data){
    if (strtolower (substr ($data->Name,0,strlen($searched_string))) == strtolower ($searched_string)){
        $dataPath = "../data/".$data->Source."/games_seo/".$data->NameSEO."/today.json";
        $today = str_replace(',[0000,0]','',file_get_contents($dataPath));
        $today_data = json_decode($today,true);
        $dataPath = "../data/".$data->Source."/games_seo/".$data->NameSEO."/gamedata.json";
        $gameDataString = str_replace(',[0000,0]','',file_get_contents($dataPath));
        $gameData = json_decode($gameDataString,true);

        $hisArr = [];
        $data->LastCcu = "?";
        $data->Peak24Hours = "?";
        if (($today_data !== FALSE) && (count($today_data) > 0)){
            foreach ($today_data as $h) {
                $hisArr[] = (int)$h['Ccu'];
            }
        }
        $data->hisArr = $hisArr;
        if ($gameData !== FALSE){
            $data->LastCcu = $gameData[0]['CurrentCcu'];
            $data->Peak24Hours = $gameData[0]['TopCcu24h'];
        }

        array_push ($matching_data,$data);
    }
}

$dataURL = '../data/store.json';
$stores = json_decode(file_get_contents($dataURL));

$aux = array();
foreach ($stores as $store) {
    $aux[$store->Store] = $store;
}
$stores = $aux;

echo json_encode([
    'stores' => $stores,
    'matching_data' => $matching_data
]);
?>

