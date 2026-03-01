<script>
    // Función para filtrar reporte de compras
    function filtrar_reporte_compras(pagina) {
        let fecha_inicio = document.querySelector('#reporte_fecha_inicio').value;
        let fecha_fin = document.querySelector('#reporte_fecha_fin').value;
        let medio_pago = document.querySelector('#reporte_medio_pago').value;
        let estado = document.querySelector('#reporte_estado').value;
        
        let contenedor = document.querySelector('#contenedor_reporte_compras');
        contenedor.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>';
        
        let datos = new FormData();
        datos.append("accion", "reporte_compras_filtrado");
        datos.append("pagina", pagina);
        datos.append("fecha_inicio", fecha_inicio);
        datos.append("fecha_fin", fecha_fin);
        datos.append("medio_pago", medio_pago);
        datos.append("estado", estado);
        
        fetch("<?php echo server_url ?>ajax/compraAjax.php", {
            method: 'POST',
            body: datos
        })
        .then(respuesta => respuesta.text())
        .then(respuesta => {
            contenedor.innerHTML = respuesta;
        })
        .catch(error => {
            console.error('Error:', error);
            contenedor.innerHTML = '<div class="alert alert-danger">Error al cargar el reporte. Intente nuevamente.</div>';
        });
    }
    
    // Función para limpiar filtros
    function limpiar_filtros_reporte_compras() {
        document.querySelector('#reporte_fecha_inicio').value = '';
        document.querySelector('#reporte_fecha_fin').value = '';
        document.querySelector('#reporte_medio_pago').value = '';
        document.querySelector('#reporte_estado').value = '';
        document.querySelector('#contenedor_reporte_compras').innerHTML = '';
    }

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
    
    // Carga automática del reporte al cargar la página con filtros vacíos
    document.addEventListener('DOMContentLoaded', function() {
        filtrar_reporte_compras(1);
    });
</script>
