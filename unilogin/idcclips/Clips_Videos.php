<?php
include "../funcionesunistats.php";
/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	cJuego:CHARACTER
	iIDUsuario:INTEGER
	cFechaDesde:CHARACTER
	cFechaHasta:CHARACTER
	iLimite:INTEGER
	cSortByOpt:CHARACTER
	cSortOrderOpt:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tVideos:TEMP-TABLE
TEMP-TABLES:
	tVideos:
		DURACION:INTEGER
		FECHA_CREACION:CHARACTER
		FECHA_PUBLICACION:CHARACTER
		FUENTE:CHARACTER
		TITULO:CHARACTER
		IDUSUARIO:INTEGER
		IP:CHARACTER
		JUEGO:CHARACTER
		VIEWS:INTEGER
		LIKES:INTEGER
		DISLIKES:INTEGER
		COMMENTS:INTEGER
		VIDEO_LINK:CHARACTER
FORMAT: Clips_Videos.php?cLogin=&cPassword=&cJuego=&iIDUsuario=&cFechaDesde=&cFechaHasta=&iLimite=&cSortByOpt=&cSortOrderOpt=
*/
if (isset($_REQUEST['cLogin'])){
	$cLogin = ($_REQUEST['cLogin']);
}else{
	$cLogin = "";
}
if (isset($_REQUEST['cPassword'])){
	$cPassword = ($_REQUEST['cPassword']);
}else{
	$cPassword = "";
}
if (isset($_REQUEST['cJuego'])){
	$cJuego = ($_REQUEST['cJuego']);
}else{
	$cJuego = "";
}
if (isset($_REQUEST['iIDUsuario'])){
	$iIDUsuario = ($_REQUEST['iIDUsuario']);
}else{
	$iIDUsuario = "";
}
if (isset($_REQUEST['cFechaDesde'])){
	$cFechaDesde = ($_REQUEST['cFechaDesde']);
}else{
	$cFechaDesde = "";
}
if (isset($_REQUEST['cFechaHasta'])){
	$cFechaHasta = ($_REQUEST['cFechaHasta']);
}else{
	$cFechaHasta = "";
}
if (isset($_REQUEST['iLimite'])){
	$iLimite = ($_REQUEST['iLimite']);
}else{
	$iLimite = "";
}
if (isset($_REQUEST['cSortByOpt'])){
	$cSortByOpt = ($_REQUEST['cSortByOpt']);
}else{
	$cSortByOpt = "";
}
if (isset($_REQUEST['cSortOrderOpt'])){
	$cSortOrderOpt = ($_REQUEST['cSortOrderOpt']);
}else{
	$cSortOrderOpt = "";
}
if (isset($_REQUEST['xmlFormat'])){
	$xmlFormat = $_REQUEST['xmlFormat'];
}else {
	$xmlFormat = "0";
}

	$res = Clips_Videos($cLogin,$cPassword,$cJuego,$iIDUsuario,$cFechaDesde,$cFechaHasta,$iLimite,$cSortByOpt,$cSortOrderOpt);

	if ($xmlFormat == "1"){
		echo convertirXML ("Clips_Videos",$res);
	} else {
		echo json_encode ($res);
	}

function convertirXMLParametro ($parametros,$numPadres){
        $paramsRequest = "";
        for ($i = 1;$i < $numPadres; $i++) {
                $tabulacion = chr(9) . $tabulacion;
        }
        foreach($parametros as $parametro => $valor){
                if (is_array($valor) || is_object ($valor)){
                        $valorAux = convertirXMLParametro($valor, $numPadres + 1);
                        if (strstr($valorAux,-1) != "\n"){
                                $valorAux = $valorAux . "\n";
                        }
                        $paramRequest = <<<PARAMREQUEST
$tabulacion<$parametro>
$valorAux$tabulacion</$parametro>
PARAMREQUEST;

                }
                else {
                        $valorAux = $valor;
                        $paramRequest = <<<PARAMREQUEST
$tabulacion<$parametro>$valorAux</$parametro>
PARAMREQUEST;

                }
                if ($paramsRequest != ""){
                        if (substr($paramsRequest,-1) != "\n"){
                                $paramsRequest = $paramsRequest . "\n";
                        }
                }
                $paramsRequest = $paramsRequest . $paramRequest;
        }
        return $paramsRequest;
}

function convertirXML ($nombre,$parametros){
        $nombreEtiquetaResponse = $nombre . "Response";
        $xmlListaParametros = convertirXMLParametro($parametros,4);
        $xml = <<<REQUEST
<?xml version='1.0' encoding='UTF-8'?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
	<S:Body>
		<ns2:$nombreEtiquetaResponse xmlns:ns2="http://services.vlink.com.bo/">
$xmlListaParametros
		</ns2:$nombreEtiquetaResponse>
	</S:Body>
</S:Envelope>
REQUEST;

        return $xml;
}

?>