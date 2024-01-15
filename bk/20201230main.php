<?php

	ini_set('display_errors',false);
	$source = 'default';
        if (isset($_GET['source']) && !empty($_GET['source'])) {
                $source = strtolower($_GET['source']);
        }


	$title = "Game Charts - " . ucfirst ($source) . ": Top and Trending Games Statistics";
	$description = "GameCharts. This page shows ". ucfirst ($source) . " Top Games and Trending of Games by current players or average players.";
	$canonical = "https://gamecharts.org/$source";

	$trending = array();
	$trending_average = array();
	$topdata = array();
	$topdata_average = array();
	$stores = array();
    function get_top_games($platform_name){
        $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
        return array_slice($top_games, 0, 5, true);
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
<!--  HEADER -->
<?php include('headerNew.php'); ?>
    
    <style>
    	.center{
    		text-align: center;
    	}
    </style>

<body>
<!--  NAVIGATION -->
<?php include('navigation.php'); ?>

<!--  SUB NAVIGATION -->
<div class="row game-platforms">
    <span class="game-platforms-menu" style="margin-left: 0">
         <?php
         foreach($stores as $store) {
             if ($store->Store == $source){
                 echo('<li><a href="https://gamecharts.org/'.$store->Store.'"><img src="'.$store->Splash.'" alt="' . $store->Store . '"/></a></li>');
             }
         }
         ?>
    </span>
    <div class="route">
        <a href="https://gamecharts.org">Home</a>&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;<a href="#"><?php echo ucfirst($source)?></a>
    </div>
</div>

<!--  MAIN CONTENT -->
<div class="page-wrapper page-wrapper-img">
        <div class="page-wrapper-inner-add align-items-center">
            <div class="container-fluid pb-0">
                <!--desktop-->
                <div class="row-add desktop-screen justify-content-center mb-5 pb-3" style="display:none;">


                    <div class="col-lg-8-add col-xs-12 game-list">
                        <div class="desktop-ads-column-left">
                            <table>
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
                            <table>
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
                                    <td  class="desktop-ads-bottom">
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
                        	            <div class="card-header bg-gradient-grey">
                                	        <div class="d-flex flex-row justify-content-between">
                                        	    <h3 class="h5 font-secondary text-uppercase m-0">Trending</h3>
	                                            <h4 class="h5 font-secondary text-uppercase text-white m-0">By Current Players</h4>
        	                                </div>
                	                    </div>
                        	            <div class="card-body p-0">
                                	        <div class="table-responsive">
	                                            <table class="table table-centered mb-0">
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
        	                                                    <td><a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
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
                	        <div class="mobile-add">
	                                    <ins class="adsbygoogle mobile-add-1"
        	                                data-ad-client="ca-pub-2433076550762661"
                	                        data-ad-slot="4014468333">
					    </ins>
	                                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        	                </div>

				<div class="row">
        	                    <!-- <h1>Trending Games</h1> -->
                	            <div class="col-12">
                        	        <div id="trending_game_slider" class="trending_game_slider">
                                	    <?php foreach($trending as $data):?>
                                        	<?php $i_game = json_decode(file_get_contents('./data/' . $source . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

	                                        <div class="row ml-0 mr-1">
        	                                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                	                                <a href="./<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img src="<?php echo $i_game[0]->Splash;?>" alt="<?php echo($data['name']); ?>" alt="Go to <?php echo($data['name']); ?> site" /></a>
                        	                    </div>
                                	            
                                        	    <div style="display: inline-block;padding: 0; height: 215px; overflow-y: scroll;" class="col-sm-12 col-md-7">
                                                	<div class="" style="margin-left: 3px; margin-right: 3px">
                                                    <?php echo strip_tags($i_game[0]->AboutGame);?>
	                                                </div>
        	                                    </div>
                	                        </div>


                        	            <?php endforeach;?>
	                                </div>
        	                    </div><!--end col-->
                	        </div>
				<div class="mobile-add">
                                    <ins class="adsbygoogle mobile-add-1"
                                        data-ad-client="ca-pub-2433076550762661"
                                        data-ad-slot="4014468333">
				    </ins>
                                    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
	                        </div>


        	                <div class="row">
	                            <div class="col-12">
        	                        <div class="card">
                	                    <div class="card-header bg-gradient-grey">
                        	                <div class="d-flex flex-row justify-content-between">
                                	            <h3 class="h5 font-secondary text-uppercase m-0">Top Games</h3>
                                        	    <h4 class="h5 font-secondary text-uppercase text-white m-0">By Current Players</h4>
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
                                                	            <td><a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
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
        	                                <div class="d-flex justify-content-end border-top py-1 px-3"><a class="btn btn-primary btn-round waves-effect waves-light" href="https://gamecharts.org/<?php echo($source); ?>/player_count"> More </a></div>
                	                    </div><!--end card-body-->
                        	        </div><!--end card-->
	                            </div><!--end col-->
        	                </div>
				<div class="mobile-add">
	                                    <ins class="adsbygoogle mobile-add-1"
        	                                data-ad-client="ca-pub-2433076550762661"
                	                        data-ad-slot="4014468333">
					    </ins>
                        	            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
	                        </div>

        	                <div class="row">
                	            <!-- <h1>Top Games</h1> -->
                        	    <div class="col-12">
                                	<div id="top_game_slider" class="top_game_slider">
	                                    <?php foreach($topdata as $data):?>
        	                                <?php $i_game = json_decode(file_get_contents('./data/' . $source . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>
	
        	                                <div class="row ml-0 mr-1">
                	                            <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                        	                        <a href="./<?php echo($source); ?>/<?php echo($data->NameSEO); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data->Name);?>" /></a>
                                	            </div>
                                        	    
	                                            <div style="display: inline-block;padding: 0; height: 215px; overflow-y: scroll;" class="col-sm-12 col-md-7">
        	                                        <div class="" style="margin-left: 3px; margin-right: 3px">
                	                                    <?php echo strip_tags($i_game[0]->AboutGame);?>
                        	                        </div>
                                	            </div>
                                        	</div>

	                                    <?php endforeach;?>
        	                        </div>
                	            </div><!--end col-->
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
							    <th class="center">Today</th>
                        	                            <th class="center">Current Players</th>
                                	                </tr>
                                        	        </thead>
                                                	<tbody>
	                                                <?php
        	                                        if(count($trending_average)) {
	
        	                                            foreach($trending_average as $data){
                	                                        ?>
                        	                                <tr>
                                	                            <td><a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                        	                    <td class="text-success text-center" style="font-weight:800"><?php echo $data['change']?></td>
								    <td>
                                                                        <div class="chart-today" data-series='<?php echo json_encode($data['hist']) ?>'></div>
                                                                    </td>
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
        	                    <!-- <h1>Trending Games</h1> -->
                	            <div class="col-12">
                        	        <div id="trending_game_average_slider" class="trending_game_slider">
                                	    <?php foreach($trending_average as $data):?>
                                        	<?php $i_game = json_decode(file_get_contents('./data/' . $source . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

	                                        <div class="row ml-0 mr-1">
        	                                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                	                                <a href="./<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data['name']); ?>" alt="Go to <?php echo($data['name']); ?> site" /></a>
                        	                    </div>
                                            
                                	            <div style="display: inline-block;padding: 0; height: 215px; overflow-y: scroll;" class="col-sm-12 col-md-7">
                                        	        <div class="" style="margin-left: 3px; margin-right: 3px">
                                                	    <?php echo strip_tags($i_game[0]->AboutGame);?>
	                                                </div>
        	                                    </div>
                	                        </div>


                        	            <?php endforeach;?>
	                                </div>
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
                        	                                    <td><a a class="text-dark" style="font-weight:500" href="./<?php echo($source); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
								    <td class="center"><?php echo number_format((float)$data->LastCcu)?></td>
                                        	                    <td class="center text-gray"><?php echo number_format((float)$data->MaxAvg24h)?></td>
                                                	            <td class="center text-gray"><?php echo number_format((float)$data->MaxAvg30d)?></td>
                                                        	    <td class="center text-gray"><?php echo number_format((float)$data->MaxAvg)?></td>
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
        	                                <div class="d-flex justify-content-end border-top py-1 px-3"><a class="btn btn-primary btn-round waves-effect waves-light" href="https://gamecharts.org/<?php echo($source); ?>/player_average"> More </a></div>
                	                    </div><!--end card-body-->
                        	        </div><!--end card-->
	                            </div><!--end col-->
        	                </div>

				<div class="row">
                	        <!--  <h1>Top Games</h1> -->
                        	<div class="col-12">
	                            <div id="top_game_average_slider" class="top_game_slider">
        	                    <?php foreach($topdata_average as $data):?>
                               	
                                    <?php $i_game = json_decode(file_get_contents('./data/' . $source . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>
    
	    				<div class="row ml-0 mr-1">
                	                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
        	                                     <a href="./<?php echo($source); ?>/<?php echo($data->NameSEO); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data->Name);?>" /></a>
                	                        </div>
    
                        	                <div style="display: inline-block;padding: 0; height: 215px; overflow-y: scroll;" class="col-sm-12 col-md-7">
                                	            <div class="" style="margin-left: 3px; margin-right: 3px">
                                                <?php echo strip_tags($i_game[0]->AboutGame);?>
                                        	    </div>
	                                        </div>
	                                    </div>
                                    <?php endforeach;?>
	                                </div>
        	                    </div><!--end col-->
                	        </div>
			</div>
                </div><!-- container -->
                
            </div>

        </div>
        <!-- container -->
        <!-- end page content -->
    </div>

    <!--footer page-->

    <?php include ('footer.php'); ?>


        <!-- jQuery  -->
    <script src="https://gamecharts.org/assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="https://gamecharts.org/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://gamecharts.org/assets/js/waves.min.js"></script>
    <script src="https://gamecharts.org/assets/js/jquery.slimscroll.min.js"></script>

    <script src="https://gamecharts.org/assets/plugins/moment/moment.js"></script>

    <script src="https://gamecharts.org/assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="https://gamecharts.org/assets/pages/jquery.apexcharts.init.js"></script>

    <script src="https://gamecharts.org/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
    <script src="https://gamecharts.org/assets/pages/jquery.charts-sparkline.js"></script>

    <!-- App js -->
    <script src="https://gamecharts.org/assets/js/app.js"></script>
    <script src="https://gamecharts.org/assets/js/searchbox.js"></script>
    <script src="https://gamecharts.org/assets/js/checkCookie.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
    </body>
</html>
<script>
$(document).ready(function(){
    	$(".desktop-screen").show();

    	$('#top_game_slider').bxSlider({touchEnabled: false});
        $('#trending_game_slider').bxSlider({touchEnabled: false});
        $('#trending_game_average_slider').bxSlider({touchEnabled: false});
        $('#top_game_average_slider').bxSlider({touchEnabled: false});
        
});
</script>
