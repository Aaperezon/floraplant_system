<?php
    include "./connection/Connection.php";

    echo("aSDasds");
    $asd = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $asd = $_POST;
        print_r($asd);
        echo('
            <script>
                console.log("AAAAAAAAAAAAAAAA")
            </script>
        
        ');
        /*
        $terminar = trim($_POST["terminar"]);
        $data = [
            "id_punto_de_control" => trim($_POST["id_punto_de_control"]),
            "id_trabajador"=> trim($_POST["id_trabajador"]),
        ];
        if($terminar == false){
            $result = json_decode(Post("StartActivity",$data), true);
        }
        else if($terminar == true){
            $result = json_decode(Post("EndActivity",$data), true);
        }
        $_SERVER["REQUEST_METHOD"] = "";
        unset($_POST['id_punto_de_control']);
        unset($_POST['id_trabajador']);
        unset($_POST['terminar']);
        */
        
      
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="./js/jquery-3.6.0.min" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/admin.css">
    
</head>
<body>

    <div class="sidebar">
        <img id="img-logo" src="./images/floraplant_logo.png" alt="...">
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?> " method="POST">
                <input type="hidden" name="worker" value="details"/>
                <button type="submit" >Detalles</button>
        </form>
        <?php
            $result = json_decode(Get("CheckWorkers",[]), true);
            $counter = 0;
            foreach ($result as $val){
                $counter++;
                echo('
                    <form id="form'.$counter.'"action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="POST">
                        <input type="hidden" name="worker" value="'.$val['id'].'"<input/>
                        <button type="submit" >'.$val['nombre'].'</button>
                    </form>
                    ');
            }
        ?>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?> " method="POST">
                <input type="hidden" name="worker" value="settings"/>
                <button type="submit" >Ajustes</button>
        </form>
    </div>
      
    <div id="main">
    
        <div class="container">
            <div class="row">
                <div class="col-1">
              
                </div>
                <div class="col-3">

                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <?php
                                        print_r($asd);
                                    ?>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Hoy
                                    </div>
                                    <div class="h7 mb-0 font-weight-bold text-gray-800">2021-04-30</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-1">

                </div>
                <div class="col-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Pedidos del día
                                    </div>
                                    <div class="h7 mb-0 font-weight-bold text-gray-800">60 %</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-1">

                </div>
                <div class="col-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Ganancias
                                    </div>
                                    <div class="h7 mb-0 font-weight-bold text-gray-800">$40,000.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
               

            </div>
            <br><br>
            <div class="row">
                <div class="col-1">

                </div>
                <div class="col-11">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Grafica
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <select class="custom-select" id="validatedInputGroupSelect" required name="subproceso">
                                                <option selected value="">Selecciona alguno</option>
                                                    <option value="Valor">Opcion1</option>
                                                    <option value="Valor">Opcion2</option>
                                                    <option value="Valor">Opcion3</option>
                                                    <option value="Valor">Opcion4</option>
                                                   
                                            </select>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <div class="h7 mb-0 font-weight-bold text-gray-800">
                                        GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-GRAFICA-
                                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                    </div>
                                    
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    
</body>
</html>