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
                     echo('<li><a href="http://gamecharts.local/'.$store->Store.'"><img src="'.$store->Splash.'" alt="'.$store->Store.'"/></a> </li>');
                 }
             }
             ?>
        </span>
        <span class="top-games"><a style="color:white"
                href="http://gamecharts.local/<?php echo $source; ?>/<?php echo $subFolder; ?>">TOP
                GAMES</a></span>
        <div class="route-top">
            <a href="http://gamecharts.local">Home</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;
            <a href="http://gamecharts.local/<?php echo $source;?>"><?php echo ucfirst($source);?></a>&nbsp;&nbsp;<i
                class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo $navigationTag; ?></a>
        </div>
    </div>
    <div class="page-wrapper page-wrapper-img">

        <div class="page-wrapper-inner-add align-items-center position-relative">
            <div class="container-fluid-add pb-0">
                <!--desktop-->
                <div class="row-add desktop-screen justify-content-center mb-5 pb-3" style="display:none;">

                    <div class="col-lg-8-add col-xs-12 game-list">
                        <div class="desktop-ads-column-left">
                            <table class="desktop-ads-table">
                                <tr>
                                    <td>
                                        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
                                            data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
                                        </ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="desktop-ads-bottom">
                                        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
                                            data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
                                        </ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="desktop-ads-column-right">
                            <table class="desktop-ads-table">
                                <tr>
                                    <td>
                                        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
                                            data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
                                        </ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="desktop-ads-bottom">
                                        <ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px;"
                                            data-ad-client="ca-pub-2433076550762661" data-ad-slot="4014468333">
                                        </ins>
                                        <script>
                                            (adsbygoogle = window.adsbygoogle || []).push({});
                                        </script>
                                    </td>
                                </tr>
                            </table>
                        </div>
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
                                                                    href="http://gamecharts.local/<?php echo($source); ?>/<?php echo($data->NameSEO); ?>">
                                                                    <img class="img-thumnail"
                                                                        src="<?php echo($data->Splash)?>"
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

                            <div class="mobile-add">
                                <ins class="adsbygoogle mobile-add-1" data-ad-client="ca-pub-2433076550762661"
                                    data-ad-slot="4014468333"></ins>
                                <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>

                            <div class="row">
                                <div class="col-12" style="text-align: right">
                                    <?php
                                if ($page >= 2) {
                                    echo('<a href="http://gamecharts.local/'."$source/$subFolder/".($page-1).'">Previous</a>');
                                    //if(isset($top_data) && count($top_data)) {
                                    if ($nextPageRel != "") {
                                        echo(' or ');
                                    } else {
                                        echo(' page ');
                                    }
                                }
                            //if(isset($top_data) && count($top_data)) {
if ($nextPageRel != "") {
    echo(' <a href="http://gamecharts.local/'."$source/$subFolder/".($page+1).'">Next</a> page');
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
                                                    href="http://gamecharts.local/<?php echo($source); ?>/<?php echo($data->NameSEO); ?>"><img
                                                        src="<?php echo($i_game[0]->Splash);?>"
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
                            <div class="mobile-add">
                                <ins class="adsbygoogle mobile-add-1" data-ad-client="ca-pub-2433076550762661"
                                    data-ad-slot="4014468333"></ins>
                                <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                            </div>

                        </div>
                    </div>

                </div>


                <?php
                    /*$zone = 2;
                    include('ads.php'); */
                    ?>
            </div><!-- container -->
        </div>


    </div>
    <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <?php include('footer.php'); ?>

    <!-- jQuery  -->
    <script src="http://gamecharts.local/assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="http://gamecharts.local/assets/js/bootstrap.bundle.min.js"></script>
    <script src="http://gamecharts.local/assets/js/waves.min.js"></script>
    <script src="http://gamecharts.local/assets/js/jquery.slimscroll.min.js"></script>

    <script src="http://gamecharts.local/assets/plugins/moment/moment.js"></script>

    <script src="http://gamecharts.local/assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="http://gamecharts.local/assets/pages/jquery.apexcharts.init.js"></script>

    <script src="http://gamecharts.local/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
    <script src="http://gamecharts.local/assets/pages/jquery.charts-sparkline.js"></script>


    <!-- App js -->
    <script src="http://gamecharts.local/assets/js/checkCookie.js"></script>
    <script src="http://gamecharts.local/assets/js/app.js"></script>
    <script src="http://gamecharts.local/assets/js/searchbox.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

</body>

</html>

<script>
    $(document).ready(function() {
        $(".desktop-screen").show();
        $('#top_game_slider').bxSlider({
            touchEnabled: false
        });
    })
</script>
