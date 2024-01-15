<?php 

ini_set('display_errors',true);
session_start(); 

if($_GET['action']=="borrar")
{
	if(cleantmp()){
		echo "<br/> Petici&oacute;n ejecutada con &eacute;xito. <br/>";
	}
	else {
		echo "<br/> Algo ha fallado. </br>";
	}
}

?>
<p align='center'>(Borrado de servicios WSDL. <a href="wsdl.php?action=borrar">borrar ahora</a>)</a></p>

<?php
function cleantmp() 
{
	$directory = "/tmp";
	if( !$dirhandle = opendir($directory) ){
		return false;
	}

	$error = '<br/>';
	$exito = '<br/>';
	
	while( false !== ($filename = readdir($dirhandle)) ) 
	{
		   if( $filename != "." && $filename != ".." ) 
		   {
			   $filename = $directory. "/". $filename;
			   if(strpos($filename,'wsdl-')!==FALSE)
			   {
				   $res = unlink($filename);
				   if ($res) {
						$exito .= '++ Fichero eliminado: '.$filename."<br/>";
				   }
				   else
				   {
						$error .= '++ Error al eliminar: '.$filename."<br/>";
				   }
				} 
			}
	}
	echo("<br/>");
	echo($exito);
	echo($error);
	return true;
}
?>
