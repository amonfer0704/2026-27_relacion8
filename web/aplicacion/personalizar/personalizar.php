<?php

/**
 * Clase de personalizar. Permite elegir entre varios colores en
 * una lista desplegable para cambiar el color del texto
 * y del fondo. Las cookies se guardan durante 30 días
 * Solo pueden acceder los usuarios con permisos 2
 */
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
/**
 * Control de acceso para que solo entren usuarios registrados
 */
if(!$acceso->hayUsuario()){
    header("Location: /aplicacion/acceso/login.php");
    exit;
}
/**
 * Control para el usuario registrado, prohibiendo el acceso 
 * a los usuarios que no tengan el permiso 2
 */
if(!$acceso->puedePermiso(2)){
    paginaError("No tienes permiso para personalizar los colores");
    exit;
}

/**
 * Variables para las validaciones del formulario
 */
$errores = [];
$correcto = false;

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

/**
 * Validación del botón y los datos donde, al pulsar el botón guardar
 * se validan los colores de los comboBox
 */
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

/**
 * Array de datos con el color del texto y el fondo
 */
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
/**
 * Método cuerpo que muestra un formulario con dos listas desplegables
 * con colores, los errores de validación y el mensaje si se han guardado bien
 *
 * @param array $datos Colores actuales: "colorTexto" y "colorFondo"
 * @param array $errores Errores de validación
 * @param boolean $correcto true si los colores se han guardado correctamente
 * @return void
 */
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
        <?php 
        if(isset($errores["colorTexto"])){
            echo $errores["colorTexto"];
        }
        ?>
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
        <?php 
        if(isset($errores["colorFondo"])){
            echo $errores["colorFondo"];
        }
        ?>
        <br><br>
        <input type="submit" name="guardar" value="Guardar">
    </form>
<?php
}
