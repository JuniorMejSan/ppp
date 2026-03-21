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

    //modelo para validar la contraseña del administrador (usuario id 1)
    protected static function validar_password_admin_modelo($password){
        $query = "SELECT idUsuario FROM usuario WHERE idUsuario = 1 AND password = :Password AND estado = 'Activa'";
        $sql = mainModel::conectar() -> prepare($query);
        $sql -> bindParam(':Password', $password);
        $sql -> execute();
        return $sql;
    } 
}  