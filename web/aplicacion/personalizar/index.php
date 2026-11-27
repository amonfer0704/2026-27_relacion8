<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");
//controlador
/**
 * Barra de inicio con enlace y el último elemento sin enlace
 */
$barra = [
    [
        "TEXTO" => "index",
    ]
];

/**
 * Control de acceso para que solo entren usuarios registrados
 */
if(!$acceso->hayUsuario()){
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

/**
 * Contador de visitas en la página de index
 */
$visitas = 0;
if(isset($_COOKIE["visitas"])){
    try{
        $visitas = $_COOKIE["visitas"];
        validaEntero($visitas, 0, 1000000, 0);
    }
    catch(TypeError $e){
        $visitas = 0;
    }
    
}

$visitas = $visitas + 1;
setcookie("visitas", $visitas, time()+60*60*24*2);

/**
 * Colores por defecto, siendo el texto en negro y el fondo en blanco
 * Se guarda en las cookies
 */
$colorTexto = "negro";
$colorFondo = "blanco";
if(isset($_COOKIE["colorTexto"]) && validaRango($_COOKIE["colorTexto"], COLORESTEXTO, 2)){
    $colorTexto = $_COOKIE ["colorTexto"];
}

if(isset($_COOKIE["colorFondo"]) && validaRango($_COOKIE["colorFondo"], COLORESFONDO, 2)){
    $colorFondo = $_COOKIE ["colorFondo"];
}
$usuario=getenv("MYSQL_USER");

/**
 * Variable para que se muestre el cambio en la vista
 */
$texto = "Esta página cambia con los colores que elijas en personalizar.php";
/**
 * Datos con las variables
 */
$datos = [
    "texto" => $texto,
    "visitas" => $visitas
];
//dibuja la plantilla de la vista
inicioCabecera("Personalizar");
cabecera($colorTexto, $colorFondo);
finCabecera();
inicioCuerpo("Personalizar", $barra);
cuerpo($datos);  //llamo a la vista
finCuerpo();
// **********************************************************

//vista
/**
 * Función cabecera con los estilos que cambian dependiendo de lo que 
 * se eliga en personalizar.php
 *
 * @param string $colorTexto
 * @param string $colorFondo
 * @return void
 */
function cabecera(string $colorTexto, string $colorFondo) 
{
    ?>
    <style>
        #documento {
            color: <?php echo COLORESTEXTO[$colorTexto]?>;
            background-color: <?php echo COLORESFONDO[$colorFondo]?>;
        }
    </style>
    <?php
}

//vista
function cuerpo(array $datos)
{
?>
    <!--Información dentro de la función cuerpo que muestra las visitas 
    de la página, sabiendo que se guarda en una cookie. De esta forma, 
    aunque recargues la página, sigue contando cuántas veces has visitado esta página-->
    <p><?php echo $datos["texto"]?></p>
    <h3>Se ha visitado la página <?php echo $datos["visitas"]?> veces</h3>
<?php
}
