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
		if (isset($appid) && !empty($appid)) {
			if (is_numeric($appid)) {
				$result = file_get_contents('http://gamecharts.local/game.php?source='.$source.'&appid='.$appid);
			}
			else if ($appid == 'top') {
				$result = file_get_contents('http://gamecharts.local/top.php?source='.$source.'&type=ccu&page='.$page);
			}
			else if ($appid == 'average') {
				$result = file_get_contents('http://gamecharts.local/top.php?source='.$source.'&type=avg&page='.$page);
			}
			else if ($appid == 'trending') {
				$result = file_get_contents('http://gamecharts.local/trending.php?source='.$source);
			}
			else if ($appid == 'data') {
				die();
			} else {
                //echo $appid; die();
                $result = file_get_contents('http://gamecharts.local/game.php?source='.$source.'&nameseo='.$appid);
				//$result = file_get_contents('http://gamecharts.local/main.php?source='.$source);
			}
		}
		else {

			if (empty($source) || $source=='default') {
				$result = file_get_contents('http://gamecharts.local/index.php');
			}
            else if ($source == 'about') {
                $result = file_get_contents('http://gamecharts.local/about.php');
            }
            else if ($source == 'privacy') {
                $result = file_get_contents('http://gamecharts.local/privacy.php');
            }
            else if ($source == 'cookies') {
                $result = file_get_contents('http://gamecharts.local/cookies.php');
            }
			else {
				$result = file_get_contents('http://gamecharts.local/main.php?source='.$source);
			}
		}
    }
    else {
    	$result = file_get_contents('http://gamecharts.local/index.php');
    }

    echo($result);
