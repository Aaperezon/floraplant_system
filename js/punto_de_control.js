let load = () => {
    let data;
    Notification.requestPermission();
    if (!('Notification' in window)) {
        alert('Este navegador no soporta las notificaciones del sistema');
    }
    function Llamado1(url){
        $.ajax({
        type: "GET",
        url: url,
        datatype: "json",
        async: false,
        success: function(responseFunction){
            responseFunction = JSON.parse(responseFunction);
            data = responseFunction;
        }
        });
    }
    function Llamado2(url){
        $.ajax({
            type: "GET",
            url: url,
            datatype: "json",
            async: false,
            success: function(responseFunction){
            responseFunction = JSON.parse(responseFunction);
            }
        });
    }
    function CrearNotificacion(titulo, orden, descripcion){
        let options = {
            body: orden + ": "+descripcion,
            icon: './images/floraplant_logo.png'
        }
        let n = new Notification(titulo,options);
       
    }

    function Ejecutar(){
        if (Notification.permission === 'granted') {
            let id_subproceso = document.getElementById("id_subproceso_aux").value
            Llamado1("./modulos/revisar_notificacion.php/?id_subproceso="+String(id_subproceso));
            if(data != null){
                data.forEach(element => {
                    CrearNotificacion(element.subproceso, element.orden, element.descripcion)
                    Llamado2("./modulos/revisar_notificacion.php/?id_notificacion="+String(element.id));
                });
                location.reload();
            }
           
        }
        
    }
    setInterval(function(){
        Ejecutar()
    },1000)
}
addEventListener("DOMContentLoaded", load)
