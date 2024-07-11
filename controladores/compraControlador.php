<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en compraAjax.php
    require_once "../modelos/compraModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/compraModelo.php";
}

class compraControlador extends compraModelo{

    //controlador para buscar proveedor para la compra
    public function buscar_proveedor_compra_controlador(){
        
        //recivimos el termino de busqueda
        $proveedor = mainModel::limpiar_cadena($_POST["buscar_proveedor"]);

        //verificamos que no venga vacia
        if($proveedor == ""){
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debe introducir el RUC
                    </p>
                </div>';
                exit(); //hacemos que se detenga la ejecucion del codigo
        }

        //comprobar texto en la bd
        $query_datos_proveedor = "SELECT * FROM proveedor WHERE proveedor_ruc LIKE '%$proveedor%' ORDER BY proveedor_nombre ASC";
        $datos_proveedor = mainModel::ejecutar_consulta_simple($query_datos_proveedor);

        //verificamos si hay datos
        if($datos_proveedor -> rowCount() >= 1){

            $datos_proveedor = $datos_proveedor -> fetchAll(); //reasignamos todos los datos que trae la consulta al arreglo

            //tabla que mostrara los datos del cliente buscado
            $tabla = '<div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>';

            //recorremos los registros que trajo la consulta para poder mostrarlos
            foreach($datos_proveedor as $rows){
                 $tabla.= '<tr class="text-center">
                                    <td>'.$rows['proveedor_nombre'].' - '.$rows['proveedor_ruc'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick = "agregar_proveedor('.$rows['proveedor_id'].')"><i
                                                class="fas fa-user-plus"></i></button>
                                    </td>
                                </tr>';
            }

            $tabla.= '      </tbody>
                        </table>
                    </div>';

            return $tabla;
        }else{
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún proveedor en el sistema que coincida con <strong>“'.$proveedor.'”</strong>
                    </p>
                    <p class="text-center mb-0">
                        <strong>¿Desea agregar un nuevo proveedor?</strong>
                    </p>
                    <p class="text-center mb-0">
                        <button type="button" class="btn btn-primary" onclick = "proveedorNuevo()"><i
                                                class="fas fa-user-plus"></i>  Agregar nuevo proveedor</button>
                    </p>
                </div>';
        }
    }

    //controlador para agregar proveedor a la compra
    public function agregar_proveedor_compra_controlador(){

        //recuperamos el id del proveedor seleccionado 
        $id = mainModel::limpiar_cadena(($_POST['id_agregar_proveedor']));

        //comprobamos proveedor en la base de datos
        $query_check_proveedor = "SELECT * FROM proveedor WHERE proveedor_id = '$id'";
        $check_proveedor = mainModel::ejecutar_consulta_simple($query_check_proveedor);

        if ($check_proveedor -> rowCount() <= 0){//no existe el proveedor en la base de datos
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos podido encontrar el proveedor en el sistema",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }else{
            $campos = $check_proveedor -> fetch();
        }

        //iniciamos la sesion
        session_start(['name' => 'ppp']);

        if(empty($_SESSION['datos_proveedor'])){//variable de sesion que tendran todos los datos que se agregue
            //si viene vacio lo llenamos
            $_SESSION['datos_proveedor'] = [
                "ID" => $campos['proveedor_id'],
                "RUC" => $campos['proveedor_ruc'],
                "Nombre" => $campos['proveedor_nombre']
            ];

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Proveedor agregado",
                "Texto"=> "Proveedor agregado a la compra satisfactoriamente",
                "Tipo" => "success"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se ha podido agregar el proveedor a la compra o esta compra ya tiene un proveedor, intentelo nuevamente",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
        }
    }

    //funcion para eliminar proveedor de la compra
    public function eliminar_proveedor_compra_controlador(){

        //iniciamos la sesion
        session_start(['name' => 'ppp']);

        //quitamos la sesion para que se elimine el proveedor agregado
        unset($_SESSION['datos_proveedor']);

        //comprobramos que la variable de sesion ya no existe o no tiene datos
        if (empty($_SESSION['datos_proveedor'])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Proveedor removido",
                "Texto"=> "Los datos del proveedor han sido removimos exitosamente",
                "Tipo" => "success"
            ];
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han podido remover los datos del proveedor",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //funcion para buscar items para la compra
    public function buscar_item_compra_controlador(){
    
        //recivimos el termino de busqueda
        $item = mainModel::limpiar_cadena($_POST["buscar_item"]);

        //verificamos que no venga vacia
        if($item == ""){
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debe introducir el codigo o nombre del Item
                    </p>
                </div>';
                exit(); //hacemos que se detenga la ejecucion del codigo
        }

        //comprobar texto en la bd
        $query_datos_item = "SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%') AND (item_estado = 'Habilitado') ORDER BY item_codigo ASC";
        $datos_item = mainModel::ejecutar_consulta_simple($query_datos_item);

        //verificamos si hay datos
        if($datos_item -> rowCount() >= 1){

            $datos_item = $datos_item -> fetchAll(); //reasignamos todos los datos que trae la consulta al arreglo

            //tabla que mostrara los datos del item buscado
            $tabla = '<div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>';

            //recorremos los registros que trajo la consulta para poder mostrarlos
            foreach($datos_item as $rows){
                $tabla.= '<tr class="text-center">
                                    <td>'.$rows['item_codigo'].' - '.$rows['item_nombre'].' - '.moneda.''.$rows['item_precio_compra'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick = "modal_agregar_item('.$rows['item_id'].')"><i
                                                class="fas fa-box-open"></i></button>
                                    </td>
                                </tr>';
            }

            $tabla.= '      </tbody>
                        </table>
                    </div>';

            return $tabla;
        }else{
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún item en el sistema que coincida con <strong>“'.$item.'”</strong>
                    </p>
                    <p class="text-center mb-0">
                        <strong>¿Desea agregar un nuevo item?</strong>
                    </p>
                    <p class="text-center mb-0">
                        <button type="button" class="btn btn-primary" onclick = "itemNuevo()"><i
                                                class="fas fa-user-plus"></i>  Agregar nuevo item</button>
                    </p>
                </div>';
        }
    }

    //funcion para agregar items a la compra
    public function agregar_item_compra_controlador(){

        //recuperamos el id del item
        $id = mainModel::limpiar_cadena($_POST['id_agregar_item']);

        //comprobando que el item exista y este habilitado en la bd
        $query_check_item = "SELECT * FROM item WHERE item_id = '$id' AND item_estado = 'Habilitado'";
        $check_item = mainModel::ejecutar_consulta_simple($query_check_item);

        //verificamos que si se traigan datos
        if ($check_item ->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos podido encontrar el item en el sistema, intentelo nuevamente",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_item -> fetch();
        }
        
        //recuperamos lo detalles de la compra ingresados
        $cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad']);

        //comprovamos que no supere el stock
        if($campos['item_stock'] < $cantidad){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La cantidad ingresada supera el stock del producto, el stock actual es " . $campos['item_stock'],
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }

        //comprobamos que los campos no vengan vacios
        if($cantidad == ""){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Debe colocar la cantidad a vender",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }

        //verificamos el formato de los datos 
        if(mainModel::verificar_datos("[0-9]{1,7}",$cantidad)){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La cantidad no tiene el formato requerido",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }

        //iniciamos la sesion 
        session_start(['name' => 'ppp']);
        
        //condicional para verificar si la sesion esta vacia
        if (empty($_SESSION['datos_item'][$id])) {//creamos una sesion con el nombre datos_item y verificamos si esta vacia
            //llenamos un array con los datos
            $_SESSION['datos_item'][$id] = [
                "ID" => $campos['item_id'],
                "Codigo" => $campos['item_codigo'],
                "Stock" => $campos['item_stock'],
                "Nombre"=> $campos['item_nombre'],
                "Precio"=> $campos['item_precio'],
                "Precio_compra"=> $campos['item_precio_compra'],
                'Detalle'=> $campos['item_detalle'],
                'Cantidad'=> $cantidad,
            ];

            //mensaje de item agregado
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item agregado",
                "Texto"=> "El item se agrego correctamente",
                "Tipo" => "success"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "El item que intenta agregar ya se encuentra seleccionado",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }
        
    }

    //funcion pata eliminar item de la compra
    public function eliminar_item_compra_controlador(){

        //recuperamos el id del item a eliminar
        $id = mainModel::limpiar_cadena($_POST['id_eliminar_item']);

        //iniciamos la sesion
        session_start(['name' => 'ppp']);

        //limpiamos la sesion, pero con el item especifico que se quiere eliminar
        unset($_SESSION['datos_item'][$id]);

        //comprobramos que la variable de sesion ya no existe o no tiene datos
        if (empty($_SESSION['datos_item'][$id])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Item removido",
                "Texto"=> "Los datos del item han sido removimos exitosamente",
                "Tipo" => "success"
            ];
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han podido remover los datos del item",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //funcion para mostrar los medios de pago
    public function obtener_metodos_pago() {
        $query_metodo_pago = "SELECT * FROM metodopago";
        $metodo_pago = mainModel::ejecutar_consulta_simple($query_metodo_pago);
        
        $metodos = [];
        if($metodo_pago->rowCount() >= 1){
            $metodos = $metodo_pago->fetchAll();
        }
        return $metodos;
    }
    
    //controlador para los datos de la compra
    public function datos_compra_controlador($tipo, $id){

        $tipo = mainModel::limpiar_cadena($tipo);

        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return compraModelo::datos_compra_modelo($tipo, $id);

    }

    //funcion para agregar compra
    public function agregar_compra_controlador(){

        //iniciamos sesion
        session_start(['name' => 'ppp']);

        //comprobmos si la compra lleva items
        if ($_SESSION['compra_item'] == 0) {//vriable de sesion que se creo en la vista de compra nueva 
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Necesita agregar items a la compra",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }

        //no es necesario agregar el cliente a la compra, en caso el cliente no quiera dar sus datos, se coloca el 2 nueve veces

        //recivimos todos los datos de a compra, incluyendo la fecha y hora que estan ocultas
        $fecha_compra = mainModel::limpiar_cadena($_POST['fecha_compra_reg']);
        $hora_compra = mainModel::limpiar_cadena($_POST['hora_compra_reg']);
        $metodo_pago_compra = mainModel::limpiar_cadena($_POST['compra_metodo_reg']);
        //el total de la compra que esta en el input readonly no se recive porque ya es una variable de sesion
        $observacion_compra = mainModel::limpiar_cadena($_POST['compra_observacion_reg']);
        //verificamos la observacion cuando viene vacio
        $observacion_compra = $observacion_compra == "" ? '-' : $observacion_compra;

        //comprobamos integridad de los datos
        //condicional para verificar formato de fecha
        if (mainModel::verificar_fecha($fecha_compra)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La FECHA no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("\d{2}:\d{2}:\d{2}", $hora_compra)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La HORA no coincide con el formato solicitado",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        $query_metodo_pago = "SELECT * FROM metodopago";
        $metodo_pago = mainModel::ejecutar_consulta_simple($query_metodo_pago);

        $metodos = [];
        if($metodo_pago->rowCount() >= 1){
            $metodos = $metodo_pago->fetchAll();
        }

        $metodo_valido = false;
        foreach($metodos as $metodo){
            if (strtoupper($metodo_pago_compra) === $metodo['idMetodoPago']) {
                $metodo_valido = true;
                break;
            }
        }

        if (!$metodo_valido) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto"=> "El MÉTODO DE PAGO no coincide con ninguno de los métodos permitidos",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }
        
        //verificamos que la fecha de compra no sea mayor a la fecha actual
        //creamos variable para almacenar la fecha actual
        $fechaActual = date('Y-m-d');
        if($fecha_compra != $fechaActual){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "La FECHA de la compra no coincide con la FECHA actual",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();
        }

        //formatear fecha, hora y montos
        $total_compra = number_format($_SESSION['compra_total'],2,".","");
        $fecha_compra = date("Y-m-d", strtotime($fecha_compra));
        $hora_compra = date("H:i:s", strtotime($hora_compra));
        //generamos correlativo para la compra
        //primero contamos las compras registradas para poder llevar un orden
        $sql_check_compras = "SELECT compra_id FROM compra";
        $correlativo = mainModel::ejecutar_consulta_simple($sql_check_compras);

        $correlativo = ($correlativo -> rowCount()) + 1;
        //lo almacenamos en una variable
        $codigo = mainModel::generar_codigo_aleatorio("CC", 7, $correlativo);//CV(Codigo de compra), longitud de 7, se concatena con guion y luego el numeroque seria el correlativo

        $datos_compra_reg = [
            "Codigo_compra" => $codigo,
            "Fecha_compra" => $fecha_compra,
            "Hora_compra" => $hora_compra,
            "Cantidad_compra" => $_SESSION['compra_item'],
            "Total_compra" => $total_compra,
            "Metodo_compra" => $metodo_pago_compra,
            "Observacion_compra" => $observacion_compra,
            "Usuario_compra" => $_SESSION['usuario_ppp'],
            "Proveedor_compra" =>  isset($_SESSION['datos_proveedor']['ID']) ? ($_SESSION['datos_proveedor']['ID']) : '999'

        ];

        //registrar la compra
        $agregar_compra = compraModelo::agregar_compra_modelo($datos_compra_reg);

        //comprobamos insercion
        if($agregar_compra -> rowCount() != 1){//hubo error

            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Error al registrar la compra, por favor intente nuevamente",
                "Tipo" => "error"
            ];

            echo json_encode($alerta);
            exit();

        }

        //codigo registrar detalle de compra
        //variabe para verificar errores
        $errores_detalle = 0;

        //recorremos todos los items de la compra
        foreach ($_SESSION['datos_item'] as $items) {
            //damos formato al precio de los productos
            $precio = number_format($items['Precio'],2,'.','');

            //array de datos para guardar en el detalle
            $datos_detalle_reg = [
                'compra'=> $codigo,
                'Item_id'=> $items['ID'],
                'Item_cantidad'=> $items['Cantidad'],
                'Item_precio'=> $precio,
                'Detalle_total'=> $items['Cantidad'] * $precio
            ];

            //registrar detalle de compra
            $agregar_detalle = compraModelo::agregar_detalle_compra_modelo($datos_detalle_reg);

            //verificamos insercion
            if($agregar_detalle -> rowCount() != 1){//hubo error
    
                //definimos error en 1
                $errores_detalle = 1;
                break;//terminamos foreach
            }

            // Actualizar el stock del item
            /*$nuevo_stock = $items['Cantidad'];
            $actualizar_stock = "UPDATE item SET item_stock = item_stock - $nuevo_stock WHERE item_id = '{$items['ID']}'";
            $resultado_stock = mainModel::ejecutar_consulta_simple($actualizar_stock);

            if($resultado_stock->rowCount() != 1){
                $errores_detalle = 1;
                break;
            }*/
        }

        //comprobacion de errores
        if ($errores_detalle == 0) {//no hay errores en la insercion de detalle

            //vaciamos las variables de sesion para limpiar la compra
            unset($_SESSION['datos_proveedor']);
            unset($_SESSION['datos_item']);

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "compra registrada",
                "Texto"=> "La compra fue registrada satisfactoriamente",
                "Tipo" => "success"
            ];

        } else {//eliminamos los datos de la insercion erronea

            compraModelo::eliminar_compra_modelo($codigo, 'Detalle_compra'); 
            compraModelo::eliminar_compra_modelo($codigo, 'Compra');

            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Error al registrar la compra y sus detalles, por favor intente nuevamente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        

    }

    //funcion para paginar compras
    public function paginador_compra_controlador($pagina, $registros, $privilegio, $url, $tipo, $fecha_inicio, $fecha_final){//recibe la pagina actual, cuantos registros quiero que se muestren por pagina ,el privilegio para ocultar algunas opciones como actualizar o eliminar, la url para los enlaces de cada boton de la paginacion, tipo para cuando el listado se muestra en la busqueda o en el listado simple, las fechas para la busqueda

        //para evitar inyeccion sql
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = server_url.$url."/";

        $tipo = mainModel::limpiar_cadena($tipo);
        $fecha_inicio = mainModel::limpiar_cadena($fecha_inicio);
        $fecha_final = mainModel::limpiar_cadena($fecha_final);
        $tabla = "";//tabla creada con las compras

        //validaciones segun la pagina de la tabla, para que no se pueda modificar la url de cada pagina de la tabla
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;//es decir si la pagina viene definida se parsea a entero y si no se le asigna el valor o no es un numero se redirecciona a la pag 1

        //variable para ver desde que registro empezamos acontar
        $inicio = ($pagina > 0) ? (($pagina * $registros)-$registros) : 0;

        //validacion de fechas
        if ($tipo == "Busqueda") {
            if(mainModel::verificar_fecha($fecha_inicio) || mainModel::verificar_fecha($fecha_final)){
                return '
                    <div class="alert alert-danger text-center" role="alert">
                        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
                        <p class="mb-0">No se puede realizar la busqueda, ha ingresado una fecha incorrecta</p>
                    </div>
                ';
                exit();
            }
        }

        //variable para traer los datos especificos de la compra y cliente
        $campos = "compra.compra_id, compra.compra_codigo, compra.compra_fecha, compra.compra_hora, compra.compra_cantidad, compra.compra_total, compra.metodo_id, compra.compra_observacion, compra.usuario_nombre, compra.cliente_id, compra.compra_estado, cliente.cliente_nombre, cliente.cliente_apellido, metodopago.nombre, usuario.user";

        //condicion para la consulta a la base de datos, si es listado normal o de busqueda
        if($tipo == "Busqueda" && $fecha_inicio != "" && $fecha_final != ""){ //si el tipo es de busqueda, y las fechas no vienen vacias

            //consulta para que el resultado coindica con la busqueda realizada
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos 
            FROM compra 
            LEFT JOIN cliente
            ON compra.cliente_id = cliente.cliente_id 
            LEFT JOIN metodopago 
            ON compra.metodo_id = metodopago.idMetodoPago 
            LEFT JOIN usuario
            ON compra.usuario_nombre = usuario.user
            WHERE (compra.compra_fecha BETWEEN '$fecha_inicio' AND '$fecha_final') 
            ORDER BY compra.compra_fecha DESC";
        }else{
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos 
            FROM compra 
            LEFT JOIN cliente
            ON compra.cliente_id = cliente.cliente_id 
            LEFT JOIN metodopago 
            ON compra.metodo_id = metodopago.idMetodoPago 
            LEFT JOIN usuario
            ON compra.usuario_nombre = usuario.user
            ORDER BY compra.compra_fecha DESC";
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
                        <th>CODIGO DE compra</th>
                        <th>CLIENTE</th>
                        <th>VENDEDOR</th>
                        <th>FECHA Y HORA DE compra</th>
                        <th>ESTADO ACTUAL</th>
                        <th>DETALLES</th>
                        <th>COMPROBANTE</th>';
                        if($privilegio == 1){
                            $tabla.= '<th>DEVOLVER</th>';
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
                                <td>'.$rows['compra_codigo'].'</td>
                                <td>'.(empty($rows['cliente_nombre']) ? '-' : $rows['cliente_nombre']).' '.$rows['cliente_apellido'].'</td>
                                <td>'.$rows['usuario_nombre'].'</td>
                                <td>'.date("d-m-Y", strtotime($rows['compra_fecha'])).' '.$rows['compra_hora'].'</td>';

                                if ($rows['compra_estado'] == 'Pagado') {
                                    $tabla .= '<td><span class="badge badge-success">Pagada</span></td>';
                                }else{
                                    $tabla .= '<td><span class="badge badge-danger">Devuelta</span></td>';
                                }

                                $tabla .= '<td>
                                    <button class="btn btn-info" onclick="verDetallescompra('.$rows['compra_id'].')">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                                <td>
                                    <a href="'.server_url.'comprobante/invoice.php?id='.mainModel::encryption($rows['compra_id']).'" class="btn btn-info" target = "_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>';
                            if($privilegio == 1){

                                if ($rows['compra_estado'] == "Devuelto") {
                                    $tabla .= '<td>
                                            <button class="btn btn-warning" disabled>
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                    </td>';
                                } else {
                                    $tabla .= '<td>
                                        <form class = "FormularioAjax" action="'.server_url.'ajax/compraAjax.php" method="POST" data-form="compra_devuelta" autocomplete="off">
                                        <input type = "hidden" name = "compra_id_devuelta" value = "'.mainModel::encryption($rows['compra_codigo']).'">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>';
                                }
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
            $tabla .= '<p class = "text-right">Mostrando compras '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' registros</p>';
            $tabla .= mainModel::paginador_tablas( $pagina, $Npaginas, $url, 7);
        }

        return $tabla;

    }

    //funcion para la devolucion de compra
    public function devolver_compra_controlador(){
    //recibimos el id
    $codigo = mainModel::decryption($_POST['compra_id_devuelta']);
    $codigo = mainModel::limpiar_cadena($codigo);

    //comprobamos que este registrado en la bd
    $query_check_compra = "SELECT compra_codigo FROM compra WHERE compra_codigo = '$codigo'";
    $check_compra = mainModel::ejecutar_consulta_simple($query_check_compra);
    if($check_compra -> rowCount() <= 0){
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Ocurrió un error",
            "Texto"=> "No hemos encontrado la compra que desea devolver",
            "Tipo" => "error"
        ];

        echo json_encode($alerta);
        exit();
    }

    //verificamos privilegios del usuario que está eliminando
    session_start(['name' => 'ppp']);
    if($_SESSION['privilegio_ppp'] != 1){
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Ocurrió un error",
            "Texto"=> "Usted no cuenta con los permisos necesarios para realizar esta acción",
            "Tipo" => "error"
        ];

        echo json_encode($alerta);
        exit();
    }

    // recuperar detalles de la compra
    $query_detalle_compra = "SELECT * FROM detalle_compra WHERE compra_codigo = '$codigo'";
    $detalle_compra = mainModel::ejecutar_consulta_simple($query_detalle_compra);
    if($detalle_compra->rowCount() <= 0){
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Ocurrió un error",
            "Texto"=> "No se encontraron detalles para esta compra",
            "Tipo" => "error"
        ];

        echo json_encode($alerta);
        exit();
    }

    $items = $detalle_compra->fetchAll();

    // devolver compra y actualizar stock
    $devolver_compra = compraModelo::devolver_compra_modelo($codigo, "Pagado");
    if($devolver_compra->rowCount() == 1){
        $errores = 0;
        foreach($items as $item){
            $actualizar_stock = compraModelo::actualizar_stock_item($item['item_id'], $item['detallecompra_item_cantidad'], 'sumar');
            if($actualizar_stock->rowCount() != 1){
                $errores++;
            }
        }

        if($errores == 0){
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "compra Devuelta",
                "Texto"=> "La compra ha sido devuelta satisfactoriamente y el stock actualizado",
                "Tipo" => "success"
            ];
        }else{
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto"=> "La compra fue devuelta pero hubo un problema al actualizar el stock",
                "Tipo" => "error"
            ];
        }
    }else{
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Ocurrió un error",
            "Texto"=> "No se puede devolver esta compra",
            "Tipo" => "error"
        ];
    }
    echo json_encode($alerta);
}


    //funcion para ver detalles en modal
    public function obtener_detalles_compra_controlador() {
        $compra_id = mainModel::limpiar_cadena($_POST['compra_id']);
        
        // Obtener los detalles de la compra desde el modelo
        $detalles = compraModelo::obtener_detalles_compra_modelo($compra_id);
        
        if ($detalles && !empty($detalles)) {
            $primerDetalle = $detalles[0]; // Usamos el primer elemento para los detalles generales
            
            // Construir el HTML con los detalles de la compra
            $output = "<p><strong>Codigo de compra: </strong>" . $primerDetalle['compra_codigo'] . "</p>";
            $output .= "<p><strong>Cliente: </strong> ". ($primerDetalle['cliente_nombre'] == "" ? "Cliente Generico" : $primerDetalle['cliente_nombre']) . " " . $primerDetalle['cliente_apellido'] . "</p>";
            $output .= "<p><strong>Vendedor: </strong>" . $primerDetalle['usuario_nombre'] . "</p>";
            $output .= "<p><strong>Fecha: </strong> " . $primerDetalle['compra_fecha'] . "</p>";
            $output .= "<p><strong>Hora: </strong> " . $primerDetalle['compra_hora'] . "</p>";
            $output .= "<p><strong>Total: </strong>".moneda." " . $primerDetalle['compra_total'] . "</p>";
            $output .= "<p><strong>Medio de Pago: </strong>" . $primerDetalle['nombre'] . "</p>";
    
            $output .= '<div class="table-responsive">
                        <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                                <th>#</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>';
            $total = 0;
            $contador = 1;
            foreach ($detalles as $item) {
                $output .= '<tr class="text-center">
                                <td>' . $contador . '</td>
                                <td>' . $item['item_codigo'] . '</td>
                                <td>' . $item['item_nombre'] . '</td>
                                <td>'.moneda.' ' . number_format($item['item_precio'], 2, '.', '') . '</td>
                                <td>' . $item['detallecompra_item_cantidad'] . '</td>
                                <td>'.moneda.' ' . number_format($item['detallecompra_total'], 2, '.', '') . '</td>
                            </tr>'
                            ;
                $total += $item['detallecompra_total'];
                $contador++;
            }
            
            $output .= '
                            <tr class="text-center">
                                <th><strong>TOTAL</strong></th>
                                <td colspan="3"></td>
                                <th>' . $item['compra_cantidad'] . ' item(s)</th>
                                <th>'.moneda.' ' . number_format($total, 2, '.', '') . '</th>
                            </tr>
                        </table>
                        </div>';
            
            return $output;
        } else {
            return "<p>No se encontraron detalles para esta compra.</p>";
        }
    }

    public function editar_cantidad_item_compra_controlador() {
        // Recuperar datos del formulario
        $id = mainModel::limpiar_cadena($_POST['id_editar_item']);
        $nueva_cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad_editar']);
        
        session_start(['name' => 'ppp']);
        // Validar y actualizar la cantidad en la variable de sesión
        if (!empty($_SESSION['datos_item'][$id])) {

            if($nueva_cantidad > $_SESSION['datos_item'][$id]['Stock']){
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Error al editar",
                    "Texto" => "La cantidad ingresada supera el stock del producto, el stock actual es " . $_SESSION['datos_item'][$id]['Stock'],
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $_SESSION['datos_item'][$id]['Cantidad'] = $nueva_cantidad;
    
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Cantidad actualizada",
                    "Texto" => "La cantidad del item ha sido actualizada correctamente",
                    "Tipo" => "success"
                ];
            
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Error al editar",
                "Texto" => "El item que intenta editar no se encuentra en la lista actual de items",
                "Tipo" => "error"
            ];
        }
    
        // Devolver respuesta en formato JSON
        echo json_encode($alerta);
        exit();
    }
    
    
}