<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

//Clase que para la tabla repcama
class repcama extends conexion {

    private $table = "repcama";
    //campos 
    private $id_repcama = "";
    private $id_cama = "";
    private $habi = "";
    private $estado = "";
    private $limpia = "";
    private $hora_ent = "";
    private $hora_sal = "";
    private $fecha_ent = "0000-00-00";
    private $fecha_sal = "0000-00-00";
    private $adultos = "";
    private $ninos = "";
    private $observaciones = "";

   //***** LISTAR (GET) ******************************
    public function listaRepcama($pagina = 1){
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

    public function obtenerRepcama($id){
        $query = "SELECT * FROM " . $this->table . " WHERE 
        id_cama = '$id'";
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

                if(!isset($datos['id_cama'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_cama = $datos['id_cama'];
                    if(isset($datos['habi'])) { $this->habi = $datos['habi']; }
                    if(isset($datos['estado'])) { $this->estado = $datos['estado']; }
                    if(isset($datos['limpia'])) { $this->limpia = $datos['limpia']; }
                    if(isset($datos['hora_ent'])) { $this->hora_ent = $datos['hora_ent']; }
                    if(isset($datos['hora_sal'])) { $this->hora_sal = $datos['hora_sal']; }
                    if(isset($datos['fecha_ent'])) { $this->fecha_ent = $datos['fecha_ent']; }
                    if(isset($datos['fecha_sal'])) { $this->fecha_sal = $datos['fecha_sal']; }
                    if(isset($datos['adultos'])) { $this->adultos = $datos['adultos']; }
                    if(isset($datos['ninos'])) { $this->ninos = $datos['ninos']; }
                    if(isset($datos['observaciones'])) { $this->observaciones = $datos['observaciones']; }
                    $resp = $this->insertarRepcama();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_repcama" => $resp
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

 
    private function insertarRepcama(){
        $query = "INSERT INTO " . $this->table . " (id_cama,habi,estado,limpia,hora_ent,hora_sal,fecha_ent,fecha_sal,adultos,ninos,observaciones)
        values
        ('" . $this->id_cama . "','" . $this->habi . "','" . $this->estado ."','" . $this->limpia . "','"  . $this->hora_ent . "','" . $this->hora_sal . "','" . $this->fecha_ent . "','" . $this->fecha_sal .  "','" . $this->adultos . "','" . $this->ninos ."','" . $this->observaciones ."')"; 
        
        $resp = parent::nonQueryId($query);
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
                if(!isset($datos['id_repcama'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_repcama = $datos['id_repcama'];
                    if(isset($datos['id_cama'])) { $this->id_cama = $datos['id_cama']; }
                    if(isset($datos['habi'])) { $this->habi = $datos['habi']; }
                    if(isset($datos['estado'])) { $this->estado = $datos['estado']; }
                    if(isset($datos['limpia'])) { $this->limpia = $datos['limpia']; }
                    if(isset($datos['hora_ent'])) { $this->hora_ent = $datos['hora_ent']; }
                    if(isset($datos['hora_sal'])) { $this->hora_sal = $datos['hora_sal']; }
                    if(isset($datos['fecha_ent'])) { $this->fecha_ent = $datos['fecha_ent']; }
                    if(isset($datos['fecha_sal'])) { $this->fecha_sal = $datos['fecha_sal']; }
                    if(isset($datos['adultos'])) { $this->adultos = $datos['adultos']; }
                    if(isset($datos['ninos'])) { $this->ninos = $datos['ninos']; }
                    if(isset($datos['observaciones'])) { $this->observaciones = $datos['observaciones']; }

                    $resp = $this->modificarRepcama();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_repcama" => $this->id_repcama
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


    private function modificarRepcama(){
        $query = "UPDATE " . $this->table . " SET id_cama ='" . $this->id_cama . "',habi = '" . $this->habi . "', estado = '" . $this->estado . "', limpia = '" .
        $this->limpia . "', hora_ent = '" . $this->hora_ent . "', hora_sal = '" . $this->hora_sal . "', fecha_ent = '" . $this->fecha_ent . "', fecha_sal = '" . $this->fecha_sal . "', adultos = '" . $this->adultos . "', ninos = '" . $this->ninos . "', observaciones = '" . $this->observaciones .
         "' WHERE id_repcama = '" . $this->id_repcama . "'"; 

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

                if(!isset($datos['id_repcama'])){
                    return $_respuestas->error_400();
                }else{
                    $this->id_repcama = $datos['id_repcama'];
                    $resp = $this->eliminarRepcama();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "id_repcama" => $this->id_repcama
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


    private function eliminarRepcama(){
        $query = "DELETE FROM " . $this->table . " WHERE id_repcama= '" . $this->id_repcama . "'";
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