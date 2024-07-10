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

    //funcion para buscar cliente por su DNI
    function buscar_cliente(){

        let input_cliente = document.querySelector('#input_cliente').value; //seleccionamos un elemento del dom mediante un selector
        input_cliente = input_cliente.trim(); //quita espacios en los extremos

        //si el input viene vacio
        if(input_cliente != ""){

            let tabla_clientes = document.querySelector('#tabla_clientes');
            tabla_clientes.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';

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

        }
    }//fin de funcion

    //funcion para ir a agregar nuevo cliente
    function clienteNuevo(){
        window.location.href = '<?php echo server_url; ?>client-new/';
    }

    //funcion para agregar cliente a la venta
    function agregar_cliente(id){

        $('#ModalCliente').modal('hide');

        Swal.fire({//boton de confirmacion de la alerta
            title: '¿Quieres agregar este cliente?',
            text: 'Se agregara el cliente para realizar la venta',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f06a11',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, agregar',
            cancelButtonText: 'No, Cancelar'
        }).then((result) => {
            if(result.value) {
                let datos = new FormData(); //array de datos del cliente a buscar
                datos.append("id_agregar_cliente", id);//le asignamos los valores

                fetch("<?php echo server_url ?>ajax/ventaAjax.php", {
                    method: 'POST',
                    body:datos
                }) //se envia la url y las configuraciones
                .then(respuesta => respuesta.json())//llega el dato desde el html
                .then(respuesta => {
                    return alertas_ajax(respuesta);
                })
            }else{
                $('#ModalCliente').modal('show');
            }
        });
    }//fin de funcion

    //funcion para buscar item por su codigo o nombre
    function buscar_item(){
    let input_item = document.querySelector('#input_item').value.trim();
    
    if(input_item != ""){
        let tabla_items = document.querySelector('#tabla_items');
        tabla_items.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';
        
        let datos = new FormData();
        datos.append("buscar_item", input_item);

        fetch("<?php echo server_url ?>ajax/ventaAjax.php", {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            tabla_items.innerHTML = respuesta;
        })
        .catch(error => {
            console.error('Error:', error);
            tabla_items.innerHTML = '<div class="alert alert-danger">Error al buscar. Intente nuevamente.</div>';
        });
    }
}


    document.querySelector('#input_item').addEventListener('input', debounce(buscar_item, 300));

    document.querySelector('#input_cliente').addEventListener('input', debounce(buscar_cliente, 300));

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    //funcion para modela del item a la venta
    function modal_agregar_item(id){

        //ocultamos del modal de busqueda
        $('#ModalItem').modal('hide');
        //mostramos el modal de agregar item
        $('#ModalAgregarItem').modal('show');

        //quiero darle el valor del id del item a un input html lo hacemos mediante el id de dicho input
        document.querySelector('#id_agregar_item').setAttribute("value", id); //value es un atributo para colocarle un valor a un input

    }//fin de funcion

    //funcion para cerrar el modal de agregar y abrir el de buscar el item
    function modal_buscar_item(id){

        //ocultamos del modal de busqueda
        $('#ModalAgregarItem').modal('hide');
        //mostramos el modal de agregar item
        $('#ModalItem').modal('show');

    }//fin de funcion

    function verDetallesVenta(ventaId) {
    // Hacer una petición AJAX para obtener los detalles de la venta
    $.ajax({
        url: '../ajax/VentaAjax.php',
        method: 'POST',
        data: { accion: 'obtenerDetallesVenta', venta_id: ventaId },
        success: function(response) {
            // Cargar los detalles en el modal
            $('#detallesVentaContent').html(response);
            // Mostrar el modal
            $('#modalDetallesVenta').modal('show');
        },
        error: function() {
            alert('Error al cargar los detalles de la venta');
        }
    });
}
    
</script>