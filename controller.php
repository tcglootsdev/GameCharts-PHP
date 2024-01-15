<?php

	ini_set('display_errors',false);
    date_default_timezone_set("Europe/Madrid");
    $dataURL = './data/';
    $ahora = time();
    $hoy = date("dmy");
    $pagina = '';

    //	CARGA DE PARAMETROS
    if (isset($_GET['dataURL']) && !empty($_GET['dataURL'])) {
        $dataURL = $_GET['dataURL'];
    } else {

        $appid = '0';
	$source = 'default';

	if (isset($_GET['url']) && !empty($_GET['url'])) {

                $stringURL = strtolower($_GET['url']);
                $tok = strtok($stringURL, "/");

                if ($tok !== false) {
                        $source = $tok;
                        $tok = strtok("/");
                }

                if ($tok !== false) {
                        $appid = $tok;
                        $tok = strtok("/");
                }

                if ($tok !== false) {
                        $pagina = $tok;
                }
        }

	$dataURL .= $source . "/" . $appid . "/";

    }

    $actualYear = date("Y");
	
    $fechaOrigen = $actualYear.'-01-01';
    if (isset($_GET['inicio']) && !empty($_GET['inicio'])) {
        $fechaOrigen = $_GET['inicio'];
    }

    $fechaFinal = ($actualYear+1).'-01-01';
    if (isset($_GET['final']) && !empty($_GET['final'])) {
        $fechaFinal = $_GET['final'];
    }
    
    $page = '';
    if (isset($_GET['page']) && !empty($_GET['page'])) {
    	$page = strtolower($_GET['page']);
    } else if (!empty($pagina)) {
	$page = $pagina;
    }
    
    // CONTROLER

	if (isset($source) && !empty($source)) {
		if ($source == "search"){
			replaceQueryString ("search=$appid");
			include ("search.php");
			die();
		}
		if (isset($appid) && !empty($appid)) {
			if (is_numeric($appid)) {
				//$result = file_get_contents('http://gamecharts.local/game.php?source='.$source.'&appid='.$appid);
				replaceQueryString ("source=$source&appid=$appid");
				include "game.php";
			}
			else if ($appid == 'top' || $appid == "player_count") {
				//$result = file_get_contents('http://gamecharts.local/top.php?source='.$source.'&type=ccu&page='.$page);
				 replaceQueryString ("source=$source&type=ccu&page=$page");
				include "top.php";
			}
			else if ($appid == 'average'|| $appid == "player_average") {
				//$result = file_get_contents('http://gamecharts.local/top.php?source='.$source.'&type=avg&page='.$page);
				replaceQueryString ("source=$source&type=avg&page=$page");
                                include "top.php";
			}
			else if ($appid == 'trending') {
				//$result = file_get_contents('http://gamecharts.local/trending.php?source='.$source);
				replaceQueryString ("source=$source");
                                include "trending.php";
			}
			else if ($appid == 'data') {
				die();
			} else {
               			 //echo $appid; die(); //$result = file_get_contents('http://gamecharts.local/main.php?source='.$source);  //old
                		//$result = file_get_contents('http://gamecharts.local/game.php?source='.$source.'&nameseo='.$appid);
				replaceQueryString ("source=$source&nameseo=$appid");
                                include "game.php";
			}
		}
		else {
	    if (empty($source) || $source=='default') {
				//$result = file_get_contents('http://gamecharts.local/index.php');
				include "index.php";
	    }
            else if ($source == 'about') {
                //$result = file_get_contents('http://gamecharts.local/about.php');
		include "about.php";
            }
            else if ($source == 'privacy') {
                //$result = file_get_contents('http://gamecharts.local/privacy.php');
		include "privacy.php";
            }
            else if ($source == 'cookies') {
                //$result = file_get_contents('http://gamecharts.local/cookies.php');
		include "cookies.php";
            }
			else {
				//$result = file_get_contents('http://gamecharts.local/main.php?source='.$source);
				replaceQueryString ("source=$source");	
				include "main.php";
			}
		}
    }
    else {
    	//$result = file_get_contents('http://gamecharts.local/index.php');
	include "index.php";
    }

    //echo($result);
    function replaceQueryString ($newQueryString){
	$_SERVER['QUERY_STRING'] = $newQueryString;
	$_GET = proper_parse_str ($newQueryString);
    }
    
    function proper_parse_str($str) {
  # result array
  $arr = array();

  # split on outer delimiter
  $pairs = explode('&', $str);

  # loop through each pair
  foreach ($pairs as $i) {
    # split into name and value
    list($name,$value) = explode('=', $i, 2);
   
    # if name already exists
    if( isset($arr[$name]) ) {
      # stick multiple values into an array
      if( is_array($arr[$name]) ) {
        $arr[$name][] = $value;
      }
      else {
        $arr[$name] = array($arr[$name], $value);
      }
    }
    # otherwise, simply stick it in a scalar
    else {
      $arr[$name] = $value;
    }
  }

  # return result array
  return $arr;
}
