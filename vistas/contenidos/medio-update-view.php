<?php
if ($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2) {//verificamos si tiene los permisos necesarios
    echo $lc->forzar_cierre_sesion_controlador();
    exit();
}
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR MEDIO DE PAGO
    </h3>
    <p class="text-justify">
        Edita los campos que creas necesarios del medio de pago seleccionado. Recuerda que la descripción del medio de pago debe
        ser única, no se permiten duplicados.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>medio-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR
                MEDIO DE PAGO</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>medio-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                LISTA DE MEDIOS DE PAGO HABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>medio-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                MEDIOS DE PAGO INHABILITADOS</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <?php
    require_once "./controladores/medioControlador.php";

    $ins_medio = new medioControlador();
    $datos_medio = $ins_medio->datos_medio_controlador("Unico", $pagina[1]);
    if ($datos_medio->rowCount() == 1) {
        $campos = $datos_medio->fetch();

        ?>
        <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/medioAjax.php" method="POST"
            data-form="update" autocomplete="off">

            <input type="hidden" name="medio_id_up" value="<?php echo $pagina[1]; ?>">

            <fieldset>
                <legend><i class="far fa-edit"></i> &nbsp; Actualizar medio de pago</legend>

                <div class="container-fluid">
                    <div class="row justify-content-center">

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="medio_descripcion" class="bmd-label-floating">
                                    Descripción del medio de pago
                                </label>

                                <input type="text" class="form-control" name="medio_descripcion_up" id="medio_descripcion"
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