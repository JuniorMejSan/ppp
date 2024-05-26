<?php
if ($peticionAjax) {//cuando e sun apeticion ajax el archivo se va a ejecutar en usuarioAjax.php
    require_once "../modelos/usuarioModelo.php";
}else { //si no, significa que se esta ejecutando desde index.php
    require_once "./modelos/usuarioModelo.php";
}

class usuarioControlador extends usuarioModelo{

}