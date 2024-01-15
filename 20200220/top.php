<?php
	$baseURL = "./data/steam/top/ccu";
	$page = 0;
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	if($page < 0){
		$page = 0;
	}

	$topccuURL = $baseURL.'/topccu_'.$page.'.json';
	if(file_exists($topccuURL)){
		$topccu = file_get_contents($topccuURL);
		echo $topccu;
	}else{
		$dataURL = './data/steam/top/topccu.json';
    	$top_data = file_get_contents($dataURL);
    	echo $top_data;
	}
?>