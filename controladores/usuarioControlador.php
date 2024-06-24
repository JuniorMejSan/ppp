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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario where (idUsuario != '$id' and idUsuario != '1') and dni LIKE '%$busqueda%' order by nombre asc limit $inicio, $registros";
        }else{
            //se muestran todos los registros excepto los que tengan el privilegio de administrador y el que este logueado
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario where idUsuario != '$id' and idUsuario != '1' order by nombre asc limit $inicio, $registros";
        }

        //variable de conexion
        $conexion = mainModel::conectar();

        //almacena todos los datos seleccionados desde la bd
        $datos = $conexion->query($consulta);
        if(!$datos){
            // Manejo del error de consulta
            $errorInfo = $conexion->errorInfo();
            die("Error en la consulta SQL: " . $errorInfo[2]);
        }
        //array de datos
        $datos = $datos -> fetchAll();

        //conteo del total de registros
        $query_conteo = "SELECT FOUND_ROWS()";
        $total = $conexion -> query($query_conteo);
        if(!$total){
            // Manejo del error de consulta
            $errorInfo = $conexion->errorInfo();
            die("Error en la consulta SQL para el conteo: " . $errorInfo[2]);
        }
        $total = (int)$total->fetchColumn(); //parse a entero y lo almacena en la variable
        
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
            $reg_inicio = $inicio + 1; //variable para mostrar cuantos registros se estan mostrando en la tabla
            foreach ($datos as $rows) {
                $tabla .= '<tr class="text-center">
                                <td>'.$contador.'</td>
                                <td>'.$rows['dni'].'</td>
                                <td>'.$rows['nombre'].' '.$rows['apellido'].'</td>
                                <td>'.$rows['telefono'].'</td>
                                <td>'.$rows['user'].'</td>
                                <td>'.$rows['email'].'</td>
                                <td>
                                    <a href="'.server_url.'user-update/'.mainModel::encryption($rows['idUsuario']).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                                <td>
                                    <form class = "FormularioAjax" action="'.server_url.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
                                    <input type = "hidden" name = "usuario_id_del" value = "'.mainModel::encryption($rows['idUsuario']).'">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>';
                $contador++;
            }

            //fin de la cantidad de registros que se mustran en la pagina de la tabla
            $reg_final = $contador - 1;

        }else{//no hay registros en la bd
            if($total >= 1){//si hy mas de un registro 
                $tabla .= '<tr class="text-center" ><td colspan = "9">
                <a href = "'.$url.'" class = "btn btn-raised btn-primary btn-sm">Clic aqui para recargar el listado</a>
                </tr>';

            }else{
                $tabla .= '<tr class="text-center" ><td colspan = "9">Ningun registro coincide con el termino de busqueda</td></tr>';
            }
        }

        //cierre de las etiquetas
        $tabla .= '</tbody></table></div>';

        //colocamos los botones para la paginacion de la tabla que muestra los usuarios
        if($total >= 1 && $pagina <= $Npaginas){ //para verificar si hay registros y estamos en una pagina correcta
            $tabla .= '<p class = "text-right">Mostrando registros del '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' registros</p>';
            $tabla .= mainModel::paginador_tablas( $pagina, $Npaginas, $url, 7);
        }

        return $tabla;

    }

    //controlador para eliminar usuarios
    public function eliminar_usuario_controlador(){

        //recive el id del usuario y lo desencripta
        $id = mainModel::decryption($_POST['usuario_id_del']);
        //para evitar inyecciones sql limpiamos la cadena
        $id = mainModel::limpiar_cadena($id);

        //preugntamos si es el id 1, porque ese no se puede eliminar, es el admin
        if($id == 1){
            $alerta =[
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"No se puede eliminar el usuario administrador",
                "Tipo"=>"error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //comprobar que el id del usuario existe en la bd 
        $query_check_usaurio = "SELECT idUsuario FROM usuario WHERE idUsuario = '$id'";
        $check_usuario = mainModel::ejecutar_consulta_simple($query_check_usaurio);

        if($check_usuario -> rowCount() <= 0){ //no existe ningun registro que coincida

            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"El usuario que intenta eliminar no existe en el sistema",
                "Tipo"=>"error"
            ];
            echo json_encode( $alerta);
            exit();
        }

        //comprobamos que el usuario a eliminar no tenga ventas realizadas, si tiene ventas no se puede eliminar
        $query_check_venta = "SELECT idUsuario FROM venta WHERE idUsuario = '$id' LIMIT 1";
        $check_venta = mainModel::ejecutar_consulta_simple($query_check_venta);

        if($check_venta -> rowCount() > 0){

            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"No es posible eliminar este usuario porque tiene ventas asociadas, se recomienda deshabilitarlo si ya no será usado",
                "Tipo"=>"error"
            ];
            echo json_encode( $alerta);
            exit();
        }

        //comprobar el privilegio del usuario que esta eliminando(accion)
        session_start(['name' => 'ppp']);//iniciamos sesion

        if($_SESSION['privilegio_ppp'] != 1){ //si el usuario no es admin, no podrá eliminar otros usuarios
            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"No es posible eliminar este usuario porque no tiene los permisos necesarios",
                "Tipo"=>"error"
            ];
            echo json_encode( $alerta);
            exit();
        }

        //elimar usuario
        $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);

        //condicional para comprobar si se ha elimiando el usuario del sistema
        if($eliminar_usuario -> rowCount() == 1){
            $alerta = [
                "Alerta"=>"recargar",
                "Titulo"=>"Usuario eliminado",
                "Texto"=>"El usuario se ha eliminato exitosamente",
                "Tipo"=>"success"
            ];
        }else{
            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"No se ha podido eliminar el usuario, por favor intente nuevamente",
                "Tipo"=>"error"
            ];
        }
        echo json_encode($alerta);
    }

    //controlador para seleccionar datos del usuario
    public function datos_usuario_controlador($tipo, $id){

        //limpiamos para prevenir la inyeccion sql
        $tipo = mainModel::limpiar_cadena($tipo);
        $id = mainModel::decryption($id);//desencriptamos
        $id = mainModel::limpiar_cadena($id);

        return usuarioModelo::datos_usuario_modelo($tipo, $id);//le enviamos los parametros al modelo

    }

    //controlador para acualizar datos de usuario
    public function actualizar_usuario_controlador(){

        //recivimos el id que viene encriptado
        $id = mainModel::decryption($_POST['usuario_id_up']);
        $id = mainModel::limpiar_cadena($id);

        //verificando que el id existe en la bd
        $query_check_user = "SELECT * FROM usuario WHERE idUsuario = '$id'";
        $check_user = mainModel::ejecutar_consulta_simple($query_check_user);

        //verificamos si trae registros
        if($check_user -> rowCount() <= 0){//no existe
            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"El usuario que intenta actualizar no existe en el sistema",
                "Tipo"=>"error"
            ];
            echo json_encode($alerta);
            exit();
        }else{//existe

            //creamos array de datos y lo llenamos
            $campos = $check_user->fetch();
        }

        //alamcenamos en variables todos los campos que manda el form
        $dni = mainModel::limpiar_cadena($_POST['usuario_dni_up']);//usamos limpiar_cadena del main model para evitar injecciones sql y le pasamos el name del input del form
        $nombre = mainModel::limpiar_cadena($_POST['usuario_nombre_up']);
        $apellido = mainModel::limpiar_cadena($_POST['usuario_apellido_up']);
        $telefono = mainModel::limpiar_cadena($_POST['usuario_telefono_up']);
        $direccion = mainModel::limpiar_cadena($_POST['usuario_direccion_up']);
        
        $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
        $email = mainModel::limpiar_cadena($_POST['usuario_email_up']);

        //verificamos si el estado vienen definido
        if(isset($_POST['usuario_estado_up'])){
            $estado = mainModel::limpiar_cadena( $_POST['usuario_estado_up']);
        }else{
            $estado = $campos['estado'];
        }

        //verificamos si el privilegio vienen definido
        if(isset($_POST['usuario_privilegio_up'])){
            $privilegio = mainModel::limpiar_cadena( $_POST['usuario_privilegio_up']);
        }else{
            $privilegio = $campos['privilegio'];
        }

        //verificacion de credenciales en el form para permitir actualizar datos
        $admin_usuario = mainModel::limpiar_cadena($_POST['usuario_admin']);
        $admin_clave = mainModel::limpiar_cadena($_POST['clave_admin']);

        //verificamos el tipo de cuenta
        $tipo_cuenta = mainModel::limpiar_cadena($_POST['tipo_cuenta']);

        //compraobamos que los datos no vengan vacios
        if ($dni == "" || $nombre == "" || $apellido == "" || $telefono == "" || $direccion == "" || $usuario == "" || $email == "" || $admin_usuario == "" || $admin_clave == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han completado los campos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //verificamos que cada dato enviado tenga el formato requerido
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

        if (mainModel::verificar_datos("[a-zA-Z0-9 ]{1,35}", $usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE DE USUARIO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificamos si las credenciales ingresadas para poder actualizar tiene el formato solicitado
        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $admin_usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Su NOMNBRE DE USUARIO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Su CONTRASEÑA no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        
        //encriptamos la clave del usuario
        $admin_clave = mainModel::encryption($admin_clave);

        //
        if($privilegio < 1 || $privilegio > 3){ //si el privilegio no es valido o esta fuera de rango
            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"El privilegio seleccionado no es valido",
                "Tipo"=>"error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificamos que el estado tenga los valores adecuados
        if($estado != "Activa" && $estado != "Deshabilitada"){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo"=>"Ocurrio un error",
                "Texto"=>"El estado seleccionado no es valido",
                "Tipo"=>"error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //si el valor del DNI que viene por el formulario es distinto al que está en la base de datos, es decir que lo esta cambiando
        if($dni !=  $campos['dni']){
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
        }
        

        //si el valor del USUARIO que viene por el formulario es distinto al que está en la base de datos, es decir que lo esta cambiando
        if($usuario !=  $campos['user']){
            $query_check_usuario = "select user from usuario where user = '$usuario'";
            $check_usuario = mainModel::ejecutar_consulta_simple($query_check_usuario);
            if ($check_usuario -> rowCount() > 0) { //verificamos si la consulta trajo datos, SI NO TRAJO ES PORQUE NO EXISTE
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "El NOMBRE DE USUARIO ya se encuentra registrado",
                    "Tipo" => "error"
                ];

                echo json_encode($alerta);
                exit();
            }
        }

        //si el valor del EMAIL que viene por el formulario es distinto al que está en la base de datos, es decir que lo esta cambiando
        if($email != $campos['email'] && $email != ""){
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){//verificamos que tenga formato de correo
                $query_check_email = "select email from usuario where email = '$email'";
                $check_email = mainModel::ejecutar_consulta_simple($query_check_email);
                if ($check_email -> rowCount() > 0) { //verificamos si la consulta trajo datos
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrio un error",
                        "Texto"=> "El CORREO DE USUARIO ya se encuentra registrado",
                        "Tipo" => "error"
                    ];

                    echo json_encode($alerta);
                    exit();
                }
            }else{
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "Ha ingresado un correo no valido",
                    "Tipo" => "error"
                ];

                echo json_encode($alerta);
                exit();
            }
        }

        //comprobando claves
        if($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "" ){//preguntamos si no vienen definido, es decir que vengan vacios
            if($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']){//verificamos si coinciden
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "Las contraseñas ingresados no coinciden",
                    "Tipo" => "error"
                ];

                echo json_encode($alerta);
                exit();
            }else{
                if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_1']) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $_POST['usuario_clave_nueva_2'])){//verificamos si tiene el formato solicitado
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrio un error",
                        "Texto"=> "Las contraseñas no coinciden con el formato solicitado",
                        "Tipo" => "error"
                    ];
                }
                $clave = mainModel::encryption($_POST['usuario_clave_nueva_1']);
            }
        }else{
            $clave = $campos['password'];
        }

        //verificamos que las credenciales para actualizar sean correctas
        if($tipo_cuenta == "Propia"){
            $query_check_cuenta = "select idUsuario from usuario where user = '$admin_usuario' AND password = '$admin_clave' AND idUsuario = '$id'";
            $check_cuenta = mainModel::ejecutar_consulta_simple($query_check_cuenta);
        }else{
            session_start(['name' => 'ppp']);//verificamos que tenga los permisos necesarios
            if($_SESSION['privilegio_ppp'] != 1){//no tiene permisos necesarios
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "No tiene los permisos necesarios para actualizar el usuario",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $query_check_cuenta = "select idUsuario from usuario where user = '$admin_usuario' AND password = '$admin_clave'";
            $check_cuenta = mainModel::ejecutar_consulta_simple($query_check_cuenta);
        }

        //contamos cuantos registros fueron seleccionados
        if($check_cuenta -> rowCount() <= 0){//quiere decir que no se ha seleccionado ningun registro en la base de datos
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Nombre o clave de administrados no validos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();

        }

        //preparando datos para enviarlos al  mdoelo
        $datos_usuario_up = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Email"=> $email,
            "User" => $usuario,
            "Password" => $clave,
            "Estado" => $estado,
            "Privilegio" => $privilegio,
            "ID" => $id
        ];

        //para actualizar los datos
        if(usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)){
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Usuario actualizado",
                "Texto"=> "Los datos del usuario han sido actualizado",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se pudo actualizar el usuario",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }
}