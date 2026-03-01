<?php
$peticionAjax = true;
require_once "../config/app.php";

$id = $_GET['id'] ?? 0;

require_once "../controladores/ventaControlador.php";
$ins_venta = new ventaControlador();
$datos_venta = $ins_venta->datos_venta_controlador("Unico", $id);

switch ($datos_venta->rowCount()) {
	case 1:
		$datos_venta = $datos_venta->fetch();

		require_once "../controladores/usuarioControlador.php";
		$ins_usuario = new usuarioControlador();
		$datos_usuario = $ins_usuario->datos_usuario_controlador("Comprobante", $ins_usuario->encryption($datos_venta['usuario_nombre']));
		$datos_usuario = $datos_usuario->fetch();

		require_once "../controladores/clienteControlador.php";
		$ins_cliente = new clienteControlador();
		$datos_cliente = $ins_cliente->datos_cliente_controlador("Unico", $ins_cliente->encryption($datos_venta['cliente_id']));

		if (!is_array($datos_cliente)) {
			$datos_cliente = $datos_cliente->fetch();
		}

		$datos_detalle = $ins_venta->datos_venta_controlador("Detalle", $ins_venta->encryption($datos_venta['venta_codigo']));
		$datos_detalle = $datos_detalle->fetchAll();
		$total = 0;
		?>
		<!DOCTYPE html>
		<html lang="es">

		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Comprobante</title>
			<style>
				body,
				table {
					font-family: "Lucida Console", Monaco, monospace;
					line-height: 0.8 !important;
					font-size: 8px;
				}

				body {
					margin: 0px !important;
					zoom: 200%;
				}

				.txt-center {
					text-align: center;
				}

				.txt-right {
					text-align: right;
				}

				.txt-left {
					text-align: left;
				}

				.bold {
					font-weight: bold;
				}

				.hr-header {
					border: none;
					border-top: 1px solid #000;
					margin: 5px 0;
				}

				.hr-detalle {
					border: none;
					border-top: 1px solid #000;
					margin: 3px 0;
				}

				table {
					width: 100%;
					border-collapse: collapse;
				}

				td {
					padding: 2px 5px;
				}
			</style>
		</head>

		<body>
			<table border="0" width="100%">
				<tr>
					<td colspan="4" class="txt-center bold" style="font-size: 12px;">
						CORPORACIÓN USQAY SAC
					</td>
				</tr>
				<tr>
					<td colspan="4" class="txt-center">
						RUC: 1234567890<br />
						Dirección: Jiron Tacna 258 Oficina 1B - Piura<br />
						Teléfono: (+51) 973105651<br />
						<hr class="hr-header" />
					</td>
				</tr>
				<tr>
					<td colspan="4" class="txt-center bold">
						COMPROBANTE DE PAGO<br />
						N° <?php echo $datos_venta['venta_codigo']; ?>
						<hr class="hr-header" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<b><?php echo date('d/m/Y', strtotime($datos_venta['venta_fecha'])); ?></b>
					</td>
					<td colspan="2" class="txt-right">
						<b><?php echo date('H:i:s', strtotime($datos_venta['venta_hora'])); ?></b>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="txt-left bold">
						Cliente:
						<?php echo $datos_cliente['cliente_nombre'] ?? 'Cliente Genérico' . " " . ($datos_cliente['cliente_apellido'] ?? ''); ?><br />
						DNI: <?php echo $datos_cliente['cliente_dni'] ?? '-'; ?><br />
						Atendido por: <?php echo $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido']; ?>
						<hr class="hr-header" />
					</td>
				</tr>
			</table>

			<table border="0" width="100%">
				<tr class="txt-center bold">
					<td>Cant</td>
					<td colspan="2">Descripción</td>
					<td>Total</td>
				</tr>
				<tr>
					<td colspan="4">
						<hr class="hr-detalle" />
					</td>
				</tr>
				<?php
				foreach ($datos_detalle as $items) {
					$subtotal = $items['detalleVenta_item_cantidad'] * $items['detalleVenta_item_precio'];
					$total += $subtotal;
					echo "<tr>";
					echo "<td class='txt-center'>" . $items['detalleVenta_item_cantidad'] . "</td>";
					echo "<td colspan='2'>" . $items['item_nombre'] . "</td>";
					echo "<td class='txt-right'>" . number_format($subtotal, 2) . "</td>";
					echo "</tr>";
				}
				?>
				<tr>
					<td colspan="4">
						<hr class="hr-detalle" />
					</td>
				</tr>
			</table>

			<table border="0" width="100%">
				<tr class="txt-center bold">
					<td></td>
					<td class="txt-right">TOTAL:</td>
					<td colspan="2" class="txt-right">
						<?php echo moneda . ' ' . number_format($total, 2); ?>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<hr class="hr-detalle" />
					</td>
				</tr>
				<?php if ($datos_venta['venta_observacion'] != ""): ?>
					<tr class="txt-center">
						<td colspan="4">
							<b>OBSERVACIÓN:</b> <?php echo $datos_venta['venta_observacion']; ?>
						</td>
					</tr>
				<?php endif; ?>
			</table>

			<br /><br />
			<div class="txt-center">Este es una representacion de un ticket de pago.</div>
			<br />
		</body>

		</html>
		<?php
		break;
	default:
		?>
		<!DOCTYPE html>
		<html lang="es">

		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title><?php echo company; ?></title>
			<?php include "../vistas/inc/link.php"; ?>
		</head>

		<body>
			<div class="full-box container-404">
				<div>
					<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
					<h1 class="text-center">Ocurrio un error</h1>
					<p class="lead text-center">No se ha encontrado al venta seleccionada</p>
				</div>
			</div>
			<?php include "../vistas/inc/scripts.php"; ?>
		</body>

		</html>
<?php
}
?>