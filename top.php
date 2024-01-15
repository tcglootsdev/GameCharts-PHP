<?php
include "max_pages.php";
ini_set('display_errors', false);
date_default_timezone_set("America/New_York");

function get_top_games($platform_name)
{
    $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
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
$nextPageRel = "    <link rel=\"next\" href=\"https://gamecharts.org/steam/player_count/$next_page\" />\n";
if ($page != 1) {
    if ($page == 2) {
        $prevPageRel = "    <link rel=\"prev\" href=\"https://gamecharts.org/steam/player_count\" />\n";
    } else {
        $prevPageRel = "    <link rel=\"prev\" href=\"https://gamecharts.org/steam/player_count/$prev_page\" />\n";
    }
    $titlePageTag = " Page $page";
    $descriptionPageTag = " $titlePageTag for";
    $pageFolder = "/$page";
}

$title = "Game Charts - " . ucfirst($source) . ": $titleTag$titlePageTag";
$description = "GameCharts. This page shows ". ucfirst($source) . "$descriptionPageTag Top Games by $navigationTag.";
$canonical = "https://gamecharts.org/$source/$subFolder$pageFolder";

$baseURL = './data/' . $source . '/top/' . $type .'/';
$dataURL = $baseURL . 'top'.$type.'_'.$page.'.json';
$file = file_get_contents($dataURL);
if (!file_exists($baseURL . 'top'.$type.'_'.$next_page.'.json')) {
    $nextPageRel = "";
}
$top_data = json_decode($file);
    //!!!!!!!!!!!in order to check seo we can change max_pages value in max_pages.php to 3 so we don't check thousends of pages
if ($max_pages > 0) {
    if ($page >= $max_pages) {
        $nextPageRel = "";
    }
}
    //var_dump($top_data); die();
$dataURL = './data/store.json';
$stores = json_decode(file_get_contents($dataURL));

$aux = array();
foreach ($stores as $store) {
    $aux[$store->Store] = $store;
}
$stores = $aux;
?>

<!DOCTYPE html>
<html lang="en">
<!--  HEADER -->
<?php include('headerNew.php'); ?>

<style>
    .center {
        text-align: center;
    }
</style>

<body>

    <?php include('navigation.php') ?>

    <div class="row game-platforms">
        <span class="game-platforms-menu" style="margin-left: 0">
            <?php
            foreach ($stores as $store) {
               if ($store->Store == $source) {
                   echo('<li><a href="https://gamecharts.org/'.$store->Store.'"><img alt="'.$store->Store.'" src="'.$store->Splash.'" alt="'.$store->Store.'"/></a> </li>');
               }
           }
           ?>
       </span>
       <span class="top-games"><a style="color:white"
        href="https://gamecharts.org/<?php echo $source; ?>/<?php echo $subFolder; ?>">TOP
    GAMES</a></span>
    <div class="route-top">
        <a href="https://gamecharts.org">Home</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;
        <a href="https://gamecharts.org/<?php echo $source;?>"><?php echo ucfirst($source);?></a>&nbsp;&nbsp;<i
        class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo $navigationTag; ?></a>
    </div>
</div>
<div class="page-wrapper page-wrapper-img">

    <div class="page-wrapper-inner-add align-items-center position-relative">
        <div class="container-fluid-add pb-0">
            <!--desktop-->
            <div class="row-add desktop-screen justify-content-center mb-5 pb-3">

                <div class="col-lg-8-add col-xs-12 game-list">
                    <div class="content-column">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <h4 class="header-title mt-0 mb-4">Trending</h4> -->

                                        <span style="color:#aca5ad;font-size:24px"><?php echo($bytop); ?></span>
                                        <div class="table-responsive">
                                            <table class="table table-centered table-striped mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th></th>
                                                        <th>Name</th>
                                                        <th class="center">Current Players</th>
                                                        <th class="center">24-hour peak</th>
                                                        <th class="center">30-days peak</th>
                                                        <th class="center">Peak players</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

                                                    if (isset($top_data) && count($top_data)) {
                                                        $index = 1 + (25*($page-1));
                                                        foreach ($top_data as $data) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $index++?>.
                                                                </td>
                                                                <td><a style="color:#303030;font-weight:500"
                                                                    href="https://gamecharts.org/<?php echo($source); ?>/<?php echo($data->NameSEO); ?>">
                                                                    <img class="img-thumnail lazyload blur-up" width="100%" height="100%" 
                                                                    data-src="<?php echo($data->Splash)?>"
                                                                    alt="<?php echo($data->Store)?>" /><?php echo($data->Name); ?></a>
                                                                </td>
                                                                <td class="center"><?php echo number_format($data->LastCcu)?>
                                                            </td>
                                                            <!-- <td>
                                                                <div id="sparkline2" class="text-center"></div>
                                                            </td> -->
                                                            <td class="center" style="color:grey">
                                                                <?php
                                                                if ($type == "avg") {
                                                                    echo number_format($data->MaxAvg24h);
                                                                } else {
                                                                    echo number_format($data->TopCcu24h);
                                                                } ?>
                                                            </td>
                                                            <td class="center" style="color:grey">
                                                                <?php
                                                                if ($type == "avg") {
                                                                    echo number_format($data->MaxAvg30d);
                                                                } else {
                                                                    echo number_format($data->TopCcu30d);
                                                                } ?>
                                                            </td>
                                                            <td class="center" style="color:grey">
                                                                <?php
                                                                if ($type == "avg") {
                                                                    echo number_format($data->MaxAvg);
                                                                } else {
                                                                    echo number_format($data->TopCcu);
                                                                } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="5"> Not Games Found </td>
                                                    </tr>

                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                    </div>

                    <div class="row">
                        <div class="col-12" style="text-align: right">
                            <?php
                            if ($page >= 2) {
                                echo('<a href="https://gamecharts.org/'."$source/$subFolder/".($page-1).'">Previous</a>');
                                    //if(isset($top_data) && count($top_data)) {
                                if ($nextPageRel != "") {
                                    echo(' or ');
                                } else {
                                    echo(' page ');
                                }
                            }
                            //if(isset($top_data) && count($top_data)) {
                            if ($nextPageRel != "") {
                                echo(' <a href="https://gamecharts.org/'."$source/$subFolder/".($page+1).'">Next</a> page');
                            }
                            ?>

                        </div>
                    </div>

                    <div class="row">
                        <!--  <h1>Top Games</h1> -->
                        <div class="col-12">
                            <div id="top_game_slider" class="top_game_slider">
                                <?php foreach ($top_data as $data):?>

                                    <?php $i_game = json_decode(file_get_contents('./data/' . $source . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>

                                    <div class="row ml-1 mr-1">
                                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                            <a 
                                            href="https://gamecharts.org/<?php echo($source); ?>/<?php echo($data->NameSEO); ?>"><img
                                            class="lazyload blur-up" width="100%" height="100%"
                                            data-src="<?php echo($i_game[0]->Splash);?>"
                                            alt="<?php echo($data->Name);?>" /></a>
                                        </div>

                                        <div style="display: inline-block;padding: 0; height: 215px; overflow-y: scroll;"
                                        class="col-sm-12 col-md-7">
                                        <div class="" style="margin-left: 3px; margin-right: 3px">
                                            <?php echo strip_tags($i_game[0]->AboutGame);?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <!--end col-->
                </div>

            </div>
        </div>

    </div>
</div><!-- container -->
</div>


</div>
<!-- end page content -->
</div>
<!-- end page-wrapper -->

<?php include('footer.php'); ?>

</body>

</html>
