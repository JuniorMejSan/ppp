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
    if (alerta.Aleta==="simple") {
        
    }
}