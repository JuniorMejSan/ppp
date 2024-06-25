<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/clienteModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/clienteModelo.php";
}

class clienteControlador extends clienteModelo{

    //controlador para registrar cliente
    public static function agregar_cliente_controlador(){

        //recibimos los datos que se envian desde el form
        $dni = mainModel::limpiar_cadena($_POST['cliente_dni_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
        $apellido = mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);

        //comprobar que los campos obligatorios no esten vacios
        if ($dni == "" || $nombre == "" || $apellido == "" || $telefono == "" || $direccion == "") {
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
        if(mainModel::verificar_datos("[0-9-]{1,27}", $dni)){
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

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El APELLIDO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9()+]{8,20}", $telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El TELÉFONO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DIRECCIÓN no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar DNI para que no se repita en la bd
        $query_check_dni = "SELECT cliente_dni FROM cliente WHERE cliente_dni ='$dni'";
        $check_dni = mainModel::ejecutar_consulta_simple($query_check_dni);

        //comprobar que el dni no este registrando mediante el conteo de rows que trae
        if($check_dni -> rowCount() > 0) {//ya existe dni en la bd
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DNI ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //rray de datos a enviar al modelo para el registro
        $datos_cliente_reg = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion
        ];

        //variable para agregar
        $agregar_cliente = clienteModelo::agregar_cliente_modelo($datos_cliente_reg);

        //condicional para verificar el correcto registro
        if( $agregar_cliente -> rowCount() == 1){
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Registro exitoso",
                "Texto"=> "Cliente registrado satisfactoriamente",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Ocurrio un problema al registrar el cliente, porfavor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //controlador para listar los clientes
    public function paginador_cliente_controlador($pagina, $registros, $privilegio, $url, $busqueda){//recibe la pagina actual, cuantos registros quiero que se muestren por pagina ,el privilegio para ocultar algunas opciones como actualizar o eliminar, la url para los enlaces de cada boton de la paginacion, busqueda para el listado normal o el de la funcion busqueda
        $pagina = mainModel::limpiar_cadena($pagina); //para evitar inyeccion sql
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = server_url.$url."/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";//tabla creada con los cliente

        //validaciones segun la pagina de la tabla, para que no se pueda modificar la url de cada pagina de la tabla
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;//es decir si la pagina viene definida se parsea a entero y si no se le asigna el valor o no es un numero se redirecciona a la pag 1

        //variable para ver desde que registro empezamos acontar
        $inicio = ($pagina > 0) ? (($pagina * $registros)-$registros) : 0;

        //condicion para la consulta a la base de datos, si es listado normal o de busqueda
        if(isset($busqueda) && $busqueda != ""){ //significa que estamos mandando datos desde el formluario de busqueda en la vista de cleinte

            //consulta para que el resultado coindica con la busqueda realizada
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente 
            WHERE cliente_estado = 'Habilitado' 
            AND cliente_dni LIKE '%$busqueda%' 
            ORDER BY cliente_nombre ASC 
            LIMIT $inicio, $registros";
        }else{
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM cliente 
            WHERE cliente_estado = 'Habilitado' 
            order by cliente_nombre asc 
            limit $inicio, $registros";
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
                        <th>DIRECCION</th>';
                        if($privilegio == 1 || $privilegio == 2){
                            $tabla.= '<th>ACTUALIZAR</th>';
                        }
                        if($privilegio == 1){
                            $tabla.= '<th>ELIMINAR</th>';
                        }
                        
                        $tabla.= '</tr>
                </thead>
                <tbody>';
        
        if($total >= 1 && $pagina <= $Npaginas){//hay registros en la bd
            
            $contador = $inicio + 1;
            $reg_inicio = $inicio + 1; //variable para mostrar cuantos registros se estan mostrando en la tabla
            foreach ($datos as $rows) {
                $tabla .= '<tr class="text-center">
                                <td>'.$contador.'</td>
                                <td>'.$rows['cliente_dni'].'</td>
                                <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'</td>
                                <td>'.$rows['cliente_telefono'].'</td>
                                <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
                                title="'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'" data-content="'.$rows['cliente_direccion'].'">
                                <i class="fas fa-info-circle"></i>
                            </button></td>';
                            if($privilegio == 1 || $privilegio == 2){
                                $tabla .= '<td>
                                    <a href="'.server_url.'client-update/'.mainModel::encryption($rows['cliente_id']).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>';
                            }
                            if($privilegio == 1){
                                $tabla .= '<td>
                                    <form class = "FormularioAjax" action="'.server_url.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
                                    <input type = "hidden" name = "cliente_id_del" value = "'.mainModel::encryption($rows['cliente_id']).'">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>';
                            }
                                
                            $tabla .= '</tr>';
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
            $tabla .= '<p class = "text-right">Mostrando cliente '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' registros</p>';
            $tabla .= mainModel::paginador_tablas( $pagina, $Npaginas, $url, 7);
        }

        return $tabla;

    }

    //controaldor para eliminar cliente
    public function eliminar_cliente_controlador(){
        //recivimos el id
        $id = mainModel::decryption($_POST['cliente_id_del']);//lo que recive desde el input del form
        $id = mainModel::limpiar_cadena($id);//evitamos inyeccion sql

        //comprobamos que este registrado en la bd
        $query_check_cliente = "SELECT cliente_id FROM cliente WHERE cliente_id = '$id'";
        $check_cliente = mainModel::ejecutar_consulta_simple($query_check_cliente);
        if($check_cliente -> rowCount() <= 0){//si es menor igual a 0 el id que se quiere eliminar no existe en la bd(error)
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos encontrado el cliente a eliminar en el sistema",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar que el cliente no tenga ventas amarradas
        $query_check_venta = "SELECT cliente_id FROM venta WHERE cliente_id = '$id' LIMIT 1";
        $check_venta = mainModel::ejecutar_consulta_simple($query_check_venta);
        if($check_venta -> rowCount() >= 1){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No es posible eliminar el cliente seleccionado, tiene ventas asociadas",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificamos privilegios del usario que está eliminando
        session_start(['name' => 'ppp']);//iniciamos sesion
        if($_SESSION['privilegio_ppp'] != 1){//no tiene los permisos necesaris
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Usted no cuenta con los permisos necesarios para realizar esta acción",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //eliminar cliente
        $eliminar_cliente = clienteModelo::eliminar_cliente_modelo($id);
        if($eliminar_cliente -> rowCount() == 1){//si se eliminó
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente eliminado",
                "Texto"=> "El cliente ha sido eliminado exitosamente",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se puede eliminar el cliente, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //controlador para seleccionar los datos de los clientes
    public function datos_cliente_controlador($tipo, $id){
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return clienteModelo::datos_cliente_modelo($tipo, $id);
    }

    //controlador para actualizar cliente
    public function actualizar_cliente_controlador(){
        //recuperamos el id
        $id = mainModel::decryption($_POST['cliente_id_up']);
        $id = mainModel::limpiar_cadena($id);

        //comprobamos cliente en la bd
        $query_check_cliente = "SELECT * FROM cliente WHERE cliente_id = '$id'";
        $check_cliente = mainModel::ejecutar_consulta_simple($query_check_cliente);

        //verificamos que el cliente exista
        if($check_cliente -> rowCount() <= 0){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El cliente que intenta actualizar no se encuentra registrado en el sistema",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }else{
            $campos = $check_cliente -> fetch();
        }

        $dni = mainModel::limpiar_cadena($_POST['cliente_dni_up']);
        $nombre = mainModel::limpiar_cadena($_POST['cliente_nombre_up']);
        $apellido = mainModel::limpiar_cadena($_POST['cliente_apellido_up']);
        $telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_up']);
        $direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_up']);

        //verificamos que los campos no vengan vacios
        if ($dni == "" || $nombre == "" || $apellido == "" || $telefono == "" || $direccion == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han completado los campos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        
        if(mainModel::verificar_datos("[0-9-]{1,27}", $dni)){
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

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El NOMBRE no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $apellido)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El APELLIDO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9()+]{8,20}", $telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El TELÉFONO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El DIRECCIÓN no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar DNI para que no se repita en la bd
        if($dni != $campos['cliente_dni']){
            $query_check_dni = "SELECT cliente_dni FROM cliente WHERE cliente_dni ='$dni'";
            $check_dni = mainModel::ejecutar_consulta_simple($query_check_dni);

            //comprobar que el dni no este registrando mediante el conteo de rows que trae
            if($check_dni -> rowCount() > 0) {//ya existe dni en la bd
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

        //verificamos privilegios
        session_start(['name' => 'ppp']);

        if($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No cuenta con los privilegios necesarios para realizar esta acción",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //array para actualizar datos
        $datos_cliente_up = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "ID" => $id
        ];

        if(clienteModelo::actualizar_cliente_modelo($datos_cliente_up)){
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente actualizado",
                "Texto"=> "Los datos del cliente han sido actualizados exitosamente",
                "Tipo" => "success"
            ];

        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Cliente actualizado",
                "Texto"=> "No se han podido actualizar los datos del cliente, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        
    }
}