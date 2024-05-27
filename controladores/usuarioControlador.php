<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/usuarioModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/usuarioModelo.php";
}

class usuarioControlador extends usuarioModelo{

    //controlador para agregar usuario
    public function agregar_usuario_controlador(){//controlador para agregar losd atos del usuario
        //guardamos en variables todos los datos que enviamos desde el formulario
        $dni = mainModel::limpiar_cadena($_POST['usuario_dni_reg']); //usamos limpiar_cadena del main model para evitar injecciones sql
        $nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_reg']);
        $apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_reg']);

        $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
        $email = mainModel::limpiar_cadena($_POST['usuario_email_reg']);
        $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
        $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);
        
        $privilegio = mainModel::limpiar_cadena($_POST['usuario_privilegio_reg']);

        //validamos que los datos no esten vacios
        if ($dni == "" || $nombre == "" || $apellido == "" || $telefono == "" || $direccion == "" || $usuario == "" || $email == "" || $clave1 == "" || $clave2 == "") {
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

        //verificar integridad de datos, es decir que sigan el pattern
        if(mainModel::verificar_datos("[0-9-]{8,20}", $dni)){
            //si entra es porque si se tienen errores en ese dato
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DNI no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
    }
}