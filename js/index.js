let load = () => {
    var subprocess_options = document.getElementById("validatedInputGroupSelect")
    $.ajax({
        type: "GET",
        url: connection.uri+"/ReadSubprocess/",
        datatype: "json",
        async: false,
        success: function(data){
          console.log("Inside ajax: "+data);                
        }
    });










    //let btnCrear = document.getElementById("crear")
    //btnCrear.addEventListener("click", crear)
}
addEventListener("DOMContentLoaded", load)
