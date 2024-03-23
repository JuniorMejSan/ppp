<?php

require_once "./modelos/vistasModelo.php";

class vistasControlador extends vistasModelo{  //se crea la clase vistasControlador que hereda de vistasModelo

    //controlador para obtener plantilla y mostrarla
    public function obtener_plantilla_controlador(){
        return require_once "./vistas/plantilla.php";
    }

    //controlador para obtener vista
    public function obtener_vistas_controlador(){
        if (isset($_GET['views'])) {   //comprueba el nombre que se envia por url en .htaccess 
            $ruta = explode("/", $_GET['views']); //explode sirve para dividir, en esta caso la variable que viene por url con "/"
            $respuesta = vistasModelo::obtener_vistas_modelo($ruta[0]);
        }else {
            $respuesta = "login";
        }
        return $respuesta;
    }
}
?>