<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR VENTAS POR FECHA
    </h3>
    <p class="text-justify">
        GESTION DE VENTAS
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADAS</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>venta-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR
                POR FECHA</a>
        </li>
    </ul>
</div>

<?php
if(!isset($_SESSION['fecha_inicio_venta']) && empty($_SESSION['fecha_inicio_venta']) && !isset($_SESSION['fecha_final_venta']) && empty($_SESSION['fecha_final_venta'])){//si no esta definida o no existe se muestra el formulario para inicial la busqueda 

?>

<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
    <input type="hidden" name="modulo" value="venta">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="busqueda_inicio_venta">Fecha inicial (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_inicio"
                            id="busqueda_inicio_venta" maxlength="30">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="busqueda_final_venta">Fecha final (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_final"
                            id="busqueda_final_venta" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp;
                            BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
}else{//si ya viene definida se muestran los resultados

?>

<div class="container-fluid">
    <form class="FormularioAjax" action="<?php echo server_url; ?>/ajax/buscadorAjax.php" method="POST" data-form="search" autocomplete="off">
    <input type="hidden" name="modulo" value="venta">
    <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Fecha de busqueda: <strong><?php echo date("d-m-Y", strtotime($_SESSION['fecha_inicio_venta'])); ?> &nbsp; a &nbsp; <?php echo date("d-m-Y", strtotime($_SESSION['fecha_final_venta'])); ?></strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp;
                            ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="container-fluid">
<?php
    require_once "./controladores/ventaControlador.php";

    //instanciamos el controlador
    $ins_venta = new ventaControlador();

    echo $ins_venta->paginador_venta_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], 'Busqueda', $_SESSION['fecha_inicio_venta'], $_SESSION['fecha_final_venta']);
?>
</div>
<?php

}
?>
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