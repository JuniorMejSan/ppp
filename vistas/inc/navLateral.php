<section class="full-box nav-lateral">
    <div class="full-box nav-lateral-bg show-nav-lateral"></div>
    <div class="full-box nav-lateral-content">
        <figure class="full-box nav-lateral-avatar">
            <i class="far fa-times-circle show-nav-lateral"></i>
            <img src="<?php echo server_url; ?>vistas/assets/img/usqay_logo2.png" class="img-fluid" alt="Avatar">
            <figcaption class="roboto-medium text-center">
                <?php echo $_SESSION['nombre_ppp']." ".$_SESSION['apellido_ppp']; ?> <br><small class="roboto-condensed-light"><?php echo $_SESSION['usuario_ppp']; ?></small>
            </figcaption>
        </figure>
        <nav class="full-box nav-lateral-menu">
            <ul>
                <li>
                    <a href="<?php echo server_url; ?>home/"><i class="fab fa-dashcube fa-fw"></i> &nbsp; Dashboard</a>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Clientes <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo server_url; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Cliente</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                clientes</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-pallet fa-fw"></i> &nbsp; Inventario <i
                            class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo server_url; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar item</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                Inventario</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar item</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp;
                        Ventas <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo server_url; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nueva venta</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>venta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                                Finalizadas</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>venta-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar
                                por fecha</a>
                        </li>
                    </ul>
                </li>
                <?php if($_SESSION['privilegio_ppp'] == 1){//control de modulos a los que tienen acceso los direntes tipos de usuarios ?>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp; Usuarios <i
                            class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo server_url; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                usuarios</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar usuario</a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fa fa-truck"></i> &nbsp; Proveedores <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="<?php echo server_url; ?>proveedor-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Proveedores</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>proveedor-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                Proveedores</a>
                        </li>
                        <li>
                            <a href="<?php echo server_url; ?>proveedor-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Proveedores</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo server_url; ?>empresa/"><i class="fas fa-store-alt fa-fw"></i> &nbsp; Empresa</a>
                </li>
            </ul>
        </nav>
    </div>
</section>