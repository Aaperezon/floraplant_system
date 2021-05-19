<?php
include "./connection/Connection.php";


$subproceso = $usuario = $contraseña ="";

session_start();
/*
if(isset($_SESSION['POST'])){
    $session = $_SESSION['POST'];
    unset($_SESSION['POST']);
    print_r($session);
    if($session != null && isset($session['fallo']) == true){
        $message = "Usuario y/o contraseña incorrectos.";
        echo("Fallo en entrada de datos");
        //print_r($session);
    }else{
        $message = "";
    }
}
else{
    $message = "";
}
*/

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    session_destroy();
    $data = [
        "id_subproceso" => trim($_POST["subproceso"]),
        "usuario"=> trim($_POST["usuario"]),
        "contraseña" => trim($_POST["contraseña"])
    ];
    $attempted_subprocess = trim($_POST["subproceso"]);
    $result = json_decode(Post("LogInWorker",$data), true);
    if($result["tipo"] == "administrador"){
        session_start();
        $_POST['id_trabajador'] = $result['id_trabajador'];
        $_POST['id_subproceso'] = $result['id_subproceso'];
        unset($_POST['usuario'],$_POST['contraseña'],$_POST['subproceso']);
        $_SESSION['POST'] = $_POST;
        if($_POST['id_subproceso'] == 1 ){
            header("location: admin.php");
        }else if($_POST['id_subproceso'] == 2 ){
            header("location: operador.php");
        }else{
            header("location: punto_de_control.php");
        }
    }else if($result["tipo"] == "operador"){
        session_start();
        $_POST['id_trabajador'] = $result['id_trabajador'];
        $_POST['id_subproceso'] = $result['id_subproceso'];
        unset($_POST['usuario'],$_POST['contraseña'],$_POST['subproceso']);
        $_SESSION['POST'] = $_POST;
        if($_POST['id_subproceso'] == 2 ){
            header("location: operador.php");
        }else{
            $_POST['fallo'] = true; 
            $message = "Usuario y/o contraseña incorrectos para el subproceso.";

        }
    }else if($result["tipo"] == "trabajador"){
        session_start();
        $_POST['id_trabajador'] = $result['id_trabajador'];
        $_POST['id_subproceso'] = $result['id_subproceso'];
        unset($_POST['usuario'],$_POST['contraseña'],$_POST['subproceso']);
        $_SESSION['POST'] = $_POST;
        if($_POST['id_subproceso'] == 1 || $_POST['id_subproceso'] == 2){
            $_POST['fallo'] = true; 
            $message = "Usuario y/o contraseña incorrectos para el subproceso.";
        }else{
            header("location: punto_de_control.php");
        }
    }else if ($result["tipo"] == "Error"){
        $_POST['fallo'] = true; 
        $message = "Usuario y/o contraseña incorrectos para el subproceso.";

    }
      
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="./js/jquery-3.6.0.min" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>

    <div id="carouselCaptions" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner ">
            <div class="carousel-item active carousel-image"  data-interval="3000">
                <img src="./images/carousel1.jpg" class="" alt="...">
            </div>
            <div class="carousel-item carousel-image"  data-interval="3000">
                <img src="./images/carousel2.jpg" class="" alt="...">
            </div>
            <div class="carousel-item carousel-image"  data-interval="3000">
                <img src="./images/carousel3.jpg" class="" alt="...">
            </div>
               
            <div class="container">
            <div class="row">
                <div class="col-1">
                </div>
                <div class="col-10">
                    <!-- <?php print_r($_POST);?> -->
                    <br><br><br><br><br>
                    <div class="page-header header" >
                        <h1>Registro</h1>
                    </div>
                    <br>
                    <br>
                <div class="card mb-3" >
                    <div class="row no-gutters">
                        <div class="col-md-4">
                        <img id="img-login" src="./images/floraplant_logo.png" alt="...">
                        </div>
                        <div class="col-md-7">
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div>
                                    <label for="validationDefault03">Usuario</label>
                                    <input type="text" name="usuario" class="form-control" id="validationDefault03" required value="<?php echo $usuario; ?>">
                                </div>
                                <br>
                                <div>
                                    <label for="validationDefault03">Contraseña</label>
                                    <input type="password" name="contraseña" class="form-control" id="validationDefault03" required value="">
                                </div>
                                <br>
                                <div class="input-group is-invalid">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="validatedInputGroupSelect">Subproceso</label>
                                    </div>
                                    <select class="custom-select" id="validatedInputGroupSelect" required name="subproceso">
                                    <option selected value="">Selecciona alguno</option>

                                        <?php  
                                            $result = json_decode(Get("ReadSubprocess",[]), true);
                                            foreach($result as $val){ 
                                                echo '<option value="'.$val['id'].'">'.$val['subproceso'].'</option>'; 
                                            }
                                        ?>
                                   

                                    </select>
                                </div>
                                <br><br>
                                <p style="color:#FF0000"><?php echo $message ?></p>
                                <br>
                                <input type="submit" class="btn btn-primary" value="Entrar">
                                <a href="index.php" class="btn btn-default">Cancelar</a>
                            </form>
                        </div>
                    </div>
                </div>
                </div>






        </div>
        </div>


          


            
        </div>
        <div class="col-1">
        </div>
    </div>
    </div>
</body>
</html>