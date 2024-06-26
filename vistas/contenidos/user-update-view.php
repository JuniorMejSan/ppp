<!-- Page header -->
<?php  
    if($lc -> encryption($_SESSION['id_ppp']) != $pagina[1]){//comprabar si el id encriptado que pasa la url es igual al id del user logueado, se coloca $pagina[1] por que en la url la posicion 1 es la que trae el id encriptado
        if($_SESSION['privilegio_ppp'] != 1){//verificamos si tiene los permisos necesarios
            echo $lc -> forzar_cierre_sesion_controlador();
            exit();
        }
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR USUARIO
    </h3>
    <p class="text-justify">
        Edite los campos que desea actualizar.
    </p>
</div>

<?php if($_SESSION['privilegio_ppp'] == 1){ //esta parte solo se muestra si el usuario es admin?>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
        </li>
    </ul>
</div>
<?php }?>

<!-- Content -->
<div class="container-fluid">
    <?php //seleccion de datos y comprobacion si existen en la bd

        require_once "./controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();//instanciamos

        $datos_usuario = $ins_usuario -> datos_usuario_controlador("Unico", $pagina[1]);
        
        if($datos_usuario -> rowCount() == 1){//existe usaurio con el id en la bd
            $campos = $datos_usuario -> fetch();//con fetch llenamos el array $campos con todos los datos del usuario
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/usuarioAjax.php" method="POST" data-form="update" autocomplete="off">
    <!-- Input que contiene el id del usuario a actualizar -->
    <input type="hidden" name="usuario_id_up" value="<?php echo $pagina[1]; ?>"> 
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información personal</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- DNI -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_dni" class="bmd-label-floating">DNI</label>
                            <input type="text" pattern="[0-9-]{1,20}" class="form-control" name="usuario_dni_up"
                                id="usuario_dni" maxlength="20" value=" <?php echo $campos['dni']; ?> ">
                        </div>
                    </div>
                    <!-- Nombre -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_nombre" class="bmd-label-floating">Nombres</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control"
                                name="usuario_nombre_up" id="usuario_nombre" maxlength="35" value=" <?php echo $campos['nombre']; ?> ">
                        </div>
                    </div>
                    <!-- Apellido -->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_apellido" class="bmd-label-floating">Apellidos</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control"
                                name="usuario_apellido_up" id="usuario_apellido" maxlength="35" value=" <?php echo $campos['apellido']; ?>">
                        </div>
                    </div>
                    <!-- Telefono -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{8,20}" class="form-control" name="usuario_telefono_up"
                                id="usuario_telefono" maxlength="20" value=" <?php echo $campos['telefono']; ?>">
                        </div>
                    </div>
                    <!-- Direccion -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control"
                                name="usuario_direccion_up" id="usuario_direccion" maxlength="190" value=" <?php echo $campos['direccion']; ?>">
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
                    <!-- Nombre de usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_usuario" class="bmd-label-floating">Nombre de usuario</label>
                            <input type="text" pattern="[a-zA-Z0-9 ]{1,35}" class="form-control"
                                name="usuario_usuario_up" id="usuario_usuario" maxlength="35" value=" <?php echo $campos['user']; ?> ">
                        </div>
                    </div>
                    <!-- Correo de usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_email" class="bmd-label-floating">Email</label>
                            <input type="email" class="form-control" name="usuario_email_up" id="usuario_email"
                                maxlength="70" value=" <?php echo $campos['email']; ?> ">
                        </div>
                    </div>
                    
                    <!-- Estado del usuario (solo administrador) -->
                    <?php
                    if($_SESSION['privilegio_ppp'] == 1 && $campos['idUsuario'] != 1){ //editar estado de usuario solo se muestra para admin, ademas la cuenta del asmin no se puede deshabiitar
                    ?>
                    <div class="col-12">
                        <div class="form-group">
                            <span>Estado de la cuenta &nbsp; <?php if($campos['estado'] == 'Activa'){ //muestra el estado de la cuenta en la bd
                                echo '<span class="badge badge-info">Activa</span>';
                            }else{
                                echo '<span class="badge badge-danger">Deshabilitada</span>';
                            } ?> </span>
                            <select class="form-control" name="usuario_estado_up">
                                <option value="Activa" <?php if($campos['estado'] == 'Activa'){ echo 'selected=""'; } //coloca en el select el estado que tiene el usuario en la bd ?>>Activa</option>
                                <option value="Deshabilitada" <?php if($campos['estado'] == 'Deshabilitada'){ echo 'selected=""'; } //coloca en el select el estado que tiene el usuario en la bd?>>Deshabilitada</option>
                            </select>
                        </div>
                    </div>
                    <?php 
                    }
                    ?>    
                </div>
            </div>
        </fieldset>
        <br><br><br>
        <fieldset>
            <legend style="margin-top: 40px;"><i class="fas fa-lock"></i> &nbsp; Nueva contraseña</legend>
            <p>Para actualizar la contraseña de esta cuenta ingrese una nueva y vuelva a escribirla. En caso que no
                desee actualizarla debe dejar vacíos los dos campos de las contraseñas.</p>
            <div class="container-fluid">
                <div class="row">
                    <!-- Clave 1 de usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_clave_nueva_1" class="bmd-label-floating">Contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_nueva_1"
                                id="usuario_clave_nueva_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                    <!-- Clave 2 de usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_clave_nueva_2" class="bmd-label-floating">Repetir contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_nueva_2"
                                id="usuario_clave_nueva_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        <!-- Nivel de privilegio de usuario -->
        </fieldset>
        <?php
        if ($_SESSION['privilegio_ppp'] == 1 && $campos['idUsuario'] != 1) { //editar privilegios de usuario solo se muestra para admin, ademas la cuenta del admin no puede cambiar de privilegio
        ?>
        <br><br><br>
        <fieldset>
            <legend><i class="fas fa-medal"></i> &nbsp; Nivel de privilegio</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <p><span class="badge badge-info">Control total</span> Permisos para registrar, actualizar y
                            eliminar</p>
                        <p><span class="badge badge-success">Edición</span> Permisos para registrar y actualizar</p>
                        <p><span class="badge badge-dark">Registrar</span> Solo permisos para registrar</p>
                        <div class="form-group">
                            <select class="form-control" name="usuario_privilegio_up">
                                <option value="1" <?php if($campos['privilegio'] == 1){ echo 'selected=""'; } ?>>Control total <?php if($campos['privilegio'] == 1){ echo '(Actual)'; } ?></option>

                                <option value="2" <?php if($campos['privilegio'] == 2){ echo 'selected=""'; } ?>>Edición <?php if($campos['privilegio'] == 2){ echo '(Actual)'; } ?></option>

                                <option value="3" <?php if($campos['privilegio'] == 3){ echo 'selected=""'; } ?>>Registrar <?php if($campos['privilegio'] == 3){ echo '(Actual)'; } ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <?php
        }
        ?>
        <br><br><br>
        <fieldset>
            <p class="text-center">Para poder guardar los cambios en esta cuenta debe de ingresar su nombre de usuario y
                contraseña</p>
            <div class="container-fluid">
                <div class="row">
                    <!-- Usuario del admin para poder editar usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="usuario_admin" class="bmd-label-floating">Nombre de usuario</label>
                            <input type="text" pattern="[a-zA-Z0-9]{1,35}" class="form-control" name="usuario_admin"
                                id="usuario_admin" maxlength="35" required="">
                        </div>
                    </div>
                    <!-- Contraseña del admin para poder editar usuario -->
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="clave_admin" class="bmd-label-floating">Contraseña</label>
                            <input type="password" class="form-control" name="clave_admin" id="clave_admin"
                                pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <?php if($lc -> encryption($_SESSION['id_ppp']) != $pagina[1]){ //condicional para verificar si el id le pertenece al usaurio que está editando ?>
        <input type="hidden" name = "tipo_cuenta" value="Impropia"> <!-- No le pertenece la cuenta -->
        <?php }else{ ?>
        <input type="hidden" name = "tipo_cuenta" value="Propia"> <!-- Le pertenece la cuenta -->
        <?php } ?>
        <p class="text-center" style="margin-top: 40px;">
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp;
                ACTUALIZAR</button>
        </p>
    </form>
    <?php
        }else{//si no existe el usuario en la bd se muestra el error de abajo
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

    // Configurar campo de DNI
    setupNumberInput('usuario_dni', 8);

    setupNumberInput('usuario_telefono', 9);
});
</script>