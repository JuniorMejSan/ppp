<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['token']) && isset($_POST['usuario'])) { //si se esta enviando datos desde un formulario

    //instanciamos al controlador
    require_once "../controladores/loginControlador.php";
    $ins_login = new loginControlador();

    echo $ins_login -> cerrar_sesion_controlador();

}else { //si no significa que se esta intentando acceder desde el navegador

    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
} 