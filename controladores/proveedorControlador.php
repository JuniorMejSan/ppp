<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/proveedorModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/proveedorModelo.php";
}

class proveedorControlador extends proveedorModelo{

    public static function agregar_proveedor_controlador(){

        //recibimos los datos que se envian desde el form
        $ruc = mainModel::limpiar_cadena($_POST['proveedor_ruc_reg']);
        $nombre = mainModel::limpiar_cadena($_POST['proveedor_nombre_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['proveedor_direccion_reg']);
        $pais = mainModel::limpiar_cadena($_POST['proveedor_pais_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['proveedor_telefono_reg']);
        $email = mainModel::limpiar_cadena($_POST['proveedor_email_reg']);

        //comprobar que los campos obligatorios no esten vacios
        if ($ruc == "" || $nombre == "" || $direccion == "" || $pais == "" || $telefono == "" || $email == "") {
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
        if(mainModel::verificar_datos("\d{11}", $ruc)){
            //si entra es porque si se tienen errores en ese dato
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El RUC no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $nombre)){
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

        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La DIRECCIÓN no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $pais)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El PAÍS no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9()+]{8,20}", $telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El TELEFONO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar DNI para que no se repita en la bd
        $query_check_ruc = "SELECT proveedor_ruc FROM proveedor WHERE proveedor_ruc ='$ruc'";
        $check_ruc = mainModel::ejecutar_consulta_simple($query_check_ruc);

        //comprobar que el dni no este registrando mediante el conteo de rows que trae
        if($check_ruc -> rowCount() > 0) {//ya existe dni en la bd
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El RUC ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //rray de datos a enviar al modelo para el registro
        $datos_proveedor_reg = [
            "RUC" => $ruc,
            "Nombre" => $nombre,
            "Direccion" => $direccion,
            "Pais" => $pais,
            "Telefono" => $telefono,
            "Email" => $email
        ];

        //variable para agregar
        $agregar_proveedor = proveedorModelo::agregar_proveedor_modelo($datos_proveedor_reg);

        //condicional para verificar el correcto registro
        if( $agregar_proveedor -> rowCount() == 1){
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Registro exitoso",
                "Texto"=> "Proveedor registrado satisfactoriamente",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Ocurrio un problema al registrar el proveedor, porfavor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    public function paginador_proveedor_controlador($pagina, $registros, $privilegio, $url, $busqueda){//recibe la pagina actual, cuantos registros quiero que se muestren por pagina ,el privilegio para ocultar algunas opciones como actualizar o eliminar, la url para los enlaces de cada boton de la paginacion, busqueda para el listado normal o el de la funcion busqueda
        $pagina = mainModel::limpiar_cadena($pagina); //para evitar inyeccion sql
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = server_url.$url."/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";//tabla creada con los Proveedor

        //validaciones segun la pagina de la tabla, para que no se pueda modificar la url de cada pagina de la tabla
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;//es decir si la pagina viene definida se parsea a entero y si no se le asigna el valor o no es un numero se redirecciona a la pag 1

        //variable para ver desde que registro empezamos acontar
        $inicio = ($pagina > 0) ? (($pagina * $registros)-$registros) : 0;

        //condicion para la consulta a la base de datos, si es listado normal o de busqueda
        if(isset($busqueda) && $busqueda != ""){ //significa que estamos mandando datos desde el formluario de busqueda en la vista de cleinte

            //consulta para que el resultado coindica con la busqueda realizada
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedor 
            WHERE proveedor_estado = 'Habilitado' 
            AND proveedor_ruc LIKE '%$busqueda%' 
            ORDER BY proveedor_nombre ASC 
            LIMIT $inicio, $registros";
        }else{
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedor 
            WHERE proveedor_estado = 'Habilitado' 
            order by proveedor_nombre asc 
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
                        <th>RUC</th>
                        <th>NOMBRE</th>
                        <th>DIRECCIÓN</th>
                        <th>PAÍS</th>
                        <th>TELEFONO</th>
                        <th>EMAIL</th>';
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
                                <td>'.$rows['proveedor_ruc'].'</td>
                                <td>'.$rows['proveedor_nombre'].'</td>
                                <td><button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
                                title="'.$rows['proveedor_nombre'].'" data-content="'.$rows['proveedor_direccion'].'">
                                <i class="fas fa-info-circle"></i>
                            </button></td>
                                <td>'.$rows['proveedor_pais'].'</td>
                                <td>'.$rows['proveedor_telefono'].'</td>
                                <td>'.$rows['proveedor_email'].'</td>';
                            if($privilegio == 1 || $privilegio == 2){
                                $tabla .= '<td>
                                    <a href="'.server_url.'proveedor-update/'.mainModel::encryption($rows['proveedor_id']).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>';
                            }
                            if($privilegio == 1){
                                $tabla .= '<td>
                                    <form class = "FormularioAjax" action="'.server_url.'ajax/proveedorAjax.php" method="POST" data-form="delete" autocomplete="off">
                                    <input type = "hidden" name = "proveedor_id_del" value = "'.mainModel::encryption($rows['proveedor_id']).'">
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
            $tabla .= '<p class = "text-right">Mostrando proveedor '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' registros</p>';
            $tabla .= mainModel::paginador_tablas( $pagina, $Npaginas, $url, 7);
        }

        return $tabla;

    }

    public function eliminar_proveedor_controlador(){
        //recivimos el id
        $id = mainModel::decryption($_POST['proveedor_id_del']);//lo que recive desde el input del form
        $id = mainModel::limpiar_cadena($id);//evitamos inyeccion sql

        //comprobamos que no este registrado en la bd
        $query_check_proveedor = "SELECT proveedor_id FROM proveedor WHERE proveedor_id = '$id'";
        $check_proveedor = mainModel::ejecutar_consulta_simple($query_check_proveedor);
        if($check_proveedor -> rowCount() <= 0){//si es menor igual a 0 el id que se quiere eliminar no existe en la bd(error)
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos encontrado el proveedor a eliminar en el sistema",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar que el proveedor no tenga ventas amarradas
        $query_check_venta = "SELECT proveedor_id FROM venta WHERE proveedor_id = '$id' LIMIT 1";
        $check_venta = mainModel::ejecutar_consulta_simple($query_check_venta);
        if($check_venta -> rowCount() >= 1){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No es posible eliminar el proveedor seleccionado, tiene ventas asociadas",
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

        //eliminar Proveedor
        $eliminar_proveedor = proveedorModelo::eliminar_proveedor_modelo($id);
        if($eliminar_proveedor -> rowCount() == 1){//si se eliminó
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Proveedor eliminado",
                "Texto"=> "El proveedor ha sido eliminado exitosamente",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se puede eliminar el proveedor, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    public function datos_proveedor_controlador($tipo, $id){
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return proveedorModelo::datos_proveedor_modelo($tipo, $id);
    }

    public function actualizar_proveedor_controlador(){
        //recuperamos el id
        $id = mainModel::decryption($_POST['proveedor_id_up']);
        $id = mainModel::limpiar_cadena($id);

        //comprobamos proveedor en la bd
        $query_check_proveedor = "SELECT * FROM proveedor WHERE proveedor_id = '$id'";
        $check_proveedor = mainModel::ejecutar_consulta_simple($query_check_proveedor);

        //verificamos que el cliente exista
        if($check_proveedor -> rowCount() <= 0){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El proveedor que intenta actualizar no se encuentra registrado en el sistema",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }else{
            $campos = $check_proveedor -> fetch();
        }

        $ruc = mainModel::limpiar_cadena($_POST['proveedor_ruc_up']);
        $nombre = mainModel::limpiar_cadena($_POST['proveedor_nombre_up']);
        $direccion = mainModel::limpiar_cadena($_POST['proveedor_direccion_up']);
        $pais = mainModel::limpiar_cadena($_POST['proveedor_pais_up']);
        $telefono = mainModel::limpiar_cadena($_POST['proveedor_telefono_up']);
        $email = mainModel::limpiar_cadena($_POST['proveedor_email_up']);

        //verificamos que los campos no vengan vacios
        if ($ruc == "" || $nombre == "" || $direccion == "" || $pais == "" || $telefono == "" || $email == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han completado los campos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        
        if(mainModel::verificar_datos("\d{11}", $ruc)){
            //si entra es porque si se tienen errores en ese dato
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El RUC no coincide con el formato solicitado",
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

        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La DIRECCIÓN no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}", $pais)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El PAIS no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9()+]{8,20}", $telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El TELEFONO no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar ruc para que no se repita en la bd
        if($ruc != $campos['proveedor_ruc']){
            $query_check_ruc = "SELECT proveedor_ruc FROM proveedor WHERE proveedor_ruc ='$ruc'";
            $check_ruc= mainModel::ejecutar_consulta_simple($query_check_ruc);

            //comprobar que el dni no este registrando mediante el conteo de rows que trae
            if($check_ruc -> rowCount() > 0) {//ya existe ruc en la bd
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "El RUC ya se encuentra registrado",
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
        $datos_proveedor_up = [
            "RUC" => $ruc,
            "Nombre" => $nombre,
            "Direccion" => $direccion,
            "Pais" => $pais,
            "Telefono" => $telefono,
            "Email" => $email,
            "ID" => $id
        ];

        if(proveedorModelo::actualizar_proveedor_modelo($datos_proveedor_up)){
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Proveedor actualizado",
                "Texto"=> "Los datos del proveedor han sido actualizados exitosamente",
                "Tipo" => "success"
            ];

        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Proveedor actualizado",
                "Texto"=> "No se han podido actualizar los datos del proveedor, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        
    }
}