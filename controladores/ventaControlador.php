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
    
}