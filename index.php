<?php
require_once "./config/app.php"; //configuraciones iniciales del sistema
require_once "./controladores/vistasControlador.php"; 

$plantilla = new vistasControlador();

$plantilla -> obtener_plantilla_controlador();

