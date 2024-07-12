<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; REALIZADAS
    </h3>
    <p class="text-justify">
        GESTION DE COMPRAS REALIZADAS
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>compra-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA COMPRA</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>compra-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                LISTADO</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>compra-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>compra-reporte/"><i class="fas fa-chart-pie"></i> &nbsp; GRAFICOS - REPORTES</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
<?php
    require_once "./controladores/compraControlador.php";

    //instanciamos el controlador
    $ins_compra = new compraControlador();

    echo $ins_compra->paginador_compra_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], "", "", "");
?>
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
    include_once "./vistas/inc/compra.php";
?>