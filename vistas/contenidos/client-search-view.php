<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE
    </h3>
    <p class="text-justify">
        Por favor, a continuación ingrese el DNI del CLIENTE que desea buscar
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
            <a class="active" href="<?php echo server_url; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR
                CLIENTE</a>
        </li>
    </ul>
</div>

<!-- Content here-->
<!-- busqueda -->
<?php
if(!isset($_SESSION['busqueda_cliente']) && empty($_SESSION['busqueda_cliente'])){//si no esta definida o no existe se muestra el formulario para inicial la busqueda 

?>
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo server_url; ?>/ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
    <input type="hidden" name="modulo" value="cliente">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <!-- Termino de busqueda -->
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Qué cliente estas buscando?</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp;
                            BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}else{//si ya viene definida se muestran los resultados

?>
<!-- eliminar busqueda -->
<div class="container-fluid">
    <form class="FormularioAjax" action="<?php echo server_url; ?>/ajax/buscadorAjax.php" method="POST" data-form="search" autocomplete="off">
        <input type="hidden" name="modulo" value="cliente">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda_cliente']; ?>”</strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i>
                            &nbsp; ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- tabla de Resultados -->
<div class="container-fluid"> 
<?php 
    require_once "./controladores/clienteControlador.php";

    //intanciamos el controlador
    $ins_cliente = new clienteControlador();

    echo $ins_cliente -> paginador_cliente_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], $_SESSION['busqueda_cliente']);
?>
</div>
<?php

}
?>

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
    setupNumberInput('inputSearch', 8);

});
</script>