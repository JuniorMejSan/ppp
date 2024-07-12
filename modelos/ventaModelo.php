<?php
require_once "mainModel.php";

class ventaModelo extends mainModel{
    
    //modelo para registrar venta
    protected static function agregar_venta_modelo($datos){

        $query_agregar_venta = "INSERT INTO venta(`venta_codigo`, `venta_fecha`, `venta_hora`, `venta_cantidad`, `venta_total`,  `metodo_id`, `venta_observacion`, `usuario_nombre`, `cliente_id`, `venta_estado`) VALUES (:Codigo, :Fecha, :Hora, :Cantidad, :Total, :Metodo, :Observacion, :Usuario, :Cliente, 'Pagado')";
        $sql = mainModel::conectar() -> prepare($query_agregar_venta);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Fecha", $datos['Fecha']);
        $sql -> bindParam(":Hora", $datos['Hora']);
        $sql -> bindParam(":Cantidad", $datos['Cantidad']);
        $sql -> bindParam(":Total", $datos['Total']);
        $sql -> bindParam(":Metodo", $datos['Metodo']);
        $sql -> bindParam(":Observacion", $datos['Observacion']);
        $sql -> bindParam(":Usuario", $datos['Usuario']);
        $sql -> bindParam(":Cliente", $datos['Cliente']);
        $sql -> execute();

        return $sql;
    }

    //modelo para registrar detalle de venta
    protected static function agregar_detalle_venta_modelo($datos){

        $query_agregar_detalle_venta = "INSERT INTO detalle_venta(`venta_codigo`, `item_id`, `detalleVenta_item_cantidad`, `detalleVenta_item_precio`,  `detalleVenta_total`) VALUES (:Venta, :Item_id, :Item_cantidad, :Item_precio, :Detalle_total)";
        $sql = mainModel::conectar() -> prepare($query_agregar_detalle_venta);

        $sql -> bindParam(":Venta", $datos['Venta']);
        $sql -> bindParam(":Item_id", $datos['Item_id']);
        $sql -> bindParam(":Item_cantidad", $datos['Item_cantidad']);
        $sql -> bindParam(":Item_precio", $datos['Item_precio']);
        $sql -> bindParam(":Detalle_total", $datos['Detalle_total']);
        $sql -> execute();

        return $sql;
    }

    //los siugientes modelos para elimianr ventas se usan solo en caso de haber algun error con la insercion de datos
    //modelo para eliminar ventas
    protected static function eliminar_venta_modelo($codigo, $tipo){//el codigo de la venta a eliminar, el tipo hace referencia si quiere eliminar de la tabla detalles o de la table venta

        //condicional para verificar de donde se quiere eliminar
        if($tipo == "Venta"){

            $query_eliminar_venta = "DELETE * FROM venta WHERE venta_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_eliminar_venta);

        }elseif ($tipo = "Detalle_venta") {

            $query_eliminar_detalle_venta = "DELETE * FROM detalle_venta WHERE venta_codigo = :Codigo";
            $sql = mainModel::conectar() -> prepare($query_eliminar_detalle_venta);
        }

        $sql -> bindParam(":Codigo", $codigo);
        $sql -> execute();

        return $sql;
    }

    //modelo para la devolucion de venta
    protected static function devolver_venta_modelo($codigo, $estado){
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
    protected static function datos_venta_modelo($tipo, $id){

        //controlamos segun el tipo de accion
        if($tipo == "Unico"){
            $query_seleccionar_venta = "SELECT * FROM venta WHERE venta_id = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_venta);
            $sql -> bindParam(":ID", $id);
        }elseif($tipo == "Conteo"){
            $query_seleccionar_venta = "SELECT venta_id FROM venta";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_venta);
        }elseif($tipo == "Detalle"){
            $query_seleccionar_detalle = "SELECT * FROM detalle_venta dv INNER JOIN venta v ON dv.venta_codigo = v.venta_codigo INNER JOIN item i ON dv.item_id = i.item_id WHERE dv.venta_codigo = :Codigo";
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

    protected static function obtener_datos_ventaxmes_modelo() {
        $query = "SELECT YEAR(venta_fecha) AS año, MONTH(venta_fecha) AS mes, SUM(venta_total) AS total_ventas FROM venta WHERE venta_estado = 'Pagado' GROUP BY YEAR(venta_fecha), MONTH(venta_fecha) ORDER BY año DESC, mes DESC";
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function obtener_ventas_finalizadas_devueltas_modelo() {
        $query = "SELECT 
                    SUM(CASE WHEN venta_estado = 'Pagado' THEN 1 ELSE 0 END) AS cantidad_pagado, 
                    SUM(CASE WHEN venta_estado = 'Devuelto' THEN 1 ELSE 0 END) AS cantidad_devuelto 
                  FROM venta";
    
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_metodos_pago_modelo() {
        $query = "SELECT mp.nombre AS metodo_pago, 
                         COUNT(v.venta_id) AS cantidad_ventas, 
                         SUM(v.venta_total) AS total_ventas
                  FROM venta v
                  INNER JOIN metodopago mp ON v.metodo_id = mp.idMetodoPago
                  GROUP BY mp.nombre
                  ORDER BY total_ventas DESC";
    
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
}