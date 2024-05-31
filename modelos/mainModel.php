<?php
    if ($peticionAjax) { //si es true significa que esta accediendo desde la carpeta ajax
        require_once "../config/server.php";
    }else { //cuando $peticionAjax es flase indica que estamos entrando desde index.php 
        require_once "./config/server.php";
    }

    class mainModel{
        //funcion para conectar a la BD
        protected static function conectar(){
            $conexion = new PDO(sgdb, user, pass);
            $conexion -> exec("SET CHARACTER utf8");
            return $conexion;
        }

        //funcion para ejecutar consultas simples
        protected static function ejecutar_consulta_simple($consulta){
            $sql = self::conectar()-> prepare($consulta);//con self hacemos referencia a una funcion o metodo del modelo actual
            $sql -> execute();
            return $sql;
        }

        //fucnion para encriptar cadenas o textos planos (hash)
        public function encryption($string){
			$output=FALSE;
			$key=hash('sha256', secret_key);
			$iv=substr(hash('sha256', secret_iv), 0, 16);
			$output=openssl_encrypt($string, method, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		}

        //funcion para desenciptar cadenas (hash)
		protected static function decryption($string){
			$key=hash('sha256', secret_key);
			$iv=substr(hash('sha256', secret_iv), 0, 16);
			$output=openssl_decrypt(base64_decode($string), method, $key, 0, $iv);
			return $output;
		}

        //funcion para generear codigos aleatorios para identificar las ventas
        protected static function generar_codigo_aleatorio($letra, $longitud, $numero){ //ejemplo P123-1 $letra es para P, $longitud para la cantidad de digitos y $numero es el correlativo segun la base de datos
            for ($i=1; $i <= $longitud ; $i++) { //hace iteraciones para elejir numero al azar, segun la longitud
                $aleatorio = rand(0,9); //rand escoge un numero al azar entre el 0 y el 9
                $letra .= $aleatorio; //concatena la letra con el numero aleatorio como el ejemplo P123-1
            }
            return $letra."-".$numero; //concatena todos las variables para tener el codigo aleatorio
        }

        //funcion para evitar inyecciones sql, va a limpiar textos o caracteres cuando se envien datos mediante formularios
        protected static function limpiar_cadena($cadena){
            $cadena = trim($cadena); //devuelve la cadena de texto sin de los espacios en blanco en ambos extremos
            $cadena = stripslashes($cadena); //quita las barras de un string 
            $cadena = str_ireplace("<script>","", $cadena); //busca si el string contiene "<script>" y lo reemplaza por vacio
            $cadena = str_ireplace("</script>","", $cadena);
            $cadena = str_ireplace("<script src","", $cadena);
            $cadena = str_ireplace("<script type=","", $cadena);
            $cadena = str_ireplace("SELECT * FROM","", $cadena);
            $cadena = str_ireplace("DELETE * FROM","", $cadena);
            $cadena = str_ireplace("INSERT INTO","", $cadena);
            $cadena = str_ireplace("DROP TABLE","", $cadena);
            $cadena = str_ireplace("DROP DATABASE","", $cadena);
            $cadena = str_ireplace("TRUNCATE TABLE","", $cadena);
            $cadena = str_ireplace("SHOW TABLES","", $cadena);
            $cadena = str_ireplace("SHOW DATABASES","", $cadena);
            $cadena = str_ireplace("<?php","", $cadena);
            $cadena = str_ireplace("?>","", $cadena);
            $cadena = str_ireplace("--","", $cadena);
            $cadena = str_ireplace("<","", $cadena);
            $cadena = str_ireplace(">","", $cadena);
            $cadena = str_ireplace("[","", $cadena);
            $cadena = str_ireplace("]","", $cadena);
            $cadena = str_ireplace("^","", $cadena);
            $cadena = str_ireplace("==","", $cadena);
            $cadena = str_ireplace(";","", $cadena);
            $cadena = str_ireplace("::","", $cadena);
            $cadena = stripslashes($cadena);
            $cadena = trim($cadena);
            return $cadena;
        }

        //funcion para validar datos string
        protected static function verificar_datos($filtro, $cadena){//$filtro son los caracteres permitidos y $cadena el dato a validar
            if (preg_match("/^".$filtro."$/", $cadena)) {//preg_match sirve para verificar coincidencias, en este caso $cadena con el filtro que le pasamos
                return false; //es decir cpincide con el formato solicitado y no hay errores
            }else{
                return true;
            }
        }

        //funcion para validar fechas
        protected static function verificar_fecha($fecha){
            $valores = explode("-", $fecha);//es un arra con todos los datos de una fecha, porque los separa por el -
            if (count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0])) { //$valores debe tener solo 3 datos (d-m-a), checkdate revisa que sea una fecha primero recive mes, luego día  luego año peor el formato es a-m-d
                return false; //es decir cpincide con el formato solicitado y no hay errores
            }else{
                return true;
            }
        }

        //funcion para paginar tabla
        protected static function paginador_tablas($pagina, $Npaginas, $url, $botones){//$pagina recive la pagina actual de la tabla, $Npagians la cantidad de paginas que tiene el listado, $url la pagina a la que lleva cada boton, $botones la cantidad de botones que se van a mostrar
            $tabla = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">'; //esto es la navegacion de botones de una tabla en la vista
            if ($pagina == 1) { //cuando la paginacion esta en la pagina 1 se deshabilita el boton para regresar
                $tabla.='<li class="page-item disabled"><a class="page-link"><i class="fas fa-arrow-left"></i></a></li>';
            }else{ //se habilita
                $tabla.='<li class="page-item">
                            <a class="page-link" href="'.$url.'1/">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="'.$url.($pagina-1).'/">Anterior</a>
                        </li>'; //en el jref enviamos al usuario a la pag 1 y con -1 va retrocediendo en las paginas
            }

            $ci = 0; //contador de iteraciones, para la cantidad de vueltas del ciclo
            //for para mostrar la cantidad de botones que se van a mostrar en la paginacion
            for ($i=$pagina; $i <= $Npaginas; $i++) { 
                if ($ci >= $botones) { //muestra la cantidad de botones segun la cantidad de iteraciones
                    break;
                }
                //if para mostrar sobreado el boton de la pagina donde nos encontramos
                if($pagina == $i){
                    $tabla .= '<li class="page-item">
                                    <a class="page-link active" href="'.$url.$i.'/">'.$i.'</a>
                                </li>'; //active para el sombreado y concatenamos $i para que sombree el boton segun la pagina donde nos encontramos
                }else{
                    $tabla .= '<li class="page-item">
                                    <a class="page-link" href="'.$url.$i.'/">'.$i.'</a>
                                </li>'; //se le quita el active
                }
                $ci++;
            }

            if ($pagina == $Npaginas) { //cuando la paginacion esta en la pagina 1 se deshabilita el boton para regresar
                $tabla.='<li class="page-item disabled"><a class="page-link"><i class="fas fa-arrow-right"></i></a></li>';
            }else{ //se habilita
                $tabla.='<li class="page-item">
                            <a class="page-link" href="'.$url.($pagina+1).'/">Siguiente</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="'.$url.$Npaginas.'/">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </li>'; //en el href enviamos al usuario a la pag 1 y con -1 va retrocediendo en las paginas
            }
            $tabla.='</ul></nav>'; //concatenamos el final de la navegacion
            return $tabla; //retornamos toda la paginacion
        }
    }