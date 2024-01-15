<?php

	ini_set('display_errors',false);
	date_default_timezone_set("Europe/Madrid");

    function get_top_games($platform_name){
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
	$page = '1';
	if (isset($_GET['page']) && !empty($_GET['page'])) {
		$page = strtolower($_GET['page']);
	}
	$baseURL = './data/' . $source . '/top/' . $type .'/';
	$dataURL = $baseURL . 'top'.$type.'_'.$page.'.json';
	$file = file_get_contents($dataURL);
    $top_data = json_decode($file);
    
    $dataURL = './data/store.json';
    $stores = json_decode(file_get_contents($dataURL));
    
    $aux = array();
    foreach($stores as $store) {
    	$aux[$store->Store] = $store;
    }
    $stores = $aux;
    if ($type=='ccu') {
    	$bytop = 'By Current Players';
    }
    else if ($type=='avg') {
    	$bytop = 'By Average Players';
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Game Charts</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="GameCharts" name="description" />
        <meta content="Jose Maria Lopez-Terradas" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="http://gamecharts.local/assets/images/favicon.ico">

        <!-- App css -->
        <link href="http://gamecharts.local/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="http://gamecharts.local/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="http://gamecharts.local/assets/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    
    <style>
    	.center{
    		text-align: center;
    	}
    </style>

    <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-gradient-green fixed-top">
        <a href="http://gamecharts.local"><img src="http://gamecharts.local/assets/images/logo-1.png" class="logoGameCharts" alt="Game Charts logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link game-subject" href="#">Realtime game analysis and charts</a>
                </li>
            </ul>
            <ul class="list-unstyled topbar-nav navbar-search">
                <li class="hide-phone app-search">
                    <form role="search" class="">
                        <input type="text" id="searchBox" placeholder="Search..." class="form-control bg-light-gray">
                        <a href=""><i class="fas fa-search"></i></a>
                    </form>
                    <div id="searched_game">
                        <div class="item"> Not Games Found </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="row game-platforms">
        <span class="game-platforms-menu" style="margin-left: 0">
             <?php
             foreach($stores as $store) {
                 if ($store->Store == $source){
                     echo('<li><a href="http://gamecharts.local/'.$store->Store.'"><img src="'.$store->Splash.'"/></a> </li>');
                 }
             }
             ?>
        </span>
        <span class="top-games"><a style="color:white" href="http://gamecharts.local/<?php echo $source; ?>/top">TOP GAMES</a></span>
        <div class="route-top">
            <a href="http://gamecharts.local">Home</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;
            <a href="http://gamecharts.local/<?php echo $source?>"><?php echo $source?></a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#">Top</a>
        </div>
    </div>

    <div class="page-wrapper page-wrapper-img">
        <div class="add-1-container row justify-content-center align-items-center">
            <div class="bg-light"> Add1: 970×250 Billboard</div>
        </div>
        <div class="page-wrapper-inner align-items-center position-relative">
            <div class="container-fluid pb-0">
                <div class="row justify-content-between mb-5 pb-3">
                    <div class="col-lg-2 col-xs-6">
                        <div class="add-2">
                            <span class="h4 text-white text-center p-3"> Add 2: 600x 300px</span>
                        </div>
                    </div>

                    <div class="col-lg-8 col-xs-12 game-list">
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
                                                    <!-- <th class="center">Last 30 Days</th> -->
                                                    <th class="center">Peak Players</th>
                                                    <!-- <th class="center">Hours Played</th> -->
                                                    <th class="center">30-days peak</th>
                                                    <th class="center">24-hour peak</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                if(isset($top_data) && count($top_data)) {

                                                    $index = 1 + (25*($page-1));
                                                    foreach($top_data as $data){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index++?>.</td>
                                                            <td><a style="color:#303030;font-weight:500" href="http://gamecharts.local/<?php echo($source); ?>/<?php echo($data->AppID); ?>"><?php echo($data->Name); ?></a></td>
                                                            <td class="center"><?php echo number_format($data->LastCcu)?></td>
                                                            <!-- <td>
                                                                <div id="sparkline2" class="text-center"></div>
                                                            </td> -->
                                                            <td class="center" style="color:grey"><?php echo number_format($data->TopCcu)?></td>
                                                            <td class="center" style="color:grey"><?php echo number_format($data->TopCcu24h)?></td>
                                                            <td class="center" style="color:grey"><?php echo number_format($data->TopCcu30d)?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else {
                                                    ?>
                                                    <tr><td colspan="5"> Not Games Found </td></tr>

                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-->
                        </div>

                        <div class="row">
                            <div class="col-12" style="text-align: right">
                                <?php
                                if ($page >= '2') {
                                    echo('<a href="http://gamecharts.local/'.$source.'/top/'.($page-1).'">Previous</a>');
                                    if(isset($top_data) && count($top_data)) {
                                        echo(' or ');
                                    }
                                    else {
                                        echo(' page ');
                                    }
                                }
                                if(isset($top_data) && count($top_data)) {
                                    echo(' <a href="http://gamecharts.local/'.$source.'/top/'.($page+1).'">Next</a> page');
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-xs-6">
                        <div class="add-3">
                            <span class="h4 text-white text-center p-3"> Add 3: 600x 300px</span>
                        </div>
                    </div>

                </div><!-- container -->
            </div>


        </div>
        <!-- end page content -->
    </div>
        <!-- end page-wrapper -->

    <footer class="section footer-classic context-dark bg-image" style="background: #2d3246;">
        <div class="container" style="padding: 3em;">
            <div class="row row-30">
                <div class="col-md-3 col-xs-6 text-white footer-text">
                    &copy; 2019-<?php echo(date('Y')); ?> Game Charts
                </div>
                <div class="col-md-3 col-xs-6 text-white footer-item">
                    <li style="list-style-type: none;">Supported Platforms
                        <ul style="padding-top: 10px;">
                            <?php foreach($stores as $store):?>
                            <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>"><?php echo $store->Store?></a>
                                <?php endforeach;?>
                        </ul>
                    </li>
                </div>
                <div class="col-md-6 col-xs-6 row">
                    <?php foreach($stores as $store):?>
                        <div class="footer-item col-md-6 col-xs-6">
                            <li style="list-style-type: none;"><a href="http://gamecharts.local/<?php echo $store->Store?>/top">Top <?php echo $store->Store?> Games</a>
                                <ul style="padding-top: 10px;">
                                    <?php $platform_top_games = get_top_games($store->Store); foreach ($platform_top_games as $platform_top_game):?>
                                        <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>/<?php echo $platform_top_game->AppID?>"><?php echo $platform_top_game->Name?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </footer>

        <!-- jQuery  -->
    <script src="http://gamecharts.local/assets/js/jquery.min.js"></script>
    <script src="http://gamecharts.local/assets/js/bootstrap.bundle.min.js"></script>
    <script src="http://gamecharts.local/assets/js/waves.min.js"></script>
    <script src="http://gamecharts.local/assets/js/jquery.slimscroll.min.js"></script>

    <script src="http://gamecharts.local/assets/plugins/moment/moment.js"></script>

    <script src="http://gamecharts.local/assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="http://gamecharts.local/assets/pages/jquery.apexcharts.init.js"></script>

    <script src="http://gamecharts.local/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
    <script src="http://gamecharts.local/assets/pages/jquery.charts-sparkline.js"></script>


    <!-- App js -->
    <script src="http://gamecharts.local/assets/js/app.js"></script>
    <script src="http://gamecharts.local/assets/js/searchbox.js"></script>

    </body>
</html>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "380px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
