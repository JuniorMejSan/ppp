<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/itemModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/itemModelo.php";
}

class itemControlador extends itemModelo{

    //controlador para agregar item
    public static function agregar_item_controlador(){

        //recibimos los datos que se envian desde el form
        $codigo = mainModel::limpiar_cadena($_POST['item_codigo_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['item_nombre_reg']);
        $stock = mainModel::limpiar_cadena($_POST['item_stock_reg']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_reg']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);

        //comprobar que los campos obligatorios no esten vacios
        if ($codigo == "" || $nombre == "" || $stock == "" || $estado == "") {
            //aqui se definen los tipos de alerta que se esperan en alerta_ajax de alerta.js
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han completado los campos",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit(); //deja de ejecutarse el codigo y muestra la alerta
        }

        //verificamos integridad de los datos
        if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}", $codigo)){
            //si entra es porque si se tienen errores en ese dato
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El CODIGO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if(mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $nombre)){
            //si entra es porque si se tienen errores en ese dato
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,9}", $stock)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La STOCK no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if($detalle != ""){
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $detalle)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "El DETALLE no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
    
                echo json_encode($alerta);
                exit();
            }
        }

        if($estado != "Habilitado" && $estado != "Deshabilitado"){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Debe seleccionar un ESTADO valido",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar CODIGO para que no se repita en la bd
        $query_check_codigo = "SELECT item_codigo FROM item WHERE item_codigo ='$codigo'";
        $check_codigo = mainModel::ejecutar_consulta_simple($query_check_codigo);

        //comprobar que el CODIGO no este registrando mediante el conteo de rows que trae
        if($check_codigo -> rowCount() > 0) {//ya existe dni en la bd
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El ITEM ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //array de datos a enviar al modelo para el registro
        $datos_item_reg = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Estado" => $estado,
            "Detalle" => $detalle
        ];

        //variable para agregar
        $agregar_item = itemModelo::agregar_item_modelo($datos_item_reg);

        //condicional para verificar el correcto registro
        if( $agregar_item -> rowCount() == 1){
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Registro exitoso",
                "Texto"=> "ITEM registrado satisfactoriamente",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Ocurrio un problema al registrar el ITEM, porfavor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }
}