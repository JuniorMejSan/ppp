<?php
    if($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2){//verificamos si tiene los permisos necesarios
        echo $lc -> forzar_cierre_sesion_controlador();
        exit();
    }
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PROVEEDOR
    </h3>
    <p class="text-justify">
        Edite los campos que desea actualizar.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>proveedor-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>proveedor-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PROVEEDORES</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>proveedor-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PROVEEDOR</a>
        </li>
    </ul>
</div>

<!-- Content here-->
<div class="container-fluid">
    <?php
        require_once "./controladores/proveedorControlador.php";

        $ins_proveedor = new proveedorControlador();

        $datos_proveedor = $ins_proveedor -> datos_proveedor_controlador("Unico", $pagina[1]);

        if($datos_proveedor -> rowCount() == 1){
            
            //todos los datos del cliente seleccionado
            $campos = $datos_proveedor -> fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/proveedorAjax.php" method="POST" data-form="update" autocomplete="off">
    <input type="hidden" name="proveedor_id_up" value="<?php echo $pagina[1]; ?>">     
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- RUC-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_ruc" class="bmd-label-floating">RUC</label>
                            <input type="text" pattern="\d{11}" class="form-control" name="proveedor_ruc_up"
                                id="proveedor_ruc" maxlength="11" value="<?php echo $campos['proveedor_ruc']; ?>" required>
                        </div>
                    </div>
                    <!-- Nombre-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control"
                                name="proveedor_nombre_up" id="proveedor_nombre" maxlength="40" value="<?php echo $campos['proveedor_nombre']; ?>" required>
                        </div>
                    </div>
                    <!-- Direccion-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control"
                                name="proveedor_direccion_up" id="proveedor_direccion" maxlength="40" value="<?php echo $campos['proveedor_direccion']; ?>">
                        </div>
                    </div>
                    <!-- País-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_pais" class="bmd-label-floating">País</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="proveedor_pais_up"
                                id="proveedor_pais" maxlength="40" required value="<?php echo $campos['proveedor_pais']; ?>">
                        </div>
                    </div>
                    <!-- Telefono-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{8,20}" class="form-control"
                                name="proveedor_telefono_up" id="proveedor_telefono" maxlength="20" required value="<?php echo $campos['proveedor_telefono']; ?>">
                        </div>
                    </div>
                    <!-- Email-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_email" class="bmd-label-floating">Email</label>
                            <input type="email" class="form-control" name="proveedor_email_up" id="proveedor_email"
                                maxlength="70" value=" <?php echo $campos['proveedor_email']; ?> ">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center" style="margin-top: 40px;">
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp;
                ACTUALIZAR</button>
        </p>
    </form>
    <?php
        }else{
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
    <?php
        }
    ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Función para filtrar y limitar la entrada a solo números y máximo caracteres
    function setupNumberInput(inputId, maxLength) {
        var input = document.getElementById(inputId);
        
        input.addEventListener('input', function (e) {
            // Reemplaza todo lo que no sea un dígito con una cadena vacía
            this.value = this.value.replace(/\D/g, '');
            // Limita la longitud al máximo especificado
            if (this.value.length > maxLength) {
                this.value = this.value.slice(0, maxLength);
            }
        });
    }

    // Configurar campo de RUC
    setupNumberInput('proveedor_ruc', 11);

    // Configurar campo de teléfono
    setupNumberInput('proveedor_telefono', 9);

});
</script>