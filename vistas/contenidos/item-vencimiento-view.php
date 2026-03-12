<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-exclamation-triangle fa-fw" style="color: #ff9800;"></i> &nbsp; PRODUCTOS PRÓXIMOS A VENCER
    </h3>
    <p class="text-justify">
        Listado de productos que están próximos a vencer o que ya han vencido (15 días o menos).
    </p>
</div>

<?php
    require_once "./controladores/itemControlador.php";
    $ins_item = new itemControlador();
    $productos_por_vencer = $ins_item->items_proximos_vencer_controlador();
?>

<?php if(count($productos_por_vencer) > 0): ?>
<div class="full-box" style="padding: 0 15px 15px 15px;">
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>CÓDIGO</th>
                    <th>PRODUCTO</th>
                    <th>PRESENTACIÓN</th>
                    <th>STOCK</th>
                    <th>FECHA VENCIMIENTO</th>
                    <th>DÍAS RESTANTES</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php $cont = 1; foreach($productos_por_vencer as $prod): ?>
                <tr class="text-center">
                    <td><?php echo $cont; ?></td>
                    <td><?php echo htmlspecialchars($prod['item_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($prod['item_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($prod['presentacion']); ?></td>
                    <td><?php echo htmlspecialchars($prod['item_stock']); ?></td>
                    <td><?php echo htmlspecialchars($prod['item_fecha_vencimiento']); ?></td>
                    <td>
                        <?php if($prod['dias_restantes'] <= 0): ?>
                            <span class="badge" style="background-color: #f44336; color: #fff;">VENCIDO</span>
                        <?php elseif($prod['dias_restantes'] <= 7): ?>
                            <span class="badge" style="background-color: #ff9800; color: #fff;"><?php echo $prod['dias_restantes']; ?> días</span>
                        <?php else: ?>
                            <span class="badge" style="background-color: #ffc107; color: #333;"><?php echo $prod['dias_restantes']; ?> días</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($prod['dias_restantes'] <= 0): ?>
                            <span style="color: #f44336; font-weight: bold;"><i class="fas fa-times-circle"></i> Vencido</span>
                        <?php elseif($prod['dias_restantes'] <= 7): ?>
                            <span style="color: #ff9800; font-weight: bold;"><i class="fas fa-exclamation-circle"></i> Urgente</span>
                        <?php else: ?>
                            <span style="color: #ffc107; font-weight: bold;"><i class="fas fa-clock"></i> Próximo</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php $cont++; endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="full-box" style="padding: 15px;">
    <div class="alert alert-success text-center" role="alert">
        <i class="fas fa-check-circle fa-fw"></i> No hay productos próximos a vencer en los próximos 15 días.
    </div>
</div>
<?php endif; ?>
