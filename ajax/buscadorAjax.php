<?php
session_start(['name' => 'ppp']);//iniciamos sesion
require_once "../config/app.php";

if(isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){//inicial es para buscar usuario, eliminar es de cualquier form, fecha_inicio es para buscar las ventas, fecha_final es para buscar las ventas

    //array que tendra las vistas donde se redirecciona despues de cada busquesa
    $data_url = [
        "usuario" => "user-search",
        "cliente" => "client-search",
        "proveedor" => "proveedor-search",
        "item" => "item-search",
        "venta" => "venta-search"
    ];

    //verifica que el valor enviado sea de un form especifico, es decir segun lo que definimos
    if(isset($_POST['modulo'])){
        $modulo = $_POST['modulo'];

        //verificamos si lo que trae $modulo esta definido en el array $data_url
        if(!isset($data_url[$modulo])){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrio un error",
                "Texto"=> "Ha ocurrido un error, no se puede continuar con la busqueda",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }else{//no viene definido el valor de modulo desde el form
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Ocurrio un error",
            "Texto"=> "No se puede continuar con la busqueda debido a un error",
            "Tipo" => "error"
        ];
        echo json_encode($alerta);
        exit();
    }

    //condicional para crear y elimiar las variables de sesion, en este caso como se esta haciendo la busque da del usuario el valor de $modulo debe ser 'usuario'
    if($modulo == "venta"){//para las ventas el form de busqueda es diferente tiene 2 datos fecha inicion y fin
        $fecha_inicio = "fecha_inicio_".$modulo."";
        $fecha_final = "fecha_final_".$modulo."";

        //compraobacion para iniciar busqueda
        if(isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){

            //comprobmos que vengan definidos los 2 valores 
            if($_POST['fecha_inicio'] == "" || $_POST['fecha_inicio'] == ""){
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "Por favor introducir ambas fechas para proceder con la busqueda",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //si traen el valor definido y correcto
            $_SESSION[$fecha_inicio] = $_POST['fecha_inicio'];//le asignamos a las variables d esesion los valores que se envian
            $_SESSION[$fecha_final] = $_POST['fecha_final'];

            //eliminar la busqueda
            if(isset($_POST['eliminar_busqueda'])){
                unset($_SESSION[$fecha_inicio]);
                unset($_SESSION[$fecha_final]);
            }
        }
    }else{//para la busqueda en los demas form, clientes y usuarios (no ventas)
        $name_var = "busqueda_".$modulo;

        //iniciamos busqueda
        if(isset($_POST['busqueda_inicial'])){
            //comprobamos que venga definido el valor desde el form
            if($_POST['busqueda_inicial'] == ""){
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrio un error",
                    "Texto"=> "Por favor introducir untermino para proceder con la busqueda",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            //si viene definido el valor de busqueda entonces creamos los valores de sesion
            $_SESSION[$name_var] = $_POST['busqueda_inicial'];
        }

        //eliminar la busqueda
        if(isset($_POST['eliminar_busqueda'])){
            unset($_SESSION[$name_var]);
        }
    }

    //redireccionamos

    $url = $data_url[$modulo];

    $alerta = [
        "Alerta" => "redireccionar",
        "URL" => server_url.$url."/"
    ];
    echo json_encode($alerta);

}else{
    session_unset(); //se vacia la sesion
    session_destroy(); //se destruye la sesion
    header("Location: ". server_url. "login/");//se redirecciona al login
    exit();//dejamos de ejecutar codigo php
}