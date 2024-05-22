<?php
    if ($peticionAjax) { //si es true significa que esta accediendo desde la carpeta ajax
        require_once "../config/server.php";
    }else { //cuando $peticionAjax es flase indica que estamos entrando desde index.php 
        require_once "./config/server.php";
    }

    class mainModel{
        //funcion para conectar a la BD
        protected static function conectar(){
            $conexion = new PDO(sgdb, user, pass);
            $conexion -> exec("SET CHARACTER utf8");
            return $conexion;
        }

        //funcion para ejecutar consultas simples
        protected static function ejecutar_consulta_simple($consulta){
            $sql = self::conectar()-> prepare($consulta);//con self hacemos referencia a una funcion o metodo del modelo actual
            $sql -> execute();
            return $sql;
        }
    }