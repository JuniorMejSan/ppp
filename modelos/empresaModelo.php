<?php
require_once "mainModel.php";

class empresaModelo extends mainModel{

    //modelo para los datos de la empresa
    protected static function datos_empresa_modelo(){

        $query_datos_empresa = "SELECT * FROM empresa";
        $sql = mainModel::conectar() -> prepare($query_datos_empresa);

        $sql -> execute();
        return $sql;
    }
}