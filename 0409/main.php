<?php

	ini_set('display_errors',false);

	$trending = array();
	$trending_average = array();
	$topdata = array();
	$topdata_average = array();
	$stores = array();
    function get_top_games($platform_name){
        $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
        return array_slice($top_games, 0, 5, true);
    }
	$source = 'default';
	if (isset($_GET['source']) && !empty($_GET['source'])) {
		$source = strtolower($_GET['source']);
	}
	//echo $source; die();
	$baseURL = './data/'.$source . "/";	  

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
		$todayURL = $baseURL.'games/'.$data->AppID.'/today.json';
		
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
			'app_id'    =>  $data->AppID,
			'store'		=>	$source
		);
		$trending_arr[] = $info;
	}
	$trending = $trending_arr;
	
	
	$dataURL = $baseURL.'trending/trendingavg.json';
	$trending_average = json_decode(file_get_contents($dataURL));
	$trending_arr = [];
	foreach($trending_average as $data)
	{
		if($data->YesterdayAverage == 0){
			$change = $data->CurrentAverage;
		}else{
			$change = ($data->CurrentAverage / (float)$data->YesterdayAverage - 1) * 100;
		}
	
		$info = array(
				'name'      =>  $data->Name,
				'change'    =>  '+'.number_format($change, 1).'%',
				'ccu'       =>  number_format($data->CurrentAverage),
				'app_id'    =>  $data->AppID,
				'store'		=>	$source
		);
		$trending_arr[] = $info;
	}
	$trending_average = $trending_arr;
	

	$dataURL = $baseURL.'top/topccu.json';
	$topdata = json_decode(file_get_contents($dataURL));
	
	$dataURL = $baseURL.'top/maxavg.json';
	$topdata_average = json_decode(file_get_contents($dataURL));
	
	$dataURL = $baseURL.'../store.json';
	$stores = json_decode(file_get_contents($dataURL));

	$aux = array();
	foreach($stores as $store) {
		$aux[$store->Store] = $store;
	}
	$stores = $aux;
	
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
        <div class="route">
            <a href="http://gamecharts.local">Home</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo $source?></a>
        </div>
    </div>

    <div class="page-wrapper page-wrapper-img">
        <div class="add-1-container row justify-content-center align-items-center">
            <div class="bg-light">Add1: 970×250 Billboard</div>
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
                                    <div class="card-header bg-gradient-grey">
                                        <div class="d-flex flex-row justify-content-between">
                                            <h3 class="h5 font-secondary text-uppercase m-0">Trending</h3>
                                            <h4 class="h5 font-secondary text-uppercase text-white m-0">By Current Players</h4>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0" style="overflow: hidden;">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="center">24-hour Change</th>
                                                    <th class="center">Today</th>
                                                    <th class="center">Current Players</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(count($trending)) {

                                                    foreach($trending as $data){
                                                        ?>
                                                        <tr>
                                                            <td><a class="text-dark" style="font-weight:500" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                                            <td  class="text-success text-center font-weight-900"><?php echo $data['change']?></td>
                                                            <td>
                                                                <div class="chart-today" data-series='<?php echo json_encode($data['hist']) ?>'></div>
                                                            </td>
                                                            <td class="text-gray center"><?php echo $data['ccu']?></td>
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-gradient-grey">
                                        <div class="d-flex flex-row justify-content-between">
                                            <h3 class="h5 font-secondary text-uppercase m-0">Top Games</h3>
                                            <h4 class="h5 font-secondary textuppercase text-white m-0">By Current Players</h4>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th class="center">Current Players</th>
                                                    <th class="center">24-hour peak</th>
                                                    <th class="center">30-days peak</th>
                                                    <th class="center">Peak Players</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                if(count($topdata)) {

                                                    $index = 1;
                                                    foreach($topdata as $data){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index++?>.</td>
                                                            <td><a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo ($data->AppID); ?>"><?php echo $data->Name?></a></td>
                                                            <td class="center"><?php echo number_format($data->LastCcu)?></td>
                                                            <td class="center text-gray"><?php echo number_format($data->TopCcu24h)?></td>
                                                            <td class="center text-gray"><?php echo number_format($data->TopCcu30d)?></td>
                                                            <td class="center text-gray"><?php echo number_format($data->TopCcu)?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>

                                                    <tr><td colspan="5"> Not Games Found </td></tr>

                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end border-top py-1 px-3"><a class="btn btn-primary btn-round waves-effect waves-light" href="http://gamecharts.local/<?php echo($source); ?>/top"> More </a></div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-->
                        </div>

                        <div class="align-items-center row justify-content-center mb-4">
                            <div class="add-4">
                                <span class="h4 text-white text-center mt-0"> Add 4: 728x 90px</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-gradient-grey">
                                        <div class="d-flex flex-row justify-content-between">
                                            <h3 class="h5 font-secondary text-uppercase m-0">Trending</h3>
                                            <h4 class="h5 font-secondary text-uppercase text-white m-0">By Average Players</h4>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0" style="overflow: hidden;">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="center">24-hour Change</th>
                                                    <th class="center">Current Players</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(count($trending_average)) {

                                                    foreach($trending_average as $data){
                                                        ?>
                                                        <tr>
                                                            <td><a class="text-dark" style="font-weight:500" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                                            <td class="text-success text-center" style="font-weight:800"><?php echo $data['change']?></td>
                                                            <td class="center text-gray"><?php echo $data['ccu']?></td>
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
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-gradient-grey">
                                        <div class="d-flex flex-row justify-content-between">
                                            <h3 class="h5 font-secondary text-uppercase m-0">Top Games</h3>
                                            <h4 class="h5 font-secondary text-uppercase text-white m-0">By Average Players</h4>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th class="center">Average Players</th>
                                                    <th class="center">24-hour Average</th>
                                                    <th class="center">30-days Average</th>
                                                    <th class="center">Max Average Players</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                if(count($topdata_average)) {

                                                    $index = 1;
                                                    foreach($topdata_average as $data){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index++?>.</td>
                                                            <td><a a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo ($data->AppID); ?>"><?php echo $data->Name?></a></td>
                                                            <td class="center"><?php echo number_format($data->LastCcu)?></td>
                                                            <!-- <td>
                                                                <div id="sparkline2" class="text-center"></div>
                                                            </td> -->
                                                            <td class="center text-gray"><?php echo number_format($data->MaxAvg24h)?></td>
                                                            <td class="center text-gray"><?php echo number_format($data->MaxAvg30d)?></td>
                                                            <td class="center text-gray"><?php echo number_format($data->MaxAvg)?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>

                                                    <tr><td colspan="5"> Not Games Found </td></tr>

                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-end border-top py-1 px-3"><a class="btn btn-primary btn-round waves-effect waves-light" href="http://gamecharts.local/<?php echo($source); ?>/average"> More </a></div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div><!--end col-->
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

    <!--footer page-->

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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/waves.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/moment/moment.js"></script>

    <script src="assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="assets/pages/jquery.apexcharts.init.js"></script>

    <script src="assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
    <script src="assets/pages/jquery.charts-sparkline.js"></script>


    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script src="assets/js/searchbox.js"></script>
    </body>
</html>

<script>

// $('#searchBox').submit(function(e){
// 	   e.preventDefault();
// 	   return false;
// 	});
//
// 	$('#searchBox').keydown(function (e) {
// 	    if (e.keyCode == 13) {
// 	        e.preventDefault();
// 	        return false;
// 	    }
// 	});
//
// 	$('#searchBox').keyup(function(){
// 		var value = $(this).val();
// 		if (value.length >= 3) {
//
// 			var searchString = value.substring(0,3).toLowerCase();
// 			var gameList = $.getJSON( "http://gamecharts.local/data/search/" + searchString + ".json", function( data ) {
//
// 				$('#searched_game').show();
// 				$('#searched_game').html('');
// 				var encontrado = false;
// 				var encontrados = 0;
//
// 				$.each( data, function( item, field ) {
//
// 					var gameName = field.Name.toLowerCase();
// 					var searchGame = gameName.substring(0,value.length).toLowerCase();
// 					var searchedGame = value.toLowerCase();
//
// 					if (searchedGame == searchGame)
// 					{
// 						encontrado = true;
// 						encontrados++;
// 						var texto = '<div class="item" style="width:100%; text-align:right; padding-right:10px; height:60px; background-color: #fff; border: 2px solid #000; border-radius: 20px; margin-top:1px;" ><a style="top: 0;left: 260px;display: block;height: 0;line-height: 70px;width: initial;text-align: center;color: #a8a8b1;" href="./' + field.Source + '/' + field.AppId + '"><img src="' + field.Logo + '" style="max-height:60px; float:left;">' + field.Name + '<br/><br/>';
// 						texto = texto + '</a></div>';
// 						$('#searched_game').append(texto);
// 					}
//
// 					if (encontrados > 5) {
// 						return false;
// 					}
//
// 				  });
//
// 				if (!encontrado) {
// 					$('#searched_game').html(' Not Games Found ');
// 				}
//
// 			})
// 			.fail(function() {
// 				console.log( "error" );
// 			})
// 			.always(function() {
// 			});
// 		}
// 		else {
// 			$('#searched_game').hide();
// 		}
// 	});


</script>
