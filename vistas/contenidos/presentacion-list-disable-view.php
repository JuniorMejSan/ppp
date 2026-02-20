<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS
    </h3>
    <p class="text-justify">
        Listado de los ITEMS con sus respectivos datos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>presentacion-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR
                PRESENTACION</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>presentacion-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                PRESENTACIONES HABILITADAS</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>presentacion-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                PRESENTACIONES INHABILITADAS</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
<?php
    require_once "./controladores/itemControlador.php";

    //instanciamos el controlador
    $ins_item = new itemControlador();

    echo $ins_item->paginador_presentacion_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], 'Inhabilitado');
?>
</div>