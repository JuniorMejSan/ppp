<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente']) || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item'])) {//busca desde el valor ingresado en el modal del cliente, 

    //instanciamos al controlador
    require_once "../controladores/ventaControlador.php";
    $ins_venta = new ventaControlador();

    //activamos controlador bucar cliente
    if(isset($_POST['buscar_cliente'])){
        echo $ins_venta -> buscar_cliente_venta_controlador();
    }

    //agregar cliente a la venta
    if(isset($_POST['id_agregar_cliente'])){
        echo $ins_venta -> agregar_cliente_venta_controlador();
    }

    //el9minar cliente a la venta
    if(isset($_POST['id_eliminar_cliente'])){
        echo $ins_venta -> eliminar_cliente_venta_controlador();
    }

    //activamos controlador bucar item
    if(isset($_POST['buscar_item'])){
        echo $ins_venta -> buscar_item_venta_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}