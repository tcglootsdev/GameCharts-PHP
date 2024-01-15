<?php

    ini_set('display_errors',false);
    date_default_timezone_set("Europe/Madrid");
    $stores = json_decode(file_get_contents('https://gamecharts.org/data/store.json'));
    function get_top_games($platform_name){
        $top_games = json_decode(file_get_contents('./data/' . $platform_name . '/top/topccu.json'));
        return array_slice($top_games, 0, 5, true);
    }   
?>
<html>

<head>
    <meta charset="utf-8" />
    <title>Game Charts Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- App favicon -->
        <link rel="shortcut icon" href="https://gamecharts.org/assets/images/favicon.ico">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="https://gamecharts.org/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="https://gamecharts.org/assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="https://gamecharts.org/assets/css/custom-highcharts.css" rel="stylesheet" type="text/css" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43282477-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-43282477-5');
</script>

</head>

<body style="overflow-x: hidden">

<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>


<?php include('navigation.php'); ?>

<div class="row game-platforms" style="background: #f2f5f7"></div>
<div class="page-wrapper">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h1 class="mt-2">About</h1>
            <div class="mt-2 content">
                <p>Measuring the trends of games on the main stores can give some great insights,
                and this website aims to be a valuable tool to do so. An unpopular game does
                not necessarily indicate a bad game, and vice versa. If you have any questions
                or feedback please contact via email.</p>

                <p>This website is facilitated by a web frontend service and a data
                collector service that queries the most populars games stores. The
                collector queries the number of concurrent players on an hourly interval
                for every single game in the platforms catalog, and it has been collecting data
                since January of 2020.</p>
                
                <p>Website and services are hosted by <a
                    href="https://www.ovh.com/">OVH
                </a></p>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<?php include ('footer.php'); ?>

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
<script src="assets/js/checkCookie.js"></script>
<script src="https://gamecharts.org/assets/js/app.js"></script>
<script src="https://gamecharts.org/assets/js/searchbox.js"></script>
</body>
</html>
