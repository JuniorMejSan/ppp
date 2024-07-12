<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADAS
    </h3>
    <p class="text-justify">
        GESTION DE VENTAS FINALIZADAS
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>venta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                FINALIZADAS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-reporte/"><i class="fas fa-chart-pie"></i> &nbsp; GRAFICOS - REPORTES</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
<?php
    require_once "./controladores/ventaControlador.php";

    //instanciamos el controlador
    $ins_venta = new ventaControlador();

    echo $ins_venta->paginador_venta_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], "", "", "");
?>
</div>

<!-- Modal para detalles de venta -->
<div class="modal fade" id="modalDetallesVenta" tabindex="-1" role="dialog" aria-labelledby="modalDetallesVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetallesVentaLabel"><strong>Detalles de la Venta</strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detallesVentaContent">
                <!-- Aquí se cargarán los detalles de la venta -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<?php
    include_once "./vistas/inc/venta.php";
?>