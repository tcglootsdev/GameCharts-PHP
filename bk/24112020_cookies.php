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
    <title>Game Charts Privacy</title>
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
            <div class="content">
<h1>Cookie Policy for gamecharts</h1>

<p>This is the Cookie Policy for gamecharts, accessible from https://gamecharts.org</p>

<p><strong>What Are Cookies</strong></p>

<p>As is common practice with almost all professional websites this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or 'break' certain elements of the sites functionality.</p>

<p>For more general information on cookies, please read <a href="https://www.cookieconsent.com/what-are-cookies/">"What Are Cookies"</a>.</p>

<p><strong>How We Use Cookies</strong></p>

<p>We use cookies for a variety of reasons detailed below. Unfortunately in most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.</p>

<p><strong>Disabling Cookies</strong></p>

<p>You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of the this site. Therefore it is recommended that you do not disable cookies.</p>

<p><strong>The Cookies We Set</strong></p>

<ul>







<li>
    <p>Site preferences cookies</p>
    <p>In order to provide you with a great experience on this site we provide the functionality to set your preferences for how this site runs when you use it. In order to remember your preferences we need to set cookies so that this information can be called whenever you interact with a page is affected by your preferences.</p>
</li>

</ul>

<p><strong>Third Party Cookies</strong></p>

<p>In some special cases we also use cookies provided by trusted third parties. The following section details which third party cookies you might encounter through this site.</p>

<ul>

<li>
    <p>This site uses Google Analytics which is one of the most widespread and trusted analytics solution on the web for helping us to understand how you use the site and ways that we can improve your experience. These cookies may track things such as how long you spend on the site and the pages that you visit so we can continue to produce engaging content.</p>
    <p>For more information on Google Analytics cookies, see the official Google Analytics page.</p>
</li>




<li>
    <p>The Google AdSense service we use to serve advertising uses a DoubleClick cookie to serve more relevant ads across the web and limit the number of times that a given ad is shown to you.</p>
    <p>For more information on Google AdSense see the official Google AdSense privacy FAQ.</p>
</li>





</ul>

<p><strong>More Information</strong></p>

<p>Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site. This Cookies Policy was created with the help of the <a href="https://www.cookiepolicygenerator.com">Cookies Policy Template Generator</a> and the <a href="https://www.privacypolicytemplate.net/">Privacy Policy Template Generator</a>.</p>

<p>However if you are still looking for more information then you can contact us through one of our preferred contact methods:</p>

<ul>
<li>Email: admin@gamecharts.org</li>

</ul>
      
</div>
    </div>
        <div class="col-md-3"></div>
    </div>
</div>

<?php include('footer.php'); ?>

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
