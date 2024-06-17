<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['proveedor_ruc_reg']) || isset($_POST['proveedor_id_del']) || isset($_POST['cliente_id_up'])) { //si se esta enviando datos desde un formulario, puede ser registrar, elimiar o actualizar

    //instanciamos al controlador
    require_once "../controladores/proveedorControlador.php";
    $ins_proveedor = new proveedorControlador();

    //agregar cliente
    if(isset($_POST['proveedor_ruc_reg'])){//solo si vienen definifos estos datos se procede con el registro
        echo $ins_proveedor -> agregar_proveedor_controlador(); //llamamos al controlador
    }

    //condicion para eliminar un cliente
    if(isset($_POST['proveedor_id_del'])){
        echo $ins_proveedor -> eliminar_proveedor_controlador();
    }

    //condicion para actualizar un cliente
    if(isset($_POST['cliente_id_up'])){
        echo $ins_cliente -> actualizar_cliente_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}