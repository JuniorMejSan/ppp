<?php
$peticionAjax = true;
require_once "../config/app.php";
require_once "../controladores/loginControlador.php";

if(isset($_POST['admin_password_check'])){
    $lc = new loginControlador();
    $lc->validar_password_admin_controlador();
}else{
    session_start(['name' => 'ppp']);
    session_unset();
    session_destroy();
    header("Location: ".server_url."login/");
    exit();
}
