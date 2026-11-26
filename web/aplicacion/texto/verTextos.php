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
        "TEXTO" => "ver textos",
    ]
];

$textos = isset($_SESSION["textos"])?$_SESSION["textos"]:[];
$cadena = "";
$errores = [];
$colorTexto = "negro";
$colorFondo = "blanco";
if(isset($_COOKIE["colorTexto"]) && validaRango($_COOKIE["colorTexto"], COLORESTEXTO, 2)){
    $colorTexto = $_COOKIE ["colorTexto"];
}

if(isset($_COOKIE["colorFondo"]) && validaRango($_COOKIE["colorFondo"], COLORESFONDO, 2)){
    $colorFondo = $_COOKIE ["colorFondo"];
}

if(isset($_POST["registrar"])){
    $cadena = isset($_POST["cadena"]) ? mb_trim($_POST["cadena"]) : "";

    if($cadena === ""){
        $errores["cadena"] = "Debes escribir un texto";
    }
    else if(!validaCadena($cadena, 100, "")){
        $errores["cadena"] = "El texto no puede tener más de 100 caracteres";
    }
    if(count($errores) === 0){
        $textos[] = new RegistroTexto($cadena);
        $_SESSION["textos"] = $textos;
        $cadena = "";
    }
}

if(isset($_POST["limpiar"])){
    $textos = [];
    $_SESSION["textos"] = $textos;
}

$usuario=getenv("MYSQL_USER");

//dibuja la plantilla de la vista
inicioCabecera("APLICACION PRIMER TRIMESTRE");
cabecera($colorTexto, $colorFondo);
finCabecera();
inicioCuerpo("2DAW APLICACION", $barra);
cuerpo($textos, $cadena, $errores);  //llamo a la vista
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
function cuerpo(array $textos, string $cadena, array $errores)
{
?>
    <form action ="/aplicacion/texto/verTextos.php" method="post">
        <input type="text" name="cadena" placeholder="Escriba una cadena">
        <br>
        <input type="submit" value="registrar" name="registrar">
        <?php
        if(isset($errores["cadena"])){
            echo "<div class='error'>" . $errores["cadena"] . "</div>";
        }

        ?>
        <br><br>
        <textarea rows="10" cols="60" readonly><?php
            foreach($textos as $registro){
                echo $registro->getFechaHora()->format("d/m/Y H:i:s") . " - " . $registro->getCadena() . "\n";
            }
        ?></textarea>
        <br>
        <input type="submit" name="limpiar" value="Limpiar">
    </form>
<?php
}
