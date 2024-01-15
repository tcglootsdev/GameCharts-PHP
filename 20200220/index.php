<?php
    $baseURL = './data/steam/';
    $dataURL = $baseURL.'trending/trendingccu.json';
    $trending_data = json_decode(file_get_contents($dataURL));
    $trending_arr = [];
    foreach($trending_data as $data){
        if($data->YesterdayCcu == 0){
            $change = $data->CurrentCcu;
        }else{
            $change = ($data->CurrentCcu / (float)$data->YesterdayCcu - 1) * 100;
        }

        $todayURL = $baseURL.$data->AppID.'/today.json';
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
            'app_id'    =>  $data->AppID
        );
        $trending_arr[] = $info;
    }
    $trending_data = $trending_arr;

    $dataURL = './data/steam/top/topccu.json';
    $top_data = json_decode(file_get_contents($dataURL));
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Game Charts</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A premium admin dashboard template by mannatthemes" name="description" />
        <meta content="Mannatthemes" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
        <script data-ad-client="ca-pub-9457982685178503" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>


    </head>
    <style>
    	.center{
    		text-align: center;
    	}
    </style>

    <body class="boxed">

        <!-- Top Bar Start -->
        <div class="topbar">
             <!-- Navbar -->
             <nav class="navbar-custom">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="/" class="logo">
                        <span>
<!--                            <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">-->
                        </span>
                        <span style="font-size: 25px;color: grey; font-weight: 500">
<!--                            <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg">-->
                            gamecharts.org
                        </span>
                    </a>
                </div>



                <ul class="list-unstyled topbar-nav mb-0">
                    <li class="hide-phone app-search">
                        <form role="search" class="">
                            <input type="text" placeholder="Search..." class="form-control">
                            <a href=""><i class="fas fa-search"></i></a>
                        </form>
                    </li>

                </ul>

            </nav>
            <!-- end navbar-->
        </div>
        <!-- Top Bar End -->
       <br>
       <br>
       <br>
        <div class="page-wrapper">
           <br>
            <!--end page-wrapper-inner -->
            <!-- Page Content-->
            <div class="page-content" style="width:60%;margin-left:auto;margin-right:auto">
                <div class="container-fluid">
                	<div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <h4 class="header-title mt-0 mb-4">Trending</h4> -->
                                    <h2 style="color:#6c757d">Trending</h2>
                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                	<th>Name</th>
                                                	<th class="center">24-hour Change</th>
                                                	<!-- <th class="center">Last 48 hours</th> -->
                                                    <th class="center">Today</th>
                                                	<th class="center">Current Players</th>
                                            	</tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($trending_data as $data){
                                                ?>
                                                    <tr>
                                                        <td><a style="color:#303030;font-weight:500" href="detail.php?appid=<?php echo $data['app_id']?>&source=steam"><?php echo $data['name']?></a></td>
                                                        <td style="color:green;font-weight:800"><?php echo $data['change']?></td>
                                                        <td>
                                                           <div class="chart-today" data-series='<?php echo json_encode($data['hist']) ?>'></div>
                                                        </td>
                                                        <td class="center" style="color:grey"><?php echo $data['ccu']?></td>
                                                    </tr>
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
                                <div class="card-body">
                                    <!-- <h4 class="header-title mt-0 mb-4">Trending</h4> -->

                                    <span style="color:#6c757d;font-size:30px;font-weight:800">Top Games</span>
                                    <span style="color:#aca5ad;font-size:24px">By Current Players</span>
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
                                            <tbody id="topGameTable">
                                                <?php
                                                    $index = 1;
                                                    foreach($top_data as $data){
                                                ?>
                                                <tr>
                                                	<td><?php echo $index++?>.</td>
                                                    <td><a style="color:#303030;font-weight:500" href="detail.php?appid=<?php echo $data->AppID?>&source=steam"><?php echo $data->Name?></a></td>
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
                                                ?>
                                            </tbody>

                                        </table>
                                        <div style="float: right;">
                                            <a href="###" id="btnMore">More...</a>
                                        </div>

                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div>


                </div><!-- container -->

                <footer class="footer text-center text-sm-left">
                    &copy; 2020 Game Charts
                </footer>
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

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

        <script>
            var pageNum = 0;
            $("#btnMore").on('click',function(e){
                e.preventDefault();
                pageNum++;
                $.ajax({
                    url:'top.php?page=' + pageNum,
                    success:function(result){
                        if(result == ""){
                            $("#btnMore").hide();
                        }
                        var table = $("#topGameTable");
                        console.log(result);
                        var gameData = JSON.parse(result);
                        var i = 0, index = 0;
                        if(pageNum == 0 || pageNum == 1){
                            table.html("");    
                        }else{
                            i = 1;
                            index = table.children().length - 1;
                        }
                        for(; i < gameData.length; i++){
                            table.append(' <tr><td>' + (index + i + 1) + '.</td>' + 
                                            '<td><a style="color:#303030;font-weight:500" href="detail.php?appid=' + gameData[i]['AppID'] + '&source=steam">' + gameData[i]['Name'] + '</a></td>'+
                                            '<td class="center">' + parseInt(gameData[i]['LastCcu']).toLocaleString() + '</td>' +
                                            '<td class="center" style="color:grey">' + parseInt(gameData[i]['TopCcu']).toLocaleString() + '</td>' + 
                                            '<td class="center" style="color:grey">' + parseInt(gameData[i]['TopCcu24h']).toLocaleString() + '</td>' + 
                                            '<td class="center" style="color:grey">' + parseInt(gameData[i]['TopCcu30d']).toLocaleString() + '</td>' + 
                                            '</tr>'
                            );
                        }
                    },
                    error:function(){

                    }
                });

            });
        </script>


    </body>
</html>
