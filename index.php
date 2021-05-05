<?php
require_once "./connection/connection.php";
$subproceso = $usuario = $contraseña ="";
session_start();
if(isset($_SESSION['POST'])){
    $session = $_SESSION['POST'];
    unset($_SESSION['POST']);
    if($session != null && isset($session['fallo']) == true){
        $message = "Usuario y/o contraseña incorrectos.";
        print_r($session);
    }else{
        $message = "";
    }
}
else{
    $message = "";
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $subproceso = trim($_POST["subproceso"]);
    $usuario = trim($_POST["usuario"]);
    $contraseña = trim($_POST["contraseña"]);

    $stmt = $pdo->prepare("CALL ReconocerTrabajador(?, ?, ?)");
    $stmt->bind_param("iss", $subproceso, $usuario, $contraseña);
    try{
        if($stmt->execute()){
            $stmt = $stmt->get_result()->fetch_all();   
            $_POST = $stmt[0];
            session_start();
            $_SESSION['POST'] = $_POST;
            header("location: punto_de_control.php");
        }else{
            session_start();
            $_POST['fallo'] = true; 
            $_SESSION['POST'] = $_POST;
            header("location: .");
            
        }
    }catch(Exception $e){

    }
  
    mysqli_close($pdo);
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
    <script src="js/connection.js"></script>
    <script src="js/index.js"></script>
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
                                    <input type="password" name="contraseña" class="form-control" id="validationDefault03" required value="<?php echo $contraseña; ?>">
                                </div>
                                <br>
                                <div class="input-group is-invalid">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="validatedInputGroupSelect">Subproceso</label>
                                    </div>
                                    <select class="custom-select" id="validatedInputGroupSelect" required name="subproceso">
                                    <option selected value="">Selecciona alguno</option>

                                        <?php  
                                            $stmt = $pdo->prepare("CALL LeerSubprocesos()");
                                            $stmt->execute();
                                            $stmt = $stmt->get_result()->fetch_all();   
                                            foreach($stmt as $val){ echo '<option value="'.$val[0].'">'.$val[1].'</option>'; }
                                            mysqli_close($pdo);
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