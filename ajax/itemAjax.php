<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (
    isset($_POST['item_codigo_reg']) || 
    isset($_POST['item_id_del']) || 
    isset($_POST['item_id_enable']) || 
    isset($_POST['item_id_up']) || 
    isset($_POST['presentacion_descripcion_reg']) || 
    isset($_POST['presentacion_id_del']) || 
    isset($_POST['presentacion_id_enable']) || 
    isset($_POST['presentacion_id_up']) || 
    (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_grafico') || 
    (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_stock') || 
    (isset($_POST['accion']) && $_POST['accion'] == 'obtener_vendidos_grafico')
    ) { //si se esta enviando datos desde un formulario, puede ser registrar, elimiar o actualizar

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

    if(isset($_POST['presentacion_descripcion_reg'])){
        echo $ins_item -> agregar_presentacion_controlador();
    }

    if(isset($_POST['presentacion_id_del'])){
        echo $ins_item -> eliminar_presentacion_controlador();
    }

    if(isset($_POST['presentacion_id_enable'])){
        echo $ins_item -> habilitar_presentacion_controlador();
    }

    if(isset($_POST['presentacion_id_up'])){
        echo $ins_item -> actualizar_presentacion_controlador();
    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_grafico'){
        echo $ins_item -> obtener_datos_grafico_controlador();
    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_stock'){
        echo $ins_item->obtener_datos_stock_controlador();
    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_vendidos_grafico'){
        echo $ins_item->obtener_datos_vendidos_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}