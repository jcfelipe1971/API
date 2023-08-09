<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';


class entrada extends conexion{

    public function login($json)
     {
      
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        if(!isset($datos['usuario']) || !isset($datos["clave"]))
         {
          //faltan datos
          return $_respuestas->error_400();
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
                $result = $_respuestas->okcod_seccion($datos[0]['cod_seccion']);
                return $result;
               }
              else
               {
                //el usuario esta inactivo
                return $_respuestas->error_200("El usuario esta inactivo");
               }
              }
             else
              {
               //la contraseña no es igual
               return $_respuestas->error_200("Clave es incorrecta");
              }
            }
           else
            {
             //no existe el usuario
             return $_respuestas->error_200("El usuaro $usuario  no existe ");
            }
        }//if(!isset($datos['usuario']) || !isset($datos["clave"]))
     }//public function login($json)



    private function obtenerDatosUsuario($usuario){
        $query = "SELECT UsuarioId,clave,Estado,cod_seccion FROM usuarios WHERE Usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }else{
            return 0;
        }
    }



}




?>