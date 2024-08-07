<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['buscar_proveedor']) || isset($_POST['id_agregar_proveedor']) || isset($_POST['id_eliminar_proveedor']) || isset($_POST['buscar_item']) || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item']) ||isset($_POST['fecha_compra_reg']) || isset($_POST['compra_id_devuelta']) || (isset($_POST['accion']) && $_POST['accion'] == 'obtenerDetallesCompra') || isset($_POST['detalle_cantidad_editar']) || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_mes') || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_estado') || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_metodo_pago')) {

    //instanciamos al controlador
    require_once "../controladores/compraControlador.php";
    $ins_compra = new compraControlador();

    //activamos controlador bucar proveedor
    if(isset($_POST['buscar_proveedor'])){
        echo $ins_compra -> buscar_proveedor_compra_controlador();
    }

    //agregar proveedor a la compra
    if(isset($_POST['id_agregar_proveedor'])){
        echo $ins_compra -> agregar_proveedor_compra_controlador();
    }

    //el9minar proveedor a la compra
    if(isset($_POST['id_eliminar_proveedor'])){
        echo $ins_compra -> eliminar_proveedor_compra_controlador();
    }

    //activamos controlador bucar item
    if(isset($_POST['buscar_item'])){
        echo $ins_compra -> buscar_item_compra_controlador();
    }

    //agregar item a la compra
    if (isset($_POST['id_agregar_item'])) {
        echo $ins_compra -> agregar_item_compra_controlador();
    }

    //agregar item a la compra
    if (isset($_POST['id_eliminar_item'])) {
        echo $ins_compra -> eliminar_item_compra_controlador();
    }

    //activar controlador para guardar compra
    if (isset($_POST['fecha_compra_reg'])) {
        echo $ins_compra -> agregar_compra_controlador();
    }

    if(isset($_POST['compra_id_devuelta'])){
        echo $ins_compra -> devolver_compra_controlador();
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'obtenerDetallesCompra') {
        echo $ins_compra -> obtener_detalles_compra_controlador();
    }

    if (isset($_POST['detalle_cantidad_editar'])) {
        echo $ins_compra -> editar_cantidad_item_compra_controlador();
    }
    
    if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_mes') {
        echo $ins_compra->obtener_datos_compras_mes_controlador();
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_estado') {
        echo $ins_compra->obtener_datos_compras_estado_controlador();
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_compras_metodo_pago') {
        echo $ins_compra->obtener_datos_compras_metodo_pago_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}