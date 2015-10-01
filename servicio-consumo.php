<?php 
/**
* Plugin Name: WebService candidaturas
* Plugin URI: http://joselazo.es/plugins/
* Description: WebService para consumo de candidaturas
* Version: 1.0
* Author: Jose Lazo
* Author URI: http://joselazo.es/
* License: GPL12
*/

require_once( __FILE__ . '/lib/nusoap.php');//aquí invocamos la librería nusoap
 
$miURL = 'localhost';  						//definimos la url del web service, si esta alojado localmente se usa localhost
                                            //si esta en un equipo en red usamos una dirección ip como aquí
                                            //si esta en un servidor remoto usaremos el nombre de dominio del servidor o de nuestra pagina web
                                            //ejemplo: www .mipagina123abc.com/ejemplows
 
$server = new soap_server();                // definimos la variable server como soap_server
 
$server->configureWSDL('Servicio Web Candidaturas', $miURL);  //configuramos la wsdl con el nombre de miservicioweb
 
$server->wsdl->schemaTargetNamespace = $miURL;


/*
 
* Ejemplo 1: Enviar_respuesta es una funcion sencilla que recibe un parametro y retorna el mismo
 
* con una cadena concatenada
 
*/
 
$server->register('enviar_respuesta', // Nombre de la funcion
 
array('parametro' => 'xsd:string'),   // Parametros de entrada
 
array('return' => 'xsd:string'),      // Parametros de salida
 
$miURL);
 
function enviar_respuesta( $parametro )
{
	return new soapval('return', 'xsd:string', 'Hola, esto lo envia el Servidor: '.$parametro);
}


/*
 
Compruebo que hay datos con el formulario que recibo como parametro
 
*/
$server->register('buscar_datos',    // Nombre de la funcion
 
array('formulario' => 'xsd:string'), // Parametros de entrada (nombre del formulario)
 
array('return'     => 'xsd:string'), // Parametros de salida
 
$miURL);
 
function buscar_datos( $formulario )
{
	//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion
	 
	$encontro="No";
	 
	global $wpdb; // hace la conexion con la BD

	// $formulario = "Candidatura";

	$consulta = "SELECT * FROM wp_cf7dbplugin_submits WHERE form_name = '$formulario'"; // string que almacena la consulta a ejecutar

	$resultado = $wpdb->get_results( $consulta, OBJECT ); // ejecuta la consulta a la base de datos
	 
	while ( $f = mysql_fetch_row( $resultado ) )
	{ // Convertimos el resultado en un vector
	 
		$encontro="Si";
	}
	return new soapval('return', 'xsd:string',$encontro);
}


/*
 
Muestro los datos del formulario que recibo como parametro
 
*/
$server->register('mostrar_datos_formulario', // Nombre de la funcion
 
array('formulario' => 'xsd:string'), // Parametros de entrada
 
array('return' => 'xsd:string'), // Parametros de salida
 
$miURL);
 
function mostrar_datos_formulario( $formulario )
{
	//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion
	global $wpdb; // hace la conexion con la BD

	// $formulario = "Candidatura";

	$consulta = "SELECT * FROM wp_cf7dbplugin_submits WHERE form_name = '$formulario'"; // string que almacena la consulta a ejecutar

	$resultado = $wpdb->get_results( $consulta, OBJECT ); // ejecuta la consulta a la base de datos
	 
	return new soapval('return', 'xsd:string', $resultado );
 
}
 ?>