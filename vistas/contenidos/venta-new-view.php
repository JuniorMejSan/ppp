<?php
require_once "./controladores/ventaControlador.php";
$ins_venta = new ventaControlador();
$metodos_pago = $ins_venta->obtener_metodos_pago();

$fechaActual = date('Y-m-d');
$horaActual = date('H:i:s');
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA
    </h3>
    <p class="text-justify">
        GESTION DE VENTAS
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo server_url; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA VENTA</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-venta/"><i class="far fa-calendar-alt"></i> &nbsp; RESERVACIONES</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-pending/"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; VENTAS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; FINALIZADAS</a>
        </li>
        <li>
            <a href="<?php echo server_url; ?>venta-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>

<div class="container-fluid">
    <div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR CLIENTE O ITEMS</p>
            <p class="text-center">
                <?php if(empty($_SESSION['datos_cliente'])){ ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCliente"><i
                        class="fas fa-user-plus"></i> &nbsp; Agregar cliente</button>
                <?php } ?>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem"><i
                        class="fas fa-box-open"></i> &nbsp; Agregar item</button>
            </p>
            <div>
                <span class="roboto-medium">CLIENTE:</span>
                <?php if(empty($_SESSION['datos_cliente'])){ ?>

                    <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un
                        cliente</span>

                <?php }else{?>

                    <form class="FormularioAjax" action="<?php echo server_url ?>ajax/ventaAjax.php" method="POST" data-form = "venta" style="display: inline-block !important;">
                        <input type="hidden" name="id_eliminar_cliente" value="<?php echo $_SESSION['datos_cliente']['ID'] ?>">
                    <?php echo $_SESSION['datos_cliente']['Nombre']." ".$_SESSION['datos_cliente']['Apellido']." - ".$_SESSION['datos_cliente']['DNI']?>
                        <button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                    </form>

                <?php } ?>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>CODIGO</th>
                            <th>ITEM</th>
                            <th>CANTIDAD</th>
                            <th>PRECIO <?php echo moneda ?></th>
                            <th>SUBTOTAL <?php echo moneda ?></th>
                            <th>VENDEDOR</th>
                            <th>DETALLE</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(isset($_SESSION['datos_item']) && count($_SESSION['datos_item']) >= 1){

                                //creamos variables de sesion que llevaran el importe total y la cantidad de items
                                $_SESSION['venta_total'] = 0;
                                $_SESSION['venta_item'] = 0;
                                //contador de items
                                $contador = 1;

                                //recorremos el array
                                foreach ($_SESSION['datos_item'] as $items) {//recorre todos los valores que trae el array

                                    //para calcular el subtotal
                                    $subtotal = $items['Cantidad'] * $items['Precio'];
                                    //damos formato a subtotal
                                    $subtotal = number_format($subtotal,2,'.','');
                                    
                        ?>
                        <tr class="text-center">
                            <td><?php echo $contador ?></td>
                            <td><?php echo $items['Codigo'] ?></td>
                            <td><?php echo $items['Nombre'] ?></td>
                            <td><?php echo $items['Cantidad'] ?></td>
                            <td><?php echo moneda.number_format($items['Precio'],2,'.','') ?></td>
                            <td><?php echo moneda.$subtotal ?></td>
                            <td><?php echo $_SESSION['usuario_ppp'] ?></td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover"
                                    title="<?php echo $items['Nombre'] ?>" data-content="<?php echo $items['Detalle'] ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            <td>
                                <form class="FormularioAjax" action="<?php echo server_url; ?>/ajax/ventaAjax.php" method="POST" data-form="venta" autocomplete="off">
                                    <input type="hidden" name="id_eliminar_item" value="<?php echo  $items['ID']?>">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                $contador++;
                                //vamos sumando todos los subtotales
                                $_SESSION['venta_total'] += $subtotal;
                                //sumamos todos los items de la venta
                                $_SESSION['venta_item'] += $items['Cantidad'];

                                }
                        ?>
                        
                        <tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="2"></td>
                            <td><strong><?php echo  $_SESSION['venta_item']?> items</strong></td>
                            <td colspan="1"></td>
                            <td><strong><?php echo  moneda.number_format($_SESSION['venta_total'],2,'.','')?></strong></td>
                            <td colspan="2"></td>
                        </tr>
                        <?php 
                            }else {
                                $_SESSION['venta_total'] = 0;
                                $_SESSION['venta_item'] = 0;
                        ?>
                        <tr class="text-center">
                            <td colspan = "7" >Esperando Items...</td>
                        </tr>
                        <?php 
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <form class="FormularioAjax" action="<?php echo server_url; ?>/ajax/ventaAjax.php" method="POST" data-form="save" autocomplete="off">

            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="venta_metodo" class="bmd-label-floating">Método de pago</label>
                                <select class="form-control" name="venta_metodo_reg" id="venta_metodo">
                                    <?php foreach($metodos_pago as $metodo): ?>
                                        <option value="<?php echo $metodo['idMetodPago']; ?>">
                                            <?php echo $metodo['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="prestamo_total" class="bmd-label-floating">Total a pagar</label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly=""
                                    value="<?php echo  moneda.number_format($_SESSION['venta_total'],2,'.','')?>" id="prestamo_total" maxlength="10">
                                <input type="hidden" name="fecha_venta_reg" id = "fecha_venta" value="<?php echo $fechaActual; ?>">
                                <input type="hidden" name="hora_venta_reg" id = "hora_venta" value="<?php echo $horaActual; ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="venta_observacion" class="bmd-label-floating">Observación</label>
                                <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}" class="form-control"
                                    name="venta_observacion_reg" id="venta_observacion" maxlength="400">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <p class="text-center" style="margin-top: 40px;">
                <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp;
                    GUARDAR</button>
            </p>
        </form>
    </div>
</div>


<!-- MODAL CLIENTE -->
<div class="modal fade" id="ModalCliente" tabindex="-1" role="dialog" aria-labelledby="ModalCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCliente">Agregar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_cliente" class="bmd-label-floating">DNI</label>
                        <input type="number" pattern="[0-9]{1,8}" class="form-control"
                            name="input_cliente" id="input_cliente" maxlength="8">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_clientes">
                    <!-- Aqui se miestran los resultados de la busqueda de clientes para las ventas -->
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_cliente()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL ITEM -->
<div class="modal fade" id="ModalItem" tabindex="-1" role="dialog" aria-labelledby="ModalItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalItem">Agregar item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_item" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control"
                            name="input_item" id="input_item" maxlength="30" autocomplete = "OFF">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_items">
                    <!-- Aqui se miestran los resultados de la busqueda de items para las ventas -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick = "buscar_item()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL AGREGAR ITEM -->
<div class="modal fade" id="ModalAgregarItem" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarItem"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php echo server_url; ?>/ajax/ventaAjax.php" method="POST" data-form="default" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarItem">Ingrese la cantidad a vender</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_item" id="id_agregar_item">
                <div cs="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad</label>
                                <input type="number" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad"
                                    id="detalle_cantidad" maxlength="7" required="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick = "modal_buscar_item()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<?php
    include_once "./vistas/inc/venta.php";
?>