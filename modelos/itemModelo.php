<?php
require_once "mainModel.php";

class itemModelo extends mainModel{

    //modelo para agregar item
    protected static function agregar_item_modelo($datos){

        $query_agregar_item = "INSERT INTO item(`item_codigo`, `item_nombre`, `item_stock`, `item_precio`, `item_estado`, `item_detalle`) VALUES (:Codigo, :Nombre, :Stock, :Precio, :Estado, :Detalle)";
        $sql = mainModel::conectar() -> prepare($query_agregar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Precio", $datos['Precio']);
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
}