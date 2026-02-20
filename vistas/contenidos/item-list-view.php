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
            <a href="<?php echo server_url; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS HABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-list-disable/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                ITEMS INHABILITADOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
<?php
    require_once "./controladores/itemControlador.php";

    //instanciamos el controlador
    $ins_item = new itemControlador();

    echo $ins_item->paginador_item_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $pagina[0], "", 'Habilitado');
?>
</div>