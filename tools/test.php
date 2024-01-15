<?php

function showLastModified ($file){
	if (!file_exists($file)){
		return;
	}
	$fileLastModified = filemtime($file);
         echo "$file: " . date ("Y-m-d H:i:s",$fileLastModified). "\n<br>";
}
	date_default_timezone_set('America/New_York');
echo "Hora actual: " . date ("Y-m-d H:i:s") . "\n<br>";
showLastModified("../data/steam/top/ccu/topccu_300.json");
showLastModified("../data/steam/top/ccu/topccu_301.json");
showLastModified("../data/steam/top/ccu/topccu_302.json");
	showLastModified("../data/steam/top/ccu/topccu_303.json");
	 showLastModified("../data/steam/top/ccu/topccu_304.json");
 showLastModified("../data/steam/top/ccu/topccu_305.json");
 showLastModified("../data/steam/top/ccu/topccu_306.json");
 showLastModified("../data/steam/top/ccu/topccu_307.json");
 showLastModified("../data/steam/top/ccu/topccu_308.json");
 showLastModified("../data/steam/top/ccu/topccu_309.json");


?>
