<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

//Clase que para la tabla productos
class productos extends conexion {

    private $table = "productos";
    //campos 
    private $cod_arti = "";
    private $desc_arti = "";
    private $um = "";
    private $precio = "";
    private $existencia = "";
   

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
        $query = "SELECT * FROM " . $this->table . " WHERE cod_arti = '$id'";
        return parent::obtenerDatos($query);
    }

    public function DameProductosFecha($fecha){
      $query = "SELECT * FROM " . $this->table . " WHERE fecha > $fecha";
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
                    if(isset($datos['desc_arti'])) { $this->desc_arti = $datos['desc_arti']; }
                    if(isset($datos['um'])) { $this->um = $datos['um']; }
                    if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                    if(isset($datos['existencia'])) { $this->existencia = $datos['existencia']; }
                    $resp = $this->insertarProducto();
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

 
    private function insertarProducto(){
        $query = "INSERT INTO " . $this->table . " (cod_arti,desc_arti,um,precio)
        values
        ('" . $this->cod_arti . "','" . $this->desc_arti  . "','" . $this->um .  "','" .  $this->precio . "','" .  $this->existencia ."')"; 
        
        $resp = parent::nonQuery($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }

    //******  UPDATE  ( PUT ) ***********************
    public function put($json){
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
                    if(isset($datos['desc_arti'])) { $this->desc_arti = $datos['desc_arti']; }
                    if(isset($datos['um'])) { $this->um = $datos['um']; }
                    if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                    if(isset($datos['existencia'])) { $this->existencia = $datos['existencia']; }
                    $resp = $this->ModificaProducto();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "cod_arti" => $this->cod_arti
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


    private function ModificaProducto(){
        $query = "UPDATE " . $this->table . " SET cod_arti ='" . $this->cod_arti . "',desc_arti = '" . $this->desc_arti . "',um = '" . $this->um . "',precio = '" . $this->precio . "',existencia = '" . $this->existencia  . "'"; 

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

                if(!isset($datos['cod_arti'])){
                    return $_respuestas->error_400();
                }else{
                    $this->cod_arti = $datos['cod_arti'];
                    $resp = $this->EliminaProducto();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "cod_arti" => $this->cod_arti
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


    private function EliminaProducto(){
        $query = "DELETE FROM " . $this->table . " WHERE cod_arti= '" . $this->cod_arti . "'";
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