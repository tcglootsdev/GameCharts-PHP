<?php
ini_set('display_errors',false);

$trending = array();
$trending_average = array();
$topdata = array();
$topdata_average = array();
$stores = array();
$baseURL = './data/';

$dataURL = $baseURL.'alltrendingccu.json';
$trending = json_decode(file_get_contents($dataURL));
$trending_arr = [];
foreach($trending as $data)
{
    if($data->YesterdayCcu == 0){
        $change = $data->CurrentCcu;
    }else{
        $change = ($data->CurrentCcu / (float)$data->YesterdayCcu - 1) * 100;
    }
    $todayURL = $baseURL.$data->Store.'/games_seo/'.$data->NameSEO.'/today.json';

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
        'store'   =>  $data->Store
    );
    $trending_arr[] = $info;
}
$trending = $trending_arr;

function get_top_games($platform_name){
    $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
    return array_slice($top_games, 0, 5, true);
}

$dataURL = $baseURL.'alltrendingavg.json';
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
        'app_id'    =>  $data->NameSEO,
        'store'   =>  $data->Store
    );
    $trending_arr[] = $info;
}
$trending_average = $trending_arr;


$dataURL = $baseURL.'alltopccu.json';
$topdata = json_decode(file_get_contents($dataURL));

$dataURL = $baseURL.'allmaxavg.json';
$topdata_average = json_decode(file_get_contents($dataURL));

$dataURL = $baseURL.'store.json';
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
<?php include('header.php'); ?>

<body>
<!--  NAVIGATION -->
<?php include('navigation.php'); ?>

<!--  SUB NAVIGATION -->
<div class="row game-platforms">
	<span class="supported-platforms col-12 col-md-3 text-center">Supported Platforms</span>
    <!-- <span class="game-platforms-menu col-12 col-md-6"> -->
        <?php
        foreach($stores as $store) {
          echo('<div class="col-6 col-md-2 text-center"><a href="https://gamecharts.org/'.$store->Store.'"><img src="'.$store->Splash.'" alt="'.$store->Store.'" style="max-width:100%; max-height:45px;"/></a></div>');
        }
        ?>
    <!-- </span> -->
</div>

<!--  MAIN CONTENT -->
<div class="page-wrapper page-wrapper-img">
    <div class="page-wrapper-inner align-items-center position-relative">
        <!-- Page Content-->
        <div class="container-fluid px-0">
        
            <!-- desktop-screen-->
            <div class="row desktop-screen justify-content-center mb-5 pb-3" style="display:none;">
            
                <?php
                $zone = 1;
                include('ads.php'); 
                ?>
                
                <div class="col-lg-8 col-xs-12 game-list">
                    
                      <?php
                    $zone = 6;
                    include('ads.php'); 
                    ?>
                    
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-gradient-grey">
                                    <div class="row justify-content-between">
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
                                                <th>Platform</th>
                                                <th class="text-center">24-hour Change</th>
                                                <th class="text-center">Today</th>
                                                <th class="text-center">Current Players</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(count($trending)) {

                                                foreach($trending as $data){
                                                    ?>
                                                    <tr>
                                                        <td><a class="text-dark" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                                        <td><a href="./<?php echo($data["store"]); ?>"><img src="<?php echo($stores[$data["store"]]->Splash);?>" alt="<?php echo($stores[$data["store"]]->Store)?>" style="max-width:75px; max-height:30px"/></a>
                                                        <td class="text-success text-center font-weight-900"><?php echo $data['change']?></td>
                                                        <td>
                                                            <div class="chart-today text-center" data-series='<?php echo json_encode($data['hist']) ?>'></div>
                                                        </td>
                                                        <td class="text-center text-gray"><?php echo $data['ccu']?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else {
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
                        <!--  <h1>Trending Games</h1> -->
                        <div class="col-12">
                            <div id="trending_game_slider" class="trending_game_slider">
                                <?php foreach($trending as $data):?>
                                    <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data['store'] . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

                                    <div class="row ml-1 mr-1">
                                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                            <a href="./<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data['name']); ?>" /></a>
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
                                    <div class="row justify-content-between">
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
                                                <th>Platform</th>
                                                <th class="text-center">Current Players</th>
                                                <th class="text-center">24-hour peak</th>
                                                <th class="text-center">30-days peak</th>
                                                <th class="text-center">Peak Players</th>
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
                                                        <td><a class="text-dark" href="./<?php echo($data->Store); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
                                                        <td><a href="./<?php echo($data->Store); ?>"><img src="<?php echo($stores[$data->Store]->Splash);?>"  alt="<?php echo($stores[$data->Store]->Store)?>" style="max-width:75px; max-height:30px"/></a></td>
                                                        <td class="text-center"><?php echo number_format($data->LastCcu)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->TopCcu24h)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->TopCcu30d)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->TopCcu)?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
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
                        <!--  <h1>Top Games</h1> -->
                        <div class="col-12">
                            <div id="top_game_slider" class="top_game_slider">
                                <?php foreach($topdata as $data):?>
                                    <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data->Store . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>

                                    <div class="row ml-1 mr-1">
                                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                             <a href="./<?php echo($data->Store); ?>/<?php echo($data->AppID); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data->Name);?>" /></a>
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
                    
                     <?php
                        $zone = 0;
                        include('ads.php'); 
                      ?>
                    
                    <?php
                        $zone = 4;
                        include('ads.php'); 
                    ?>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-gradient-grey">
                                    <div class="row justify-content-between">
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
                                                <th>Platform</th>
                                                <th class="text-center">24-hour Change</th>
                                                <th class="text-center">Current Players</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(count($trending_average)) {

                                                foreach($trending_average as $data){
                                                    ?>
                                                    <tr>
                                                        <td><a class="text-dark" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                                        <td><a href="./<?php echo($data["store"]); ?>"><img src="<?php echo($stores[$data["store"]]->Splash);?>"  alt="<?php echo($stores[$data["store"]]->Store)?>" style="max-width:75px; max-height:30px"/></a></td>
                                                        <td class="text-success text-center font-weight-900"><?php echo $data['change']?></td>
                                                        <td class="text-center text-gray"><?php echo $data['ccu']?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else {
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
                        <!--  <h1>Trending Games</h1> -->
                        <div class="col-12">
                            <div id="trending_game_average_slider" class="trending_game_slider">
                                <?php foreach($trending_average as $data):?>
                                    <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data['store'] . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

                                    <div class="row ml-1 mr-1">
                                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                            <a href="./<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data['name']); ?>" /></a>
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
                                    <div class="row justify-content-between">
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
                                                <th>Platform</th>
                                                <th class="text-center">Average Players</th>
                                                <th class="text-center">24-hour Average</th>
                                                <th class="text-center">30-days Average</th>
                                                <th class="text-center">Max Average Players</th>
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
                                                        <td><a class="text-dark" href="./<?php echo($data->Store); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
                                                        <td><a href="./<?php echo($data->Store); ?>"><img src="<?php echo($stores[$data->Store]->Splash);?>" style="max-width:75px; max-height:30px" alt="Go to <?php echo $data->Name?> site"/></a></td>
                                                        <td class="text-center"><?php echo number_format($data->LastCcu)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->MaxAvg24h)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->MaxAvg30d)?></td>
                                                        <td class="text-center text-gray"><?php echo number_format($data->MaxAvg)?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            else
                                            {
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
                        <!--  <h1>Top Games</h1> -->
                        <div class="col-12">
                            <div id="top_game_average_slider" class="top_game_slider">
                                <?php foreach($topdata_average as $data):?>
                                    <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data->Store . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>
    
                                    <div class="row ml-1 mr-1">
                                        <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                             <a href="./<?php echo($data->Store); ?>/<?php echo($data->AppID); ?>"><img src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo($data->Name);?>" /></a>
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
                    
                     <?php
                    $zone = 5;
                    include('ads.php'); 
                    ?>
                    
                    
                </div>
                
                
            
            	<?php
                $zone = 2;
                include('ads.php'); 
                ?>
            </div>
        </div>



	 	
		<?php /* ?>
        <!--mobile-screen-->
        <div class="row mobile-screen justify-content-center mb-5 pb-3" style="display:none;">
            <div class="row col-12">
                <div class="card">
                    <div class="card-header bg-gradient-grey">
                        <div class="row justify-content-between">
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
                                    <th>Platform</th>
                                    <th class="text-center">24-hour Change</th>
                                    <th class="text-center">Today</th>
                                    <th class="text-center">Current Players</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(count($trending)) {

                                    foreach($trending as $data){
                                        ?>
                                        <tr>
                                            <td><a class="text-dark" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                            <td><a href="./<?php echo($data['store']);?>"><img src="<?php echo($stores[$data["store"]]->Splash);?>"  alt="<?php echo($stores[$data["store"]]->Store)?>" style="max-width:75px; max-height:30px"/></a>
                                            <td class="text-success text-center font-weight-900"><?php echo $data['change']?></td>
                                            <td>
                                                <div class="chart-today text-center" data-series='<?php echo json_encode($data['hist']) ?>'></div>
                                            </td>
                                            <td class="text-center text-gray"><?php echo $data['ccu']?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else {
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
            <div class="row col-12">
                <div class="add-2">
                    <span class="h4 text-white text-center p-3"> Add 2: 600x 300px</span>
                </div>
            </div>
            <div class="row col-12" style="margin-top: 1em;">
                <div class="card">
                    <div class="card-header bg-gradient-grey">
                        <div class="row justify-content-between">
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
                                    <th>Platform</th>
                                    <th class="text-center">Current Players</th>
                                    <th class="text-center">24-hour peak</th>
                                    <th class="text-center">30-days peak</th>
                                    <th class="text-center">Peak Players</th>
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
                                            <td><a class="text-dark" href="./<?php echo($data->Store); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
                                            <td><a href="<?php echo($stores[$data->Store]->Url);?>"><img src="<?php echo($stores[$data->Store]->Splash);?>" style="max-width:75px; max-height:30px" alt="Go to <?php echo $data->Name?> site" /></a></td>
                                            <td class="text-center"><?php echo number_format($data->LastCcu)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->TopCcu24h)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->TopCcu30d)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->TopCcu)?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
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
            <div class="col-12 align-items-center row justify-content-center mb-4">
                <div class="add-4">
                    <span class="h4 text-white text-center mt-0"> Add 4: 728x 90px</span>
                </div>
            </div>
            <div class="row col-12">
                <div class="card">
                    <div class="card-header bg-gradient-grey">
                        <div class="row justify-content-between">
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
                                    <th>Platform</th>
                                    <th class="text-center">24-hour Change</th>
                                    <th class="text-center">Current Players</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(count($trending_average)) {

                                    foreach($trending_average as $data){
                                        ?>
                                        <tr>
                                            <td><a class="text-dark" href="./<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a></td>
                                            <td><a href="<?php echo($stores[$data["store"]]->Url);?>"><img src="<?php echo($stores[$data["store"]]->Splash);?>" style="max-width:75px; max-height:30px" alt="Go to <?php echo $data['name']?> site"/></a></td>
                                            <td class="text-success text-center font-weight-900"><?php echo $data['change']?></td>
                                            <td class="text-center text-gray"><?php echo $data['ccu']?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else {
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
            <div class="row col-12">
                <div class="add-3">
                    <span class="h4 text-white text-center p-3"> Add 3: 600x 300px</span>
                </div>
            </div>
            <div class="row col-12" style="margin-top: 1em;">
                <div class="card">
                    <div class="card-header bg-gradient-grey">
                        <div class="row justify-content-between">
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
                                    <th>Platform</th>
                                    <th class="text-center">Average Players</th>
                                    <th class="text-center">24-hour Average</th>
                                    <th class="text-center">30-days Average</th>
                                    <th class="text-center">Max Average Players</th>
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
                                            <td><a class="text-dark" href="./<?php echo($data->Store); ?>/<?php echo ($data->NameSEO); ?>"><?php echo $data->Name?></a></td>
                                            <td><a href="<?php echo($stores[$data->Store]->Url);?>"><img src="<?php echo($stores[$data->Store]->Splash);?>" style="max-width:75px; max-height:30px" alt="Go to <?php echo $data->Name?> site" /></a></td>
                                            <td class="text-center"><?php echo number_format($data->LastCcu)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->MaxAvg24h)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->MaxAvg30d)?></td>
                                            <td class="text-center text-gray"><?php echo number_format($data->MaxAvg)?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
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
            <div class="row">
                    <div class="col-12">
                      <h2 class="ml-4">Trending Games</h2>
                        <div id="trending_game_slider_mobile" class="trending_game_slider_mobile">
                            <?php foreach($trending as $data):?>
                                <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data['store'] . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

                                <div class="row ml-4 mr-2">
                                    <div style="display: block; width: 100%" >
                                        <img class="img-thumnail" src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo ($i_game[0]->Name);?>"/>
                                    </div>


                                    <div style="display: block; height: 215px; overflow-y: scroll; width: 100%">

                                        <?php echo ($i_game[0]->AboutGame);?>
                                    </div>
                                </div>


                            <?php endforeach;?>
                        </div>
                    </div><!--end col-->
            </div>
            <div class="row">
                    <div class="col-12">
                      <h2 class="ml-4">Top Games</h2>
                        <div id="top_game_slider_mobile" class="top_game_slider_mobile">
                            <?php foreach($topdata as $data):?>
                                <?php $i_game = json_decode(file_get_contents('https://gamecharts.org/data/' . $data->Store . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>

                                <div class="row ml-4 mr-2">
                                    <div style="display: block; width: 100%" >
                                        <img class="img-thumnail" src="<?php echo ($i_game[0]->Splash);?>" alt="<?php echo ($i_game[0]->Name);?>" />
                                    </div>


                                    <div style="display: block; height: 215px; overflow-y: scroll; width: 100%">
                                        <?php echo ($i_game[0]->AboutGame);?>
                                    </div>
                                </div>


                            <?php endforeach;?>
                        </div>
                    </div><!--end col-->
            </div>
        </div>
        <?php */?>
    </div>
    <!-- container -->
    <!-- end page content -->
</div>


<?php include('footer.php'); ?>


<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/waves.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/moment/moment.js"></script>
<script src="assets/plugins/apexcharts/apexcharts.min.js"></script>
<script src="assets/pages/jquery.apexcharts.init.js"></script>
<script src="assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="assets/pages/jquery.charts-sparkline.js"></script>
<!-- App js -->
<script src="assets/js/checkCookie.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/searchbox.js"></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
<script>
	/*
    $("body").on("slide.bs.carousel","#sliderhome",function(){
        $(".animatable").addClass("fadeInLeft");
        $(".animatable-custom-left").addClass("slideInLeftCustom");
        $(".animatable-custom-right").addClass("slideInRightCustom");
        $(".animatable-custom-gradient").addClass("bg-gradient-animation");
        $(".animatable-up").addClass("fadeInUp");
    });
    $('#sliderhome').carousel({
        interval: 6000,
        pause: false
    });
    */
    $(document).ready(function (){
        $(".desktop-screen").show();
        
        $('#trending_game_slider').bxSlider({touchEnabled: false});
        $('#top_game_slider').bxSlider({touchEnabled: false});
        $('#trending_game_average_slider').bxSlider({touchEnabled: false});
        $('#top_game_average_slider').bxSlider({touchEnabled: false});
		
		(adsbygoogle = window.adsbygoogle || []).push({});
    });
    
</script>
</body>
</html>
