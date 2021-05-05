<?php
    include "../connection/connection.php";
    //print_r($_POST);
    $id_subproceso = $_POST['id_subproceso'];
    $id_trabajador = $_POST['id_trabajador'];
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