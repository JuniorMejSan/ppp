<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['cliente_dni_reg'])) { //si se esta enviando datos desde un formulario, puede ser registrar, elimiar o actualizar

    //instanciamos al controlador
    require_once "../controladores/clienteControlador.php";
    $ins_cliente = new clienteControlador();

    //agregar cliente
    if(isset($_POST['cliente_dni_reg']) && isset($_POST['cliente_nombre_reg'])){//solo si vienen definifos estos datos se procede con el registro
        echo $ins_cliente -> agregar_cliente_controlador(); //llamamos al controlador

        

    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}