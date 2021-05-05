<?php 
    require "../connection/connection.php";
    $result=null;
    if($pdo!=null){
        $auxParam = array_keys($_GET);
        foreach ($auxParam as $val){
            $bindings[] = $_GET[$val];
        }
        $bindings = implode(",", $bindings);
        if(array_keys($_GET)[0] == "id_notificacion") {
            $sql = "CALL VistoNotificacion(".$bindings.");";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute()){
                $result = "Operation Success";
            }
        }
        else if(array_keys($_GET)[0] == "id_subproceso"){
            $sql = "CALL RevisarNotificaciones(".$bindings.");";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute()){
                $stmt = $stmt->get_result();
                while($row = $stmt->fetch_array(MYSQLI_ASSOC)){
                    $result[] = $row;            
                }
            }
        }else{
            echo("Error en los parametros");
            return;
        }  
    }
    else{
        $result = "Connection error";
    }
    echo json_encode($result);
?>