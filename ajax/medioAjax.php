<?php
$peticionAjax = true;
require_once "../config/app.php";

if (
    isset($_POST['medio_descripcion_reg']) ||
    isset($_POST['medio_id_del']) ||
    isset($_POST['medio_id_enable']) ||
    isset($_POST['medio_id_up'])
) {

    require_once "../controladores/medioControlador.php";
    $ins_medio = new medioControlador();

    /* Registrar */
    if (isset($_POST['medio_descripcion_reg'])) {
        echo $ins_medio->agregar_medio_controlador();
    }

    /* Eliminar / Inhabilitar */
    if (isset($_POST['medio_id_del'])) {
        echo $ins_medio->eliminar_medio_controlador();
    }

    /* Habilitar */
    if (isset($_POST['medio_id_enable'])) {
        echo $ins_medio->habilitar_medio_controlador();
    }

    /* Actualizar */
    if (isset($_POST['medio_id_up'])) {
        echo $ins_medio->actualizar_medio_controlador();
    }

} else {
    // Si intentan acceder desde el navegador directamente
    session_start(['name' => 'ppp']);
    session_unset();
    session_destroy();
    header("Location: " . server_url . "login/");
    exit();
}