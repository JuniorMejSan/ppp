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

}