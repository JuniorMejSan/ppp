<?php
    if($_SESSION['privilegio_ppp'] < 1 || $_SESSION['privilegio_ppp'] > 2){//verificamos si tiene los permisos necesarios
        echo $lc -> forzar_cierre_sesion_controlador();
        exit();
    }
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CLIENTE
    </h3>
    <p class="text-justify">
        Edite los campos que desea actualizar.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>client-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
        </li>
    </ul>
</div>

<!-- Content here-->
<div class="container-fluid">
    <?php
        require_once "./controladores/clienteControlador.php";

        $ins_cliente = new clienteControlador();

        $datos_cliente = $ins_cliente -> datos_cliente_controlador("Unico", $pagina[1]);

        if($datos_cliente -> rowCount() == 1){
            
            //todos los datos del cliente seleccionado
            $campos = $datos_cliente -> fetch();
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/clienteAjax.php" method="POST" data-form="update" autocomplete="off">
    <input type="hidden" name="cliente_id_up" value="<?php echo $pagina[1]; ?>">     
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- DNI-->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="cliente_dni" class="bmd-label-floating">DNI</label>
                            <input type="text" pattern="[0-9-]{1,27}" class="form-control" name="cliente_dni_up"
                                id="cliente_dni" maxlength="8" value="<?php echo $campos['cliente_dni']; ?>">
                        </div>
                    </div>
                    <!-- Nombre-->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="cliente_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control"
                                name="cliente_nombre_up" id="cliente_nombre" maxlength="40" value="<?php echo $campos['cliente_nombre']; ?>">
                        </div>
                    </div>
                    <!-- Apellido-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cliente_apellido" class="bmd-label-floating">Apellido</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control"
                                name="cliente_apellido_up" id="cliente_apellido" maxlength="40" value="<?php echo $campos['cliente_apellido']; ?>">
                        </div>
                    </div>
                    <!-- Telefono-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cliente_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{8,20}" class="form-control" name="cliente_telefono_up"
                                id="cliente_telefono" maxlength="9" value="<?php echo $campos['cliente_telefono']; ?>">
                        </div>
                    </div>
                    <!-- Direccion-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cliente_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control"
                                name="cliente_direccion_up" id="cliente_direccion" maxlength="150" value="<?php echo $campos['cliente_direccion']; ?>">
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
    setupNumberInput('cliente_dni', 8);

    // Configurar campo de teléfono
    setupNumberInput('cliente_telefono', 9);

});
</script>