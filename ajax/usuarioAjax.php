<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del'])) { //si se esta enviando datos desde un formulario
    //instanciamos al controlador
    require_once "../controladores/usuarioControlador.php";
    $ins_usario = new usuarioControlador();

    //condicon para indicarle que vamos a usar el controlador de registro una vez se envie el parametro y registramos el usuario
    if (isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])) {
        echo $ins_usario -> agregar_usuario_controlador();
    }

    //eliminar usuario
    if (isset($_POST['usuario_id_del'])) {
        echo $ins_usario -> eliminar_usuario_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}