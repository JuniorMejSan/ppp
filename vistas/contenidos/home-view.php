<style>
.modulo-card {
    background: #fafafa;
    border-radius: 8px;
    padding: 18px 12px;
    text-align: center;
    border: 1px solid #eee;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}
.modulo-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    background: #fff;
}
</style>

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

<!-- Alerta de productos con stock bajo -->
<?php
    $items_stock_bajo = $ins_item_alerta->items_stock_bajo_controlador();
    if(count($items_stock_bajo) > 0):
?>
<div class="full-box" style="margin-bottom: 15px; padding: 0 15px;">
    <div style="background-color: #f8d7da; border-left: 5px solid #dc3545; border-radius: 5px; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-box-open fa-2x" style="color: #dc3545;"></i>
            <div>
                <strong style="color: #721c24; font-size: 16px;">¡Stock bajo!</strong>
                <p style="margin: 0; color: #721c24;">Tiene <strong><?php echo count($items_stock_bajo); ?></strong> producto(s) con stock bajo o agotado(s).</p>
            </div>
        </div>
        <a href="<?php echo server_url; ?>item-stock/" style="background-color: #dc3545; color: #fff; padding: 8px 18px; border-radius: 4px; text-decoration: none; font-weight: bold; white-space: nowrap;">
            <i class="fas fa-eye fa-fw"></i> Verificar aquí
        </a>
    </div>
</div>
<?php endif; ?>

<?php
    require_once "./controladores/clienteControlador.php";
    $ins_cliente = new clienteControlador();
    $total_clientes = $ins_cliente->datos_cliente_controlador("Conteo", 0);

    require_once "./controladores/itemControlador.php";
    $ins_item = new itemControlador();
    $total_item = $ins_item->datos_item_controlador("Conteo", 0);

    require_once "./controladores/ventaControlador.php";
    $ins_venta = new ventaControlador();
    $total_venta = $ins_venta->datos_venta_controlador("Conteo", 0);

    require_once "./controladores/proveedorControlador.php";
    $ins_proveedor = new proveedorControlador();
    $total_proveedores = $ins_proveedor->datos_proveedor_controlador("Conteo", 0);

    require_once "./controladores/compraControlador.php";
    $ins_compra = new compraControlador();
    $total_compras = $ins_compra->datos_compra_controlador("Conteo", 0);

    require_once "./controladores/itemControlador.php";
    $ins_presentacion = new itemControlador();
    $total_presentaciones = $ins_presentacion->datos_presentacion_controlador("Conteo", 0);

    require_once "./controladores/medioControlador.php";
    $ins_medio_pago = new medioControlador();
    $total_medios_pago = $ins_medio_pago->datos_medio_controlador("Conteo", 0);

    $ingresos_medios = $ins_venta->ingresos_por_medio_pago_controlador();
    $total_general = 0;
    foreach($ingresos_medios as $m){ $total_general += $m['total_ventas']; }
?>

<!-- SECCIÓN: Ingresos del Día -->
<div style="margin: 0 15px 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border: 1px solid #e0e0e0;">
    <div style="padding: 14px 20px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-money-bill-wave" style="color: #555; font-size: 18px;"></i>
        <h5 style="margin: 0; color: #333; font-weight: 600;">Ingresos del Día</h5>
        <span style="margin-left: auto; color: #888; font-size: 13px;"><?php echo date('d/m/Y'); ?></span>
    </div>
    <div style="padding: 18px 20px;">
        <div class="row" style="align-items: stretch;">
            <!-- Total general -->
            <div class="col-12 col-md-3" style="margin-bottom: 12px;">
                <div style="background: #f5f5f5; border-radius: 6px; padding: 18px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center; border: 1px solid #e0e0e0;">
                    <p style="margin: 0; color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Total del Día</p>
                    <p style="margin: 6px 0 0; font-size: 28px; font-weight: bold; color: #333;">
                        <?php echo moneda . ' ' . number_format($total_general, 2); ?>
                    </p>
                </div>
            </div>
            <!-- Desglose por medio de pago -->
            <?php foreach($ingresos_medios as $medio): ?>
            <div class="col-6 col-md-3" style="margin-bottom: 12px;">
                <div style="background: #fafafa; border-radius: 6px; padding: 14px; text-align: center; height: 100%; border: 1px solid #eee;">
                    <i class="fas fa-credit-card" style="color: #777; font-size: 14px;"></i>
                    <p style="margin: 6px 0 2px; font-weight: 600; color: #444; font-size: 13px;"><?php echo htmlspecialchars($medio['medio_pago']); ?></p>
                    <p style="margin: 0; font-size: 20px; font-weight: bold; color: #333;">
                        <?php echo moneda . ' ' . number_format($medio['total_ventas'], 2); ?>
                    </p>
                    <p style="margin: 4px 0 0; color: #999; font-size: 11px;"><?php echo $medio['cantidad_ventas']; ?> venta(s)</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- SECCIÓN: Módulos del Sistema -->
<div style="margin: 0 15px 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border: 1px solid #e0e0e0;">
    <div style="padding: 14px 20px; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-th-large" style="color: #555; font-size: 18px;"></i>
        <h5 style="margin: 0; color: #333; font-weight: 600;">Módulos del Sistema</h5>
    </div>
    <div style="padding: 18px 15px;">
        <div class="row">

            <!-- Clientes -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>client-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-users fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Clientes</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_clientes->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registrados</p>
                    </div>
                </a>
            </div>

            <!-- Inventario -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>item-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-pallet fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Inventario</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_item->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registrados</p>
                    </div>
                </a>
            </div>

            <!-- Ventas -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>venta-new/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-hand-holding-usd fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Ventas</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_venta->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registradas</p>
                    </div>
                </a>
            </div>

            <!-- Usuarios (solo admin) -->
            <?php if($_SESSION['privilegio_ppp'] == 1):
                require_once "./controladores/usuarioControlador.php";
                $ins_usuario = new usuarioControlador();
                $total_usuarios = $ins_usuario->datos_usuario_controlador("Conteo", 0);
            ?>
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>user-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-user-secret fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Usuarios</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_usuarios->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registrados</p>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <!-- Proveedores -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>proveedor-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fa fa-truck fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Proveedores</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_proveedores->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registrados</p>
                    </div>
                </a>
            </div>

            <!-- Compras -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>compra-new/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-store-alt fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Compras</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_compras->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registradas</p>
                    </div>
                </a>
            </div>

            <!-- Presentaciones -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>presentacion-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-boxes fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Presentaciones</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_presentaciones->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registradas</p>
                    </div>
                </a>
            </div>

            <!-- Medios de Pago -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>medio-list/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-credit-card fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Medios de Pago</h6>
                        <p style="margin: 0; color: #333; font-size: 20px; font-weight: bold;"><?php echo $total_medios_pago->rowCount(); ?></p>
                        <p style="margin: 0; color: #aaa; font-size: 11px;">Registrados</p>
                    </div>
                </a>
            </div>

            <!-- Reporte de Ventas -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>reporte-ventas/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-chart-bar fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Rpt. Ventas</h6>
                        <p style="margin: 4px 0 0; color: #aaa; font-size: 11px;">Generar Reportes</p>
                    </div>
                </a>
            </div>

            <!-- Reporte de Compras -->
            <div class="col-6 col-md-4 col-lg-3" style="margin-bottom: 14px;">
                <a href="<?php echo server_url; ?>reporte-compras/" style="text-decoration: none;">
                    <div class="modulo-card">
                        <i class="fas fa-chart-line fa-2x" style="color: #555;"></i>
                        <h6 style="margin: 10px 0 4px; color: #333; font-weight: 600; font-size: 13px;">Rpt. Compras</h6>
                        <p style="margin: 4px 0 0; color: #aaa; font-size: 11px;">Generar Reportes</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>