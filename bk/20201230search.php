<?php
    function get_top_games($platform_name){
        $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
        return array_slice($top_games, 0, 5, true);
    }

    ini_set('display_errors', false);
    date_default_timezone_set("America/New_York");
    $searched_string = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searched_string = strtolower($_GET['search']);
    }

    $filename = substr($searched_string,0,2);

    $navigationTag = "Search games: ";
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

    $title = "Game Charts - Search";
    $description = "GameCharts. This page shows games matching searched text.";
    $canonical = "http://gamecharts.local/$search";

    $baseURL = './data/search/';
    $dataURL = $baseURL . $filename.'.json';
    $file = file_get_contents($dataURL);
    $all_data = json_decode($file);
    $matching_data = array();
    foreach ($all_data as $data){
	if (strtolower (substr ($data->Name,0,strlen($searched_string))) == strtolower ($searched_string)){
		array_push ($matching_data,$data);
	}
    }
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
        <span class="search"><a style="color:white"
                href="http://gamecharts.local/Search">Search</a></span>
        <div class="route-top">
            <a href="http://gamecharts.local">Home</a>&nbsp;&nbsp;
            <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#">Search</a>
        </div>
    </div>
    <div class="page-wrapper page-wrapper-img">

        <div class="page-wrapper-inner-add align-items-center position-relative">
            <div class="container-fluid-add pb-0">
                <!--desktop-->
                <div class="row-add desktop-screen justify-content-center mb-5 pb-3">

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
							    <th>Platform</th>
                                                            <th class="center">24-hour peak</th>
                                                            <th class="center">Today</th>
                                                            <th class="center">Current players</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                if (isset($matching_data) && count($matching_data)) {
                                                    $index = 1 + (25*($page-1));
                                                    foreach ($matching_data as $data) {
							$dataPath = "./data/".$data->Source."/games_seo/".$data->NameSEO."/today.json";
							$today = str_replace(',[0000,0]','',file_get_contents($dataPath));
						        $today_data = json_decode($today,true);
							$dataPath = "./data/".$data->Source."/games_seo/".$data->NameSEO."/gamedata.json";
                                                        $gameDataString = str_replace(',[0000,0]','',file_get_contents($dataPath));
                                                        $gameData = json_decode($gameDataString,true);
	
							$hisArr = [];
							$data->LastCcu = "?";
							$data->Peak24Hours = "?";
							echo "<!--";
							var_dump ($gameData);
							echo "-->";
							if (($today_data !== FALSE) && (count($today_data) > 0)){
								foreach ($today_data as $h) {
	                                                                $hisArr[] = (int)$h['Ccu'];
        	                                                }
								//$data->LastCcu = number_format($today_data[count($today_data) - 1]['Ccu']);
							}
							if ($gameData !== FALSE){
								$data->LastCcu = $gameData[0]['CurrentCcu'];
								$data->Peak24Hours = $gameData[0]['TopCcu24h'];
							}
							$href = "http://gamecharts.local/".$data->Source."/".$data->NameSEO;
                                                        ?>
                                                        <tr>
							    <td><a
                                                                    href="<?php echo $href; ?>"><img
                                                                        src="<?php echo($data->Logo); ?>"
                                                                        alt="<?php echo($data->Name);?>"
                                                                        style="max-width:150px; max-height:70px" /></a>
							    </td>
                                                            <td><a class="text-dark" href="<?php echo $href; ?>"><?php echo $data->Name?></a>
                                                            </td>
                                                            </td>
                                                            <td><a href="./<?php echo($data->Source); ?>"><img
                                                                        src="<?php echo($stores[$data->Source]->Splash); ?>"
                                                                        alt="<?php echo($stores[$data->Source]->Store)?>"
                                                                        style="max-width:75px; max-height:30px" /></a>
                                                            </td>


                                                            <td class="center"><?php echo $data->Peak24Hours; ?>
                                                            </td>
                                                            <!-- <td>
                                                                <div id="sparkline2" class="text-center"></div>
                                                            </td> -->
                                                            <td>
								<div class="chart-today text-center"
                                                                    data-series='<?php echo json_encode($hisArr) ?>'>
                                                                </div>

                                                            </td>

							    <td class="center" style="color:grey">
<?php
    echo $data->LastCcu;
?>
</td>
                                                        </tr>
<?php
                                                    }
                                                } else {
                                                    ?>
                                                        <tr>
                                                            <td colspan="5"> No Results Found </td>
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
        //$(".desktop-screen").show();
        $('#top_game_slider').bxSlider({
            touchEnabled: false
        });
    })
</script>
