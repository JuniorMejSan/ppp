<?php
require_once "mainModel.php";

class itemModelo extends mainModel{

    //modelo para agregar item
    protected static function agregar_item_modelo($datos){

        $query_agregar_item = "INSERT INTO item(`item_codigo`, `item_nombre`, `item_stock`, `item_estado`, `item_detalle`) VALUES (:Codigo, :Nombre, :Stock, :Estado, :Detalle)";
        $sql = mainModel::conectar() -> prepare($query_agregar_item);

        $sql -> bindParam(":Codigo", $datos['Codigo']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Stock", $datos['Stock']);
        $sql -> bindParam(":Estado", $datos['Estado']);
        $sql -> bindParam(":Detalle", $datos['Detalle']);
        $sql -> execute();

        return $sql;
    }
}