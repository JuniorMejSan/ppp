<?php
require_once "mainModel.php";

class itemModelo extends mainModel{

    //modelo para agregar item
    protected static function agregar_item_modelo($datos){

        $query_agregar_item = "INSERT INTO item(`item_codigo`, `item_nombre`, `item_stock`, `item_precio`,  `item_precio_compra`, `item_estado`, `item_detalle`) VALUES (:Codigo, :Nombre, :Stock, :Precio, :Precio_compra, :Estado, :Detalle)";
        $sql = mainModel::conectar() -> prepare($query_agregar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Precio", $datos['Precio']);
        $sql -> bindParam(":Precio_compra", $datos['Precio_compra']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> bindParam(":Detalle", $datos['Detalle']);
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
    
    //modelo para habilitar item
    protected static function habilitar_item_modelo($id){
        $query_habilitar_item = "UPDATE item SET item_estado = 'Habilitado' WHERE item_id = :ID";
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

    //modelo para actualizar item
    protected static function actualizar_item_modelo($datos){
        $query_actualizar_item = "UPDATE item SET item_codigo = :Codigo, item_nombre = :Nombre, item_stock = :Stock, item_precio = :Precio, item_precio_compra = :Precio_compra, item_estado = :Estado, item_detalle = :Detalle  WHERE item_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_actualizar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Precio", $datos['Precio']);
        $sql -> bindParam(":Precio_compra", $datos['Precio_compra']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> bindParam(":Detalle", $datos['Detalle']);
        $sql -> bindParam(":ID", $datos['ID']);

        $sql -> execute();

        return $sql;
    }
}