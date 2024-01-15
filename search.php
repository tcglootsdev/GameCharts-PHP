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

    $title = "Game Charts - Search";
    $description = "GameCharts. This page shows games matching searched text.";
    $canonical = "https://gamecharts.org/$search";

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
                     echo('<li><a href="https://gamecharts.org/'.$store->Store.'"><img src="'.$store->Splash.'" alt="'.$store->Store.'"/></a> </li>');
                 }
             }
             ?>
        </span>
        <span class="search"><a style="color:white"
                href="https://gamecharts.org/Search">Search</a></span>
        <div class="route-top">
            <a href="https://gamecharts.org">Home</a>&nbsp;&nbsp;
            <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#">Search</a>
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
							$href = "https://gamecharts.org/".$data->Source."/".$data->NameSEO;
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

