<?php
include "AppServer.php";

/*
INPUT: 
	cMonedaBase:CHARACTER
	cFecha:CHARACTER
OUTPUT:
	TCambioMoneda:TEMP-TABLE
TEMP-TABLES:
	tCambioMoneda:
*/
function CambioMoneda($cMonedaBase,$cFecha){

	$cMonedaBase= urlencode ($cMonedaBase);
	$cFecha= urlencode ($cFecha);

	$funcion = "CambioMoneda";
	$parametros = array('cMonedaBase'=>$cMonedaBase,'cFecha'=>$cFecha);
	$res = AppServer($funcion,$parametros);

	$retorno['TCambioMoneda'] = $res->TCambioMoneda;
	return $retorno;
}

/*
INPUT: 
	cIP:CHARACTER
OUTPUT:
	cIsoCC:CHARACTER
	cRegion:CHARACTER
*/
function CheckIPCountry($cIP){

	$cIP= urlencode ($cIP);

	$funcion = "CheckIPCountry";
	$parametros = array('cIP'=>$cIP);
	$res = AppServer($funcion,$parametros);

	$retorno['cIsoCC'] = urldecode($res->cIsoCC);
	$retorno['cRegion'] = urldecode($res->cRegion);
	return $retorno;
}

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
*/
function Clips_Videos($cLogin,$cPassword,$cJuego,$iIDUsuario,$cFechaDesde,$cFechaHasta,$iLimite,$cSortByOpt,$cSortOrderOpt){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cJuego= urlencode ($cJuego);
	$cFechaDesde= urlencode ($cFechaDesde);
	$cFechaHasta= urlencode ($cFechaHasta);
	$cSortByOpt= urlencode ($cSortByOpt);
	$cSortOrderOpt= urlencode ($cSortOrderOpt);

	$funcion = "Clips_Videos";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'cJuego'=>$cJuego,'iIDUsuario'=>$iIDUsuario,'cFechaDesde'=>$cFechaDesde,'cFechaHasta'=>$cFechaHasta,'iLimite'=>$iLimite,'cSortByOpt'=>$cSortByOpt,'cSortOrderOpt'=>$cSortOrderOpt);
	$res = AppServer($funcion,$parametros);

	if (isset($res->iResultado)){
		$retorno['iResultado'] = $res->iResultado;
		$retorno['cDescripcion'] = urldecode($res->cDescripcion);
		$retorno['tVideos'] = $res->tVideos;
	}
	else {
		$retorno['iResultado'] = -1;
		$retorno['cDescripcion'] = "Webservice error";
		$retorno['tVideos'] = null;
	}
	return $retorno;
}

/*
INPUT: 
	cOrigen:CHARACTER
	iIDJuego:INTEGER
	cIDUsuario:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
*/
function IsUserAPayer($cOrigen,$iIDJuego,$cIDUsuario){

	$cOrigen= urlencode ($cOrigen);
	$cIDUsuario= urlencode ($cIDUsuario);

	$funcion = "IsUserAPayer";
	$parametros = array('cOrigen'=>$cOrigen,'iIDJuego'=>$iIDJuego,'cIDUsuario'=>$cIDUsuario);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tRed:TEMP-TABLE
TEMP-TABLES:
	tRed:
		Pais:CHARACTER
		IDJuego:INTEGER
		Utm_Source:CHARACTER
		utm_Medium:CHARACTER
		utm_campaign:CHARACTER
		total_reg:INTEGER
		total_secuencia:INTEGER
		total_login_completed:INTEGER
		total_login:INTEGER
		total_launch_installa:INTEGER
		total_end_install:INTEGER
		first_day_dropoff:INTEGER
		second_day_dropoff:INTEGER
		first_week_dropoff:INTEGER
		first_month_dropoff:INTEGER
		six_month_dropoff:INTEGER
		aftersix_month_dropoff:INTEGER
*/
function StatsAdsPaisFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);

	$funcion = "StatsAdsPaisFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tRed'] = $res->tRed;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tRed:TEMP-TABLE
TEMP-TABLES:
	tRed:
		Pais:CHARACTER
		IDJuego:INTEGER
		Utm_Source:CHARACTER
		utm_Medium:CHARACTER
		utm_campaign:CHARACTER
		total_reg:INTEGER
		total_secuencia:INTEGER
		total_login_completed:INTEGER
		total_login:INTEGER
		total_launch_installa:INTEGER
		total_end_install:INTEGER
		first_day_dropoff:INTEGER
		second_day_dropoff:INTEGER
		first_week_dropoff:INTEGER
		first_month_dropoff:INTEGER
		six_month_dropoff:INTEGER
		aftersix_month_dropoff:INTEGER
*/
function StatsAdsSMCFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsAdsSMCFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tRed'] = $res->tRed;
	return $retorno;
}

/*
INPUT: 
	cFechaIni:CHARACTER
	cFechaFin:CHARACTER
	iIDJuego:INTEGER
OUTPUT:
	tJuegoResumen:TEMP-TABLE
	tJuegoAverageCcu:TEMP-TABLE
	tJuegoMaxCcuDetalle:TEMP-TABLE
TEMP-TABLES:
	tJuegoResumen:
	tJuegoAverageCcu:
		IDJUEGO:INTEGER
		FECHA:DATE
		AVERAGECONTADOR:INTEGER
		AVERAGESUMA:INTEGER
		AVERAGE_CCU:INTEGER
	tJuegoMaxCcuDetalle:
		IDJUEGO:INTEGER
		MAXIMO_CCU:INTEGER
		FECHA:DATE
		HORA:INTEGER
		MINUTO:INTEGER
*/
function StatsCCUGeneral($cFechaIni,$cFechaFin,$iIDJuego){

	$cFechaIni= urlencode ($cFechaIni);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsCCUGeneral";
	$parametros = array('cFechaIni'=>$cFechaIni,'cFechaFin'=>$cFechaFin,'iIDJuego'=>$iIDJuego);
	$res = AppServer($funcion,$parametros);

	$retorno['tJuegoResumen'] = $res->tJuegoResumen;
	$retorno['tJuegoAverageCcu'] = $res->tJuegoAverageCcu;
	$retorno['tJuegoMaxCcuDetalle'] = $res->tJuegoMaxCcuDetalle;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
OUTPUT:
	tJuegoMaxCcuDetalle:TEMP-TABLE
TEMP-TABLES:
	tJuegoMaxCcuDetalle:
		IDJUEGO:INTEGER
		MAXIMO_CCU:INTEGER
		FECHA:DATE
		HORA:INTEGER
		MINUTO:INTEGER
*/
function StatsCCU_TYW($cLogin,$cPassword,$iIDJuego){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);

	$funcion = "StatsCCU_TYW";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego);
	$res = AppServer($funcion,$parametros);

	$retorno['tJuegoMaxCcuDetalle'] = $res->tJuegoMaxCcuDetalle;
	return $retorno;
}

/*
INPUT: 
	cFechaIni:CHARACTER
	cFechaFin:CHARACTER
	iIDJuego:INTEGER
OUTPUT:
	tRed:TEMP-TABLE
TEMP-TABLES:
	tRed:
		Pais:CHARACTER
		IDJuego:INTEGER
		Utm_Source:CHARACTER
		utm_Medium:CHARACTER
		utm_campaign:CHARACTER
		total_reg:INTEGER
		total_secuencia:INTEGER
		total_login_completed:INTEGER
		total_launch_installa:INTEGER
		total_end_install:INTEGER
		total_login_launcher:INTEGER
*/
function StatsInstalacionesFechaJuego($cFechaIni,$cFechaFin,$iIDJuego){

	$cFechaIni= urlencode ($cFechaIni);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsInstalacionesFechaJuego";
	$parametros = array('cFechaIni'=>$cFechaIni,'cFechaFin'=>$cFechaFin,'iIDJuego'=>$iIDJuego);
	$res = AppServer($funcion,$parametros);

	$retorno['tRed'] = $res->tRed;
	return $retorno;
}

/*
INPUT: 
	cFechaIni:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tSO:TEMP-TABLE
	tPais:TEMP-TABLE
TEMP-TABLES:
	tSO:
		SO:CHARACTER
		INNOINI:INTEGER
		INNOFIN:INTEGER
		UPDATERINI:INTEGER
		UPDATERFIN:INTEGER
	tPais:
		Pais:CHARACTER
		INNOINI:INTEGER
		INNOFIN:INTEGER
		UPDATERINI:INTEGER
		UPDATERFIN:INTEGER
*/
function StatsInstalacionesPreFecha($cFechaIni,$cFechaFin){

	$cFechaIni= urlencode ($cFechaIni);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsInstalacionesPreFecha";
	$parametros = array('cFechaIni'=>$cFechaIni,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tSO'] = $res->tSO;
	$retorno['tPais'] = $res->tPais;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	iAgrupado:INTEGER
OUTPUT:
	tLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tLoginFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		PRIMERASEMANA:INTEGER
		PRIMERMES:INTEGER
		MASMES:INTEGER
		NUM_ACTIVOS1M:INTEGER
		NUM_REACTIVADOS3M:INTEGER
		NUM_RECAPTADOSMAS3M:INTEGER
		NUSERS:INTEGER
		Dias:INTEGER
		Semanas:INTEGER
		Meses:INTEGER
*/
function StatsLoginFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$iAgrupado){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsLoginFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'iAgrupado'=>$iAgrupado);
	$res = AppServer($funcion,$parametros);

	$retorno['tLoginFecha'] = $res->tLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cOrigen:CHARACTER
OUTPUT:
	tLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tLoginFecha:
		FECHA:DATE
		ORIGEN:CHARACTER
		PAIS:CHARACTER
		TOTAL_REGISTROS:INTEGER
		TOTAL_LOGIN_LAUNCHER:INTEGER
		TOTAL_LOGIN_COMPLETED:INTEGER
*/
function StatsLoginFechaIDC($cLogin,$cPassword,$cFechaInicio,$cFechaFin,$cOrigen){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cOrigen= urlencode ($cOrigen);

	$funcion = "StatsLoginFechaIDC";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cOrigen'=>$cOrigen);
	$res = AppServer($funcion,$parametros);

	$retorno['tLoginFecha'] = $res->tLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tLoginFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		ORIGEN:CHARACTER
		PRIMERASEMANA:INTEGER
		PRIMERMES:INTEGER
		MASMES:INTEGER
		NUSERS:INTEGER
*/
function StatsLoginFechaOrigen($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsLoginFechaOrigen";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tLoginFecha'] = $res->tLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tLoginFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		PRIMERASEMANA:INTEGER
		PRIMERMES:INTEGER
		MASMES:INTEGER
		NUSERS:INTEGER
*/
function StatsLoginFechaPais($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsLoginFechaPais";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tLoginFecha'] = $res->tLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tLoginFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PRIMERASEMANA:INTEGER
		PRIMERMES:INTEGER
		MASMES:INTEGER
		NUSERS:INTEGER
*/
function StatsLoginFechaResumen($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsLoginFechaResumen";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tLoginFecha'] = $res->tLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagadoresResumenFecha:TEMP-TABLE
TEMP-TABLES:
	tPagadoresResumenFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		TOTAL_UNICOS:INTEGER
		TOTAL_NUEVOS_PAGADORES:INTEGER
		TOTAL_VIEJOS_PAGADORES:INTEGER
		TOTAL_PAGADORES_OFERTA:INTEGER
*/
function StatsPagadoresResumenFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagadoresResumenFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagadoresResumenFecha'] = $res->tPagadoresResumenFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagadoresUnicosFecha:TEMP-TABLE
TEMP-TABLES:
	tPagadoresUnicosFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		IDUSUARIO:INTEGER
		MONEDA:CHARACTER
		PAIS:CHARACTER
		PRIMER_PAGO:DATE
		ULTIMO_PAGO:DATE
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagadoresUnicosFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagadoresUnicosFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagadoresUnicosFecha'] = $res->tPagadoresUnicosFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagadoresUnicos:TEMP-TABLE
TEMP-TABLES:
	tPagadoresUnicos:
		IDJUEGO:INTEGER
		IDUSUARIO:INTEGER
		MONEDA:CHARACTER
		PAIS:CHARACTER
		PRIMER_PAGO:DATE
		ULTIMO_PAGO:DATE
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagadoresUnicosHistorico($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagadoresUnicosHistorico";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagadoresUnicos'] = $res->tPagadoresUnicos;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagosFecha:TEMP-TABLE
TEMP-TABLES:
	tPagosFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagosFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagosFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagosFecha'] = $res->tPagosFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	iAgrupado:INTEGER
	cMoneda:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tExchangeRate:TEMP-TABLE
	tPagosLoginFecha:TEMP-TABLE
TEMP-TABLES:
	tExchangeRate:
		MONEDA:CHARACTER
		CAMBIO:DECIMAL
		FECHAHORA:DATETIME
		VALIDA:INTEGER
	tPagosLoginFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		PRIMERASEMANA:INTEGER
		PRIMERMES:INTEGER
		MASMES:INTEGER
		PAGOS_PRIMERASEMANA:DECIMAL
		PAGOS_PRIMERMES:DECIMAL
		PAGOS_MASMES:DECIMAL
		OFFER_PRIMERASEMANA:INTEGER
		OFFER_PRIMERMES:INTEGER
		OFFER_MASMES:INTEGER
		PAGOS_OFFER_PRIMERASEMANA:DECIMAL
		PAGOS_OFFER_PRIMERMES:DECIMAL
		PAGOS_OFFER_MASMES:DECIMAL
		NPAGOS:INTEGER
		NOFERTAS:INTEGER
		DIAS:INTEGER
		SEMANAS:INTEGER
		MESES:INTEGER
		PAGOS_DIAS:DECIMAL
		PAGOS_SEMANAS:DECIMAL
		PAGOS_MESES:DECIMAL
		OFFER_DIAS:INTEGER
		OFFER_SEMANAS:INTEGER
		OFFER_MESES:INTEGER
		PAGOS_OFFER_DIAS:DECIMAL
		PAGOS_OFFER_SEMANAS:DECIMAL
		PAGOS_OFFER_MESES:DECIMAL
*/
function StatsPagosLoginFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$iAgrupado,$cMoneda){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cMoneda= urlencode ($cMoneda);

	$funcion = "StatsPagosLoginFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'iAgrupado'=>$iAgrupado,'cMoneda'=>$cMoneda);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tExchangeRate'] = $res->tExchangeRate;
	$retorno['tPagosLoginFecha'] = $res->tPagosLoginFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagosPais:TEMP-TABLE
TEMP-TABLES:
	tPagosPais:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagosPaisFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagosPaisFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagosPais'] = $res->tPagosPais;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagosOrigenShopArticle:TEMP-TABLE
TEMP-TABLES:
	tPagosOrigenShopArticle:
		FECHA:DATE
		IDJUEGO:INTEGER
		ORIGEN:CHARACTER
		SHOP:CHARACTER
		ARTICULO:CHARACTER
		PAIS:CHARACTER
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagosPaisShopArticle($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagosPaisShopArticle";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagosOrigenShopArticle'] = $res->tPagosOrigenShopArticle;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
OUTPUT:
	tPagosUserLevel:TEMP-TABLE
TEMP-TABLES:
	tPagosUserLevel:
		FECHA:DATE
		IDJUEGO:INTEGER
		USERLEVEL:CHARACTER
		PAIS:CHARACTER
		DEVOLUCIONES_CANTIDAD:DECIMAL
		DEVOLUCIONES_CANTIDAD_NETA:DECIMAL
		DEVOLUCIONES_IVA:DECIMAL
		DEVOLUCIONES_NUMERO:INTEGER
		PAGOS_CANTIDAD:DECIMAL
		PAGOS_CANTIDAD_NETA:DECIMAL
		PAGOS_IVA:DECIMAL
		PAGOS_NUMERO:INTEGER
*/
function StatsPagosUserLevelFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);

	$funcion = "StatsPagosUserLevelFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin);
	$res = AppServer($funcion,$parametros);

	$retorno['tPagosUserLevel'] = $res->tPagosUserLevel;
	return $retorno;
}

/*
INPUT: 
	cIP:CHARACTER
	cPais:CHARACTER
OUTPUT:
	tPingGeneral:TEMP-TABLE
	tPingDetalle:TEMP-TABLE
TEMP-TABLES:
	tPingGeneral:
		IP:CHARACTER
		PAIS:CHARACTER
		TIEMPO:INTEGER
		NUMERO_PETICIONES:INTEGER
	tPingDetalle:
		IP:CHARACTER
		PAIS:CHARACTER
		HORA:INTEGER
		MINUTO:INTEGER
		TIEMPO:INTEGER
		NUMERO_PETICIONES:INTEGER
*/
function StatsPingGeneral($cIP,$cPais){

	$cIP= urlencode ($cIP);
	$cPais= urlencode ($cPais);

	$funcion = "StatsPingGeneral";
	$parametros = array('cIP'=>$cIP,'cPais'=>$cPais);
	$res = AppServer($funcion,$parametros);

	$retorno['tPingGeneral'] = $res->tPingGeneral;
	$retorno['tPingDetalle'] = $res->tPingDetalle;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tDropOffFecha:TEMP-TABLE
TEMP-TABLES:
	tDropOffFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_REGISTROS:INTEGER
		TOTAL_DROPOFF_FECHA:INTEGER
		TOTAL_DROPOFF_SEMANA:INTEGER
		TOTAL_DROPOFF_MES:INTEGER
*/
function StatsUserDropOffFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserDropOffFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tDropOffFecha'] = $res->tDropOffFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tDropOffMes:TEMP-TABLE
TEMP-TABLES:
	tDropOffMes:
		ANYO:INTEGER
		MES:INTEGER
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_REGISTROS:INTEGER
		TOTAL_DROPOFF_FECHA:INTEGER
		TOTAL_DROPOFF_SEMANA:INTEGER
		TOTAL_DROPOFF_MES:INTEGER
*/
function StatsUserDropOffMes($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserDropOffMes";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tDropOffMes'] = $res->tDropOffMes;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tDropOffSemana:TEMP-TABLE
TEMP-TABLES:
	tDropOffSemana:
		SEMANA:CHARACTER
		DESDE:DATE
		HASTA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_REGISTROS:INTEGER
		TOTAL_DROPOFF_FECHA:INTEGER
		TOTAL_DROPOFF_SEMANA:INTEGER
		TOTAL_DROPOFF_MES:INTEGER
*/
function StatsUserDropOffSemana($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserDropOffSemana";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tDropOffSemana'] = $res->tDropOffSemana;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tPayerFecha:TEMP-TABLE
TEMP-TABLES:
	tPayerFecha:
		FECHA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_PAYERS:INTEGER
		TOTAL_REGISTROS:INTEGER
		TOTAL_PAYER_FECHA:INTEGER
		TOTAL_PAYER_SEMANA:INTEGER
		TOTAL_PAYER_MES:INTEGER
*/
function StatsUserPayersFecha($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserPayersFecha";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tPayerFecha'] = $res->tPayerFecha;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tPayerMes:TEMP-TABLE
TEMP-TABLES:
	tPayerMes:
		ANYO:INTEGER
		MES:INTEGER
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_PAYERS:INTEGER
		TOTAL_PAYER_FECHA:INTEGER
		TOTAL_PAYER_SEMANA:INTEGER
		TOTAL_PAYER_MES:INTEGER
*/
function StatsUserPayersMes($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserPayersMes";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tPayerMes'] = $res->tPayerMes;
	return $retorno;
}

/*
INPUT: 
	cLogin:CHARACTER
	cPassword:CHARACTER
	iIDJuego:INTEGER
	cFechaInicio:CHARACTER
	cFechaFin:CHARACTER
	cPais:CHARACTER
	cUtm_Source:CHARACTER
	cUtm_Medium:CHARACTER
	cUtm_Campaign:CHARACTER
OUTPUT:
	iResultado:INTEGER
	cDescripcion:CHARACTER
	tPayerSemana:TEMP-TABLE
TEMP-TABLES:
	tPayerSemana:
		SEMANA:CHARACTER
		DESDE:DATE
		HASTA:DATE
		IDJUEGO:INTEGER
		PAIS:CHARACTER
		RED:CHARACTER
		UTM_SOURCE:CHARACTER
		UTM_MEDIUM:CHARACTER
		UTM_CAMPAIGN:CHARACTER
		TOTAL_INSTALADOS:INTEGER
		TOTAL_PAYERS:INTEGER
		TOTAL_PAYER_FECHA:INTEGER
		TOTAL_PAYER_SEMANA:INTEGER
		TOTAL_PAYER_MES:INTEGER
*/
function StatsUserPayersSemana($cLogin,$cPassword,$iIDJuego,$cFechaInicio,$cFechaFin,$cPais,$cUtm_Source,$cUtm_Medium,$cUtm_Campaign){

	$cLogin= urlencode ($cLogin);
	$cPassword= urlencode ($cPassword);
	$cFechaInicio= urlencode ($cFechaInicio);
	$cFechaFin= urlencode ($cFechaFin);
	$cPais= urlencode ($cPais);
	$cUtm_Source= urlencode ($cUtm_Source);
	$cUtm_Medium= urlencode ($cUtm_Medium);
	$cUtm_Campaign= urlencode ($cUtm_Campaign);

	$funcion = "StatsUserPayersSemana";
	$parametros = array('cLogin'=>$cLogin,'cPassword'=>$cPassword,'iIDJuego'=>$iIDJuego,'cFechaInicio'=>$cFechaInicio,'cFechaFin'=>$cFechaFin,'cPais'=>$cPais,'cUtm_Source'=>$cUtm_Source,'cUtm_Medium'=>$cUtm_Medium,'cUtm_Campaign'=>$cUtm_Campaign);
	$res = AppServer($funcion,$parametros);

	$retorno['iResultado'] = $res->iResultado;
	$retorno['cDescripcion'] = urldecode($res->cDescripcion);
	$retorno['tPayerSemana'] = $res->tPayerSemana;
	return $retorno;
}

?>
