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
            <a href="<?php echo server_url; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS HABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS INHABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>item-reporte/"><i class="fas fa-chart-pie"></i> &nbsp; GRAFICOS - REPORTES</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div id="grafico_Top5" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-md-6">
            <div id="grafico_masIngresos" style="width: 100%; height: 500px;"></div>
        </div>
        <div class="col-md-6">
            <div id="grafico_stock" style="width: 100%; height: 500px;"></div>
        </div>
        
    </div>
</div>

<?php
    include_once "./vistas/inc/item.php";
?>