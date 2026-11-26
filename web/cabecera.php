<?php 
define("RUTABASE", dirname(__FILE__));
//define("MODO_TRABAJO","produccion"); //en "produccion o en desarrollo
define("MODO_TRABAJO","desarrollo"); //en "produccion o en desarrollo
/**
 * Cambiar la fecha y hora a la de Madrid
 */
date_default_timezone_set('Europe/Madrid');

if (MODO_TRABAJO=="produccion")
    error_reporting(0);
    else 
        error_reporting(E_ALL);  

spl_autoload_register(function ($clase){
    $ruta=RUTABASE."/aplicacion/clases/";
    $fichero=$ruta."$clase.php";
    
    if (file_exists($fichero))
        {
            require_once($fichero);
        }
      else
        {
            throw new Exception("La clase $clase no se ha encontrado.");
        }
});
/**
 * Esto permite recuperar los datos del servidor o crear la sesión si es la primera vez
 */
session_start();
include(RUTABASE."/aplicacion/plantilla/plantilla.php");
include_once(RUTABASE."/scripts/librerias/validacion.php");

//creo todos los objetos que necesita mi aplicación
const COLORESFONDO= [
    "blanco" => "white",
    "rojo" => "red",
    "verde" => "green",
    "azul" => "blue",
    "morado" => "purple"
];
const COLORESTEXTO = [
    "negro" => "black",
    "blanco" => "white",
    "rojo" => "red",
    "azul" => "blue"
];