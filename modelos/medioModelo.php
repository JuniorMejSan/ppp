<?php
require_once "mainModel.php";

class medioModelo extends mainModel {

    // Modelo para agregar medio de pago
    protected static function agregar_medio_modelo($datos) {
        $query_agregar_medio = "INSERT INTO medio_pago (`descripcion`, `estado`) VALUES (:Descripcion, :Estado)";
        $sql = mainModel::conectar()->prepare($query_agregar_medio);

        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->execute();

        return $sql;
    }

    // Modelo para eliminar/inhabilitar medio de pago
    protected static function eliminar_medio_modelo($id) {
        $query_eliminar_medio = "UPDATE medio_pago SET estado = 0 WHERE id_medio_pago = :ID";
        $sql = mainModel::conectar()->prepare($query_eliminar_medio);

        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    // Modelo para habilitar medio de pago
    protected static function habilitar_medio_modelo($id) {
        $query_habilitar_medio = "UPDATE medio_pago SET estado = 1 WHERE id_medio_pago = :ID";
        $sql = mainModel::conectar()->prepare($query_habilitar_medio);

        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    // Modelo para obtener datos de un medio de pago
    protected static function datos_medio_modelo($tipo, $id) {
        if ($tipo == "Unico") {
            $query_seleccionar_medio = "SELECT * FROM medio_pago WHERE id_medio_pago = :ID";
            $sql = mainModel::conectar()->prepare($query_seleccionar_medio);
            $sql->bindParam(":ID", $id);
        } elseif ($tipo == "Conteo") {
            $query_seleccionar_medio = "SELECT id_medio_pago FROM medio_pago WHERE estado = 1";
            $sql = mainModel::conectar()->prepare($query_seleccionar_medio);
        }
        $sql->execute();
        return $sql;
    }

    // Modelo para actualizar medio de pago
    protected static function actualizar_medio_modelo($datos) {
        $query_actualizar_medio = "UPDATE medio_pago SET descripcion = :Descripcion WHERE id_medio_pago = :ID";
        $sql = mainModel::conectar()->prepare($query_actualizar_medio);

        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":ID", $datos['ID']);
        $sql->execute();

        return $sql;
    }

    // Modelo para listar medios de pago habilitados
    protected static function listar_medios_habilitados_modelo() {
        $sql = mainModel::conectar()->prepare("SELECT id_medio_pago, descripcion FROM medio_pago WHERE estado = 1 ORDER BY descripcion ASC");
        $sql->execute();
        return $sql;
    }
}