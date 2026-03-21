// Tipos de formularios que requieren contraseña del admin para usuarios que no sean el admin (id 1)
const TIPOS_PROTEGIDOS = ["delete", "update", "enable", "venta_devuelta", "compra_devuelta"];

const formularios_ajax = document.querySelectorAll(".FormularioAjax");//para seleccionar todos los formularios que tengan la clase .FormularioAjax de una vista

// Función para solicitar y validar la contraseña del administrador
function solicitarPasswordAdmin() {
    return new Promise((resolve) => {
        Swal.fire({
            title: 'Autorización requerida',
            text: 'Ingrese la contraseña del administrador para continuar',
            input: 'password',
            inputPlaceholder: 'Contraseña del administrador',
            inputAttributes: {
                autocomplete: 'off',
                maxlength: '100'
            },
            showCancelButton: true,
            confirmButtonColor: '#f06a11',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Validar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return 'Debe ingresar la contraseña';
            }
        }).then((result) => {
            if (result.value) {
                let passData = new FormData();
                passData.append('admin_password_check', result.value);

                fetch(window.pppServerUrl + 'ajax/validarAdminAjax.php', {
                    method: 'POST',
                    body: passData
                })
                .then(resp => resp.json())
                .then(resp => {
                    if (resp.status) {
                        resolve(true);
                    } else {
                        Swal.fire({
                            title: 'Acceso denegado',
                            text: resp.mensaje,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                        resolve(false);
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo validar la contraseña',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    resolve(false);
                });
            } else {
                resolve(false);
            }
        });
    });
}

// Función para procesar el envío del formulario después de la validación
function procesar_envio_formulario(data, method, action, tipo) {
    let encabezados = new Headers();
    
    let config = {
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }

    let texto_alerta;

    if (tipo === "save") {
        texto_alerta = "Los datos quedarán guardados en el sistema";
    }else if (tipo === "delete") {
        texto_alerta = "Los datos se eliminarán del listado";
    }else if (tipo === "update") {
        texto_alerta = "Los datos se actualizarán en el sistema";
    }else if (tipo === "search") {
        texto_alerta = "Se eliminarán los terminos de busqueda, deberá escribir uno nuevo";
    }else if (tipo === "venta") {
        texto_alerta = "Remover los datos seleccionados de la venta";
        texto_alerta = "Se eliminarán los terminos de busqueda, deberá escribir uno nuevo";
    }else if (tipo === "compra") {
        texto_alerta = "Remover los datos seleccionados de la compra";
        texto_alerta = "Se eliminarán los terminos de busqueda, deberá escribir uno nuevo";
    }else if (tipo === "venta_devuelta") {
        texto_alerta = "¿Quiere devolver la venta seleccionada?";
    }else{
        texto_alerta = "¿Quieres realizar la operacion solicitada?";
    }

    Swal.fire({
        title: '¿Está seguro?',
        text: texto_alerta,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f06a11',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.value) {
            fetch(action, config)
                .then(respuesta => respuesta.json())
                .then(respuesta => {
                    return alertas_ajax(respuesta);
                })
        }
    });
}

function enviar_formulario_ajax(e) {
    e.preventDefault();

    let data = new FormData(this);
    let method = this.getAttribute("method");
    let action = this.getAttribute("action");
    let tipo = this.getAttribute("data-form");

    // Si es una acción protegida y el usuario NO es el admin (id 1), pedir contraseña del admin
    if (TIPOS_PROTEGIDOS.includes(tipo) && typeof window.pppUserId !== 'undefined' && window.pppUserId != 1) {
        solicitarPasswordAdmin().then((autorizado) => {
            if (autorizado) {
                procesar_envio_formulario(data, method, action, tipo);
            }
        });
    } else {
        procesar_envio_formulario(data, method, action, tipo);
    }
}

//para el envio de los datos del formulario mediante metodo post
formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit", enviar_formulario_ajax);
});

// Interceptar botones de editar/actualizar (enlaces <a> con icono fa-sync-alt) para usuarios no admin
if (typeof window.pppUserId !== 'undefined' && window.pppUserId != 1) {
    document.querySelectorAll('a.btn.btn-success').forEach(link => {
        if (link.querySelector('i.fa-sync-alt')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let href = this.getAttribute('href');

                solicitarPasswordAdmin().then((autorizado) => {
                    if (autorizado) {
                        window.location.href = href;
                    }
                });
            });
        }
    });
}

//funcion para las alertas
function alertas_ajax(alerta) {
    if (alerta.Alerta==="simple") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        });
    }else if (alerta.Alerta==="recargar") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed) {
                location.reload();
            }
        });
    }else if (alerta.Alerta==="recargar_imprimir") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed) {
                window.open(alerta.URL, '_blank');
                location.reload();
            }
        });
    }else if (alerta.Alerta==="limpiar") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed) {
                document.querySelector(".FormularioAjax").reset();
            }
        });
    }else if (alerta.Alerta==="redireccionar") {
        window.location.href = alerta.URL;
    }
}