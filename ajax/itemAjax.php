<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['item_codigo_reg']) || isset($_POST['item_id_del']) || isset($_POST['item_id_enable']) || isset($_POST['item_id_up'])) { //si se esta enviando datos desde un formulario, puede ser registrar, elimiar o actualizar

    //instanciamos al controlador
    require_once "../controladores/itemControlador.php";
    $ins_item = new itemControlador();

    //agregar item
    if(isset($_POST['item_codigo_reg'])){//solo si vienen definifos estos datos se procede con el registro
        echo $ins_item -> agregar_item_controlador(); //llamamos al controlador
    }

    //condicion para eliminar un item
    if(isset($_POST['item_id_del'])){
        echo $ins_item -> eliminar_item_controlador();
    }

    //condicion para habilitar un item
    if(isset($_POST['item_id_enable'])){
        echo $ins_item -> habilitar_item_controlador();
    }

    //condicion para actualizar un item
    if(isset($_POST['item_id_up'])){
        echo $ins_item -> actualizar_item_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}