<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-chart-pie"></i> &nbsp; REPORTE DE COMPRAS
    </h3>
    <p class="text-justify">
        FILTRO AVANZADO DE COMPRAS
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>compra-reporte/"><i class="fas fa-chart-pie"></i> &nbsp; REPORTE</a>
        </li>
    </ul>
</div>

<!-- Formulario de filtros -->
<div class="container-fluid">
    <form class="form-neon" id="formularioReporteCompras" method="POST" autocomplete="off">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="reporte_fecha_inicio">Fecha Inicio</label>
                        <input type="date" class="form-control" name="reporte_fecha_inicio" id="reporte_fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="reporte_fecha_fin">Fecha Fin</label>
                        <input type="date" class="form-control" name="reporte_fecha_fin" id="reporte_fecha_fin" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="reporte_medio_pago">Medio de Pago</label>
                        <select class="form-control" name="reporte_medio_pago" id="reporte_medio_pago">
                            <option value="">-- TODOS --</option>
                            <?php
                                require_once "./controladores/compraControlador.php";
                                $ins_compra = new compraControlador();
                                $metodos = $ins_compra->obtener_metodos_pago();
                                foreach ($metodos as $metodo) {
                                    echo '<option value="'.$metodo['id_medio_pago'].'">'.$metodo['descripcion'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="reporte_estado">Estado</label>
                        <select class="form-control" name="reporte_estado" id="reporte_estado">
                            <option value="">-- TODOS --</option>
                            <option value="Pagado">Pagada</option>
                            <option value="Devuelto">Devuelta</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="button" class="btn btn-raised btn-info" onclick="filtrar_reporte_compras(1)">
                            <i class="fas fa-search"></i> &nbsp; FILTRAR
                        </button>
                        <button type="button" class="btn btn-raised btn-danger" onclick="limpiar_filtros_reporte_compras()">
                            <i class="fas fa-sync-alt"></i> &nbsp; LIMPIAR
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Tabla de resultados -->
<div class="container-fluid" style="margin-top: 30px;">
    <div id="contenedor_reporte_compras">
        <!-- Los resultados se cargarán aquí -->
    </div>
</div>

<!-- Modal para detalles de compra -->
<div class="modal fade" id="modalDetallesCompra" tabindex="-1" role="dialog" aria-labelledby="modalDetallesCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetallesCompraLabel"><strong>Detalles de la Compra</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detallesCompraContent">
                <!-- Aquí se cargarán los detalles de la compra -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
    include_once "./vistas/inc/reporte_compras.php";
?>
