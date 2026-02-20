<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PRESENTACIÓN
    </h3>
    <p class="text-justify">
        Ingrese los datos de la PRESENTACIÓN que son requeridos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>presentacion-new/"><i class="fas fa-plus fa-fw"></i> &nbsp;
                AGREGAR
                PRESENTACION</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>presentacion-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                LISTA DE
                PRESENTACIONES HABILITADAS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>presentacion-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                PRESENTACIONES INHABILITADAS</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/itemAjax.php" method="POST"
                data-form="save" autocomplete="off">

                <fieldset>
                    <legend class="text-center">
                        <i class="far fa-plus-square"></i> &nbsp; Información de la Presentación
                    </legend>

                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="presentacion_nombre" class="bmd-label-floating">Descripción</label>
                                    <input type="text" class="form-control" name="presentacion_descripcion_reg"
                                        id="presentacion_nombre" maxlength="140" required>
                                </div>
                            </div>

                        </div>
                    </div>
                </fieldset>

                <br>

                <p class="text-center">
                    <button type="reset" class="btn btn-raised btn-secondary btn-sm">
                        <i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR
                    </button>
                    &nbsp; &nbsp;
                    <button type="submit" class="btn btn-raised btn-info btn-sm">
                        <i class="far fa-save"></i> &nbsp; GUARDAR
                    </button>
                </p>

            </form>

        </div>
    </div>
</div>