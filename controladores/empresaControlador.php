<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/empresaModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/empresaModelo.php";
}

class empresaControlador extends empresaModelo{

}