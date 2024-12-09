<?php

require_once "modelos/clientes.modelo.php";

// CREAMOS LA CLASE
class ControladorClientes{

    // CREAMOS EL METODO
    public function create($datos){

        // VALIDAMOS QUE SE INGRESEN NOMBRES VALIDOS
        if(isset($datos["name"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["name"])){

            $json = array(
                "status" => 404,
                "detalle" => "Error: Format Not Allowed in name"
            );

            echo json_encode($json, true);

            return;

        }

        // VALIDAMOS QUE SE INGRESEN NOMBRES VALIDOS
        if(isset($datos["surname"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["surname"])){

            $json = array(
                "status" => 404,
                "detalle" => "Error: Format Not Allowed in surname"
            );

            echo json_encode($json, true);

            return;

        }

        // VALIDAMOS QUE SE INGRESEN CORREOS VALIDOS
        if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z-9_]+([.][a-zA-Z-9_]+)*[@][a-zA-Z-9_]+([.][a-zA-Z-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["email"])){

            $json = array(
                "status" => 404,
                "detalle" => "Error: Format Not Allowed in Email"

            );

            echo json_encode($json, true);

            return;

        }  

        // VALIDAR CORREOS REPETIDOS
        $clientes = ModeloClientes::index("clientes");

        foreach ($clientes as $key => $value) {

            if($value['email'] == $datos['email']){

                $json = array(
                    "status" => 404,
                    "detalle" => "Error: Repeated email in database"
                );

                echo json_encode($json, true);

                return;
            }

        }

        // GENERAR CREDENCIALES DEL USUARIO
        $id_cliente = str_replace("$","c",crypt($datos['name'].$datos['surname'], '$1$rasmusle$'));

        $llave_secreta = str_replace("$","c",crypt($datos['surname'].$datos['name'], '$1$rasmusle$'));

        // PASAMOS LOS DATOS DEL FORMULARIO Y LAS LLAVES CREADAS DENTRO DE UN ARRAY
        $datos = array(
            "name" => $datos['name'],
            "surname" => $datos['surname'],
            "email" => $datos['email'],
            "id_cliente" => $id_cliente,
            "llave_secreta" => $llave_secreta,
            "created_at" => date('Y-m-d h:i:s'),
            "updated_at" => date('Y-m-d h:i:s')
        );

        $create = ModeloClientes::create("clientes", $datos);

        if($create == "ok"){
            $json = array(
                "status" => 200,
                "detalle" => "Satisfactory process",
                "credenciales" => $id_cliente,
                "llave_secreta" => $llave_secreta
            );

            echo json_encode($json, true);

            return;
        }

    }

}

?>