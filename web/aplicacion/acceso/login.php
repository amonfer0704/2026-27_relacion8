<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");
//controlador
$barra = [
    [
        "TEXTO" => "inicio",
        "ENLACE" => "/index.php"
    ],
    [
        "TEXTO" => "login",
    ]
];
if($acceso->hayUsuario()){
    header("Location: /index.php");
    exit;
}
$usuario = "";
$contrasenia = "";
$errores = [];

$colorTexto = "negro";
$colorFondo = "blanco";
if(isset($_COOKIE["colorTexto"]) && validaRango($_COOKIE["colorTexto"], COLORESTEXTO, 2)){
    $colorTexto = $_COOKIE ["colorTexto"];
}

if(isset($_COOKIE["colorFondo"]) && validaRango($_COOKIE["colorFondo"], COLORESFONDO, 2)){
    $colorFondo = $_COOKIE ["colorFondo"];
}
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
function cuerpo(array $datos, array $errores)
{
?>
    
<?php
    if(isset($errores["login"])){
        echo $errores["login"];
    }
    formulario($datos, $errores);
}

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