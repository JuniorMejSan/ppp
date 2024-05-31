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

    //modelo para eliminar usuario
    protected static function eliminar_usuario_modelo($id){//recibe el id para saber que usaurio eliminará
        $query_eliminar_usuario = "DELETE FROM usuario WHERE idUsuario = :ID ";
        $sql = mainModel::conectar()->prepare($query_eliminar_usuario);

        $sql -> bindParam(":ID", $id);
        $sql -> execute();

        return $sql;
    }

    //modelo para seleccionar datos del usuario
    protected static function datos_usuario_modelo($tipo, $id){//el tipo es para saber si queremos los datos para actualizar al usuario o para mostrar el conteo de todos los usuarios existentes, el id para seleccionar los datos segun el id

        //condicional para detectar que tipo de seleccion de datos se hara
        if($tipo == "Unico"){//seleccion de datos para cargarlos en el form de actualizar

            $query_seleccionar_datos = "SELECT * FROM usuario WHERE idUsuario = :ID";
            $sql = mainModel::conectar() -> prepare($query_seleccionar_datos);

            $sql -> bindParam(":ID", $id);
        }else if($tipo == "Conteo"){//para conteo de datos
            $query_conteo_datos = "SELECT idUsuario FROM usuario WHERE idUsuario != '1'";
            $sql = mainModel::conectar() -> prepare($query_conteo_datos);
        }
        $sql -> execute();

        return $sql;
    }
}