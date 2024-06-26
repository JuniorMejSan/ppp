<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/loginModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/loginModelo.php";
}

class loginControlador extends loginModelo{

    //controlador para iniciar sesion
    public function iniciar_sesion_controlador(){

        //esta parte sirve para evitar inyeccion sql
        $usuario = mainModel::limpiar_cadena($_POST['usuario_log']); //estos son los name de los input en la vista del login
        $password = mainModel::limpiar_cadena($_POST['clave_log']);

        //comprobacion de campos vacios
        if ($usuario == ''|| $password == '') {//colocamos codigo JS directo porque no se trabaja con ajax
            echo '<script> 
                Swal.fire({
                    title: "Ocurrio un error",
                    text: "Los campos no pueden estar vacios",
                    type: "error",
                    confirmButtonText: "Aceptar"
                })
            </script>';
            exit();
        }

        //verificar la integridad de datos
        if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario)){//el filtro es el patern del input en la vista
            //si entra es porque si se tienen errores en ese dato
            echo '<script> 
                Swal.fire({
                    title: "Ocurrio un error",
                    text: "El nombre de USUARIO no coincide con el formato requerido",
                    type: "error",
                    confirmButtonText: "Aceptar"
                })
            </script>';
            exit();
        }

        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $password)){//el filtro es el patern del input en la vista
            //si entra es porque si se tienen errores en ese dato
            echo '<script> 
                Swal.fire({
                    title: "Ocurrio un error",
                    text: "La CLAVE no coincide con el formato requerido",
                    type: "error",
                    confirmButtonText: "Aceptar"
                })
            </script>';
            exit();
        }

        //encriptamos la clave
        $password = mainModel::encryption($password);
        
        //array que contendrá los datos
        $datos_login = [
            "Usuario" => $usuario,
            "Password" => $password
        ];

        //consulta a la base de datos con el modelo
        $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);

        if ($datos_cuenta -> rowCount() == 1) { //existe un registro que coincide con las credenciales
            //variale que almacena todos los datos que vienen en la consulta
            $row = $datos_cuenta -> fetch(); //permite hacer un array de datos mediante la consulta realizada
            //variables de sesion
            session_start(['name' => 'ppp']);//nombre clave en las varibales de sesion
            $_SESSION['id_ppp'] = $row['idUsuario']; //esta variable asignamos el valor que hay en la bd
            $_SESSION['nombre_ppp'] = $row['nombre'];
            $_SESSION['apellido_ppp'] = $row['apellido'];
            $_SESSION['usuario_ppp'] = $row['user'];
            $_SESSION['privilegio_ppp'] = $row['privilegio'];
            $_SESSION['token_ppp'] = md5(uniqid(mt_rand(), true));//para el cierre de sesion de forma segura, se crea un id unico
            return header("Location: ".server_url."home/");
        }else{//no existen las credenciales
            echo '<script> 
                Swal.fire({
                    icon: "error",
                    title: "Ocurrio un error",
                    text: "USUARIO o CLAVE incorrectos",
                    confirmButtonText: "Aceptar"
                })
            </script>';
        }
    }

    //controlador para forzar cierre de sesion
    public function forzar_cierre_sesion_controlador(){
        session_unset();
        session_destroy();
        //redireccionamos al usuario
        if(headers_sent()){//verifica si se estan enviando encabezado mediante php
            return "<script> window.location.href = '".server_url."login/'; </script>";
        }else{//se redireccion al login
            return header("Location: ".server_url."login/");
        }
    }

    //controlador para cierre de sesion
    public function cerrar_sesion_controlador(){
        session_start(['name' => 'ppp']);//todas las sesiones llevan este nombre

        $token = mainModel::decryption($_POST['token']);
        $usuario = mainModel::decryption($_POST['usuario']);

        //comprobacion antes de cerra sesion si es las variables que se envian al darle clic a cerrar son las mismas que se tiene en la sesion
        if ($token == $_SESSION['token_ppp'] && $usuario == $_SESSION['usuario_ppp']) {
            //se destruye la sesion
            session_unset();
            session_destroy();
            //redireccionamos al login
            $alerta = [
                "Alerta" => "redireccionar",
                "URL" => server_url."login/"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se pudo cerrar sesion",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta); //se parsea para que pueda ser entendido por JS
    }
    
} 