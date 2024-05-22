<?php
$peticionAjax = false;
require_once "./controladores/vistasControlador.php";
$iv = new vistasControlador(); //instancia para las vista

$vistas = $iv->obtener_vistas_controlador(); //guardar el resultado del controlador "vistasControlador"
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>
		<?php
		if ($vistas == "404") {
			echo 'NOT FOUND';
		} else {
			echo company;
		}
		?>
	</title>
	<?php
	//archivos css
	include "./vistas/inc/link.php";
	?>
</head>

<body>
	<?php
	if ($vistas == "login" || $vistas == "404") {  //pregunta si la respuesta del controlador es login o 404 
		require_once "./vistas/contenidos/" . $vistas . "-view.php";
	} else {
	?>
		<!-- Main container -->
		<main class="full-box main-container">
			<!-- Nav lateral -->
			<?php
			include "./vistas/inc/navLateral.php";
			?>
			<!-- Page content -->
			<section class="full-box page-content">
				<?php
				include "./vistas/inc/navBar.php";
				include $vistas;
				?>
			</section>
		</main>
	<?php
	}
	//archivos js
	include "./vistas/inc/scripts.php";
	?>
</body>

</html>