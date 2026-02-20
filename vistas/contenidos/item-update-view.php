<?php
if ($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2) {//verificamos si tiene los permisos necesarios
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR ITEM
    </h3>
    <p class="text-justify">
        Edita los campos que creas necesarios del item seleccionado. Recuerda que el código del item debe ser único, no se permiten duplicados. Además, ten en cuenta que el stock del item no se puede modificar desde esta sección, para actualizar el stock debes realizar una compra o venta que afecte la cantidad disponible.
    </p>
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
            <a href="<?php echo server_url; ?>item-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                LISTA DE
                ITEMS INHABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <?php
    require_once "./controladores/itemControlador.php";

    $ins_item = new itemControlador();
    $datos_item = $ins_item->datos_item_controlador("Unico", $pagina[1]);

    if ($datos_item->rowCount() == 1) {
        $campos = $datos_item->fetch();

        // lista de presentaciones habilitadas
        $presentaciones = itemControlador::listar_presentaciones_habilitadas();
        ?>
        <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/itemAjax.php" method="POST"
            data-form="update" autocomplete="off">

            <input type="hidden" name="item_id_up" value="<?php echo $pagina[1]; ?>">

            <fieldset>
                <legend><i class="far fa-edit"></i> &nbsp; Actualizar item</legend>

                <div class="container-fluid">
                    <div class="row">

                        <!-- FILA 1 -->
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="item_codigo" class="bmd-label-floating">Código</label>
                                <input type="text" class="form-control" name="item_codigo_up" id="item_codigo"
                                    maxlength="45" required value="<?php echo $campos['item_codigo']; ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="item_nombre" class="bmd-label-floating">Nombre</label>
                                <input type="text" class="form-control" name="item_nombre_up" id="item_nombre"
                                    maxlength="140" required value="<?php echo $campos['item_nombre']; ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="item_presentacion" class="bmd-label-floating">Presentación</label>
                                <select class="form-control" name="item_presentacion_up" id="item_presentacion" required>
                                    <option value="" disabled>Seleccione...</option>
                                    <?php
                                    foreach ($presentaciones as $p) {
                                        $selected = (!empty($campos['id_presentacion']) && $campos['id_presentacion'] == $p["id_presentacion"])
                                            ? 'selected'
                                            : '';
                                        echo '<option value="' . $p["id_presentacion"] . '" ' . $selected . '>' . $p["descripcion"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- FILA 2 -->
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="item_precio" class="bmd-label-floating">Precio venta</label>
                                <input type="number" step="0.01" class="form-control" name="item_precio_up" id="item_precio"
                                    required value="<?php echo $campos['item_precio']; ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="item_precio_compra" class="bmd-label-floating">Precio compra</label>
                                <input type="number" step="0.01" class="form-control" name="item_precio_compra_up"
                                    id="item_precio_compra" required value="<?php echo $campos['item_precio_compra']; ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="item_fecha_vencimiento" class="bmd-label-floating">Fecha vencimiento</label>
                                <input type="date" class="form-control" name="item_fecha_vencimiento_up"
                                    id="item_fecha_vencimiento"
                                    value="<?php echo !empty($campos['item_fecha_vencimiento']) ? $campos['item_fecha_vencimiento'] : ''; ?>">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label for="item_estado" class="bmd-label-floating">Estado</label>
                                <select class="form-control" name="item_estado_up" id="item_estado" required>
                                    <option value="Habilitado" <?php if ($campos['item_estado'] == 'Habilitado') {
                                        echo 'selected';
                                    } ?>>Habilitado</option>
                                    <option value="Deshabilitado" <?php if ($campos['item_estado'] == 'Deshabilitado') {
                                        echo 'selected';
                                    } ?>>Deshabilitado</option>
                                </select>
                            </div>
                        </div>

                        <!-- FILA 3 -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="item_detalle" class="bmd-label-floating">Detalle</label>
                                <input type="text" class="form-control" name="item_detalle_up" id="item_detalle"
                                    maxlength="190" value="<?php echo $campos['item_detalle']; ?>">
                            </div>
                        </div>

                        <!-- STOCK (solo lectura) -->
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="item_stock" class="bmd-label-floating">Stock</label>
                                <input type="number" class="form-control" name="item_stock_up" id="item_stock" readonly
                                    value="<?php echo $campos['item_stock']; ?>">
                            </div>
                        </div>

                    </div>
                </div>
            </fieldset>

            <br><br><br>
            <p class="text-center" style="margin-top: 40px;">
                <button type="submit" class="btn btn-raised btn-success btn-sm">
                    <i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR
                </button>
            </p>
        </form>

    <?php } else { ?>
        <div class="alert alert-danger text-center" role="alert">
            <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
            <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
            <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
        </div>
    <?php } ?>
</div>