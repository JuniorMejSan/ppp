<?php
	$peticionAjax = true;
	//incluimos configuraciones
	require_once "../config/app.php";

	//recivimos el parametro del id que se esta enviando por GET pero verificamos su existencia
	$id = (isset($_GET['id'])) ? $_GET['id'] : 0;


	//instanciamos el controlador de la venta
	require_once "../controladores/ventaControlador.php";
    $ins_venta = new ventaControlador();
	//lamamos la funcion que trae todos los datos de la venta
	$datos_venta = $ins_venta -> datos_venta_controlador("Unico", $id);
	//condicional para verificar si la venta existe
	if ($datos_venta -> rowCount() == 1) {//la venta existe
	//una vez se verifica que si existe llenamos la variable con los datos que devuleve la funcion de datos
	$datos_venta = $datos_venta -> fetch();
	

	//instanciamos el controaldor del usuariop
	require_once "../controladores/usuarioControlador.php";
    $ins_usuario = new usuarioControlador();
	//datos del usuario, le mandamos el id de la venta pero encriptado
	$datos_usuario = $ins_usuario -> datos_usuario_controlador("Comprobante", $ins_usuario -> encryption($datos_venta['usuario_nombre']));
	//llenamos la variable
	$datos_usuario = $datos_usuario -> fetch();


	//instanciamos el controaldor del cliente
	require_once "../controladores/clienteControlador.php";
    $ins_cliente = new clienteControlador();
	//datos del cliente, le mandamos el id de la venta pero encriptado
	$datos_cliente = $ins_cliente->datos_cliente_controlador("Unico", $ins_cliente->encryption($datos_venta['cliente_id']));
	//llenamos la variable
	if(is_array($datos_cliente)){
		// Es un cliente genérico, ya tenemos los datos
	} else {
		//llenamos la variable
		$datos_cliente = $datos_cliente->fetch();
	}
	//echo $datos_venta['cliente_id'];
	//var_dump($datos_cliente);


	require "./fpdf.php";

	$pdf = new FPDF('P','mm','Letter');
	$pdf->SetMargins(17,17,17);
	$pdf->AddPage();
	$pdf->Image('../vistas/assets/img/usqay_logo2.png',10,10,30,30,'PNG');

	$pdf->SetFont('Arial','B',18);
	$pdf->SetTextColor(255, 153, 51);
	$pdf->Cell(0,10,utf8_decode(strtoupper("CORPORACIÓN USQAY SAC")),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(-35,10,utf8_decode('N. de comprobante'),'',0,'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial','',15);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(""),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(-35,10,utf8_decode($datos_venta['venta_codigo']),'',0,'C');

	$pdf->Ln(25);

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(36,8,utf8_decode('Fecha de emisión:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(27,8,utf8_decode(date("d/m/Y", strtotime($datos_venta['venta_fecha']))),0,0);
	$pdf->Ln(8);$pdf->SetTextColor(33,33,33);
	$pdf->Cell(36,8,utf8_decode('Hora de emisión:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(27,8,utf8_decode(date("H:i:s", strtotime($datos_venta['venta_hora']))),0,0);
	$pdf->Ln(8);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(27,8,utf8_decode('Atendido por:'),"",0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(13,8,utf8_decode($datos_usuario['nombre'].' '.$datos_usuario['apellido']),0,0);

	$pdf->Ln(15);

	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,8,utf8_decode('Cliente:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$nombre_cliente = isset($datos_cliente['cliente_nombre']) ? $datos_cliente['cliente_nombre']." ".$datos_cliente['cliente_apellido'] : 'Cliente Genérico';
	$pdf->Cell(65,8,utf8_decode($nombre_cliente),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(10,8,utf8_decode('DNI:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$dni_cliente = isset($datos_cliente['cliente_dni']) ? $datos_cliente['cliente_dni'] : '-';
	$pdf->Cell(40,8,utf8_decode($dni_cliente),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(19,8,utf8_decode('Teléfono:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$telefono_cliente = isset($datos_cliente['cliente_telefono']) ? $datos_cliente['cliente_telefono'] : '-';
	$pdf->Cell(35,8,utf8_decode($telefono_cliente),0,0);
	$pdf->SetTextColor(33,33,33);

	$pdf->Ln(8);

	$pdf->Cell(20,8,utf8_decode('Dirección:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$direccion_cliente = isset($datos_cliente['cliente_direccion']) ? $datos_cliente['cliente_direccion'] : '-';
	$pdf->Cell(109,8,utf8_decode($direccion_cliente),0,0);

	$pdf->Ln(15);

	$pdf->SetFillColor(255, 153, 51);
	$pdf->SetDrawColor(38,198,208);
	$pdf->SetTextColor(33,33,33);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(15,10,utf8_decode('Cant.'),1,0,'C',true);
	$pdf->Cell(90,10,utf8_decode('Descripción'),1,0,'C',true);
	$pdf->Cell(51,10,utf8_decode('Precio'),1,0,'C',true);
	$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

	$pdf->Ln(10);

	$pdf->SetTextColor(97,97,97);

	//detalles de la venta
	$datos_detalle = $ins_venta -> datos_venta_controlador("Detalle", $ins_venta -> encryption($datos_venta['venta_codigo']));
	//le asignamos todos los datos
	$datos_detalle = $datos_detalle -> fetchAll();
	//variable para el total de la venta
	$total = 0;

	foreach($datos_detalle as $items){
	
		$subtotal = $items['detalleVenta_item_cantidad'] * $items['detalleVenta_item_precio'];
		$subtotal = number_format( $subtotal,2,'.','');
		$pdf->Cell(15,10,utf8_decode($items['detalleVenta_item_cantidad']),'L',0,'C');
		$pdf->Cell(90,10,utf8_decode($items['item_nombre']),'L',0,'C');
		$pdf->Cell(51,10,utf8_decode(moneda.' '.$items['detalleVenta_item_precio']),'L',0,'C');
		$pdf->Cell(25,10,utf8_decode(moneda.' '.$items['detalleVenta_total']),'LR',0,'C');
		$pdf->Ln(10);
		
		$total += $subtotal;
	}

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(90,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(51,10,utf8_decode('TOTAL'),'LTB',0,'C');
	$pdf->Cell(25,10,utf8_decode(moneda.' '.number_format($total,2,'.','')),'LRTB',0,'C');

	$pdf->Ln(15);

	$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: ".($datos_venta['venta_observacion'] == "" ? "Sin observación" : $datos_venta['venta_observacion'])),0,'J',false);

	$pdf->SetFont('Arial','',12);

	$pdf->Ln(25);

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(0,6,utf8_decode("CORPORACIÓN USQAY SAC"),0,0,'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,6,utf8_decode("DIRECCION DE LA EMPRESA: Jiron Tacna 258 Oficina 1B - Piura"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Teléfono: (+51) 973105651"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("(+51) 951297182"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("(+51) 956224141"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Correo: administracion@sistemausqay.com"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("soporte@sistemausqay.com"),0,0,'C');


		$pdf->Output("I","comprobante_".$datos_venta['venta_codigo'].".pdf",true);
	}else{//si no trae datos de la venta
?>
	<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo company;?></title>
		<?php include "../vistas/inc/link.php";?>
	</head>
	<body>
		<div class="full-box container-404">
			<div>
				<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
				<h1 class="text-center">Ocurrio un error</h1>
				<p class="lead text-center">No se ha encontrado al venta seleccionada</p>
			</div>
		</div>
	<?php include "../vistas/inc/scripts.php";?>
	</body>
	</html>
<?php	
	}
?>