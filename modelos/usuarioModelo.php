<?php
require_once "mainModel.php";

class usuarioModelo extends mainModel{
    //modelo para agregar usuario
    protected static function agregar_usuario_modelo($datos){//$datos contiene todos los datos enviados desde el controlador
        $query_agregar_usuario = "INSERT INTO usuario (`dni`, `nombre`, `apellido`, `telefono`, `direccion`, `email`, `user`, `password`, `estado`, `privilegio`) VALUES (:DNI, :Nombre, :Apellido, :Telefono, :Direccion, :Email, :User, :Password, :Estado, :Privilegio)";//query para agregar usuario
        $sql = mainModel::conectar()->prepare($query_agregar_usuario);//preparamos la ejecucion de la consulta
        $sql -> bindParam(":DNI", $datos['DNI']); //sirve para enlazar los datos enviados desde el controlador con la consulta
        $sql -> bindParam(':Nombre', $datos['Nombre']);
        $sql -> bindParam(':Apellido', $datos['Apellido']);
        $sql -> bindParam(':Telefono', $datos['Telefono']);
        $sql -> bindParam(':Direccion', $datos['Direccion']);
        $sql -> bindParam(':Email', $datos['Email']);
        $sql -> bindParam(':User', $datos['User']);
        $sql -> bindParam(':Password', $datos['Password']);
        $sql -> bindParam(':Estado', $datos['Estado']);
        $sql -> bindParam(':Privilegio', $datos['Privilegio']);
        $sql -> execute();

        return $sql;//se envía al controlador el resultado de este modelo
    }
}