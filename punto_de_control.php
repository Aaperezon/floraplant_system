<?php
    include "./connection/connection.php";
    session_start();
    $nombre_subproceso = "Subproceso error";
    $nombre_trabajador = "Trabajador error";

  
    if(isset($_SESSION['POST'])){
        $session = $_SESSION['POST'];
        //print_r($session);
        $id_trabajador = $session[0];
        $id_subproceso = $session[1];

        $stmt = $pdo->prepare("CALL ObtenerDatosDeRegistro(?, ?)");
        $stmt->bind_param("ii",$id_trabajador, $id_subproceso);
        if( $stmt->execute() ){
            $stmt = $stmt->get_result();
            while($row = $stmt->fetch_array(MYSQLI_ASSOC)){
                //print_r($row);
                $nombre_subproceso = $row['subproceso'];
                $nombre_trabajador = $row['nombre'];
            }
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_punto_de_control = trim($_POST["id_punto_de_control"]);
        $id_trabajador = trim($_POST["id_trabajador"]);
        $terminar = trim($_POST["terminar"]);
        if($terminar == false){
            $stmt = $pdo->prepare("CALL EmpezarActividad(?, ?)");
            $stmt->bind_param("ii", $id_punto_de_control, $id_trabajador);
        }
        else if($terminar == true){
            $stmt = $pdo->prepare("CALL TerminarActividad(?, ?)");
            $stmt->bind_param("ii", $id_punto_de_control, $id_trabajador);
        
        }
        if($stmt->execute()){
            $_SERVER["REQUEST_METHOD"] = "";
            unset($_POST['id_punto_de_control']);
            unset($_POST['id_trabajador']);
            unset($_POST['terminar']);
            /*
            $_POST['0'] = $id_trabajador;
            $_POST['1'] = $id_subproceso;
            print_r($_POST);
            */
        }else{
            echo "Error en la base de datos.";
        }
      
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
    <script src="./js/punto_de_control.js"></script>
    <link rel="stylesheet" href="./css/punto_de_control.css">
</head>
<body >
<?php echo('<input type="hidden" id="id_subproceso_aux" value='.$id_subproceso.'>'); ?>

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
        <div class="col-10">
            <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Orden</th>
                    <th>Descripcion</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                    //error_reporting(0);
                    $stmt = $pdo->prepare("CALL VerPuntosDeControl(?,?);");
                    $stmt->bind_param("ii",$id_subproceso, $id_trabajador );
                    if($stmt->execute()){
                        $counter = 0;
                        $stmt = $stmt->get_result();
                        while($row = $stmt->fetch_array(MYSQLI_ASSOC)){
                            $counter++;
                            if($row['estado'] == 'Inicio'){
                                echo '<tr class="table-green">';
                                    echo '<td >'.$counter.'</td>';
                                    echo '<td >'.$row['orden'].'</td>';
                                    echo '<td >'.$row['descripcion'].'</td>';
                                    echo '<td >'.$row['estado'].'</td>';
                                    echo ('<td>
                                        <form id="theForm" action="'. htmlspecialchars($_SERVER["PHP_SELF"])    .'" method="POST">
                                            <input type="hidden" name="id_punto_de_control" value="'.$row['id'].'"/>
                                            <input type="hidden" name="id_trabajador" value="'.$id_trabajador.'"/>
                                            <input type="hidden" name="terminar" value="'.false.'"/>
                                            <button type="submit" id="btn"  class="btn btn-light">Empezar</button>
                                        </form>
                                    </td>');
                                echo '</tr>';
                            }
                            else if($row['estado'] == 'En proceso'){
                                echo '<tr class="table-yellow">';
                                    echo '<td >'.$counter.'</td>';
                                    echo '<td >'.$row['orden'].'</td>';
                                    echo '<td >'.$row['descripcion'].'</td>';
                                    echo '<td >'.$row['estado'].'</td>';
                                    echo ('<td>
                                        <form id="theForm" action="'. htmlspecialchars($_SERVER["PHP_SELF"])    .'" method="POST">
                                            <input type="hidden" name="id_punto_de_control" value="'.$row['id'].'"/>
                                            <input type="hidden" name="id_trabajador" value="'.$id_trabajador.'"/>
                                            <input type="hidden" name="terminar" value="'.true.'"/>
                                            <button type="submit" id="btn" class="btn btn-light">Terminar</button>
                                        </form>
                                    </td>');
                                echo '</tr>';
                            }
                        }

                    }else{
                        echo '<td > </td>';
                        echo '<td > </td>';
                        echo '<td > No tienes pedidos pendientes.</td>';
                        echo '<td > </td>';
                        echo '<td > </td>';
                    }
                ?>
            </tbody>
            </table>
        </div>
        <div class="col">
        </div>
    </div>
</div>


</body>
</html>