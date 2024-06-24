<?php
    if ($_SESSION['privilegio_ppp'] != 1) { //para que no pueda entrar sin privilegios mayor a 1
        echo $lc -> forzar_cierre_sesion_controlador();
        exit();
    }
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS
    </h3>
    <p class="text-justify">
        Listado de los USUARIOS con sus respectivos datos.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo server_url; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
        </li>
        <li>
            <a class="active" href="<?php echo server_url; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
                USUARIOS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
        </li>
    </ul>
</div>

<!-- Content -->
<div class="container-fluid">
    <?php 
        require_once "./controladores/usuarioControlador.php";

        //intanciamos el controlador
        $ins_usuario = new usuarioControlador();

        echo $ins_usuario -> paginador_usuario_controlador($pagina[1],15, $_SESSION['privilegio_ppp'], $_SESSION['id_ppp'], $pagina[0], "");
    ?>
</div>