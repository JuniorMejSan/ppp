<?php
require_once "mainModel.php";

class clienteModelo extends mainModel{

    //modelo para registrar cliente
    protected static function agregar_cliente_modelo($datos){

        $query_agregar_cliente = "INSERT INTO cliente(`cliente_dni`, `cliente_nombre`, `cliente_apellido`, `cliente_telefono`, `cliente_direccion`) VALUES (:DNI, :Nombre, :Apellido, :Telefono, :Direccion)";
        $sql = mainModel::conectar() -> prepare($query_agregar_cliente);

        $sql -> bindParam(":DNI", $datos['DNI']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Apellido", $datos['Apellido']);
        $sql -> bindParam(":Telefono", $datos['Telefono']);
        $sql -> bindParam(":Direccion", $datos['Direccion']);
        $sql -> execute();

        return $sql;
    }

    //modelo para eliminar cliente
    protected static function eliminar_cliente_modelo($id){
        $query_eliminar_cliente = "DELETE FROM cliente WHERE cliente_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_eliminar_cliente);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }
    
}