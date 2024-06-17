<?php
require_once "mainModel.php";

class proveedorModelo extends mainModel{
    protected static function agregar_proveedor_modelo($datos){

        $query_agregar_proveedor = "INSERT INTO proveedor(`proveedor_ruc`, `proveedor_nombre`, `proveedor_direccion`, `proveedor_pais`, `proveedor_telefono`, `proveedor_email`, `proveedor_estado`) VALUES (:RUC, :Nombre, :Direccion, :Pais, :Telefono, :Email, 'Habilitado')";
        $sql = mainModel::conectar() -> prepare($query_agregar_proveedor);

        $sql -> bindParam(":RUC", $datos['RUC']);
        $sql -> bindParam(":Nombre", $datos['Nombre']);
        $sql -> bindParam(":Direccion", $datos['Direccion']);
        $sql -> bindParam(":Pais", $datos['Pais']);
        $sql -> bindParam(":Telefono", $datos['Telefono']);
        $sql -> bindParam(":Email", $datos['Email']);
        $sql -> execute();

        return $sql;
    }

        //modelo para eliminar proveedor
        protected static function eliminar_proveedor_modelo($id){
            $query_eliminar_proveedor= "UPDATE proveedor SET proveedor_estado = 'Inhabilitado' WHERE proveedor_id = :ID";
            $sql = mainModel::conectar() -> prepare($query_eliminar_proveedor);
    
            $sql -> bindParam(":ID", $id);
    
            $sql -> execute();
    
            return $sql;
        }
}