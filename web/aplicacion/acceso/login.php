<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");
//controlador
/**
 * Barra de inicio con enlace y el último elemento sin enlace
 */
$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/index.php"
    ],
    [
        "TEXTO" => "login",
    ]
];

/**
 * Control de acceso para que solo entren usuarios registrados
 */
if($acceso->hayUsuario()){
    header("Location: /index.php");
    exit;
}

/**
 * Variables para el formulario
 */
$usuario = "";
$contrasenia = "";
$errores = [];

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
 * Validación del botón enviar que valida los campos usuario y contraseña
 * Se comprueba con el acl si el usuario existe y si la contraseña
 * es correcta, además de que no esté borrado. Si no hay errores
 * se registra en la sesión
 */
if(isset($_POST["enviar"])){
    $usuario = isset($_POST["usuario"])?mb_trim($_POST["usuario"]):"";
    if($usuario === ""){
        $errores["usuario"] = "El usuario es obligatorio";
    }
    if(!validaCadena($usuario, 100, "")){
        $errores["usuario"] = "El usuario no puede ser tan largo";
    }

    $contrasenia = isset($_POST["contrasenia"])?$_POST["contrasenia"]:"";
    if($contrasenia === ""){
        $errores["contrasenia"] = "La contraseña es obligatoria";
    }
    if(!validaCadena($contrasenia, 100, "")){
        $errores["contrasenia"] = "La contraseña no puede ser tan largo";
    }

    if(count($errores) === 0){
        $codUsuario = $acl->getCodUsuario($usuario);
        if(!$acl->esValido($usuario, $contrasenia) || $acl->getBorrado($codUsuario)){
            $errores["login"] = "Usuario o contraseña incorrectos";
        }
        else{
            $acceso->registrarUsuario(mb_strtolower($usuario), $acl->getNombre($codUsuario), 
                                        $acl->getPermisos($codUsuario));
            header("Location: /index.php");
            exit;
        }
    }
}

/**
 * Datos para la vista, la contraseña no se devuelve al formulario
 */
$datos= ["usuario"=>$usuario];
$usuario=getenv("MYSQL_USER");

//dibuja la plantilla de la vista
inicioCabecera("Login");
cabecera($colorTexto, $colorFondo);
finCabecera();
inicioCuerpo("Login", $barra);
cuerpo($datos, $errores);  //llamo a la vista
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
 * Método que muestra el formulario y los errores
 *
 * @param array $datos Datos del controlador
 * @param array $errores Errores de validación y de login
 * @return void
 */
function cuerpo(array $datos, array $errores)
{
?>
    
<?php
    /**
     * Mostrar errores en caso de que exista algún error
     * en el login. Está en un div de clase error
     */
    if(isset($errores["login"])){
        echo "div class='error'>" . $errores["login"] . "</div>";
    }
    formulario($datos, $errores);
}

/**
 * Método que crea el formulario y muestra los errores si los hay
 *
 * @param array $datos Datos del controlador
 * @param array $errores Errores de validación de los campos
 * @return void
 */
function formulario(array $datos, array $errores){
    ?>
    <form action="/aplicacion/acceso/login.php" method="post">
        <label for="usuario">Usuario</label>
        <br>
        <input type="text" id="usuario" name="usuario" value = "<?php echo $datos["usuario"]?>">
        <?php
        if(isset($errores["usuario"])){
            echo $errores["usuario"];
        }
        ?>
        <br>
        <label for="contrasenia">Contraseña</label>
        <br>
        <input type="password" id="contrasenia" name="contrasenia">
        <?php 
        if(isset($errores["contrasenia"])){
            echo $errores["contrasenia"];
        }
        ?>
        <br>
        <input type="submit" name="enviar" value="Enviar">
    </form>

    <?php
}