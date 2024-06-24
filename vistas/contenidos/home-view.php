<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fab fa-dashcube fa-fw"></i> &nbsp; DASHBOARD
    </h3>
    <p class="text-justify">
        SISTEMA WEB PARA LA GESTIÓN DEL KARDEX Y VENTA DE EQUIPOS TECNOLÓGICOS
    </p>
</div>

<!-- Content -->
<div class="full-box tile-container">
    <?php
        require_once "./controladores/clienteControlador.php";
        $ins_cliente = new clienteControlador();

        $total_clientes = $ins_cliente -> datos_cliente_controlador("Conteo", 0);
    ?>
    <a href="<?php echo server_url; ?>client-new/" class="tile">
        <div class="tile-tittle">Clientes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?php echo $total_clientes -> rowCount(); ?> Registrados</p>
        </div>
    </a>

    <a href="<?php echo server_url; ?>item-list/" class="tile">
        <div class="tile-tittle">Inventario</div>
        <div class="tile-icon">
            <i class="fas fa-pallet fa-fw"></i>
            <p>9 Registrados</p>
        </div>
    </a>


    <a href="<?php echo server_url; ?>venta-pending/" class="tile">
        <div class="tile-tittle">Ventas</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p>200 Registrados</p>
        </div>
    </a>

    <a href="<?php echo server_url; ?>venta-list/" class="tile">
        <div class="tile-tittle">Finalizados</div>
        <div class="tile-icon">
            <i class="fas fa-clipboard-list fa-fw"></i>
            <p>700 Registrados</p>
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

    <a href="<?php echo server_url; ?>empresa/" class="tile">
        <div class="tile-tittle">Empresa</div>
        <div class="tile-icon">
            <i class="fas fa-store-alt fa-fw"></i>
            <p>1 Registrada</p>
        </div>
    </a>

</div>