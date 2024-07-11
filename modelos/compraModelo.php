<?php
require_once "mainModel.php";

class compraModelo extends mainModel{
    
    //modelo para registrar venta
    protected static function agregar_compra_modelo($datos){

        $query_agregar_compra = "INSERT INTO compra (`compra_codigo`, `compra_fecha`, `compra_hora`, `compra_cantidad`, `compra_total`,  `metodo_id`, `compra_observacion`, `usuario_nombre`, `proveedor_id`, `compra_estado`) VALUES (:Codigo, :Fecha, :Hora, :Cantidad, :Total, :Metodo, :Observacion, :Usuario, :Proveedor, 'Pagado')";
        $sql = mainModel::conectar() -> prepare($query_agregar_compra);

        $sql -> bindParam(":Codigo", $datos['Codigo_compra']);
        $sql -> bindParam(":Fecha", $datos['Fecha_compra']);
        $sql -> bindParam(":Hora", $datos['Hora_compra']);
        $sql -> bindParam(":Cantidad", $datos['Cantidad_compra']);
        $sql -> bindParam(":Total", $datos['Total_compra']);
        $sql -> bindParam(":Metodo", $datos['Metodo_compra']);
        $sql -> bindParam(":Observacion", $datos['Observacion_compra']);
        $sql -> bindParam(":Usuario", $datos['Usuario_compra']);
        $sql -> bindParam(":Proveedor", $datos['Proveedor_compra']);
        $sql -> execute();

        return $sql;
    }

    //modelo para registrar detalle de venta
    protected static function agregar_detalle_compra_modelo($datos){

        $query_agregar_detalle_compra = "INSERT INTO detalle_compra(`compra_codigo`, `item_id`, `detalleCompra_item_cantidad`, `detalleCompra_item_precio`,  `detalleCompra_total`) VALUES (:Venta, :Item_id, :Item_cantidad, :Item_precio, :Detalle_total)";
        $sql = mainModel::conectar() -> prepare($query_agregar_detalle_compra);

        $sql -> bindParam(":Venta", $datos['compra']);
        $sql -> bindParam(":Item_id", $datos['Item_id']);
        $sql -> bindParam(":Item_cantidad", $datos['Item_cantidad']);
        $sql -> bindParam(":Item_precio", $datos['Item_precio']);
        $sql -> bindParam(":Detalle_total", $datos['Detalle_total']);
        $sql -> execute();

        return $sql;
    }

    //los siugientes modelos para elimianr ventas se usan solo en caso de haber algun error con la insercion de datos
    //modelo para eliminar ventas
    protected static function eliminar_compra_modelo($codigo, $tipo){//el codigo de la venta a eliminar, el tipo hace referencia si quiere eliminar de la tabla detalles o de la table venta

        //condicional para verificar de donde se quiere eliminar
        if($tipo == "Compra"){

            $query_eliminar_compra = "DELETE * FROM compra WHERE compra_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_eliminar_compra);

        }elseif ($tipo = "Detalle_compra") {

            $query_eliminar_detalle_compra = "DELETE * FROM detalle_compra WHERE compra_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_eliminar_detalle_compra);
        }

        $sql -> bindParam(":Codigo", $codigo);
        $sql -> execute();

        return $sql;
    }

    //modelo para la devolucion de venta
    protected static function devolver_compra_modelo($codigo, $estado){
        if($estado == "Pagado"){
            $query_devolver_venta = "UPDATE venta SET venta_estado = 'Devuelto' WHERE venta_codigo = :Codigo";
            $sql = mainModel::conectar()->prepare($query_devolver_venta);
        }
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        return $sql;
    }
    
    protected static function actualizar_stock_item($item_id, $cantidad, $accion){
        if($accion == 'sumar'){
            $query_actualizar_stock = "UPDATE item SET item_stock = item_stock + :Cantidad WHERE item_id = :ItemID";
        }else if($accion == 'restar'){
            $query_actualizar_stock = "UPDATE item SET item_stock = item_stock - :Cantidad WHERE item_id = :ItemID";
        }
        $sql = mainModel::conectar()->prepare($query_actualizar_stock);
        $sql->bindParam(":Cantidad", $cantidad);
        $sql->bindParam(":ItemID", $item_id);
        $sql->execute();
        return $sql;
    }
    

    //modelo para seleccionar datos de las ventas
    protected static function datos_compra_modelo($tipo, $id){

        //controlamos segun el tipo de accion
        if($tipo == "Unico"){
            $query_seleccionar_compra = "SELECT * FROM compra WHERE compra_id = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_compra);
            $sql -> bindParam(":ID", $id);
        }elseif($tipo == "Conteo"){
            $query_seleccionar_compra = "SELECT compra_id FROM compra";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_compra);
        }elseif($tipo == "Detalle"){
            $query_seleccionar_detalle = "SELECT * FROM detalle_compra dc INNER JOIN compra c ON dc.compra_codigo = c.compra_codigo INNER JOIN item i ON dc.item_id = i.item_id WHERE dc.compra_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_detalle);
            $sql -> bindParam(":Codigo", $id);
        }
        $sql -> execute();
        return $sql;
    }

    //modelo para actualizar estado de venta en caso de que el cliente quiera cancelar (devolucion) la venta
    protected static function actualizar_venta_controler($datos){

        //condicional para verificar que es lo que se cambiara
        if ($datos['Tipo'] == 'Estado_cancelado') {
            $query_actualizar_estado_venta = "UPDATE venta SET venta_estado = 'Devuelto' WHERE venta_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_actualizar_estado_venta);
        }elseif ($datos['Tipo'] == 'Observacion') {
            $query_actualizar_observacion_venta = "UPDATE venta SET venta_observacion = :Observacion WHERE venta_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_actualizar_observacion_venta);
            $sql -> bindParam(":Observacion", $datos['Observacion']);
        }
        
        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> execute();
        return $sql;
    }

    //modelo para ver los detalles de la venta
    protected static function obtener_detalles_venta_modelo($venta_id) {
        $sql = "SELECT v.*, c.cliente_nombre, c.cliente_apellido, mp.nombre, i.item_codigo, i.item_nombre, i.item_precio, dv.detalleVenta_total, dv.detalleVenta_item_cantidad  
                FROM venta v 
                LEFT JOIN cliente c 
                ON v.cliente_id = c.cliente_id 
                LEFT JOIN metodopago mp
                ON v.metodo_id = mp.idMetodoPago 
                LEFT JOIN detalle_venta dv 
                ON v.venta_codigo = dv.venta_codigo 
                LEFT JOIN item i
                ON dv.item_id = i.item_id
                WHERE v.venta_id = :venta_id";
        
        $stmt = mainModel::conectar()->prepare($sql);
        $stmt->bindParam(":venta_id", $venta_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}