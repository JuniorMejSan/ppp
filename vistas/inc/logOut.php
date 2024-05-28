<script>
    //detectar que se presiona el boton para cerrar sesion
    let btn_salir = document.querySelector(".btn-exit-system");//clase del boton, está en navBar
    btn_salir.addEventListener('click', function(e){//el boton espera el click
        e.preventDefault();//previene el evento por defecto
        //alerta del cierre de sesion
        Swal.fire({
			title: '¿Estás seguro de cerrar sesión?',
			text: "Estás a punto de salir del sistema",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#FA3C01',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, salir!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
				//codigo para el envio de los parametros para el cierre de sesion
                let url = '<?php echo server_url; ?>ajax/loginAjax.php';//concatenamos el archivo a donde lo mandamos
                let token = '<?php echo $lc -> encryption($_SESSION['token_ppp']) ?>';
			}
		});
    });
</script>