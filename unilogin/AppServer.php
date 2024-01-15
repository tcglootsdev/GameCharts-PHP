<?php
$RUTA_LOGS = '/var/www/vhosts/gamecharts.org/logs/ws/'.date("Y-m-d_");

function AppServer($funcion,$parametros,$urn='unistats'){
	$tiempoInicio = get_micro_time();

	$fp = @fopen("../appPrimario.txt","rb");
	$leido = @fread($fp,1024);
	@fclose($fp);
	$leido = explode('#',$leido);
	$leido[1] = trim($leido[1]);
	$leido[0] = trim($leido[0]);
	ob_start(); //ante los posibles warnings de php, lanzo la salida al buffer
	try{
		$ruta = 'http://'.$leido[0].'/wsa/wsa1/wsdl?targetURI=urn:'.$urn;
		$client = new soapclient($ruta);
	}catch(Exception $e){
		try{
			$ruta = 'http://'.$leido[1].'/wsa/wsa1/wsdl?targetURI=urn:'.$urn;
			  $client = new soapclient($ruta,array('encoding'=>'UTF-8') );
			if (ob_get_length()) ob_end_clean(); //descargo el buffer solo en este caso, ya que de otra forma estará vacio
			activo($leido[1],$leido[0]);
		}
			catch(Exception $e){
			if (ob_get_length()) ob_end_clean();
			return "ERROR: -3 - ".$e->getMessage();
		}
	}
	try{
		$result = $client->$funcion($parametros);
	}catch (SoapFault $fault) {
		try{
			$ruta = 'http://'.$leido[1].'/wsa/wsa1/wsdl?targetURI=urn:'.$urn;
			  $client = new soapclient($ruta,array('encoding'=>'UTF-8'));
			if (ob_get_length()) ob_end_clean(); //descargo el buffer solo en este caso, ya que de otra forma estará vacio
			$result = $client->$funcion($parametros);
			activo($leido[1],$leido[0]);
		}catch(Exception $e){
			try{
				$ruta = 'http://'.$leido[0].'/wsa/wsa1/wsdl?targetURI=urn:'.$urn;
				$client = new soapclient($ruta,array('encoding'=>'UTF-8'));
				if (ob_get_length()) ob_end_clean(); //descargo el buffer solo en este caso, ya que de otra forma estará vacio
				$result = $client->$funcion($parametros);
				activo($leido[0],$leido[1]);
			}catch(Exception $e){
				if (ob_get_length()) ob_end_clean();
				return "ERROR: -1 - ".$e->getMessage();
			}
		}
	} 
	unset($client);
	$tiempoFin = get_micro_time();
	@alog($funcion.'.log', $_SERVER['SCRIPT_FILENAME'].' | '.$_SERVER['HTTP_REFERER'].' | '.json_encode($parametros) );
	@almacenar(($tiempoFin - $tiempoInicio),$funcion,$ruta,implode($parametros,"|"));
	return $result;
}
/* */
function activo($primario,$secundario){
    $fichero = @fopen('appPrimario.txt','w+');
    fwrite($fichero,$primario.'#'.$secundario);
    fclose($fichero);
}
function get_micro_time(){
        list($mseg,$seg) = explode(" ",microtime());
        return ((float)$mseg+(float)$seg);
}
function almacenar($tiempoTotal ,$funcion,$ruta,$parametros){
    $texto = date('d-m-Y H:i:s').': ' .$_SESSION['log']." $funcion $parametros $tiempoTotal $ruta ".$_SERVER['REQUEST_URI']. " " . $_SERVER['REMOTE_ADDR'] . " \n ";
    $hoy = date('Y').'.'.date('m').'.'.date('d');
    $archivo = @fopen('/var/www/vhosts/gamecharts.org/logs/ws/.'.$hoy.'.log','a+');
    @fwrite($archivo,$texto);
    @fclose($archivo); 
}


# Devuelve una cadena XML con los parátros pasados donde params y $nocdata es un array asociativo de nombre=>valor 
function returnXML($estado_respuesta, $codigo_respuesta, $texto_respuesta) {
        $resp = "<?xml version='1.0' encoding='UTF-8'?><data>";
        $resp .= "<estado_respuesta><![CDATA[$estado_respuesta]]></estado_respuesta>";
        $resp .= "<codigo_respuesta><![CDATA[$codigo_respuesta]]></codigo_respuesta>";
        $resp .= "<texto_respuesta><![CDATA[$texto_respuesta]]></texto_respuesta>";
        /*foreach ($params as $varname=>$value) {
                $resp .= "<$varname><![CDATA[$value]]></$varname>";
        }
        foreach ($nocdata as $varname=>$value) {
                $resp .= "<$varname>$value</$varname>";
        }*/
        header("Content-Type: text/xml");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: ".gmdate("D, j M Y H:i:s", time()+"600"));
        $resp.= "</data>";
        echo $resp;
}

if (!function_exists('alog')) {
	function alog($rutaLog, $texto){
		global $RUTA_LOGS;
		$fecha=date('Y-m-d H:i:s');
		$f=@fopen($RUTA_LOGS.$rutaLog,"a+");
		@fwrite($f,"$fecha | " . $texto . "\n");
		@fclose($f);
	}	
}
?>
