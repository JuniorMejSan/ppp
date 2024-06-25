<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Función para filtrar y limitar la entrada a solo números y máximo caracteres
    function setupNumberInput(inputId, maxLength) {
        var input = document.getElementById(inputId);
        
        input.addEventListener('input', function (e) {
            // Reemplaza todo lo que no sea un dígito con una cadena vacía
            this.value = this.value.replace(/\D/g, '');
            // Limita la longitud al máximo especificado
            if (this.value.length > maxLength) {
                this.value = this.value.slice(0, maxLength);
            }
        });
    }

    // Configurar campo de DNI
    setupNumberInput('input_cliente', 8);


    });

    function buscar_cliente(){

        let input_cliente = document.querySelector('#input_cliente').value; //seleccionamos un elemento del dom mediante un selector
        input_cliente = input_cliente.trim(); //quita espacios en los extremos

        //si el input viene vacio
        if(input_cliente != ""){

            let datos = new FormData(); //array de datos del cliente a buscar
            datos.append("buscar_cliente", input_cliente);//le asignamos los valores

            fetch("<?php echo server_url ?>ajax/ventaAjax.php", {
                method: 'POST',
                body:datos
            }) //se envia la url y las configuraciones
                .then(respuesta => respuesta.text())//llega el dato desde el html
                .then(respuesta => {
                    let tabla_clientes = document.querySelector('#tabla_clientes'); //capturamos la tabla donde se mostraran los datos
                    tabla_clientes.innerHTML = respuesta;
                })

        }else{ //mostramos alerta para que ingrese termino de busqueda
            Swal.fire({
            title: 'Ocurrio un error',
            text: 'Debes introducir un DNI',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        }
    }
</script>