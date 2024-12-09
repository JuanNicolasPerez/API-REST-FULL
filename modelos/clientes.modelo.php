<?php

require_once "conexion.php";

class ModeloClientes{

    static public function index($tabla){

        $sql = "SELECT * FROM $tabla";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();

        $stmt->close();

        $stmt = null;

    }

    static public function create($tabla, $datos){

        $sql = "INSERT INTO 
                    $tabla (	
                            nombre,	
                            apellido,
                            email,
                            id_cliente,
                            llave_secreta,
                            created_at,
                            updated_at)
                    VALUES (	
                            :nombre,	
                            :apellido,
                            :email,
                            :id_cliente,
                            :llave_secreta,
                            :created_at,
                            :updated_at)
        ";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt -> bindParam(":nombre", $datos['name'], PDO::PARAM_STR);
        $stmt -> bindParam(":apellido", $datos['surname'], PDO::PARAM_STR);
        $stmt -> bindParam(":email", $datos['email'], PDO::PARAM_STR);
        $stmt -> bindParam(":id_cliente", $datos['id_cliente'], PDO::PARAM_STR);
        $stmt -> bindParam(":llave_secreta", $datos['llave_secreta'], PDO::PARAM_STR);
        $stmt -> bindParam(":created_at", $datos['created_at'], PDO::PARAM_STR); 
        $stmt -> bindParam(":updated_at", $datos['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";

        }else{

            print_r(Conexion::conectar()->errorInfo());

        }

    }

}

?>