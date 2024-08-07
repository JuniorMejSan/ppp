<?php
$peticionAjax = true;
require_once "../config/app.php";

//detectar si los datos se envian esde un formulario para ejecutar los controladores o funciones
if (isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente']) || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_item']) || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item']) ||isset($_POST['fecha_venta_reg']) || isset($_POST['venta_id_devuelta']) || (isset($_POST['accion']) && $_POST['accion'] == 'obtenerDetallesVenta') || isset($_POST['detalle_cantidad_editar']) || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_ventasxmes_grafico') || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_ventas_finalizadas_devueltas') || (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_metodos_pago')) {

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

    //agregar item a la venta
    if (isset($_POST['id_agregar_item'])) {
        echo $ins_venta -> agregar_item_venta_controlador();
    }

    //agregar item a la venta
    if (isset($_POST['id_eliminar_item'])) {
        echo $ins_venta -> eliminar_item_venta_controlador();
    }

    //activar controlador para guardar venta
    if (isset($_POST['fecha_venta_reg'])) {
        echo $ins_venta -> agregar_venta_controlador();
    }

    if(isset($_POST['venta_id_devuelta'])){
        echo $ins_venta -> devolver_venta_controlador();
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'obtenerDetallesVenta') {
        echo $ins_venta->obtener_detalles_venta_controlador();
    }

    if (isset($_POST['detalle_cantidad_editar'])) {
        echo $ins_venta->editar_cantidad_item_venta_controlador();
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_ventasxmes_grafico') {
        echo $ins_venta->obtener_datos_ventaxmes_controlador();
    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_ventas_finalizadas_devueltas'){
        echo $ins_venta->obtener_ventas_finalizadas_devueltas_controlador();
    }

    if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_datos_metodos_pago'){
        echo $ins_venta->obtener_datos_metodos_pago_controlador();
    }

}else { //si no significa que se esta intentando acceder desde el navegador
    session_start(['name' => 'ppp']); //se le asigna un nombre a la sesion
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}