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
        "ENLACE" => "/aplicacion/personalizar/index.php",
    ],
    [
        "TEXTO" => "index",
    ]
];

$visitas = 0;
if(isset($_COOKIE["visitas"])){
    $visitas = $_COOKIE["visitas"];
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

//dibuja la plantilla de la vista
inicioCabecera("APLICACION PRIMER TRIMESTRE");
cabecera($colorTexto, $colorFondo);
finCabecera();
inicioCuerpo("2DAW APLICACION", $barra);
cuerpo($visitas);  //llamo a la vista
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
function cuerpo(int $visitas)
{
?>
    <h3>Se ha visitado la página <?php echo $visitas?> veces</h3>
<?php
}
