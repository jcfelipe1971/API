<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/pedidos.class.php';

$_respuestas = new respuestas;
$_pedidos = new pedidos;


if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaPedidos = $_pedidos->listaProductos($pagina);
        header("Content-Type: application/json");
        echo json_encode($listaPedidos);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $id_pedido = $_GET['id'];
        $DatosPedidos = $_pedidos->ObtenerPedidos($id_pedido); 
        header("Content-Type: application/json");
        echo json_encode($DatosPedidos);
        http_response_code(200);
    }
    
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_pedidos->post($postBody);
    //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);
    
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
      //recibimos los datos enviados
      $postBody = file_get_contents("php://input");
      //enviamos datos al manejador
      $datosArray = $_pedidos->put($postBody);
        //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){

        $headers = getallheaders();
        if(isset($headers["token"]) && isset($headers["id_pedido"])){
            //recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "id_pedido" =>$headers["id_pedido"]
            ];
            $postBody = json_encode($send);
        }else{
            //recibimos los datos enviados
            $postBody = file_get_contents("php://input");
        }
        
        //enviamos datos al manejador
        $datosArray = $_pedidos->delete($postBody);
        //delvovemos una respuesta 
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
       

}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}


?>

