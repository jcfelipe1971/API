<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';


class entrada extends conexion{

    public function login($json)
     {
      
        $_respustas = new respuestas;
        $datos = json_decode($json,true);
        if(!isset($datos['usuario']) || !isset($datos["clave"]))
         {
          //faltan datos
          return $_respustas->error_400();
         }
        else
         {
          //todo esta bien 
          $usuario = $datos['usuario'];
          $clave = $datos['clave'];
          $datos = $this->obtenerDatosUsuario($usuario);
          if($datos)
           {
            //verificar si la contraseña es igual
            if($clave == $datos[0]['clave'])
             {
              if($datos[0]['Estado'] == "Activo")
               {
                $result = $_respustas->response;
                return $result;
               }
              else
               {
                //el usuario esta inactivo
                return $_respustas->error_200("El usuario esta inactivo");
               }
              }
             else
              {
               //la contraseña no es igual
               return $_respustas->error_200("Clave es incorrecta");
              }
            }
           else
            {
             //no existe el usuario
             return $_respustas->error_200("El usuaro $usuario  no existe ");
            }
        }//if(!isset($datos['usuario']) || !isset($datos["clave"]))
     }//public function login($json)



    private function obtenerDatosUsuario($usuario){
        $query = "SELECT UsuarioId,clave,Estado FROM usuarios WHERE Usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }else{
            return 0;
        }
    }



}




?>