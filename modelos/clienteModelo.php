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
        $query_eliminar_cliente = "UPDATE cliente SET cliente_estado = 'Inhabilitado' WHERE cliente_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_eliminar_cliente);

        $sql -> bindParam(":ID", $id);

        $sql -> execute();

        return $sql;
    }
    
    //modelo para seleccionar los datos del cliente
    protected static function datos_cliente_modelo($tipo, $id){//tipos de consulta y el is del cliente a eliminar
        if($tipo == "Unico"){//verificamos el tipo de seleccion de datos
            $query_seleccionar_cliente = "SELECT * FROM cliente WHERE cliente_id = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_cliente);

            $sql -> bindParam(":ID", $id);
        }elseif($tipo == "Conteo"){
            $query_seleccionar_cliente = "SELECT cliente_id FROM cliente";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_cliente);
        }
        $sql -> execute();
        return $sql;
    }

    //modelo para actualizar cliente
    protected static function actualizar_cliente_modelo($datos){
        $query_actualizar_cliente = "UPDATE cliente SET cliente_dni = :DNI, cliente_nombre = :Nombre, cliente_apellido = :Apellido, cliente_telefono = :Telefono, cliente_direccion = :Direccion WHERE cliente_id = :ID";
        $sql = mainModel::conectar() -> prepare($query_actualizar_cliente);

        $sql -> bindParam(":DNI", $datos['DNI']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Apellido", $datos['Apellido']);
        $sql -> bindParam(":Telefono", $datos['Telefono']);
        $sql -> bindParam(":Direccion", $datos['Direccion']);
        $sql -> bindParam(":ID", $datos['ID']);

        $sql -> execute();

        return $sql;
    }
}