<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE
    </h3>
    <p class="text-justify">
        Ingrese los datos del CLIENTE que son requeridos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>client-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
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
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/clienteAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información requerida</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- DNI-->
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="cliente_dni" class="bmd-label-floating">DNI</label>
                            <input type="text" pattern="[0-9-]{1,27}" class="form-control" name="cliente_dni_reg"
                                id="cliente_dni" maxlength="27" required>
                        </div>
                    </div>
                    <!-- Nombre-->
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="cliente_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control"
                                name="cliente_nombre_reg" id="cliente_nombre" maxlength="40" required>
                        </div>
                    </div>
                    <!-- Apellido-->
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="cliente_apellido" class="bmd-label-floating">Apellido</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control"
                                name="cliente_apellido_reg" id="cliente_apellido" maxlength="40" required>
                        </div>
                    </div>
                    <!-- Telefono-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cliente_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{8,20}" class="form-control" name="cliente_telefono_reg"
                                id="cliente_telefono" maxlength="20" required>
                        </div>
                    </div>
                    <!-- Direccion-->
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="cliente_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control"
                                name="cliente_direccion_reg" id="cliente_direccion" maxlength="150" required>
                        </div>
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