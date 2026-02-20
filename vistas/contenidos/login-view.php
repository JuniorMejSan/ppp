<?php
if (isset($_POST['usuario_log']) && isset($_POST['clave_log'])) {
    require_once "./controladores/loginControlador.php";
    $ins_login = new loginControlador;
    echo $ins_login->iniciar_sesion_controlador();
}
?>

<div class="login-container">
    <div class="login-content">
        <div class="login-header">
            <p class="text-center login-icon">
                <i class="fas fa-user-circle"></i>
            </p>
            <p class="text-center login-title">Inicia sesión</p>
            <p class="text-center login-subtitle">Ingresa con tu cuenta</p>
        </div>

        <form action="" method="POST" autocomplete="off" autocapitalize="off" spellcheck="false">
            <div class="form-group">
                <label for="userName">
                    <i class="fas fa-user-secret"></i> &nbsp; Usuario
                </label>
                <br>

                <div class="input-wrap">
                    <input type="text" class="form-control" id="userName" name="usuario_log" pattern="[a-zA-Z0-9]{1,35}"
                        maxlength="35" required placeholder="Tu usuario" autocomplete="off" autocapitalize="off"
                        autocorrect="off">
                </div>
            </div>

            <div class="form-group">
                <label for="userPassword">
                    <i class="fas fa-key"></i> &nbsp; Contraseña
                </label>
                <br>

                <div class="input-wrap">
                    <input type="password" class="form-control" id="userPassword" name="clave_log"
                        pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required placeholder="Tu contraseña"
                        autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn-login text-center">LOG IN</button>
        </form>
    </div>
</div>