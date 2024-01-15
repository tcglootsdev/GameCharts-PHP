<?php
function addSiteToSitemap ($sitemapFile,&$sitesArray,$url, $path, $priority, $source){
	if (!in_array ($url,$sitesArray,true)){
		array_push ($sitesArray, $url);
		$xml = getSiteXml ($url, $path,$priority,$source);
		addToFile ($sitemapFile,$xml);		
		return false;
	}
	return true;
}

function getSiteXml($url,$path,$priority,$source){
	$lastMod = getModDate ($path);
	$xml =<<<SITEXML
$url;$source
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

function addTopStoreGamesPage ($sitemapFile,&$sitesArray,$siteBase,$store,$type,$page){
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
	if ($page >= 4){
		return false;
	}
	$gamedata = json_decode(file_get_contents($filename));
        foreach ($gamedata as $game){
		if (isset($game->NameSEO)){
	       		addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store . "/" . $game->NameSEO,$filename,"0.7","top$type");
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
                        addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store . "/" . $game->NameSEO,$filename,"0.7","trending$type");
                }
        }
        return true;
}


function addTopStoreGames ($sitemapFile,&$sitesArray,$siteBase,$store,$fileName){
	$num = 1;	
	while (addTopStoreGamesPage ($sitemapFile,$sitesArray,$siteBase,$store,$fileName,$num)){
		$num++;
	}
}

date_default_timezone_set('Europe/Madrid');
//echo date ("Y-m-d H:i:s\n");
$sitemapFile = fopen('sitemap.csv', 'w');
if ($sitemapFile === FALSE){
	echo "KO Coud not open sitemap2.xml";
	die();
}
$sitesArray = array();
$siteBase = "http://gamecharts.local";
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase,"./index.php","1.0","");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/privacy","./privacy.php","0.5","");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/about","./about.php","0.5","");
addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/cookies","./cookies","0.5","");
//addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/");
$dataPath = './data/store.json';
$stores = json_decode(file_get_contents($dataPath));

$aux = array();
foreach($stores as $store) {
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store,"./main.php","0.9","");
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store . "/player_count","./data/" . $store->Store . "ccu","0.8","");
    addSiteToSitemap ($sitemapFile,$sitesArray,$siteBase."/" . $store->Store . "/player_average","./data/" . $store->Store . "/top/maxavg.json","0.8","");
    addTopStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"ccu");
    addTopStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"avg");
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"all");
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"ccu");
    addTrendingStoreGames ($sitemapFile,$sitesArray,$siteBase,$store->Store,"avg");
}

fclose($sitemapFile);
?>
