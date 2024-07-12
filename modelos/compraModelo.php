<?php
require_once "mainModel.php";

class compraModelo extends mainModel{
    
    //modelo para registrar compra
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

    //modelo para registrar detalle de compra
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

    //los siugientes modelos para elimianr compras se usan solo en caso de haber algun error con la insercion de datos
    //modelo para eliminar compras
    protected static function eliminar_compra_modelo($codigo, $tipo){//el codigo de la compra a eliminar, el tipo hace referencia si quiere eliminar de la tabla detalles o de la table compra

        //condicional para verificar de donde se quiere eliminarfe
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

    //modelo para la devolucion de compra
    protected static function devolver_compra_modelo($codigo, $estado){
        if($estado == "Pagado"){
            $query_devolver_compra = "UPDATE compra SET compra_estado = 'Devuelto' WHERE compra_codigo = :Codigo";
            $sql = mainModel::conectar()->prepare($query_devolver_compra);
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
    

    //modelo para seleccionar datos de las compras
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

    //modelo para ver los detalles de la compra
    protected static function obtener_detalles_compra_modelo($compra_id) {
        $sql = "SELECT c.*, p.proveedor_nombre, p.proveedor_ruc, mp.nombre, i.item_codigo, i.item_nombre, i.item_precio, dc.detalleCompra_total, dc.detalleCompra_item_cantidad  
                FROM compra c 
                LEFT JOIN proveedor p 
                ON c.proveedor_id = p.proveedor_id 
                LEFT JOIN metodopago mp
                ON c.metodo_id = mp.idMetodoPago 
                LEFT JOIN detalle_compra dc 
                ON c.compra_codigo = dc.compra_codigo 
                LEFT JOIN item i
                ON dc.item_id = i.item_id
                WHERE c.compra_id = :compra_id";
        
        $stmt = mainModel::conectar()->prepare($sql);
        $stmt->bindParam(":compra_id", $compra_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_compras_mes_modelo() {
        $query = "SELECT YEAR(compra_fecha) AS año,
                         MONTH(compra_fecha) AS mes,
                         SUM(compra_total) AS total_compras
                  FROM compra
                  WHERE compra_estado = 'Pagado'
                  GROUP BY YEAR(compra_fecha), MONTH(compra_fecha)
                  ORDER BY año DESC, mes DESC";
    
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_compras_estado_modelo() {
        $query = "SELECT 
                    SUM(CASE WHEN compra_estado = 'Pagado' THEN 1 ELSE 0 END) AS cantidad_pagado,
                    SUM(CASE WHEN compra_estado = 'Devuelto' THEN 1 ELSE 0 END) AS cantidad_devuelto
                  FROM compra";
    
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_compras_metodo_pago_modelo() {
        $query = "SELECT mp.nombre AS metodo_pago, COUNT(c.compra_id) AS cantidad_compras, SUM(c.compra_total) AS total_compras FROM compra c INNER JOIN metodopago mp ON c.metodo_id = mp.idMetodoPago WHERE c.compra_estado = 'Pagado' GROUP BY mp.nombre ORDER BY total_compras DESC;";
    
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}