<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
    </h3>
    <p class="text-justify">
        SISTEMA DE PUNTO DE VENTA Y CONTROL DE INVENTARIO PARA FARMACIA.
    </p>
</div>

<!-- Alerta de productos próximos a vencer -->
<?php
    require_once "./controladores/itemControlador.php";
    $ins_item_alerta = new itemControlador();
    $productos_por_vencer = $ins_item_alerta->items_proximos_vencer_controlador();
    if(count($productos_por_vencer) > 0):
?>
<div class="full-box" style="margin-bottom: 15px; padding: 0 15px;">
    <div style="background-color: #fff3cd; border-left: 5px solid #ff9800; border-radius: 5px; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-triangle fa-2x" style="color: #ff9800;"></i>
            <div>
                <strong style="color: #856404; font-size: 16px;">¡Atención!</strong>
                <p style="margin: 0; color: #856404;">Tiene <strong><?php echo count($productos_por_vencer); ?></strong> producto(s) próximo(s) a vencer o ya vencido(s).</p>
            </div>
        </div>
        <a href="<?php echo server_url; ?>item-vencimiento/" style="background-color: #ff9800; color: #fff; padding: 8px 18px; border-radius: 4px; text-decoration: none; font-weight: bold; white-space: nowrap;">
            <i class="fas fa-eye fa-fw"></i> Verificar aquí
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Content -->
<div class="full-box tile-container">
    <?php
        require_once "./controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();

        $total_clientes = $ins_cliente -> datos_cliente_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>client-list/" class="tile">
        <div class="tile-tittle">Clientes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?php echo $total_clientes -> rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/itemControlador.php";
        $ins_item = new itemControlador();

        $total_item = $ins_item -> datos_item_controlador("Conteo", 0);
    ?>

    <a href="<?php echo server_url; ?>item-list/" class="tile">
        <div class="tile-tittle">Inventario</div>
        <div class="tile-icon">
            <i class="fas fa-pallet fa-fw"></i>
            <p><?php echo $total_item -> rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/ventaControlador.php";
        $ins_venta = new ventaControlador();

        $total_venta = $ins_venta -> datos_venta_controlador("Conteo", 0);
    ?>

    <a href="<?php echo server_url; ?>venta-new/" class="tile">
        <div class="tile-tittle">Ventas</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p><?php echo $total_venta -> rowCount(); ?> Registradas</p>
        </div>
    </a>


    <?php 
        if($_SESSION['privilegio_ppp'] == 1){//control de modulos a los que tienen acceso los direntes tipos de usuarios 

            require_once "./controladores/usuarioControlador.php";//para poder mostrar el conteo de usuario registrados
            $ins_usuario = new usuarioControlador();//instanciamos
            $total_usuarios = $ins_usuario -> datos_usuario_controlador("Conteo", 0);//como es solo de conteo, el parametro del id lo colocamos en 0
        
    ?>
    <a href="<?php echo server_url; ?>user-list/" class="tile">
        <div class="tile-tittle">Usuarios</div>
        <div class="tile-icon">
            <i class="fas fa-user-secret fa-fw"></i>
            <p><?php echo $total_usuarios -> rowCount(); ?> Registrados</p>
        </div>
    </a>
    <?php } ?>

    <?php
        require_once "./controladores/proveedorControlador.php";
        $ins_proveedor = new proveedorControlador();

        $total_proveedores = $ins_proveedor -> datos_proveedor_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>proveedor-list/" class="tile">
        <div class="tile-tittle">Proveedores</div>
        <div class="tile-icon">
            <i class="fa fa-truck"></i>
            <p><?php echo $total_proveedores -> rowCount(); ?> Registrados</p>
        </div>
    </a>
    
    <?php
        require_once "./controladores/compraControlador.php";
        $ins_compra = new compraControlador();

        $total_compras = $ins_compra -> datos_compra_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>compra-new/" class="tile">
        <div class="tile-tittle">COMPRAS</div>
        <div class="tile-icon">
            <i class="fas fa-store-alt fa-fw"></i>
            <p><?php echo $total_compras -> rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/itemControlador.php";
        $ins_presentacion = new itemControlador();

        $total_presentaciones = $ins_presentacion -> datos_presentacion_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>presentacion-list/" class="tile">
        <div class="tile-tittle">Presentaciones</div>
        <div class="tile-icon">
            <i class="fas fa-boxes fa-fw"></i>
            <p><?php echo $total_presentaciones -> rowCount(); ?> Registradas</p>
        </div>
    </a>

    <?php
        require_once "./controladores/medioControlador.php";
        $ins_medio_pago = new medioControlador();

        $total_medios_pago = $ins_medio_pago -> datos_medio_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>medio-list/" class="tile">
        <div class="tile-tittle">Medios de Pago</div>
        <div class="tile-icon">
            <i class="fas fa-credit-card fa-fw"></i>
            <p><?php echo $total_medios_pago -> rowCount(); ?> Registrados</p>
        </div>
    </a>

    <a href="<?php echo server_url; ?>reporte-ventas/" class="tile">
        <div class="tile-tittle">Reporte de Ventas</div>
        <div class="tile-icon">
            <i class="fas fa-chart-bar fa-fw"></i>
            <p>Generar Reportes</p>
        </div>
    </a>

    <a href="<?php echo server_url; ?>reporte-compras/" class="tile">
        <div class="tile-tittle">Reporte de Compras</div>
        <div class="tile-icon">
            <i class="fas fa-chart-line fa-fw"></i>
            <p>Generar Reportes</p>
        </div>
    </a>

</div>