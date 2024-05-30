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

        if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2)){
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrió un error inesperado",
                "Texto"=>"Las CLAVES no coinciden con el formato solicitado",
                "Tipo"=>"error"
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

        //comprobacion de contraseña iguales en ambos campos y si no son iguales se asigna a una variable y se procesa por el hash
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
            $clave = mainModel::encryption($clave1);

        }

        //comprobacion de privilegio
        if ($privilegio < 1 || $privilegio > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El privilegio seleccionado no es valido",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //array de datos a enviar, se esta jalando los indices del usuarioModel
        $datos_usuario_reg = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "User" => $usuario,
            "Email" => $email,
            "Password" => $clave, //esta es la variable procesada por el hash
            "Estado" => "Activa", //por defecto el usuario estara activo
            "Privilegio" => $privilegio
        ];

        //variable para alamcenar lo qeu nos devuelve usuarioModel
        $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

        //condicional para preguntar si se almacenoel registro en la bd
        if ($agregar_usuario -> rowCount() == 1) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Usuario registrado",
                "Texto"=> "Los datos del usuario han sido registrado con exito",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se pudo agregar el usuario",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        //ya no se coloca exit por que aqui termina el codigo
    }

    //controlador para listar usuarios en la vista
    public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda){//recibe la pagina actual, cuantos registros quiero que se muestren por pagina ,el privilegio para ocultar algunas opciones como actualizar o eliminar y el id del usario para que no aparezca en el listado (el edita sus datos desde el boton superior), la url para los enlaces de cada boton de la paginacion, busqueda para el listado normal o el de la funcion busqueda
        $pagina = mainModel::limpiar_cadena($pagina); //para evitar inyeccion sql
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);
        $id = mainModel::limpiar_cadena($id);

        $url = mainModel::limpiar_cadena($url);
        $url = server_url.$url."/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";//tabla creada con los usuarios

        //validaciones segun la pagina de la tabla, para que no se pueda modificar la url de cada pagina de la tabla
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;//es decir si la pagina viene definida se parsea a entero y si no se le asigna el valor o no es un numero se redirecciona a la pag 1

        //variable para ver desde que registro empezamos acontar
        $inicio = ($pagina > 0) ? (($pagina * $registros)-$registros) : 0;

        //condicion para la consulta a la base de datos, si es listado normal o de busqueda
        if(isset($busqueda) && $busqueda != ""){ //significa que estamos mandando datos desde el formluario de busqueda en la vista de usuarios

            //consulta para que el resultado coindica con la busqueda realizada
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario where ((idUsuario != '$id' and idUsuario != '1') and (dni LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%' or telefono LIKE '%$busqueda%' or user LIKE '%$busqueda%' or email LIKE '%$busqueda%)) order by nombre asc limit $inicio, $registros";
        }else{
            //se muestran todos los registros excepto los que tengan el privilegio de administrador y el que este logueado
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario where idUsuario != '$id' and idUsuario != '1' order by nombre asc limit $inicio, $registros";
        }

        //variable de conexion
        $conexion = mainModel::conectar();

        //almacena todos los datos seleccionados desde la bd
        $datos = $conexion -> query($consulta);
        //array de datos
        $datos = $datos -> fetchAll();

        //conteo del total de registros
        $query_conteo = "SELECT FOUND_ROWS()";
        $total = $conexion -> query($query_conteo);
        $total = (int)$total -> fetchColumn(); //parse a entero y lo almacena en la variable
        
        //numero de paginas totales
        $Npaginas = ceil($total / $registros);//ceil duncion de php para redondear el numero de paginas

        //variable para tabla
        $tabla.= '<div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>DNI</th>
                        <th>NOMBRE</th>
                        <th>APELLIDO</th>
                        <th>TELÉFONO</th>
                        <th>USUARIO</th>
                        <th>EMAIL</th>
                        <th>ACTUALIZAR</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>';
        
        if($total >= 1 && $pagina <= $Npaginas){//hay registros en la bd
            
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '<tr class="text-center">
                                <td>1</td>
                                <td>03045643</td>
                                <td>NOMBRE DE USUARIO</td>
                                <td>APELLIDO DE USUARIO</td>
                                <td>2345456</td>
                                <td>NOMBRE DE USUARIO</td>
                                <td>ADMIN@ADMIN.COM</td>
                                <td>
                                    <a href="<?php echo server_url; ?>user-update/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="">
                                        <button type="button" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>';
                            $contador++;
            }
        }else{//no hay registros en la bd
            if($total >= 1){//si hy mas de un registro 
                $tabla .= '<tr class="text-center" ><td colspan = "9">
                <a href = "'.$url.'" class = "btn btn-raised btn-primary btn-sm">Clic aqui para recargar el listado</a>
                </tr>';
            }else{
                $tabla .= '<tr class="text-center" ><td colspan = "9"></td>No hay registros en el sistema</tr>';
            }
        }

        //cierre de las etiquetas
        $tabla .= '</tbody></table></div>';

        return $tabla;

    }
}