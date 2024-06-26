<?php
    if ($_SESSION['privilegio_ppp'] != 1) { //para que no pueda entrar sin privilegio que no sean 1
        echo $lc -> forzar_cierre_sesion_controlador();
        exit();
    }
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO
    </h3>
    <p class="text-justify">
        Ingrese los datos del USUARIO que son requeridos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO
                USUARIO</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                USUARIOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
        </li>
    </ul>
</div>

<!-- Content -->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/usuarioAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información personal</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- DNI -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_dni" class="bmd-label-floating">DNI</label>
                            <input type="text" pattern="[0-9-]{8,20}" class="form-control" name="usuario_dni_reg"
                                id="usuario_dni" maxlength="9" required>
                        </div>
                    </div>
                    <!-- Nombre -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_nombre" class="bmd-label-floating">Nombres</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}" class="form-control"
                                name="usuario_nombre_reg" id="usuario_nombre" maxlength="35" required>
                        </div>
                    </div>
                    <!-- Apellido -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_apellido" class="bmd-label-floating">Apellidos</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}" class="form-control"
                                name="usuario_apellido_reg" id="usuario_apellido" maxlength="35" required>
                        </div>
                    </div>
                    <!-- Telefono -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{9,20}" class="form-control" name="usuario_telefono_reg"
                                id="usuario_telefono" maxlength="20" required>
                        </div>
                    </div>
                    <!-- Direccion -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control"
                                name="usuario_direccion_reg" id="usuario_direccion" maxlength="190" required>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend><i class="fas fa-user-lock"></i> &nbsp; Información de la cuenta</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_usuario">Nombre de usuario</label>
                            <input type="text" pattern="[a-zA-Z0-9]{1,35}" class="form-control"
                                name="usuario_usuario_reg" id="usuario_usuario" maxlength="35" required autocomplete="off">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_email" class="bmd-label-floating">Email</label>
                            <input type="email" class="form-control" name="usuario_email_reg" id="usuario_email"
                                maxlength="70" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_clave_1">Contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_1_reg" id="usuario_clave_1"
                                pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_clave_2" class="bmd-label-floating">Repetir contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_2_reg" id="usuario_clave_2"
                                pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend><i class="fas fa-medal"></i> &nbsp; Nivel de privilegio</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <p><span class="badge badge-info">Control total</span> Permisos para registrar,
                            actualizar y eliminar</p>
                        <p><span class="badge badge-success">Edición</span> Permisos para registrar y
                            actualizar</p>
                        <p><span class="badge badge-dark">Registrar</span> Solo permisos para registrar</p>
                        <div class="form-group">
                            <select class="form-control" name="usuario_privilegio_reg">
                                <option value="" selected="" disabled="">Seleccione una opción</option>
                                <option value="1">Control total</option>
                                <option value="2">Edición</option>
                                <option value="3">Registrar</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
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

    // Configurar campo de DNI
    setupNumberInput('usuario_dni', 8);

    setupNumberInput('usuario_telefono', 9);



});
</script>