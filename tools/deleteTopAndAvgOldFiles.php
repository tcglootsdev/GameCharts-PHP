<?php

	function showOldFilesArray($path, $oldFilesArray){
		$num = 0;
		foreach ($oldFilesArray as $oldFile){
			$num++;
			echo "File ($num): ";
			var_dump($oldFile);
			echo "\n";
		}
	}

	function deleteOldFilesArray ($path, $oldFilesArray,&$resultArray){
		$status = true;
		foreach ($oldFilesArray as $oldFile){
			$filePath = pathCombine ($path, $oldFile['name']);
			if (unlink($filePath)===FALSE){
				$status = false;
				$fileStatus = new stdClass();
				$fileStatus->fileName = $filePath;
				$fileStatus->status = "Error deleting file";
				array_push ($resultArray,$fileStatus);
			}
			else {
				$fileStatus = new stdClass();
                                $fileStatus->fileName = $filePath;
                                $fileStatus->status = "Deleted";

				array_push ($resultArray,$fileStatus);
			}
                }
		return $status;
	}

	function pathCombine ($path,$file){
		return realpath ($path) . DIRECTORY_SEPARATOR . $file;
	}

	function createDebugJson ($fileName){
		$debugJson =<<<DEBUGJSON
{
	"createdFrom":"gamecharts.org/tools/deleteTopAndAvgOldFiles.php",
	"file": "$fileName"
}
DEBUGJSON;
		return $debugJson;
	}

	function createDebugFile ($path,$num){
		$fileName = pathCombine ($path,"deleteTopAndAverageOldFilesDebugFile$num.json");
		$debugJson = createDebugJson ($fileName);
		if (file_put_contents ($fileName,$debugJson) === FALSE){
			$fileStatus = new stdClass();
			$fileStatus->fileName = $fileName;
			$fileStatus->status = "Debug file creation error";
			$result = new stdClass();
			$result->result = "KO";
			$result->detail = $fileStatus;
			echo json_encode ($result);
			die(1);
		}
		return true;
	}

	function createDebugFiles ($path){
		$debugFilesArray = array();
		if (createDebugFile($path,1)){
			$debugFile = array("name"=>"deleteTopAndAverageOldFilesDebugFile1.json","modDate"=>date("d.m.Y"));
                        array_push ($debugFilesArray,$debugFile);
		}

		return $debugFilesArray;		
	}

	function getOldFilesArray ($path,$seconds){
		if (isset($_REQUEST['debug'])){
			return createDebugFiles($path);
		}
		$oldFilesArray = array();
		if ($handle = opendir($path)) {
			if ($handle ===FALSE){
				echo "Invalid path: $path\n";
				die();
			}
			$numDir = 0;
			while (false !== ($file = readdir($handle))) {
				$numDir ++;
				if ($file != "." && $file != ".." && !is_dir($path . "/" .$file)){
					$fileLastModified = filemtime($path . "/" .$file);
					//$todayDate = date('d.m.Y');
					//echo $file . " " . $fileLastModified . "<br>\n";
					if ((time() - $fileLastModified > $seconds))
					{
						$oldFile = array("name"=>$file,"modDate"=>date("d.m.Y H:i:s", $fileLastModified));
						array_push ($oldFilesArray,$oldFile);
					}
				}
			}
			closedir($handle);
		}
		return $oldFilesArray;
	}

	function fileModifiedInRange ($filePath, $seconds){
		if (!file_exists ($filePath)){
			return false;
		}
		$fileLastModified = filemtime($filePath);
		if (time() - $fileLastModified <= $seconds){
			return true;
		}
		return false;
	}

	date_default_timezone_set('America/New_York');
	$storesPath = '../data/store.json';
	$secondsFromLastModify = 2700;
	//$storesPath = "/var/www/vhosts/gamecharts.org/httpdocs/data/store.json";
	$stores = json_decode(file_get_contents($storesPath));
	if ($stores === FALSE){
		die(1);
	}
	$resultArray = array();
	$status = "OK";
	$showFiles = false;
	header('Content-Type: application/json');
	foreach ($stores as $store) {
		//$topCcuPath = "../data/" . $store->Store. "/top/ccu/";
		$topCcuPath = "/var/www/vhosts/gamecharts.org/httpdocs/data/" . $store->Store. "/top/ccu";
		//if the first file is not modified in range, we asume that files were not uploaded and we don't delete files
		if (fileModifiedInRange ($topCcuPath . "/topccu_1.json",$secondsFromLastModify)){
			$topCcuOldFiles = getOldFilesArray ($topCcuPath,$secondsFromLastModify);
			if ($showFiles){
				showOldFilesArray ($topCcuPath,$topCcuOldFiles);
			}
			else
			{
				if (!deleteOldFilesArray ($topCcuPath,$topCcuOldFiles,$resultArray)){
					$status = "KO";
				}
			}
		}
		$maxAvgPath = "/var/www/vhosts/gamecharts.org/httpdocs/data/" . $store->Store. "/top/avg";
		 //if the first file is not modified in range, we asume that files were not uploaded and we don't delete files
		if (fileModifiedInRange($maxAvgPath . "/topavg_1.json", $secondsFromLastModify)){
			$maxAvgOldFiles = getOldFilesArray ($maxAvgPath,$secondsFromLastModify);
			if ($showFiles){
				showOldFilesArray ($maxAvgPath,$maxAvgOldFiles);
			}
			else {
	        	        if (!deleteOldFilesArray ($maxAvgPath,$maxAvgOldFiles,$resultArray)){
					$status = "KO";
				}
			}
		}

	}
	$result = new stdClass();
	$result->status = $status;
	$result->detail = $resultArray;
	echo json_encode ($result);
	
?>
