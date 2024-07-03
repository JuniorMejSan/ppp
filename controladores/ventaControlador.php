<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en ventaAjax.php
    require_once "../modelos/ventaModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/ventaModelo.php";
}

class ventaControlador extends ventaModelo{

    //controlador para buscar cliente para la venta
    public function buscar_cliente_venta_controlador(){
        
        //recivimos el termino de busqueda
        $cliente = mainModel::limpiar_cadena($_POST["buscar_cliente"]);

        //verificamos que no venga vacia
        if($cliente == ""){
            return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debe introducir el DNI
                    </p>
                </div>';
                exit(); //hacemos que se detenga la ejecucion del codigo
        }

        //comprobar texto en la bd
        $query_datos_cliente = "SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' ORDER BY cliente_nombre ASC";
        $datos_cliente = mainModel::ejecutar_consulta_simple($query_datos_cliente);

        //verificamos si hay datos
        if($datos_cliente -> rowCount() >= 1){

            $datos_cliente = $datos_cliente -> fetchAll(); //reasignamos todos los datos que trae la consulta al arreglo

            //tabla que mostrara los datos del cliente buscado
            $tabla = '<div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>';

            //recorremos los registros que trajo la consulta para poder mostrarlos
            foreach($datos_cliente as $rows){
                 $tabla.= '<tr class="text-center">
                                    <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' - '.$rows['cliente_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick = "agregar_cliente('.$rows['cliente_id'].')"><i
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
                        No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$cliente.'”</strong>
                    </p>
                </div>';
        }
    }

    //controlador para agregar cliente a la venta
    public function agregar_cliente_venta_controlador(){

        //recuperamos el id del cliente seleccionado 
        $id = mainModel::limpiar_cadena(($_POST['id_agregar_cliente']));

        //comprobamos cliente en la base de datos
        $query_check_clinte = "SELECT * FROM cliente WHERE cliente_id = '$id'";
        $check_cliente = mainModel::ejecutar_consulta_simple($query_check_clinte);

        if ($check_cliente -> rowCount() <= 0){//no existe el cliente en la base de datos
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No hemos podido encontrar el cliente en el sistema",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
            exit();
        }else{
            $campos = $check_cliente -> fetch();
        }

        //iniciamos la sesion
        session_start(['name' => 'ppp']);

        if(empty($_SESSION['datos_cliente'])){//variable de sesion que tendran todos los datos que se agregue
            //si viene vacio lo llenamos
            $_SESSION['datos_cliente'] = [
                "ID" => $campos['cliente_id'],
                "DNI" => $campos['cliente_dni'],
                "Nombre" => $campos['cliente_nombre'],
                "Apellido" => $campos['cliente_apellido']
            ];

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente agregado",
                "Texto"=> "Cliente agregado a la venta satisfactoriamente",
                "Tipo" => "success"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se ha podido agregar el cliente a la venta o esta venta ya tiene un cliente, intentelo nuevamente",
                "Tipo" => "error"
            ];
            //se envian los datos a JS
            echo json_encode($alerta);
        }
    }

    //funcion para eliminar cliente de la venta
    public function eliminar_cliente_venta_controlador(){

        //iniciamos la sesion
        session_start(['name' => 'ppp']);

        //quitamos la sesion para que se elimine el cliente agregado
        unset($_SESSION['datos_cliente']);

        //comprobramos que la variable de sesion ya no existe o no tiene datos
        if (empty($_SESSION['datos_cliente'])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente removido",
                "Texto"=> "Los datos del cliente han sido removimos exitosamente",
                "Tipo" => "success"
            ];
        }else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "No se han podido remover los datos del cliente",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    //funcion para buscar items para la venta
    public function buscar_item_venta_controlador(){
    
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
                                    <td>'.$rows['item_codigo'].' - '.$rows['item_nombre'].'</td>
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
                </div>';
        }
    }

    //funcion para agregar items a la venta
    public function agregar_item_venta_controlador(){

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
        
        //recuperamos lo detalles de la venta ingresados
        $cantidad = mainModel::limpiar_cadena($_POST['detalle_cantidad']);

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
                "Texto"=> "La cantidad no tiene el formato reuerido",
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
                "Nombre"=> $campos['item_nombre'],
                "Precio"=> $campos['item_precio'],
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
}