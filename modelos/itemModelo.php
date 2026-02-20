<?php
require_once "mainModel.php";

class itemModelo extends mainModel{

    //modelo para agregar item
    protected static function agregar_item_modelo($datos){

        $query_agregar_item = "INSERT INTO item(`item_codigo`, `item_nombre`, `item_stock`, `item_precio`,  `item_precio_compra`, `item_estado`, `item_detalle`, id_presentacion, item_fecha_vencimiento) VALUES (:Codigo, :Nombre, :Stock, :Precio, :Precio_compra, :Estado, :Detalle,:Presentacion, :Fecha_vencimiento)";
        $sql = mainModel::conectar() -> prepare($query_agregar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Precio", $datos['Precio']);
        $sql -> bindParam(":Precio_compra", $datos['Precio_compra']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> bindParam(":Detalle", $datos['Detalle']);
        $sql->bindParam(":Presentacion", $datos['Presentacion']);
        $sql->bindValue(":Fecha_vencimiento", $datos['Fecha_vencimiento']);
        $sql -> execute();

        return $sql;
    }

    protected static function agregar_descripcion_modelo($datos){

        $query_agregar_item = "INSERT INTO presentacion (`descripcion`, `estado`) VALUES (:Descripcion, :Estado)";
        $sql = mainModel::conectar() -> prepare($query_agregar_item);

        $sql -> bindParam(":Descripcion", $datos['Descripcion']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> execute();

        return $sql;
    }

    //modelo para eliminar item
    protected static function eliminar_item_modelo($id){
        $query_eliminar_item = "UPDATE item SET item_estado = 'Inhabilitado' WHERE item_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_eliminar_item);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }

    protected static function eliminar_presentacion_modelo($id){
        $query_eliminar_presentacion = "UPDATE presentacion SET estado = '0' WHERE id_presentacion = :ID";
        $sql = mainModel::conectar() -> prepare($query_eliminar_presentacion);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }
    
    //modelo para habilitar item
    protected static function habilitar_item_modelo($id){
        $query_habilitar_item = "UPDATE item SET item_estado = 'Habilitado' WHERE item_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_habilitar_item);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }

    protected static function habilitar_presentacion_modelo($id){
        $query_habilitar_item = "UPDATE presentacion SET estado = 1 WHERE id_presentacion = :ID";
        $sql = mainModel::conectar() -> prepare($query_habilitar_item);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }

    //modelo para seleccionar los datos de los items
    protected static function datos_item_modelo($tipo, $id){//tipos de consulta y el id del item selecciondo
        if($tipo == "Unico"){//verificamos el tipo de seleccion de datos
            $query_seleccionar_item = "SELECT * FROM item WHERE item_id = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_item);

            $sql -> bindParam(":ID", $id);
        }elseif($tipo == "Conteo"){
            $query_seleccionar_item = "SELECT item_id FROM item WHERE item_estado = 'Habilitado'";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_item);
        }
        $sql -> execute();
        return $sql;
    }

    protected static function datos_presentacion_modelo($tipo, $id){//tipos de consulta y el id del item selecciondo
        if($tipo == "Unico"){//verificamos el tipo de seleccion de datos
            $query_seleccionar_presentacion = "SELECT * FROM presentacion WHERE id_presentacion = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_presentacion);

            $sql -> bindParam(":ID", $id);
        }elseif($tipo == "Conteo"){
            $query_seleccionar_presentacion = "SELECT id_presentacion FROM presentacion WHERE item_estado = 1";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_presentacion);
        }
        $sql -> execute();
        return $sql;
    }

    //modelo para actualizar item
    protected static function actualizar_item_modelo($datos){
        $query_actualizar_item = "UPDATE item SET item_codigo = :Codigo, item_nombre = :Nombre, item_stock = :Stock, item_precio = :Precio, item_precio_compra = :Precio_compra, item_estado = :Estado, item_detalle = :Detalle, id_presentacion = :Presentacion, item_fecha_vencimiento = :Fecha_vencimiento  WHERE item_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_actualizar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Precio", $datos['Precio']);
        $sql -> bindParam(":Precio_compra", $datos['Precio_compra']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> bindParam(":Detalle", $datos['Detalle']);
        $sql -> bindParam(":Presentacion", $datos['Presentacion']);
        $sql -> bindParam(":Fecha_vencimiento", $datos['Fecha_vencimiento']);
        $sql -> bindParam(":ID", $datos['ID']);

        $sql -> execute();

        return $sql;
    }

    protected static function actualizar_presentacion_modelo($datos){
        $query_actualizar_item = "UPDATE presentacion SET descripcion = :Descripcion WHERE id_presentacion = :ID";
        $sql = mainModel::conectar() -> prepare($query_actualizar_item);

        
        $sql -> bindParam(":Descripcion", $datos['Descripcion']);
        $sql -> bindParam(":ID", $datos['ID']);

        $sql -> execute();

        return $sql;
    }

    //funcion para grafico top 5 productos mas vendidos
    protected static function obtener_datos_grafico_modelo() {
        $query = "SELECT i.item_nombre, SUM(dv.detalleVenta_item_cantidad) AS total_vendido, SUM(dv.detalleVenta_total) AS monto_total_generado 
                  FROM detalle_venta dv 
                  JOIN item i ON dv.item_id = i.item_id 
                  GROUP BY i.item_id, i.item_nombre 
                  ORDER BY monto_total_generado DESC 
                  LIMIT 5";
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_vendidos_modelo() {
        $query = "SELECT i.item_nombre, SUM(dv.detalleVenta_item_cantidad) AS total_vendido 
                  FROM detalle_venta dv 
                  JOIN item i ON dv.item_id = i.item_id 
                  GROUP BY i.item_id, i.item_nombre 
                  ORDER BY total_vendido DESC 
                  LIMIT 5";
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function obtener_datos_stock_modelo() {
        $query = "SELECT item_nombre, `item_stock` FROM `item` ORDER BY item_stock DESC;";
        $sql = mainModel::conectar()->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected static function listar_presentaciones_habilitadas_modelo(){
        $sql = mainModel::conectar()->prepare("SELECT id_presentacion, descripcion FROM presentacion WHERE estado='1' ORDER BY descripcion ASC");
        $sql->execute();
        return $sql;
    }
}