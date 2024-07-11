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
        $precio = mainModel::limpiar_cadena($_POST['item_precio_reg']);
        $precio_compra = mainModel::limpiar_cadena($_POST['item_precio_compra_reg']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_reg']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_reg']);

        //comprobar que los campos obligatorios no esten vacios
        if ($codigo == "" || $nombre == "" || $stock == "" ||  $precio == "" || $precio_compra == "" || $estado == "") {
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

        if (mainModel::verificar_datos("[0-9]{1,9}", $precio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La PRECIO de venta no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,9}", $precio_compra)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La PRECIO de compra no coincide con el formato solicitado",
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
                "Texto"=> "El codigo del ITEM ya se encuentra registrado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        $query_check_nombre = "SELECT item_nombre FROM item WHERE item_nombre ='$nombre'";
        $check_nombre = mainModel::ejecutar_consulta_simple($query_check_nombre);

        //comprobar que el CODIGO no este registrando mediante el conteo de rows que trae
        if($check_nombre -> rowCount() > 0) {//ya existe dni en la bd
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El nombre del ITEM ya se encuentra registrado",
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
            "Precio" => $precio,
            "Precio_compra" => $precio_compra,
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

    //controlador para listar items y para realizar busqueda
    public function paginador_item_controlador($pagina, $registros, $privilegio, $url, $busqueda, $tipo_item){//recibe la pagina actual, cuantos registros quiero que se muestren por pagina ,el privilegio para ocultar algunas opciones como actualizar o eliminar, la url para los enlaces de cada boton de la paginacion, busqueda para el listado normal o el de la funcion busqueda
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item 
            WHERE (item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%') 
            ORDER BY item_nombre ASC 
            LIMIT $inicio, $registros";
        }elseif($tipo_item == 'Habilitado'){
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item 
            WHERE item_estado = 'Habilitado' 
            order by item_nombre asc 
            limit $inicio, $registros";
        }else{
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM item 
            WHERE item_estado = 'Inhabilitado' 
            order by item_nombre asc 
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
                        <th>CÓDIGO</th>
                        <th>NOMBRE</th>
                        <th>STOCK</th>
                        <th>PRECIO DE VENTA</th>
                        <th>PRECIO DE COMPRA</th>
                        <th>ESTADO</th>
                        <th>DETALLE</th>';
                        if($privilegio == 1 || $privilegio == 2){
                            $tabla.= '<th>ACTUALIZAR</th>';
                        }
                        if($privilegio == 1 && $tipo_item == 'Habilitado'){
                            $tabla.= '<th>ELIMINAR</th>';
                        }elseif($privilegio == 1 && $tipo_item == 'Inhabilitado'){
                            $tabla.= '<th>HABILITAR</th>';
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
                                <td>'.$rows['item_codigo'].'</td>
                                <td>'.$rows['item_nombre'].'</td>
                                <td>'.$rows['item_stock'].'</td>
                                <td>'.moneda.' '.$rows['item_precio'].'</td>
                                <td>'.moneda.' '.$rows['item_precio_compra'].'</td>
                                <td>'.$rows['item_estado'].'</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="'.$rows['item_nombre'].'" data-content="'.($rows['item_detalle'] ? $rows['item_detalle'] : '-').'">
                                    <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>';
                            if($privilegio == 1 || $privilegio == 2){
                                $tabla .= '<td>
                                    <a href="'.server_url.'item-update/'.mainModel::encryption($rows['item_id']).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>';
                            }
                            if($privilegio == 1  && $tipo_item == 'Habilitado'){
                                $tabla .= '<td>
                                    <form class = "FormularioAjax" action="'.server_url.'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
                                    <input type = "hidden" name = "item_id_del" value = "'.mainModel::encryption($rows['item_id']).'">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>';
                            }elseif($privilegio == 1 && $tipo_item == 'Inhabilitado'){
                                $tabla .= '<td>
                                    <form class = "FormularioAjax" action="'.server_url.'ajax/itemAjax.php" method="POST" data-form="enable" autocomplete="off">
                                    <input type = "hidden" name = "item_id_enable" value = "'.mainModel::encryption($rows['item_id']).'">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i>
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
                $tabla .= '<tr class="text-center" ><td colspan = "10">
                <a href = "'.$url.'" class = "btn btn-raised btn-primary btn-sm">Clic aqui para recargar el listado</a>
                </tr>';

            }else{
                $tabla .= '<tr class="text-center" ><td colspan = "10">Ningun registro coincide con el termino de busqueda</td></tr>';
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

    //controlador para eliminar item
    public function eliminar_item_controlador(){
        //recivimos el id
        $id = mainModel::decryption($_POST['item_id_del']);//lo recive desde la tabla, en esta caso que esta en el controlador paginador
        $id = mainModel::limpiar_cadena($id);//evitamos inyeccion sql

        //comprobamos que este registrado en la bd
        $query_check_item = "SELECT item_id FROM item WHERE item_id = '$id'";
        $check_item = mainModel::ejecutar_consulta_simple($query_check_item);
        if($check_item -> rowCount() <= 0){//si es menor igual a 0 el id que se quiere eliminar no existe en la bd(error)
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos encontrado el ITEM a eliminar en el sistema",
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

        //eliminar item
        $eliminar_item = itemModelo::eliminar_item_modelo($id);
        if($eliminar_item -> rowCount() == 1){//si se eliminó
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item eliminado",
                "Texto"=> "El ITEM ha sido eliminado exitosamente, por favor revisar el modulo 'Items Inhabilitados'",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se puede eliminar el ITEM, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //controlador para habilitar item
    public function habilitar_item_controlador(){
        //recivimos el id
        $id = mainModel::decryption($_POST['item_id_enable']);//lo recive desde la tabla, en esta caso que esta en el controlador paginador
        $id = mainModel::limpiar_cadena($id);//evitamos inyeccion sql

        //comprobamos que este registrado en la bd
        $query_check_item = "SELECT item_id FROM item WHERE item_id = '$id'";
        $check_item = mainModel::ejecutar_consulta_simple($query_check_item);
        if($check_item -> rowCount() <= 0){//si es menor igual a 0 el id que se quiere eliminar no existe en la bd(error)
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos encontrado el ITEM a habilitar en el sistema",
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

        //habilitar item
        $habilitar_item = itemModelo::habilitar_item_modelo($id);
        if($habilitar_item -> rowCount() == 1){//si se eliminó
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item Habilitado",
                "Texto"=> "El ITEM ha sido habilitado exitosamente, por favor revisar el modulo 'Items Habilitados'",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se puede habilitar el ITEM, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //controlador para seleccionar los datos de los items
    public function datos_item_controlador($tipo, $id){
        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return itemModelo::datos_item_modelo($tipo, $id);
    }

    //controlador para actualizar item
    public function actualizar_item_controlador(){
        //recuperamos el id
        $id = mainModel::decryption($_POST['item_id_up']);
        $id = mainModel::limpiar_cadena($id);

        //comprobamos item en la bd
        $query_check_item = "SELECT * FROM item WHERE item_id = '$id'";
        $check_item = mainModel::ejecutar_consulta_simple($query_check_item);

        //verificamos que el item exista
        if($check_item -> rowCount() <= 0){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El item que intenta actualizar no se encuentra registrado en el sistema",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }else{
            $campos = $check_item -> fetch();
        }

        $codigo = mainModel::limpiar_cadena($_POST['item_codigo_up']);
        $nombre = mainModel::limpiar_cadena($_POST['item_nombre_up']);
        $stock = mainModel::limpiar_cadena($_POST['item_stock_up']);
        $precio = mainModel::limpiar_cadena($_POST['item_precio_up']);
        $precio_compra = mainModel::limpiar_cadena($_POST['item_precio_compra_up']);
        $estado = mainModel::limpiar_cadena($_POST['item_estado_up']);
        $detalle = mainModel::limpiar_cadena($_POST['item_detalle_up']);

        //verificamos que los campos no vengan vacios
        if ($codigo == "" || $nombre == "" || $stock == "" || $precio == "" || $precio_compra == "" || $estado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han completado los campos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        
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

        if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $nombre)) {
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
                "Texto"=> "El STOCK no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[0-9]{1,9}(\.[0-9]{1,2})?", $precio)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El PRECIO de venta no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,9}(\.[0-9]{1,2})?", $precio_compra)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El PRECIO de compra no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //verificar CODIGO para que no se repita en la bd
        if($codigo != $campos['item_codigo']){
            $query_check_codigo = "SELECT item_codigo FROM item WHERE item_codigo ='$codigo'";
            $check_codigo = mainModel::ejecutar_consulta_simple($query_check_codigo);

            //comprobar que el codigo no este registrando mediante el conteo de rows que trae
            if($check_codigo -> rowCount() > 0) {//ya existe dni en la bd
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "El CODIGO ya se encuentra registrado",
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
        $datos_item_up = [
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Stock" => $stock,
            "Precio" => $precio,
            "Precio_compra" => $precio_compra,
            "Estado" => $estado,
            "Detalle" => $detalle,
            "ID" => $id
        ];

        if(itemModelo::actualizar_item_modelo($datos_item_up)){
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item actualizado",
                "Texto"=> "Los datos del ITEM han sido actualizados exitosamente",
                "Tipo" => "success"
            ];

        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han podido actualizar los datos del cliente, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        
    }
}