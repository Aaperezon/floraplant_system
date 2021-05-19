<?php
    include "./connection/Connection.php";
    session_start();
    $nombre_subproceso = "Subproceso error";
    $nombre_trabajador = "Trabajador error";

  
    if(isset($_SESSION['POST'])){
        $session = $_SESSION['POST'];
        //print_r($session);
        $id_trabajador = $session["id_trabajador"];
        $id_subproceso = $session["id_subproceso"];
        $data = [
            "id_subproceso" => $id_subproceso,
            "id_trabajador"=> $id_trabajador,
        ];
        $result = json_decode(Get("GetDataRegitry",$data), true);
        //print_r($result);
        $nombre_subproceso = $result['subproceso'];
        $nombre_trabajador = $result['nombre'];
       
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = [
            "orden" => trim($_POST["orderNO"]),
            "descripcion" => trim($_POST["description"]),
            "direccion" => trim($_POST["address"]),
            "precio" => trim($_POST["price"])
        ];
        $result = json_decode(Post("AddNewOrder",$data), true);
        //header("location: operador.php");

      
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Evita reenviar el formulario cuando se recarga la página-->
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <meta charset="UTF-8">
    <title><?php echo($nombre_subproceso); ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/operador.css">
</head>
<body >
<?php echo('<input type="hidden" id="id_subproceso_aux" value='.$id_subproceso.'>');?>

<div class="pos-f-t" style="width: 98vw" >
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark p-4 ">
            <div class="row ">

                <div class="col-3">
                    <br>
                    <h4 class="text-white">¿Deseas cerrar sesión?</h4>
                </div>
                <div class="col">
                    <br>
                    <form action="./index.php"  method="CERRAR SESION">
                        <?php  
                            if($_SERVER["REQUEST_METHOD"] == "CERRAR SESION"){
                                session_destroy();
                            }
                        ?>
                        <button type="submit" class="btn btn-secondary btn-lg">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  
    <div class="row" style="width: 99vw">
        <div class="col-sm navbar navbar-dark header">
            <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="col-sm navbar navbar-dark header">
            <h1 class="title-text"><?php echo($nombre_subproceso.":  ".$nombre_trabajador);  ?> </h1>
        </div>
        <div class="col-3 navbar navbar-dark header">
        </div>
    </div>
    
    
</div>


<div class="container" id="contTable">
    <div class="row">
        <div class="col">
        </div>
        <div class="col-4">
            <center>
                <div id="title">Agregar ordenes</div> 
                <br><br><br>
            </center>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div>
                    <label for="validationDefault03">Numero de orden</label>
                    <input type="text" name="orderNO" class="form-control" id="validationDefault03" required value="">
                    <br>
                </div>
                <div>
                    <label for="validationDefault03">Descripcion</label>
                    <input type="text" name="description" class="form-control" id="validationDefault03" required value="">
                    <br>
                </div>
                <div>
                    <label for="validationDefault03">Direccion</label>
                    <input type="text" name="address" class="form-control" id="validationDefault01" required value="">
                    <br>
                </div>
                <div>
                    <label for="validationDefault03">Precio</label>
                    <input type="number" name="price" class="form-control" id="validationDefault03" required value="">
                    <br><br><br>
                </div>
                <div>
                    <button>Agregar</button>
                </div>
            </form>
          
           








        </div>
        <div class="col">
        </div>
    </div>
</div>


</body>
</html>