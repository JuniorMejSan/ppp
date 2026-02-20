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
        Edita los campos que creas necesarios de la presentación seleccionadada. Recuerda que el código del item debe
        ser único, no se permiten duplicados. Además, ten en cuenta que el stock del item no se puede modificar desde
        esta sección, para actualizar el stock debes realizar una compra o venta que afecte la cantidad disponible.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>presentacion-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR
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
    <?php
    require_once "./controladores/itemControlador.php";

    $ins_item = new itemControlador();
    $datos_presentacion = $ins_item->datos_presentacion_controlador("Unico", $pagina[1]);
    if ($datos_presentacion->rowCount() == 1) {
        $campos = $datos_presentacion->fetch();

        ?>
        <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/itemAjax.php" method="POST"
            data-form="update" autocomplete="off">

            <input type="hidden" name="presentacion_id_up" value="<?php echo $pagina[1]; ?>">

            <fieldset>
                <legend><i class="far fa-edit"></i> &nbsp; Actualizar item</legend>

                <div class="container-fluid">
                    <div class="row justify-content-center">

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="presentacion_descripcion" class="bmd-label-floating">
                                    Descripción de la presentación
                                </label>

                                <input type="text" class="form-control" name="presentacion_descripcion_up" id="presentacion_descripcion"
                                    maxlength="140" required value="<?php echo $campos['descripcion']; ?>">
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