<?php

class vistasModelo{

    //modelo para obtener las vistas, con protected indicamos que es protegido
    protected static function obtener_vistas_modelo($vista){
        //lista blanca de palabras que si se pueden escribir en la url, si no muestra error 404
        $listaBlanca = ["home", "client-list", "client-new", "client-search", "client-update", "item-list", "item-list-disable", "item-new", "item-search", "item-update", "item-reporte", "venta-list", "venta-new", "venta-search", "user-list", "user-new", "user-search", "user-update", "proveedor-new", "proveedor-list", "proveedor-search", "proveedor-update", "compra-list", "compra-new", "compra-search"];
        
        if (in_array($vista, $listaBlanca)) { //si el valor que viene mediante la url esta en la lista blanca
            if (is_file("./vistas/contenidos/".$vista."-view.php")) { //is_file sirve para comprobra un archivo enviado mediante url dentro del directorio
                $contenido = "./vistas/contenidos/".$vista."-view.php"; //manda a la vista
            }else {
                $contenido = "404";  //no existe
            }
        }elseif ($vista == "login" || $vista == "index") { //si no esta dentro de la lista blanca pero es login o index manda al inicio de sesion
            $contenido = "login";
        }else { //si no existe manda error 404
            $contenido = "404";
        } //retorna la variable $contenido
        return $contenido;
    }
}


