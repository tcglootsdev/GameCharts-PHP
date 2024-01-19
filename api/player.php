<?php
include "../max_pages.php";
ini_set('display_errors', false);
date_default_timezone_set("America/New_York");

function get_top_games($platform_name)
{
    $top_games = json_decode(file_get_contents('../data/' . $platform_name . '/top/topccu.json'));
    return array_slice($top_games, 0, 5, true);
}

$source = 'default';
if (isset($_GET['source']) && !empty($_GET['source'])) {
    $source = strtolower($_GET['source']);
}
$type = 'ccu';
if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = strtolower($_GET['type']);
}

$subFolder = "player_count";
$titleTag = "Player count. Concurrent players";
$navigationTag = "Player Count";
$bytop = 'By Current Players';
if ($type == "avg") {
    $subFolder = "player_average";
    $titleTag = "Average players";
    $navigationTag = "Average Players";
    $bytop = 'By Average Players';
}
$page = '1';
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = strtolower($_GET['page']);
}
$titlePageTag = "";
$descriptionPageTag = "";
$pageFolder = "";
$next_page = intval($page) + 1;
$prev_page = intval($page) - 1;
$prevPageRel = "";
$nextPageRel = "    <link rel=\"next\" href=\"http://gamecharts.local/steam/player_count/$next_page\" />\n";
if ($page != 1) {
    if ($page == 2) {
        $prevPageRel = "    <link rel=\"prev\" href=\"http://gamecharts.local/steam/player_count\" />\n";
    } else {
        $prevPageRel = "    <link rel=\"prev\" href=\"http://gamecharts.local/steam/player_count/$prev_page\" />\n";
    }
    $titlePageTag = " Page $page";
    $descriptionPageTag = " $titlePageTag for";
    $pageFolder = "/$page";
}

$title = "Game Charts - " . ucfirst($source) . ": $titleTag$titlePageTag";
$description = "GameCharts. This page shows ". ucfirst($source) . "$descriptionPageTag Top Games by $navigationTag.";
$canonical = "http://gamecharts.local/$source/$subFolder$pageFolder";

$baseURL = '../data/' . $source . '/top/' . $type .'/';
$dataURL = $baseURL . 'top'.$type.'_'.$page.'.json';
$file = file_get_contents($dataURL);
if (!file_exists($baseURL . 'top'.$type.'_'.$next_page.'.json')) {
    $nextPageRel = "";
}
$top_data = json_decode($file);

for ($i = 0; $i < count($top_data); $i++) {
    $i_game = json_decode(file_get_contents('../data/' . $source . '/games_seo/' . $top_data[$i]->NameSEO . '/gameinfo.json'));
    $top_data[$i]->i_game = $i_game;
}

//!!!!!!!!!!!in order to check seo we can change max_pages value in max_pages.php to 3 so we don't check thousends of pages
if ($max_pages > 0) {
    if ($page >= $max_pages) {
        $nextPageRel = "";
    }
}
//var_dump($top_data); die();
$dataURL = '../data/store.json';
$stores = json_decode(file_get_contents($dataURL));

$aux = array();
foreach ($stores as $store) {
    $aux[$store->Store] = $store;
}
$stores = $aux;

echo json_encode([
    'title' => $title,
    'description' => $description,
    'canonical' => $canonical,
    'stores' => $stores,
    'top_data' => $top_data,
    'navigationTag' => $navigationTag,
    'bytop' => $bytop,
    'nextPageRel' => $nextPageRel
]);
?>


