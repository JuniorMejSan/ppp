<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM
    </h3>
    <p class="text-justify">
        Ingrese los datos del ITEM que son requeridos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR
                ITEM</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS HABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS INHABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/itemAjax.php" method="POST"
        data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del item</legend>
            <div class="container-fluid">
                <div class="row">

                    <!-- FILA 1 -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_codigo" class="bmd-label-floating">Código</label>
                            <input type="text" class="form-control" name="item_codigo_reg" id="item_codigo"
                                maxlength="45" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" class="form-control" name="item_nombre_reg" id="item_nombre"
                                maxlength="140" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="item_presentacion" class="bmd-label-floating">Presentación</label>
                            <select class="form-control" name="item_presentacion_reg" id="item_presentacion" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <?php
                                require_once "./controladores/itemControlador.php";
                                $presentaciones = itemControlador::listar_presentaciones_habilitadas();
                                foreach ($presentaciones as $p) {
                                    echo '<option value="' . $p["id_presentacion"] . '">' . $p["descripcion"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- FILA 2 -->
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="item_precio" class="bmd-label-floating">Precio venta</label>
                            <input type="number" step="0.01" class="form-control" name="item_precio_reg"
                                id="item_precio" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="item_precio_compra" class="bmd-label-floating">Precio compra</label>
                            <input type="number" step="0.01" class="form-control" name="item_precio_compra_reg"
                                id="item_precio_compra" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="item_fecha_vencimiento" class="bmd-label-floating">Fecha vencimiento</label>
                            <input type="date" class="form-control" name="item_fecha_vencimiento_reg"
                                id="item_fecha_vencimiento">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="item_estado" class="bmd-label-floating">Estado</label>
                            <select class="form-control" name="item_estado_reg" id="item_estado" required>
                                <option value="Habilitado">Habilitado</option>
                                <option value="Deshabilitado">Deshabilitado</option>
                            </select>
                        </div>
                    </div>

                    <!-- FILA 3 -->
                    <div class="col-12">
                        <div class="form-group">
                            <label for="item_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" class="form-control" name="item_detalle_reg" id="item_detalle"
                                maxlength="190">
                        </div>
                    </div>

                    <!-- STOCK OCULTO -->
                    <div style="display:none;">
                        <input type="number" name="item_stock_reg" id="item_stock" value="0">
                    </div>

                </div>
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center" style="margin-top: 40px;">
            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp;
                LIMPIAR</button>
            &nbsp; &nbsp;
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp;
                GUARDAR</button>
        </p>
    </form>
</div>