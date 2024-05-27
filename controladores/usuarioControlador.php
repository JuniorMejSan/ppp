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

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $apellido)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El APELLIDO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9()+]{9,20}", $telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El TELÉFONO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DIRECCIÓN no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE DE USUARIO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("a-zA-Z0-9$@.-]{7,100}", $clave2)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Las CONTRASEÑAS no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //comprobacion de que el DNI no esté previamente registrado
        $query_check_dni = "select dni from usuario where dni = '$dni'";
        $check_dni = mainModel::ejecutar_consulta_simple($query_check_dni);
        if ($check_dni -> rowCount() > 0) { //verificamos si la consulta trajo datos
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DNI ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //comprobando existencia del usuario
        $query_check_usuario = "select user from usuario where user = '$usuario'";
        $check_usuario = mainModel::ejecutar_consulta_simple($query_check_usuario);
        if ($check_usuario -> rowCount() > 0) { //verificamos si la consulta trajo datos
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE DE USUARIO ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //comprobando existencia del CORREO y tenga el formato adecuado
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {//fucnion de php para verificar formato de correo
            $query_check_email = "select email from usuario where email = '$email'";
            $check_email = mainModel::ejecutar_consulta_simple($query_check_email);
            if ($check_email -> rowCount() > 0) { //verificamos si la consulta trajo datos
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "El CORREO ingresado ya se encuentra registrado",
                    "Tipo" => "error"
                ];

                echo json_encode($alerta);
                exit();
            }
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El CORREO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //comprobacion de contraseña iguales en ambos campos
        if ($clave1 != $clave2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Las CONTRASEÑAS deben coincidir",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }else{

        }
    }
}