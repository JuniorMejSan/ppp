<?php

class vistasModelo{

    //modelo para obtener las vistas, indicamos que es protegido
    protected static function obtener_vistas_modelo($vista){
        //lista blanca de palabras que si se pueden escribir en la url
        $listaBlanca = ["home", "client-list"];
        
        if (in_array($vista, $listaBlanca)) { //si el valor que viene mediante la url esta en la lista blanca
            if (is_file("./vistas/contenidos/".$vista."-view.php")) { //sirve para comprobra un archivo enviado mediante url dentro del directorio
                $contenido = "./vistas/contenidos/".$vista."-view.php";
            }else {
                $contenido = "404";
            }
        }elseif ($vista == "login" || $vista == "index") { //si no esta dentro de la lista blanca pero es login o index manda al inicio de sesion
            $contenido = "login";
        }else { //si no existe manda error 404
            $contenido = "404";
        } //retorna la variable $contenido
        return $contenido;
    }
}


?>