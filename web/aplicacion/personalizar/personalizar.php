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
        "TEXTO" => "personalizar",
    ]
];
if(!$acceso->hayUsuario()){
    header("Location: /aplicacion/acceso/login.php");
    exit;
}
if(!$acceso->puedePermiso(1)){
    paginaError("No tienes permiso para personalizar los colores");
    exit;
}
$colorTexto = "negro";
$colorFondo = "blanco";
$errores = [];
$correcto = false;

if(isset($_COOKIE["colorTexto"]) && validaRango($_COOKIE["colorTexto"], COLORESTEXTO, 2)){
    $colorTexto = $_COOKIE ["colorTexto"];
}

if(isset($_COOKIE["colorFondo"]) && validaRango($_COOKIE["colorFondo"], COLORESFONDO, 2)){
    $colorFondo = $_COOKIE ["colorFondo"];
}
if(isset($_POST["guardar"])){
    $nuevoTexto = isset($_POST["colorTexto"])?$_POST["colorTexto"]:"";
    if(!validaRango($nuevoTexto, COLORESTEXTO, 2)){
        $errores["colorTexto"] = "Debes seleccionar un color para el texto";
    }

    $nuevoFondo = isset($_POST["colorFondo"])?$_POST["colorFondo"]:"";
    if(!validaRango($nuevoFondo, COLORESFONDO, 2)){
        $errores["colorFondo"] = "Debes seleccionar un color para el fondo";
    }
    if(count($errores) === 0){
        $colorTexto = $nuevoTexto;
        $colorFondo = $nuevoFondo;
        $correcto = true;
        setcookie("colorTexto", $colorTexto, time()+60*60*24*30);
        setcookie("colorFondo", $colorFondo, time()+60*60*24*30);
       
    }
}

$datos = [
    "colorTexto" => $colorTexto,
    "colorFondo" => $colorFondo
];
$usuario=getenv("MYSQL_USER");

//dibuja la plantilla de la vista
inicioCabecera("Relación 8");
cabecera($colorTexto, $colorFondo);
finCabecera();
inicioCuerpo("2DAW APLICACION", $barra);
cuerpo($datos, $errores, $correcto);  //llamo a la vista
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
function cuerpo(array $datos, array $errores, bool $correcto)
{

?>
    <form action ="/aplicacion/personalizar/personalizar.php" method="post">
        <label for="colorTexto">Color del texto</label>
        <br>
        <select name="colorTexto" id="colorTexto">
            <?php

            foreach(COLORESTEXTO as $clave => $valor){
                $seleccionado = "";
                if($clave === $datos["colorTexto"]){
                    $seleccionado = " selected";
                }
                echo "<option value='" . $clave . "'" . $seleccionado . ">" . $clave . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="colorFondo">Color del fondo</label>
        <br>
        <select name="colorFondo" id="colorFondo">
            <?php

            foreach(COLORESFONDO as $clave => $valor){
                $seleccionado = "";
                if($clave === $datos["colorFondo"]){
                    $seleccionado = " selected";
                }
                echo "<option value='" . $clave . "'" . $seleccionado . ">" . $clave . "</option>";
            }
            ?>
        </select>
        <br><br>
        <input type="submit" name="guardar" value="Guardar">
    </form>
<?php
}
