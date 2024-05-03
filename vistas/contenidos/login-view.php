<div class="login-container">
    <div class="login-content">
        <p class="text-center">
            <i class="fas fa-user-circle fa-5x"></i>
        </p>
        <p class="text-center">
            Inicia sesión con tu cuenta
        </p>
        <form action="" method="POST">
            <div class="form-group">
                <label for="UserName"><i class="fas fa-user-secret" ></i> &nbsp;
                    Usuario</label>
                <input type="text" class="form-control" id="userName" name="usuario" pattern="[a-zA-Z0-9]{1,35}"
                    maxlength="35" required="" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="UserPassword"><i class="fas fa-key"></i> &nbsp;
                    Contraseña</label>
                <input type="password" class="form-control" id="userPassword" name="clave"
                    pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="" autocomplete="off">
            </div>
            <button type="submit" class="btn-login text-center">LOG IN</button>
        </form>
    </div>
</div>