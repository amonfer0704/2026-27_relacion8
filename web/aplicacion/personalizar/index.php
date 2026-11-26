<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");
//controlador
$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/index.php"
    ],
    [
        "TEXTO" => "personalizar",
    ]
];

if(!$acceso->hayUsuario()){
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

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
$colorTexto = "negro";
$colorFondo = "blanco";
if(isset($_COOKIE["colorTexto"]) && validaRango($_COOKIE["colorTexto"], COLORESTEXTO, 2)){
    $colorTexto = $_COOKIE ["colorTexto"];
}

if(isset($_COOKIE["colorFondo"]) && validaRango($_COOKIE["colorFondo"], COLORESFONDO, 2)){
    $colorFondo = $_COOKIE ["colorFondo"];
}
$usuario=getenv("MYSQL_USER");
$texto = "Esta página cambia con los colores que elijas en personalizar.php";
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
    <p><?php echo $datos["texto"]?></p>
    <h3>Se ha visitado la página <?php echo $datos["visitas"]?> veces</h3>
<?php
}
