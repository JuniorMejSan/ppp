<?php
if ($peticionAjax) {
    require_once "../modelos/medioModelo.php";
} else {
    require_once "./modelos/medioModelo.php";
}

class medioControlador extends medioModelo {

    // Controlador para agregar medio de pago
    public static function agregar_medio_controlador() {
        $descripcion = mainModel::limpiar_cadena($_POST['medio_descripcion_reg']);

        // Comprobar que los campos obligatorios no estén vacíos
        if ($descripcion == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El campo DESCRIPCIÓN es obligatorio",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar integridad de los datos
        if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La DESCRIPCIÓN contiene caracteres no permitidos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar que la descripción no esté registrada
        $check_descripcion = mainModel::conectar()->prepare("SELECT id_medio_pago FROM medio_pago WHERE descripcion=:desc LIMIT 1");
        $check_descripcion->bindParam(":desc", $descripcion);
        $check_descripcion->execute();

        if ($check_descripcion->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El MEDIO DE PAGO ya se encuentra registrado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Array de datos a enviar al modelo
        $datos_medio_reg = [
            "Descripcion" => $descripcion,
            "Estado" => 1
        ];

        // Variable para agregar
        $agregar_medio = medioModelo::agregar_medio_modelo($datos_medio_reg);

        // Condicional para verificar el correcto registro
        if ($agregar_medio->rowCount() == 1) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Éxito",
                "Texto" => "El MEDIO DE PAGO se registró correctamente",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se pudo registrar el MEDIO DE PAGO",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    // Controlador para eliminar/inhabilitar medio de pago
    public function eliminar_medio_controlador() {
        $id = mainModel::decryption($_POST['medio_id_del']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobar que esté registrado en la BD
        $query_check_medio = "SELECT id_medio_pago FROM medio_pago WHERE id_medio_pago = '$id'";
        $check_medio = mainModel::ejecutar_consulta_simple($query_check_medio);

        if ($check_medio->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El MEDIO DE PAGO no existe",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar privilegios del usuario
        session_start(['name' => 'ppp']);
        if ($_SESSION['privilegio_ppp'] != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No tienes permiso para eliminar MEDIOS DE PAGO",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Eliminar medio
        $eliminar_medio = medioModelo::eliminar_medio_modelo($id);

        if ($eliminar_medio->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Éxito",
                "Texto" => "El MEDIO DE PAGO se eliminó correctamente",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se pudo eliminar el MEDIO DE PAGO",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    // Controlador para habilitar medio de pago
    public function habilitar_medio_controlador() {
        $id = mainModel::decryption($_POST['medio_id_enable']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobar que esté registrado en la BD
        $query_check_medio = "SELECT id_medio_pago FROM medio_pago WHERE id_medio_pago = '$id'";
        $check_medio = mainModel::ejecutar_consulta_simple($query_check_medio);

        if ($check_medio->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El MEDIO DE PAGO no existe",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar privilegios del usuario
        session_start(['name' => 'ppp']);
        if ($_SESSION['privilegio_ppp'] != 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No tienes permiso para habilitar MEDIOS DE PAGO",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Habilitar medio
        $habilitar_medio = medioModelo::habilitar_medio_modelo($id);

        if ($habilitar_medio->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Éxito",
                "Texto" => "El MEDIO DE PAGO se habilitó correctamente",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se pudo habilitar el MEDIO DE PAGO",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    // Controlador para actualizar medio de pago
    public function actualizar_medio_controlador() {
        $id = mainModel::decryption($_POST['medio_id_up']);
        $id = mainModel::limpiar_cadena($id);

        // Comprobar medio en la BD
        $query_check_medio = "SELECT * FROM medio_pago WHERE id_medio_pago = '$id'";
        $check_medio = mainModel::ejecutar_consulta_simple($query_check_medio);

        if ($check_medio->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El MEDIO DE PAGO no existe",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_medio->fetch();
        }

        $descripcion = mainModel::limpiar_cadena($_POST['medio_descripcion_up']);

        // Verificar que los campos no vengan vacíos
        if ($descripcion == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El campo DESCRIPCIÓN es obligatorio",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar integridad de los datos
        if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}", $descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La DESCRIPCIÓN contiene caracteres no permitidos",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Verificar que la descripción no esté duplicada
        if ($descripcion != $campos['descripcion']) {
            $check_descripcion = mainModel::conectar()->prepare("SELECT id_medio_pago FROM medio_pago WHERE descripcion=:desc LIMIT 1");
            $check_descripcion->bindParam(":desc", $descripcion);
            $check_descripcion->execute();

            if ($check_descripcion->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error",
                    "Texto" => "El MEDIO DE PAGO ya se encuentra registrado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        // Verificar privilegios
        session_start(['name' => 'ppp']);
        if ($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No tienes permiso para actualizar MEDIOS DE PAGO",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Array para actualizar datos
        $datos_medio_up = [
            "ID" => $id,
            "Descripcion" => $descripcion
        ];

        $actualizar_medio = medioModelo::actualizar_medio_modelo($datos_medio_up);

        if ($actualizar_medio->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Éxito",
                "Texto" => "El MEDIO DE PAGO se actualizó correctamente",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se pudo actualizar el MEDIO DE PAGO",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }

    // Controlador para obtener datos de un medio
    public function datos_medio_controlador($tipo, $id) {
        $tipo = mainModel::limpiar_cadena($tipo);
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return medioModelo::datos_medio_modelo($tipo, $id);
    }

    // Controlador para listar medios de pago
    public function paginador_medio_controlador($pagina, $registros, $privilegio, $url, $tipo_medio) {

        $pagina     = mainModel::limpiar_cadena($pagina);
        $registros  = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);

        $url = mainModel::limpiar_cadena($url);
        $url = server_url . $url . "/";

        $tabla = "";

        // Validación de página
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;

        // Desde qué registro iniciamos
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        // Consulta según tipo
        if ($tipo_medio == 'Habilitado') {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * 
                        FROM medio_pago
                        WHERE estado = 1
                        ORDER BY descripcion ASC
                        LIMIT $inicio, $registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * 
                        FROM medio_pago
                        WHERE estado = 0
                        ORDER BY descripcion ASC
                        LIMIT $inicio, $registros";
        }

        // Conexión
        $conexion = mainModel::conectar();

        // Traer datos
        $datos = $conexion->query($consulta);
        if (!$datos) {
            $errorInfo = $conexion->errorInfo();
            die("Error en la consulta SQL: " . $errorInfo[2]);
        }
        $datos = $datos->fetchAll();

        // Conteo total
        $query_conteo = "SELECT FOUND_ROWS()";
        $total = $conexion->query($query_conteo);
        if (!$total) {
            $errorInfo = $conexion->errorInfo();
            die("Error en la consulta SQL para el conteo: " . $errorInfo[2]);
        }
        $total = (int)$total->fetchColumn();

        $Npaginas = ceil($total / $registros);

        // Tabla (misma lógica visual que presentaciones)
        $tabla .= '<div class="table-responsive">
            <table class="table table-dark table-sm">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>DESCRIPCIÓN</th>';

        if ($privilegio == 1 || $privilegio == 2) {
            $tabla .= '<th>ACTUALIZAR</th>';
        }

        if ($privilegio == 1 && $tipo_medio == 'Habilitado') {
            $tabla .= '<th>ELIMINAR</th>';
        } elseif ($privilegio == 1 && $tipo_medio == 'Inhabilitado') {
            $tabla .= '<th>HABILITAR</th>';
        }

        $tabla .= '</tr>
                </thead>
                <tbody>';

        if ($total >= 1 && $pagina <= $Npaginas) {

            $contador   = $inicio + 1;
            $reg_inicio = $inicio + 1;

            foreach ($datos as $rows) {

                $tabla .= '<tr class="text-center">
                            <td>' . $contador . '</td>
                            <td>' . $rows['descripcion'] . '</td>';

                // Actualizar
                if ($privilegio == 1 || $privilegio == 2) {
                    $tabla .= '<td>
                        <a href="' . server_url . 'medio-update/' . mainModel::encryption($rows['id_medio_pago']) . '/" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </td>';
                }

                // Eliminar / Habilitar (con FormularioAjax hacia medioAjax.php)
                if ($privilegio == 1 && $tipo_medio == 'Habilitado') {
                    $tabla .= '<td>
                        <form class="FormularioAjax" action="' . server_url . 'ajax/medioAjax.php" method="POST" data-form="delete" autocomplete="off">
                            <input type="hidden" name="medio_id_del" value="' . mainModel::encryption($rows['id_medio_pago']) . '">
                            <button type="submit" class="btn btn-warning">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>';
                } elseif ($privilegio == 1 && $tipo_medio == 'Inhabilitado') {
                    $tabla .= '<td>
                        <form class="FormularioAjax" action="' . server_url . 'ajax/medioAjax.php" method="POST" data-form="enable" autocomplete="off">
                            <input type="hidden" name="medio_id_enable" value="' . mainModel::encryption($rows['id_medio_pago']) . '">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    </td>';
                }

                $tabla .= '</tr>';
                $contador++;
            }

            $reg_final = $contador - 1;

        } else {

            if ($total >= 1) {
                $tabla .= '<tr class="text-center"><td colspan="10">
                    <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Clic aqui para recargar el listado</a>
                </td></tr>';
            } else {
                $tabla .= '<tr class="text-center"><td colspan="10">Ningun registro coincide con el termino de busqueda</td></tr>';
            }
        }

        $tabla .= '</tbody></table></div>';

        // Paginación
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= '<p class="text-right">Mostrando cliente ' . $reg_inicio . ' al ' . $reg_final . ' de un total de ' . $total . ' registros</p>';
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }

        return $tabla;
    }
}