<style>
    .modal-carga {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
    }

    .modal-carga-content {
        background: #fff;
        padding: 30px 40px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        font-weight: 600;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #e0e0e0;
        border-top: 5px solid #17a2b8;
        border-radius: 50%;
        animation: girar 1s linear infinite;
        margin: 0 auto 15px auto;
    }

    @keyframes girar {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR
    </h3>
    <p class="text-justify">
        Ingrese los datos del PROVEEDOR que son requeridos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>proveedor-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PROVEEDOR</a>
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
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/proveedorAjax.php" method="POST" data-form="save" autocomplete="off">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <!-- RUC-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_ruc" class="bmd-label-floating">RUC</label>
                            <input type="text" class="form-control" name="proveedor_ruc_reg" id="proveedor_ruc" maxlength="11" required>
                        </div>
                    </div>
                    <!-- Nombre-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" class="form-control"
                                name="proveedor_nombre_reg" id="proveedor_nombre" required>
                        </div>
                    </div>
                    <!-- Direccion-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" class="form-control"
                                name="proveedor_direccion_reg" id="proveedor_direccion">
                        </div>
                    </div>
                    <!-- País-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_pais" class="bmd-label-floating">País</label>
                            <input type="text" class="form-control"
                                name="proveedor_pais_reg" id="proveedor_pais">
                        </div>
                    </div>
                    <!-- Telefono-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" class="form-control" name="proveedor_telefono_reg"
                                id="proveedor_telefono" maxlength="20">
                        </div>
                    </div>
                    <!-- Email-->
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="proveedor_email" class="bmd-label-floating">Email</label>
                            <input type="email" class="form-control" name="proveedor_email_reg" 
                            id="proveedor_email">
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
    <!-- Modal Cargando -->
    <div id="modalCargaProveedor" class="modal-carga">
        <div class="modal-carga-content">
            <div class="spinner"></div>
            <p>Consultando RUC...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const inputRuc = document.getElementById('proveedor_ruc');
        const inputNombre = document.getElementById('proveedor_nombre');
        const inputDireccion = document.getElementById('proveedor_direccion');
        const modalCarga = document.getElementById('modalCargaProveedor');

        function mostrarCarga() {
            modalCarga.style.display = "flex";
        }

        function ocultarCarga() {
            modalCarga.style.display = "none";
        }

        // Solo números y máximo 11
        inputRuc.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });

        // Buscar RUC con ENTER
        inputRuc.addEventListener('keypress', function (e) {

            if (e.key === 'Enter') {
                e.preventDefault();

                let ruc = this.value.trim();

                if (ruc.length !== 11) {
                    alert("Ingrese un RUC válido de 11 dígitos");
                    return;
                }

                mostrarCarga();

                fetch("http://clientapi.sistemausqay.com/ruc.php?documento=" + ruc)
                    .then(res => res.json())
                    .then(data => {

                        ocultarCarga();

                        if (!data.razon_social) {
                            alert("RUC no encontrado");
                            return;
                        }

                        inputNombre.value = data.razon_social;
                        inputDireccion.value = data.direccion;

                    })
                    .catch(() => {
                        ocultarCarga();
                        alert("Error consultando RUC");
                    });

            }

        });

    });
</script>