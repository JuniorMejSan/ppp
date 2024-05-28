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
				window.location="index.html";
			}
		});
    });
</script>