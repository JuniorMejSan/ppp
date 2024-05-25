const formularios_ajax = document.querySelectorAll(".FormularioAjax");//para seleccionar todos los formularios que tengan la clase .FormularioAjax de una vista

function enviar_formulario_ajax(e) {//"e" contienen el evento por defecto de los formularios 
    e.preventDefault();//previene redireccionar a la url donde se envian datos
}

//para el envio de los datos del formulario
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