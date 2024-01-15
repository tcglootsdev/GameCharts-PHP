<?php


	date_default_timezone_set("Europe/Madrid");
	
	$source = 'default';
	if (isset($_GET['source']) && !empty($_GET['source'])) {
		$source = strtolower($_GET['source']);
	}
	$baseURL = './data/'.$source . "/";
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
        <link rel="shortcut icon" href="https://gamecharts.org/assets/images/favicon.ico">

        <!-- App css -->
        <link href="https://gamecharts.org/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="https://gamecharts.org/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="https://gamecharts.org/assets/css/style.css" rel="stylesheet" type="text/css" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43282477-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-43282477-5');
</script>

    </head>


    <body>
        <div class="page-wrapper">
           <br>
            <!--end page-wrapper-inner -->
            <!-- Page Content-->
            <div class="page-content" style="width:60%;margin-left:auto;margin-right:auto">
                <div class="container-fluid">
                	<div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-gradient-grey">
                                    <div class="d-flex flex-row justify-content-between">
                                        <h2 class="h5 font-secondary text-uppercase m-0">Trending</h2> 							
                                    </div>								
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                	<th>Name</th>
                                                	<th class="text-center">24-hour Change</th>
                                                	<!-- <th class="text-center">Last 48 hours</th> -->
                                                    <th class="text-center">Today</th>
                                                	<th class="text-center">Current Players</th>
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
                                                        <td class="text-center text-gray"><?php echo $data['ccu']?></td>
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

                </div><!-- container -->

                <footer class="footer text-center text-sm-left">
                    &copy; 2020 Game Charts
                </footer>
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- jQuery  -->
        <script src="https://gamecharts.org/assets/js/jquery.min.js"></script>
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

    </body>
</html>

<script>

$('#searchBox').submit(function(e){
	   e.preventDefault();
	   return false;
	});

	$('#searchBox').keydown(function (e) {
	    if (e.keyCode == 13) {
	        e.preventDefault();
	        return false;
	    }
	});

	$('#searchBox').keyup(function(){
		var value = $(this).val();
		if (value.length >= 3) { 

			var searchString = value.substring(0,3).toLowerCase();
			var gameList = $.getJSON( "./data/search/" + searchString + ".json", function( data ) {

				$('#searched_game').show();
				$('#searched_game').html('');
				var encontrado = false;
				var encontrados = 0;
			
				$.each( data, function( item, field ) {

					var gameName = field.Name.toLowerCase();
					var searchGame = gameName.substring(0,value.length).toLowerCase();
					var searchedGame = value.toLowerCase();
									
					if (searchedGame == searchGame) 
					{
						encontrado = true;
						encontrados++;
						var texto = '<div class="item" style="width:100%; text-align:right; padding-right:10px; height:60px; background-color: #fff; border: 2px solid #000; border-radius: 20px; margin-top:1px;" ><a style="top: 0;left: 260px;display: block;height: 0;line-height: 70px;width: initial;text-align: center;color: #a8a8b1;" href="./' + field.Source + '/' + field.AppId + '"><img src="' + field.Logo + '" style="max-height:60px; float:left;">' + field.Name + '<br/><br/>';
						texto = texto + '</a></div>';
						$('#searched_game').append(texto);
					}

					if (encontrados > 5) {
						return false;
					}
					
				  });
				
				if (!encontrado) {
					$('#searched_game').html(' Not Games Found ');
				}
				
			})
			.fail(function() {
				console.log( "error" );
			})
			.always(function() {
			});
		}
		else {
			$('#searched_game').hide();
		}
	});

                                                

</script>
