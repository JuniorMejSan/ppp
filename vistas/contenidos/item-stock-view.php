<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-box-open fa-fw" style="color: #dc3545;"></i> &nbsp; PRODUCTOS CON STOCK BAJO
    </h3>
    <p class="text-justify">
        Listado de productos con stock bajo o agotados (10 unidades o menos).
    </p>
</div>

<?php
    require_once "./controladores/itemControlador.php";
    $ins_item = new itemControlador();
    $items_stock_bajo = $ins_item->items_stock_bajo_controlador();
?>

<?php if(count($items_stock_bajo) > 0): ?>
<div class="full-box" style="padding: 0 15px 15px 15px;">
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>CÓDIGO</th>
                    <th>PRODUCTO</th>
                    <th>PRESENTACIÓN</th>
                    <th>PRECIO</th>
                    <th>STOCK ACTUAL</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php $cont = 1; foreach($items_stock_bajo as $prod): ?>
                <tr class="text-center">
                    <td><?php echo $cont; ?></td>
                    <td><?php echo htmlspecialchars($prod['item_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($prod['item_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($prod['presentacion']); ?></td>
                    <td><?php echo moneda . ' ' . htmlspecialchars($prod['item_precio']); ?></td>
                    <td>
                        <?php if($prod['item_stock'] <= 0): ?>
                            <span class="badge" style="background-color: #f44336; color: #fff;">0 - Agotado</span>
                        <?php elseif($prod['item_stock'] <= 5): ?>
                            <span class="badge" style="background-color: #ff9800; color: #fff;"><?php echo $prod['item_stock']; ?> unidades</span>
                        <?php else: ?>
                            <span class="badge" style="background-color: #ffc107; color: #333;"><?php echo $prod['item_stock']; ?> unidades</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($prod['item_stock'] <= 0): ?>
                            <span style="color: #f44336; font-weight: bold;"><i class="fas fa-times-circle"></i> Agotado</span>
                        <?php elseif($prod['item_stock'] <= 5): ?>
                            <span style="color: #ff9800; font-weight: bold;"><i class="fas fa-exclamation-circle"></i> Crítico</span>
                        <?php else: ?>
                            <span style="color: #ffc107; font-weight: bold;"><i class="fas fa-arrow-down"></i> Bajo</span>
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
        <i class="fas fa-check-circle fa-fw"></i> Todos los productos tienen stock suficiente.
    </div>
</div>
<?php endif; ?>
