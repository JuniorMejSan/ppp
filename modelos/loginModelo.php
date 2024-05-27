<?php
require_once "mainModel.php";

class loginModelo extends mainModel{
    //creacion del modelo para iniciar sesion
    protected static function iniciar_sesion_modelo($datos){//recibe un array para iniciar sesion, tales como el usuario y la contraseña
        $query_conexion = "select * from usuario where user = :User and password = :Password and estado = 'Activa'";
        $sql = mainModel::conectar() -> prepare($query_conexion);

        $sql -> bindParam(":User", $datos['Usuario']);
        $sql -> bindParam(':Password', $datos['Password']);
        $sql -> execute();
        return $sql;
    } 
}  