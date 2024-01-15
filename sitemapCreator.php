<?php
include "max_pages.php";
function addSiteToSitemap ($sitemapFile,&$sitesArray,$url, $path, $priority, $source, $frequence){
	if (!in_array ($url,$sitesArray,true)){
		array_push ($sitesArray, $url);
		$xml = getSiteXml ($url, $path,$priority,$source,$frequence);
		addToFile ($sitemapFile,$xml);		
		return false;
	}
	return true;
}

function getSiteXml($url,$path,$priority,$source,$frequence){
	$lastMod = getModDate ($path);
	$source = "";
	if ($source != ""){
		$source = "<!--$source-->\n";
	}
	$xml =<<<SITEXML
$source<url>
<loc>$url</loc>
<priority>$priority</priority>
<changefreq>$frequence</changefreq>
<lastmod>$lastMod</lastmod>
</url>
SITEXML;
	return $xml;
}

function getModDate ($filePath){
        if ($filePath == ""){
                return date ("Y-m-s");
        }
        if (file_exists($filePath)) {
                $modDate = date ("Y-m-d", filemtime($filePath));
        }
        else {
                $modDate = date ("Y-m-d");
        }
        return $modDate;
}

function addToFile ($file, $string){
	fwrite($file, $string . "\n");
}

function addTopStoreGamesPage ($sitemapFile,&$sitesArray,$siteBase,$store,$type,$page,$max_pages){
	/*if ($page == 1){
		if ($type == "ccu"){
		        $filename = "./data/$store/top/topccu.json";
		}
		else {
			$filename = "./data/$store/top/maxavg.json";
		}
	}*/
	if ($type == "ccu"){
                $filename = "./data/$store/top/$type/topccu_$page.json";
        }
        else {
                $filename = "./data/$store/top/$type/topavg_$page.json";
        }

	if (!file_exists ($filename)){
		return false;
	}
	//!!!!!!!!In order to check seo we can change max_pages value in max_pages.php so we don't check thousends of pages
	if ($max_pages > 0){
		if ( $page > $max_pages){
			return false;
		}
	}
	$gamedata = json_decode(file_get_contents($filename));
        foreach ($gamedata as $game){
		if (isset($game->NameSEO)){
	       		addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store . "/" . $game->NameSEO,$filename,"0.7","top$type","hourly");
		}
        }
	return true;
}

function addTrendingStoreGames ($sitemapFile,&$sitesArray,$siteBase,$store,$type){
        /*if ($page == 1){
                if ($type == "ccu"){
                        $filename = "./data/$store/top/topccu.json";
                }
                else {
                        $filename = "./data/$store/top/maxavg.json";
                }
        }*/
	if ($type == "all"){
		$filename = "./data/$store/alltrending.json";
	}
	else {
	        $filename = "./data/$store/trending/trending$type.json";
	}
	if (!file_exists ($filename)){
                return false;
        }
        $gamedata = json_decode(file_get_contents($filename));
        foreach ($gamedata as $game){
                if (isset($game->NameSEO)){
                        addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store . "/" . $game->NameSEO,$filename,"0.7","trending$type","daily");
                }
        }
        return true;
}


function addTopStoreGames ($sitemapFile,&$sitesArray,$siteBase,$store,$fileName,$max_pages){
	$num = 1;	
	while (addTopStoreGamesPage ($sitemapFile,$sitesArray,$siteBase,$store,$fileName,$num,$max_pages)){
		$num++;
	}
}

date_default_timezone_set('Europe/Madrid');
//echo date ("Y-m-d H:i:s\n");
$sitemapFile = fopen('sitemap2.xml', 'w');
if ($sitemapFile === FALSE){
	echo "KO Coud not open sitemap2.xml";
	die();
}
addToFile ($sitemapFile,"<?xml version=\"1.0\" encoding=\"utf-8\"?>");
addToFile ($sitemapFile,"<urlset>");
$sitesArray = array();
$siteBase = "http://gamecharts.local";
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase,"./index.php","1.0","","hourly");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/privacy","./privacy.php","0.5","","monthly");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/about","./about.php","0.5","","monthly");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/cookies","./cookies","0.5","","monthly");
//addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/");
$dataPath = './data/store.json';
$stores = json_decode(file_get_contents($dataPath));

$aux = array();
foreach($stores as $store) {
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store,"./top/topccu.json","0.9","","hourly");
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store . "/player_count","./data/" . $store->Store . "ccu","0.8","","hourly");
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store . "/player_average","./data/" . $store->Store . "/top/maxavg.json","0.8","","hourly");
    addTopStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"ccu",$max_pages);
    addTopStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"avg",$max_pages);
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"all");
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"ccu");
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"avg");
}
addToFile ($sitemapFile,"</urlset>");

fclose($sitemapFile);
rename ("sitemap2.xml","sitemap.xml");
echo "{\"result\":\"OK\"}";
//echo date ("Y-m-d H:i:s\n");
?>
