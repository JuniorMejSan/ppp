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
    }