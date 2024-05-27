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
        }

        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $password)){//el filtro es el patern del input en la vista
            //si entra es porque si se tienen errores en ese dato
            echo '<script> 
                Swal.fire({
                    title: "Ocurrio un error",
                    text: "El CLAVE no coincide con el formato requerido",
                    type: "error",
                    confirmButtonText: "Aceptar"
                })
            </script>';
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
            $_SESSION['id_ppp'] = $row['id_Usuario']; //esta variable asignamos el valor que hay en la bd
            $_SESSION['nombre_ppp'] = $row['nombre'];
            $_SESSION['apellido_ppp'] = $row['apellido'];
            $_SESSION['usuario_ppp'] = $row['user'];
            $_SESSION['privilegio_ppp'] = $row['privilegio'];
            $_SESSION['token_ppp'] = md5(uniqid(mt_rand(), true));//para el cierre de sesion de forma segura, se crea un id unico
            return header("Location: ".server_url."home/");
        }else{//no existen las credenciales
            echo '<script> 
                Swal.fire({
                    title: "Ocurrio un error",
                    text: "USUARIO o CLAVE incorrectos",
                    type: "error",
                    confirmButtonText: "Aceptar"
                })
            </script>';
        }
    }
    
} 