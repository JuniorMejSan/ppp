<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE MEDIOS DE PAGO
    </h3>
    <p class="text-justify">
        Listado de los MEDIOS DE PAGO con sus respectivos datos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>medio-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR
                MEDIO DE PAGO</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>medio-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                MEDIOS DE PAGO HABILITADOS</a>
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

    //instanciamos el controlador
    $ins_medio = new medioControlador();

    echo $ins_medio->paginador_medio_controlador($pagina[1], 15, $_SESSION['privilegio_ppp'], $pagina[0], 'Habilitado');
?>
</div>