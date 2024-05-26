const formularios_ajax = document.querySelectorAll(".FormularioAjax");//para seleccionar todos los formularios que tengan la clase .FormularioAjax de una vista

function enviar_formulario_ajax(e) {//"e" contienen el evento por defecto de los formularios 
    e.preventDefault();//previene redireccionar a la url donde se envian datos

    let data = new FormData(this);//arreglo para indicar de donde queremos obtener los datos
    let method = this.getAttribue("method");//indica el metodo que se va a utilizar para el envio de datos
    let action = this.getAttribue("action");//indica la url donde se va a enviar los datos
    let tipo = this.getAttribue("data-form");//indica el tipo de formulario es decir el "data-form"

    let encabezados = new Headers();//encabezado de los datos enviados por ajax Headers() sirve para obtener los encabezados
    
    let config = { //json para el envio de datos por ajaxarray con toda la configuracion del envio de datos
        method: method,
        headers: encabezados,
        mode: 'cors',
        cache: 'no-cache',
        body: data
    }

    let texto_alerta;

    if (tipo === "save") {//cuando se guardan datos
        texto_alerta = "Los datos quedarán guardados en el sistema";
    }else if (tipo === "delete") {//cuando se eliminan datos
        texto_alerta = "Los datos se eliminarán del sistema";
    }else if (tipo === "update") {//cuando se actualizan datos
        texto_alerta = "Los datos se actualizarán en el sistema";
    }else if (tipo === "search") {//cuando se buscan datos
        texto_alerta = "Se eliminarán los terminos de busqueda, deberá escribir uno nuevo";
    }else if (tipo === "venta") {//cuando se limpian datos de venta
        texto_alerta = "Remover los datos seleccionados de la venta";
    }else{//si no coincide con ningun tipo de formulario
        texto_alerta = "¿Quieres realizar la operacion solicitada?";
    }

    Swal.fire({//boton de confirmacion de la alerta
        title: '¿Está seguro?',
        text: texto_alerta,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f06a11',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed) {
            fetch(action, config) //se envia la url y las configuraciones
                .then(respuesta => respuesta.json())//parsea los datos que llegan a json
                .then(respuesta => {
                    return alertas_ajax(respuesta);
                })
        }
    });
}

//para el envio de los datos del formulario mediante metodo post
formularios_ajax.forEach(formularios => {
    formularios.addEventListener("submit", enviar_formulario_ajax);//esperando el evento submit a ejecutar y se ejecuta la funcion enviar_formulario_ajax

});

//funcion para las alertas
function alertas_ajax(alerta) {
    if (alerta.Alerta==="simple") {
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        });
    }else if (alerta.Alerta==="recargar") {//al dar clic en aceptar se va a recargar la pagina
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
    }else if (alerta.Alerta==="limpiar") {//una vez se le da a aceptar se limpian todos los valores ingresados
        Swal.fire({
            title: alerta.Titulo,
            text: alerta.Texto,
            icon: alerta.Tipo,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed) {
                document.querySelector(".FormularioAjax").reset();//solo selecciona un unico documento, el que se esta enviando o intentando limpiar
            }
        });
    }else if (alerta.Alerta==="redireccionar") {//no en todos se va a mostrar alertas, simplemente se envian datos mediante ajax
        window.location.href = alerta.URL;
    }
}