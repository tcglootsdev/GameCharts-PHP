<?php
ini_set('display_errors', false);

$title = "Game Charts - Top and Trending Games Statistics";
$description = "GameCharts. This page shows Top Games and Trending of Games by current players or average players. You can see the summary of individual game of each platfrom.";
$canonical = "http://gamecharts.local";
$trending = array();
$trending_average = array();
$topdata = array();
$topdata_average = array();
$stores = array();
$baseURL = './data/';

$dataURL = $baseURL.'alltrendingccu.json';
$trending = json_decode(file_get_contents($dataURL));
$trending_arr = [];
foreach ($trending as $data) {
    if ($data->YesterdayCcu == 0) {
        $change = $data->CurrentCcu;
    } else {
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

function get_top_games($platform_name)
{
    $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
    return array_slice($top_games, 0, 5, true);
}

$dataURL = $baseURL.'alltrendingavg.json';
$trending_average = json_decode(file_get_contents($dataURL));
$trending_arr = [];
foreach ($trending_average as $data) {
    if ($data->YesterdayAverage == 0) {
        $change = $data->CurrentAverage;
    } else {
        $change = ((float)$data->CurrentAverage / (float)$data->YesterdayAverage - 1) * 100;
    }
    $todayURL = $baseURL.$data->Store.'/games_seo/'.$data->NameSEO.'/today.json';

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
foreach ($stores as $store) {
    $aux[$store->Store] = $store;
}
$stores = $aux;

?>

<!DOCTYPE html>
<html lang="en">
<!--  HEADER -->
<?php include('headerNew.php'); ?>

<body>
    <!--  NAVIGATION -->
    <?php include('navigation.php'); ?>

    <!--  SUB NAVIGATION -->
    <div class="row game-platforms">
        <span class="supported-platforms col-12 col-md-3 text-center">Supported Platforms</span>
        <!-- <span class="game-platforms-menu col-12 col-md-6"> -->
            <?php
            foreach ($stores as $store) {
                echo('<div class="col-6 col-md-2 text-center"><a href="http://gamecharts.local/'.$store->Store.'"><img height="45px" src="'.$store->Splash.'" alt="'.$store->Store.'" style="max-width:100%; max-height:45px;"/></a></div>');
            }
            ?>
            <!-- </span> -->
        </div>

        <!--  MAIN CONTENT -->
        <div class="page-wrapper page-wrapper-img">
            <div class="page-wrapper-inner-add align-items-center position-relative">
                <!-- Page Content-->
                <div class="container-fluid-add px-0">

                    <!-- desktop-screen-->
                    <div class="row-add desktop-screen justify-content-center mb-5 pb-3" style="display:none;">

                        <div class="col-lg-8-add colxs-12 game-list ">
                            <div class="desktop-ads-column-left">
                            </div>
                            <div class="desktop-ads-column-right">
                            </div>
                            <div class="content-column">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-gradient-grey">
                                                <div class="row justify-content-between">
                                                    <h3 class="h5 font-secondary text-uppercase m-0">Trending Games</h3>
                                                    <h4 class="h5 font-secondary text-uppercase text-white m-0">By Current
                                                    Players</h4>
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
                                                            if (count($trending)) {
                                                              foreach ($trending as $data) {
                                                                ?>
                                                                <tr>
                                                                    <td><a class="text-dark"
                                                                        href="http://gamecharts.local/<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a>
                                                                    </td>
                                                                    <td><a href="http://gamecharts.local/<?php echo($data["store"]); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                                                                        data-src="<?php echo($stores[$data["store"]]->Splash); ?>"
                                                                        alt="<?php echo($stores[$data["store"]]->Store)?>"
                                                                        style="max-width:75px; max-height:30px" /></a>
                                                                    </td>
                                                                    <td class="text-success text-center font-weight-900"><?php echo $data['change']?>
                                                                </td>
                                                                <td>
                                                                    <div class="chart-today text-center"
                                                                    data-series='<?php echo json_encode($data['hist']) ?>'>
                                                                </div>
                                                            </td>
                                                            <td class="text-center text-gray"><?php echo $data['ccu']?>
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
                </div>

                <div class="row">
                    <div class="col-12">
                        <div id="trending_game_slider" class="trending_game_slider">
                            <?php foreach ($trending as $data):?>
                                <?php $i_game = json_decode(file_get_contents('./data/' . $data['store'] . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

                                <div class="row ml-0 mr-1">
                                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                                        <a 
                                        href="http://gamecharts.local/<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                                        data-src="<?php echo($i_game[0]->Splash);?>"
                                        alt="<?php echo($data['name']); ?>" /></a>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-grey">
                            <div class="row justify-content-between">
                                <h3 class="h5 font-secondary text-uppercase m-0">Top Games</h3>
                                <h4 class="h5 font-secondary text-uppercase text-white m-0">By Current
                                Players</h4>
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
                                        if (count($topdata)) {
                                            $index = 1;
                                            foreach ($topdata as $data) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $index++?>.
                                                    </td>
                                                    <td><a class="text-dark"
                                                        href="http://gamecharts.local/<?php echo($data->Store); ?>/<?php echo($data->NameSEO); ?>"><?php echo $data->Name?></a>
                                                    </td>
                                                    <td><a
                                                        href="http://gamecharts.local/<?php echo($data->Store); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                                                        data-src="<?php echo($stores[$data->Store]->Splash); ?>"
                                                        alt="<?php echo($stores[$data->Store]->Store)?>"
                                                        style="max-width:75px; max-height:30px" /></a>
                                                    </td>
                                                    <td class="text-center"><?php echo number_format($data->LastCcu)?>
                                                </td>
                                                <td class="text-center text-gray"><?php echo number_format($data->TopCcu24h)?>
                                            </td>
                                            <td class="text-center text-gray"><?php echo number_format($data->TopCcu30d)?>
                                        </td>
                                        <td class="text-center text-gray"><?php echo number_format($data->TopCcu)?>
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
    <!--  <h1>Top Games</h1> -->
    <div class="col-12">
        <div id="top_game_slider" class="top_game_slider">
            <?php foreach ($topdata as $data):?>
                <?php $i_game = json_decode(file_get_contents('./data/' . $data->Store . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>

                <div class="row ml-0 mr-1">
                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                        <a
                        href="http://gamecharts.local/<?php echo($data->Store); ?>/<?php echo($data->NameSEO); ?>"><img class="lazyload blur-up" width="100%" height="100%"
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-grey">
                <div class="row justify-content-between">
                    <h3 class="h5 font-secondary text-uppercase m-0">Trending Games</h3>
                    <h4 class="h5 font-secondary text-uppercase text-white m-0">By Average
                    Players</h4>
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
                            if (count($trending_average)) {
                                foreach ($trending_average as $data) {
                                    ?>
                                    <tr>
                                        <td><a class="text-dark"
                                            href="http://gamecharts.local/<?php echo($data["store"])?>/<?php echo $data['app_id']?>"><?php echo $data['name']?></a>
                                        </td>
                                        <td><a
                                            href="http://gamecharts.local/<?php echo($data["store"]); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                                            data-src="<?php echo($stores[$data["store"]]->Splash); ?>"
                                            alt="<?php echo($stores[$data["store"]]->Store)?>"
                                            style="max-width:75px; max-height:30px" /></a>
                                        </td>
                                        <td class="text-success text-center font-weight-900"><?php echo $data['change']?>
                                    </td>
                                    <td>
                                        <div class="chart-today text-center"
                                        data-series='<?php echo json_encode($data['hist']) ?>'>
                                    </div>
                                </td>
                                <td class="text-center text-gray"><?php echo $data['ccu']?>
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
    <!--  <h1>Trending Games</h1> -->
    <div class="col-12">
        <div id="trending_game_average_slider" class="trending_game_slider">
            <?php foreach ($trending_average as $data):?>
                <?php $i_game = json_decode(file_get_contents('./data/' . $data['store'] . '/games_seo/' . $data['app_id'] . '/gameinfo.json'));?>

                <div class="row ml-1 mr-1">
                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                        <a
                        href="http://gamecharts.local/<?php echo($data['store']); ?>/<?php echo($data['app_id']); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                        data-src="<?php echo($i_game[0]->Splash);?>"
                        alt="<?php echo($data['name']); ?>" /></a>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-grey">
                <div class="row justify-content-between">
                    <h3 class="h5 font-secondary text-uppercase m-0">Top Games</h3>
                    <h4 class="h5 font-secondary text-uppercase text-white m-0">By Average
                    Players</h4>
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
                            if (count($topdata_average)) {
                                $index = 1;
                                foreach ($topdata_average as $data) {
                                    ?>
                                    <tr>
                                        <td><?php echo $index++?>.
                                        </td>
                                        <td><a class="text-dark"
                                            href="http://gamecharts.local/<?php echo($data->Store); ?>/<?php echo($data->NameSEO); ?>"><?php echo $data->Name?></a>
                                        </td>
                                        <td><a
                                            href="http://gamecharts.local/<?php echo($data->Store); ?>"><img class="lazyload blur-up" width="100%" height="100%"
                                            data-src="<?php echo($stores[$data->Store]->Splash); ?>"
                                            style="max-width:75px; max-height:30px"
                                            alt="Go to <?php echo $data->Name?> site" /></a>
                                        </td>
                                        <td class="text-center"><?php echo number_format((float)$data->LastCcu)?>
                                    </td>
                                    <td class="text-center text-gray"><?php echo number_format((float)$data->MaxAvg24h)?>
                                </td>
                                <td class="text-center text-gray"><?php echo number_format((float)$data->MaxAvg30d)?>
                            </td>
                            <td class="text-center text-gray"><?php echo number_format((float)$data->MaxAvg)?>
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
    <!--  <h1>Top Games</h1> -->
    <div class="col-12">
        <div id="top_game_average_slider" class="top_game_slider">
            <?php foreach ($topdata_average as $data):?>
                <?php $i_game = json_decode(file_get_contents('./data/' . $data->Store . '/games_seo/' . $data->NameSEO . '/gameinfo.json'));?>

                <div class="row ml-0 mr-1">
                    <div style="display: inline-block;padding: 0" class="col-12 col-md-5">
                        <a
                        href="http://gamecharts.local/<?php echo($data->Store); ?>/<?php echo($data->NameSEO); ?>"><img class="lazyload blur-up" width="100%" height="100%"
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






<?php include('footer.php'); ?>


<script>
    $(document).ready(function() {
        $(".desktop-screen").show();
                        //}

        $('#trending_game_slider').bxSlider({
            touchEnabled: false
        });
        $('#top_game_slider').bxSlider({
            touchEnabled: false
        });
        $('#trending_game_average_slider').bxSlider({
            touchEnabled: false
        });
        $('#top_game_average_slider').bxSlider({
            touchEnabled: false
        });

                        //(adsbygoogle = window.adsbygoogle || []).push({});
    });
</script>
</body>

</html>
