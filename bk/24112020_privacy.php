<?php

    ini_set('display_errors',false);
    date_default_timezone_set("Europe/Madrid");
    $stores = json_decode(file_get_contents('http://gamecharts.local/data/store.json'));
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
        <link rel="shortcut icon" href="http://gamecharts.local/assets/images/favicon.ico">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link href="http://gamecharts.local/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="http://gamecharts.local/assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="http://gamecharts.local/assets/css/custom-highcharts.css" rel="stylesheet" type="text/css" />
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
<h1>Privacy Policy of GameCharts</h1>

<p>GameCharts operates the http://gamecharts.local website, which provides the SERVICE.</p>

<p>This page is used to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service, the GameCharts website.</p>

<p>If you choose to use our Service, then you agree to the collection and use of information in relation with this policy. The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.</p>

<p>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at http://gamecharts.local, unless otherwise defined in this Privacy Policy. Our Privacy Policy was created with the help of the <a href="https://www.privacypolicytemplate.net">Privacy Policy Template</a> and the <a href="https://www.disclaimergenerator.org/">Disclaimer Generator</a>.</p>

<h2>Information Collection and Use</h2>

<p>For a better experience while using our Service, we may require you to provide us with certain personally identifiable information, including but not limited to your name, phone number, and postal address. The information that we collect will be used to contact or identify you.</p>

<h2>Log Data</h2>

<p>We want to inform you that whenever you visit our Service, we collect information that your browser sends to us that is called Log Data. This Log Data may include information such as your computer’s Internet Protocol ("IP") address, browser version, pages of our Service that you visit, the time and date of your visit, the time spent on those pages, and other statistics.</p>

<h2>Cookies</h2>

<p>Cookies are files with small amount of data that is commonly used an anonymous unique identifier. These are sent to your browser from the website that you visit and are stored on your computer’s hard drive.</p>

<p>Our website uses these "cookies" to collection information and to improve our Service. You have the option to either accept or refuse these cookies, and know when a cookie is being sent to your computer. If you choose to refuse our cookies, you may not be able to use some portions of our Service.</p>

<p>For more general information on cookies, please read <a href="https://www.cookieconsent.com/what-are-cookies/">"What Are Cookies"</a>.</p>

<h2>Service Providers</h2>

<p>We may employ third-party companies and individuals due to the following reasons:</p>

<ul>
    <li>To facilitate our Service;</li>
    <li>To provide the Service on our behalf;</li>
    <li>To perform Service-related services; or</li>
    <li>To assist us in analyzing how our Service is used.</li>
</ul>

<p>We want to inform our Service users that these third parties have access to your Personal Information. The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.</p>

<h2>Security</h2>

<p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>

<h2>Links to Other Sites</h2>

<p>Our Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over, and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>

<p>Children's Privacy</p>

<p>Our Services do not address anyone under the age of 13. We do not knowingly collect personal identifiable information from children under 13. In the case we discover that a child under 13 has provided us with personal information, we immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us so that we will be able to do necessary actions.</p>

<h2>Changes to This Privacy Policy</h2>

<p>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.</p>

<h2>Contact Us</h2>

<p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us.</p>            </div>

        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="http://gamecharts.local/assets/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="http://gamecharts.local/assets/js/bootstrap.bundle.min.js"></script>
<script src="http://gamecharts.local/assets/js/waves.min.js"></script>
<script src="http://gamecharts.local/assets/js/jquery.slimscroll.min.js"></script>
<script src="http://gamecharts.local/assets/plugins/moment/moment.js"></script>
<script src="http://gamecharts.local/assets/plugins/apexcharts/apexcharts.min.js"></script>
<script src="http://gamecharts.local/assets/pages/jquery.apexcharts.init.js"></script>
<script src="http://gamecharts.local/assets/plugins/sparklines-chart/jquery.sparkline.min.js"></script>
<script src="http://gamecharts.local/assets/pages/jquery.charts-sparkline.js"></script>
<script src="assets/js/checkCookie.js"></script>
<script src="http://gamecharts.local/assets/js/app.js"></script>
<script src="http://gamecharts.local/assets/js/searchbox.js"></script>
</body>
</html>
