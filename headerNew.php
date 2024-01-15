<head>
    <meta charset="utf-8" />
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description;?>"/>
    <meta name="keywords" content="player, game, chart, average, current, playerunknowns, 24-hours, platform, name, steam, idcgames, xbox, current players, average players, game chart." />
    <link rel="canonical" href="<?php echo $canonical;?>" />
    <?php if (isset ($nextPageRel)){
       echo $nextPageRel;
       echo $prevPageRel;
   }
   ?>
   <meta name = "twitter:title" content="<?php echo $title;?>">
   <meta name = "twitter:card" content="summary">
   <meta name = "twitter:site" content="@gamecharts">
   <meta name = "twitter:creator" content="@gamecharts">
   <meta name = "twitter:description" content="<?php echo $description;?>"/>
   <meta name = "twitter:image" content="https://gamecharts.org/assets/images/logo-1.png"/>
   <meta property = "og:type" content="website" />
   <meta property = "og:url" content="https://gamecharts.org"/>
   <meta property = "og:image" content="https://gamecharts.org/assets/images/logo-1.png"/>
   <meta property = "og:site_name" content="Gamecharts"/>
   <meta property = "og:title" content="Game Charts - <?php echo $title;?>" />
   <meta property = "og:description" content="<?php echo $description;?>"/>

   <link rel="preconnect dns-prefetch" href="https://www.googletagmanager.com">
   <link rel="preconnect dns-prefetch" href="https://cdn.jsdelivr.net">
   <link rel="preconnect dns-prefetch" href="https://gamecharts.com">
   <link rel="preconnect dns-prefetch" href="https://cdnjs.cloudflare.com">
   <link rel="preconnect dns-prefetch" href="https://kit.fontawesome.com">
   <link rel="preconnect dns-prefetch" href="https://steamcdn-a.akamaihd.net">


   <!-- App favicon -->
   <link rel="shortcut icon" href="assets/images/favicon.ico">
   <!-- App css -->
   <link href="https://gamecharts.org/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
   <link href="https://gamecharts.org/assets/css/icons.css" rel="stylesheet" type="text/css" />
   <link href="https://gamecharts.org/assets/css/styleNew.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css" />
   <script src="https://kit.fontawesome.com/6a7583b380.js" crossorigin="anonymous"></script>
   <!-- jQuery  -->
   <script src="https://gamecharts.org/assets/js/jquery.min.js"></script>
   <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
   <script src="https://gamecharts.org/assets/js/bootstrap.bundle.min.js"></script>
   <script defer src="https://gamecharts.org/assets/js/waves.min.js"></script>
   <script defer src="https://gamecharts.org/assets/js/jquery.slimscroll.min.js"></script>
   <script defer src="https://gamecharts.org/assets/plugins/moment/moment.js"></script>
   <script defer src="https://gamecharts.org/assets/plugins/apexcharts/apexcharts.min.js"></script>
   <script defer src="https://gamecharts.org/assets/pages/jquery.apexcharts.init.js"></script>
   <script defer src="https://gamecharts.org/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
   <script defer src="https://gamecharts.org/assets/pages/jquery.charts-sparkline.js"></script>
   <!-- App js -->
   <script defer src="https://gamecharts.org/assets/js/checkCookie.js"></script>
   <script defer src="https://gamecharts.org/assets/js/app.js"></script>
   <script defer src="https://gamecharts.org/assets/js/searchbox.js"></script>
   <script defer src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
   <script src="https://gamecharts.org/js/lazysizes.min.js" async=""></script>


   <!-- Global site tag (gtag.js) - Google Analytics -->
   <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43282477-5"></script>
   <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-43282477-5');
  </script>
  <script data-ad-client="ca-pub-2433076550762661" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
