<script  type="text/javascript" src="<?php echo server_url; ?>vistas/js/googlecharts.js"></script>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; GRAFICOS - ITEMS
    </h3>
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
            <a href="<?php echo server_url; ?>venta-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>venta-reporte/"><i class="fas fa-chart-pie"></i> &nbsp; GRAFICOS - REPORTES</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div id="total_ventasxmes" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-md-6">
            <div id="ventas_estado" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-md-6">
            <div id="venta_mediopago" style="width: 100%; height: 500px;"></div>
        </div>
        
    </div>
</div>

<?php
    include_once "./vistas/inc/venta.php";
?>