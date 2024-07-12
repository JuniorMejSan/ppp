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
    setupNumberInput('input_proveedor', 11);

    });

    //funcion para buscar cliente por su DNI
    function buscar_proveedor(){

        let input_proveedor= document.querySelector('#input_proveedor').value; //seleccionamos un elemento del dom mediante un selector
        input_proveedor= input_proveedor.trim(); //quita espacios en los extremos

        //si el input viene vacio
        if(input_proveedor!= ""){

            let tabla_proveedor = document.querySelector('#tabla_proveedor');
            tabla_proveedor.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';

            let datos = new FormData(); //array de datos del cliente a buscar
            datos.append("buscar_proveedor", input_proveedor);//le asignamos los valores

            fetch("<?php echo server_url ?>ajax/compraAjax.php", {
                method: 'POST',
                body:datos
            }) //se envia la url y las configuraciones
                .then(respuesta => respuesta.text())//llega el dato desde el html
                .then(respuesta => {
                    let tabla_proveedor = document.querySelector('#tabla_proveedor'); //capturamos la tabla donde se mostraran los datos
                    tabla_proveedor.innerHTML = respuesta;
                })

        }
    }//fin de funcion

    //funcion para ir a agregar nuevo proveedor
    function proveedorNuevo(){
        window.location.href = '<?php echo server_url; ?>proveedor-new/';
    }

    //funcion para agregar proveedor a la venta
    function agregar_proveedor(id){

        $('#ModalProveedor').modal('hide');

        Swal.fire({//boton de confirmacion de la alerta
            title: '¿Quieres agregar este proveedor?',
            text: 'Se agregara el proveedor para realizar la venta',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f06a11',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, agregar',
            cancelButtonText: 'No, Cancelar'
        }).then((result) => {
            if(result.value) {
                let datos = new FormData(); //array de datos del proveedor a buscar
                datos.append("id_agregar_proveedor", id);//le asignamos los valores

                fetch("<?php echo server_url ?>ajax/compraAjax.php", {
                    method: 'POST',
                    body:datos
                }) //se envia la url y las configuraciones
                .then(respuesta => respuesta.json())//llega el dato desde el html
                .then(respuesta => {
                    return alertas_ajax(respuesta);
                })
            }else{
                $('#Modalproveedor').modal('show');
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

        fetch("<?php echo server_url ?>ajax/compraAjax.php", {
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

    document.querySelector('#input_proveedor').addEventListener('input', debounce(buscar_proveedor, 300));

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

    //funcion para agregar un nuevo item
    function itemNuevo(){
        window.location.href = '<?php echo server_url; ?>item-new/';
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

    function verDetallesCompra(compraId) {
    // Hacer una petición AJAX para obtener los detalles de la compra
    $.ajax({
        url: '../ajax/compraAjax.php',
        method: 'POST',
        data: { accion: 'obtenerDetallesCompra', compra_id: compraId },
        success: function(response) {
            // Cargar los detalles en el modal
            $('#detallesCompraContent').html(response);
            // Mostrar el modal
            $('#modalDetallesCompra').modal('show');
        },
        error: function() {
            alert('Error al cargar los detalles de la compra');
        }
    });
}


function modal_editar_cantidad(id, cantidad_actual) {
    // Llenar los campos del modal con los datos actuales
    document.querySelector('#id_editar_item').setAttribute("value", id);
    document.querySelector('#detalle_cantidad_editar').value = cantidad_actual;

    // Mostrar el modal
    $('#ModalEditarCantidad').modal('show');
}
    
</script>

<script type="text/javascript">
  google.charts.load('current', {packages: ['corechart']});
  
  google.charts.setOnLoadCallback(drawColumnChart_comprasxmes);
  google.charts.setOnLoadCallback(drawComprasEstadoChart);
  google.charts.setOnLoadCallback(drawComprasMetodosPagoChart);

  function drawColumnChart_comprasxmes() {
    $.ajax({
        url: '../ajax/compraAjax.php',
        method: 'POST',
        data: { accion: 'obtener_datos_compras_mes' },
        success: function(response) {
            const data = JSON.parse(response);
            const dataArray = [["Mes", "Total Comprado S/ ", { role: "style" }]];
            
            // Array de nombres de meses
            const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                           "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            
            data.forEach(item => {
                const nombreMes = meses[item.mes - 1]; // Obtener nombre del mes
                dataArray.push([nombreMes, parseFloat(item.total_compras), "color: #FF9933"]);
            });

            const dataTable = google.visualization.arrayToDataTable(dataArray);
            const view = new google.visualization.DataView(dataTable);
            view.setColumns([0, 1,
                             { calc: "stringify",
                               sourceColumn: 1,
                               type: "string",
                               role: "annotation" },
                             2]);

            const options = {
                title: "Salidas por Mes",
                titleTextStyle: {
                    fontSize: 16,
                    alignment: 'center'
                },
                width: 600,
                height: 400,
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
                hAxis: {
                    title: 'Mes',
                    titleTextStyle: {
                        fontSize: 16,
                        italic: false
                    }
                },
                vAxis: {
                    title: 'Total Comprado - S/.',
                    titleTextStyle: {
                        fontSize: 16,
                        italic: false
                    }
                }
            };

            const chart = new google.visualization.ColumnChart(document.getElementById("total_compraxmes"));
            chart.draw(view, options);
        },
        error: function() {
            alert('Error al cargar los datos del gráfico de columnas');
        }
    });
}

function drawComprasEstadoChart() {
            $.ajax({
                url: '../ajax/compraAjax.php',
                method: 'POST',
                data: { accion: 'obtener_datos_compras_estado' },
                success: function(response) {
                    const data = JSON.parse(response);

                    // Preparar los datos para el gráfico
                    const dataArray = [
                        ['Estado', 'Cantidad'],
                        ['Compras Finalizadas', parseFloat(data.cantidad_pagado)],
                        ['Compras Devueltas', parseFloat(data.cantidad_devuelto)]
                    ];

                    // Crear la tabla de datos de Google Charts
                    const dataTable = google.visualization.arrayToDataTable(dataArray);

                    // Opciones del gráfico de pastel
                    const options = {
                        title: 'Compras Finalizadas vs Devueltas',
                        titleTextStyle: {
                            fontSize: 16,
                            alignment: 'center'
                        },
                        width: 600,
                        height: 400,
                        pieSliceText: 'percentage',
                        slices: {
                            0: { color: '#FF9933' }, // Color para Compras Finalizadas
                            1: { color: '#FFCC99' }  // Color para Compras Devueltas (más claro)
                        }
                    };

                    // Crear el gráfico de pastel
                    const chart = new google.visualization.PieChart(document.getElementById('compra_estado'));
                    chart.draw(dataTable, options);
                },
                error: function() {
                    alert('Error al cargar los datos del gráfico de compras finalizadas vs devueltas');
                }
            });
        }

        function drawComprasMetodosPagoChart() {
            $.ajax({
                url: '../ajax/compraAjax.php',
                method: 'POST',
                data: { accion: 'obtener_datos_compras_metodo_pago' },
                success: function(response) {
                    const data = JSON.parse(response);

                    // Preparar los datos para el gráfico
                    const dataArray = [['Método de Pago', 'Cantidad de Compras', 'Total de Compras']];
                    data.forEach(item => {
                        dataArray.push([item.metodo_pago, parseInt(item.cantidad_compras), parseFloat(item.total_compras)]);
                    });

                    // Crear la tabla de datos de Google Charts
                    const dataTable = google.visualization.arrayToDataTable(dataArray);

                    // Opciones del gráfico de pastel
                    const options = {
                        title: 'Ventas por Método de Pago',
                        titleTextStyle: {
                            fontSize: 16,
                            alignment: 'center'
                        },
                        width: 600,
                        height: 400,
                        pieHole: 0.4
                    };

                    // Crear el gráfico de pastel
                    const chart = new google.visualization.PieChart(document.getElementById('compra_mediopago'));
                    chart.draw(dataTable, options);
                },
                error: function() {
                    alert('Error al cargar los datos del gráfico de ventas por método de pago');
                }
            });
        }



</script>