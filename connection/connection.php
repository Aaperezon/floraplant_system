<?php
$server = "http://127.0.0.1:5000";
function Post($route, $data){
    global $server;
    $metodo = "POST"; //cambiar a "POST" en caso de que sea post
    $url  = $server."/".$route."/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    return $response;
}
function Get($route, $data){
    global $server;
    $method = "GET"; 
    $url  = $server."/".$route."/?";
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url.http_build_query($data));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $tmp = curl_exec($ch);
    curl_close($ch);
    return $tmp;
}
  
    

?>