<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

//Clase que para la tabla pedidos
class pedidos extends conexion {

    private $table = "pedidos";
    //campos 
    private $id_pedido = "";
    private $cod_seccion = "";
    private $cantidad = "";
    private $fecha = "0000-00-00";
    private $cod_arti = "";
    private $precio = "";
   

   //***** LISTAR (GET) ******************************
    public function listaProductos($pagina = 1){
        $inicio  = 0 ;
        $cantidad = 100;
        if($pagina > 1){
            $inicio = ($cantidad * ($pagina - 1)) +1 ;
            $cantidad = $cantidad * $pagina;
        }
        $query = "SELECT * FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function ObtenerProductos($id){
        $query = "SELECT * FROM " . $this->table . " WHERE 
        id_pedido = '$id'";
        return parent::obtenerDatos($query);

    }


    //******  INSERTAR  ( POST ) ***********************
    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
                return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['cod_arti'])){
                    return $_respuestas->error_400();
                }else{
                    $this->cod_arti = $datos['cod_arti'];
                    if(isset($datos['cod_seccion'])) { $this->cod_seccion = $datos['cod_seccion']; }
                    if(isset($datos['cantidad'])) { $this->cantidad = $datos['cantidad']; }
                    if(isset($datos['fecha'])) { $this->fecha = $datos['fecha']; }
                    if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                    $resp = $this->insertarPedido();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "cod_arti" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
     
    }

 
    private function insertarPedido(){
        $query = "INSERT INTO " . $this->table . " (cod_seccion,cantidad,fecha,cod_arti,precio)
        values
        ('" . $this->cod_seccion . "','" . $this->cantidad  . "','" . $this->fecha .  "','" .  $this->cod_arti . "','" .  $this->precio ."')"; 
        
        $resp = parent::nonQueryId ($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }

    //******  UPDATE  ( PUT ) ***********************
    public function put($json)
     {
      $_respuestas = new respuestas;
      $datos = json_decode($json,true);

      if(!isset($datos['id_pedido']))
       {
        return $_respuestas->error_400();
       }
      else
       {
        $this->id_pedido = $datos['id_pedido'];
        if(isset($datos['cod_seccion'])) { $this->cod_seccion = $datos['cod_seccion']; }
        if(isset($datos['cantidad'])) { $this->cantidad = $datos['cantidad']; }
        if(isset($datos['cod_arti'])) { $this->cod_arti = $datos['cod_arti']; }
        if(isset($datos['fecha'])) { $this->fecha = $datos['fecha']; }
        if(isset($datos['precio'])) { $this->precio = $datos['precio']; } 

        $resp = $this->ModificaPedido();
        if($resp)
         {
          $respuesta = $_respuestas->response;
          $respuesta["result"] = array("id_pedido" => $this->id_pedido);
          return $respuesta;
         }
        else
         {
          return $_respuestas->error_500();
         }
       }
      }


    private function ModificaPedido(){
        $query = "UPDATE " . $this->table . " SET cod_seccion ='" . $this->cod_seccion . "',cantidad = '" . $this->cantidad . "',cod_arti = '" . $this->cod_arti . "',fecha = '" . $this->fecha . "',precio = '" . $this->precio  . "'"; 

        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }

    //******  ELIMINAR  ( DELETE ) ***********************
    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){

                if(!isset($datos['id_pedido'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_pedido = $datos['id_pedido'];
                    $resp = $this->EliminaPedido();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_pedido" => $this->id_pedido
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }



     
    }


    private function EliminaPedido(){
        $query = "DELETE FROM " . $this->table . " WHERE id_pedido= '" . $this->id_pedido . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }


    private function buscarToken(){
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }


    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }



}





?>