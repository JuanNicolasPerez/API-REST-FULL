<?php

// MENSAJE PREPARADO PARA VER QUE INFORMACION TRAEMOS
// echo "<pre>"; print_r($datos); echo "<pre>";
// return;

// CREAMOS LA CLASE
class ControladorCursos{

    // CREAMOS EL METODO INDEX PARA LA VISTA
    public function index($desde, $cantidad){


        // VALIDAR LAS CREDENCIALES DEL CLIENTE
        $clientes = ModeloClientes::index("clientes");

        // VALIDADR LAS CREDENCIALES DEL CLIENTE
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            foreach ($clientes as $key => $value) {

                if (base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                    base64_encode($value['id_cliente'].":".$value['llave_secreta'])) {

                    if (isset($desde) && isset($cantidad)) {

                        $cursos = ModeloCursos::index("cursos", "clientes", $desde, $cantidad);

                    } else{

                        $cursos = ModeloCursos::index("cursos", "clientes", null, null);

                    }

                    $json = array(
                        "status" => 200,
                        "total_register" => count($cursos),
                        "detalle" => $cursos
                    );

                    echo json_encode($json, true);

                    return;

                }

            }

        }

    }

    // CREAMOS EL METODO CREAR UN CURSO
    public function create($datos){

        // TRAEMOS LOS DATOS DE TODOS LOS CLIENTE
        $clientes = ModeloClientes::index("clientes");

        // VALIDADR LAS CREDENCIALES DEL CLIENTE
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            // RECORREMOS LOS CAMPOS DE LOS CLIENTES
            foreach ($clientes as $key => $valueCliente) {

                // COMPARAMOS LOS DATOS DEL CLIENTE CON EL USUARIO QUE ESTA REGISTRANDO
                if (base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                    base64_encode($valueCliente['id_cliente'].":".$valueCliente['llave_secreta'])) {

                    // VALIDAMOS QUE INGRESEN DATOS VALIDOS EN LOS CAMPOS
                    foreach ($datos as $key => $valueForm) {

                        // VALIDAR DATOS
                        if (isset($valueForm) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueForm)) {

                            $json = array(
                                "status" => 404,
                                "detalle" => "Error in the field ". $key
                            );

                            echo json_encode($json, true);

                            return;

                        }

                    }

                    // TRAEMOS TODOS LOS DATOS DE LOS CURSOS REGISTRADOS
                    $cursos = ModeloCursos::index("cursos", "clientes", null, null);

                    // RECORREMOS LOS CUROS
                    foreach ($cursos as $key => $value) {

                        // VALIDAR QUE EL TITULO NO ESTE REPETIDO
                        if ($value->titulo == $datos['titulo']) {

                            $json = array(
                                "status" => 404,
                                "detalle" => "The title already exists in the database"
                            );

                            echo json_encode($json, true);

                            return;

                        }

                        // VALIDAR QUE LA DESCRIPCION NO ESTE REPETIDA
                        if ($value->descripcion == $datos['descripcion']) {

                            $json = array(
                                "status" => 404,
                                "detalle" => "The description already exists in the database"
                            );

                            echo json_encode($json, true);

                            return;

                        }

                    }

                    // CAPTURAMOS LOS DATOS
                    $datos = array(
                        "titulo" => $datos['titulo'],
                        "descripcion" => $datos['descripcion'],
                        "instructor" => $datos['instructor'],
                        "imagen" => $datos['imagen'],
                        "precio" => $datos['precio'],
                        "id_creador" => $valueCliente['id'],
                        "created_at" => date('Y-m-d h:i:s'),
                        "updated_at" => date('Y-m-d h:i:s')
                    );

                    //ENVIAMOS LOS DATOS AL MODELO 
                    $create = ModeloCursos::create("cursos", $datos);

                    if($create == "ok"){

                        $json = array(
                            "status" => 200,
                            "detalle" => "Satisfactory process"
                        );

                        echo json_encode($json, true);

                        return;

                    }

                }else {

                    $json = array(
                        "status" => 404,
                        "detalle" => "Error: User Not Registered"
                    );

                    echo json_encode($json, true);

                    return;

                }

            }

        }

    }

    public function show($id){

        // TRAEMOS LOS DATOS DE TODOS LOS CLIENTE
        $clientes = ModeloClientes::index("clientes");

        // VALIDADR LAS CREDENCIALES DEL CLIENTE
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            // RECORREMOS LOS CAMPOS DE LOS CLIENTES
            foreach ($clientes as $key => $valueCliente) {

                // COMPARAMOS LOS DATOS DEL CLIENTE CON EL USUARIO QUE ESTA REGISTRANDO
                if (base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                    base64_encode($valueCliente['id_cliente'].":".$valueCliente['llave_secreta'])) {

                    // TRAEMOS TODOS LOS DATOS DEL CURSOS SOLICITADO
                    $curso = ModeloCursos::show("cursos", "clientes", $id);

                    if (!empty($curso)) {

                        $json = array(
                            "status" => 200,
                            "detalle" => $curso
                        );

                        echo json_encode($json, true);

                        return;

                    }else{
                        $json = array(
                            "status" => 404,
                            "total_registro" => 0,
                            "detalle" => "Error: No records found, with that ID"
                        );

                        echo json_encode($json, true);

                        return;
                    }

                }else {

                    $json = array(
                        "status" => 404,
                        "detalle" => "Error: User Not Registered"
                    );

                    echo json_encode($json, true);

                    return;

                }

            }

        }else {

            $json = array(
                "status" => 404,
                "detalle" => "Error: Please enter your credentials"
            );

            echo json_encode($json, true);

            return;

        }

    }

    public function update($id, $datos){

        // TRAEMOS LOS DATOS DE TODOS LOS CLIENTE
        $clientes = ModeloClientes::index("clientes");

        // VALIDADR LAS CREDENCIALES DEL CLIENTE
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            // RECORREMOS LOS CAMPOS DE LOS CLIENTES
            foreach ($clientes as $key => $valueCliente) {

                // COMPARAMOS LOS DATOS DEL CLIENTE CON EL USUARIO QUE ESTA REGISTRANDO
                if (base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                    base64_encode($valueCliente['id_cliente'].":".$valueCliente['llave_secreta'])) {

                    // VALIDAMOS QUE INGRESEN DATOS VALIDOS EN LOS CAMPOS
                    foreach ($datos as $key => $valueForm) {

                        // VALIDAR DATOS
                        if (isset($valueForm) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueForm)) {

                            $json = array(
                                "status" => 404,
                                "detalle" => "Error in the field ". $key
                            );

                            echo json_encode($json, true);

                            return;

                        }

                    }

                    // TRAEMOS TODOS LOS DATOS DE LOS CURSOS REGISTRADOS
                    $cursos = ModeloCursos::show("cursos", "clientes", $id);

                    // RECORREMOS LOS CURSOS
                    foreach ($cursos as $key => $valueCursos) {

                        // VALIDAMOS EL ID DEL CREADOR
                        if ($valueCursos->id_creador == $valueCliente['id']) {

                            // CAPTURAMOS LOS DATOS
                            $datos = array(
                                "id" => $id,
                                "titulo" => $datos['titulo'],
                                "descripcion" => $datos['descripcion'],
                                "instructor" => $datos['instructor'],
                                "imagen" => $datos['imagen'],
                                "precio" => $datos['precio'],                                
                                "updated_at" => date('Y-m-d h:i:s')
                            );

                            //ENVIAMOS LOS DATOS AL MODELO 
                            $update = ModeloCursos::update("cursos", $datos);

                            if($update == "ok"){

                                $json = array(
                                    "status" => 200,
                                    "detalle" => "Satisfactory process"
                                );

                                echo json_encode($json, true);

                                return;

                            }else {

                                $json = array(
                                    "status" => 404,
                                    "detalle" => "Error: When updating the registry"
                                );

                                echo json_encode($json, true);

                                return;
                            }

                        }else{

                            $json = array(
                                "status" => 404,
                                "detalle" => "Error: You don't have access to this course"
                            );

                            echo json_encode($json, true);

                            return;

                        }

                    }

                }else {

                    $json = array(
                        "status" => 404,
                        "detalle" => "Error: User Not Registered"
                    );

                    echo json_encode($json, true);

                    return;

                }

            }

        }else {

            $json = array(
                "status" => 404,
                "detalle" => "Error: Please enter your credentials"
            );

            echo json_encode($json, true);

            return;

        }

    }

    public function delete($id){

        // TRAEMOS LOS DATOS DE TODOS LOS CLIENTE
        $clientes = ModeloClientes::index("clientes");

        // VALIDADR LAS CREDENCIALES DEL CLIENTE
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

            // RECORREMOS LOS CAMPOS DE LOS CLIENTES
            foreach ($clientes as $key => $valueCliente) {

                // COMPARAMOS LOS DATOS DEL CLIENTE CON EL USUARIO QUE ESTA REGISTRANDO
                if (base64_encode($_SERVER['PHP_AUTH_USER'].":".$_SERVER['PHP_AUTH_PW']) == 
                    base64_encode($valueCliente['id_cliente'].":".$valueCliente['llave_secreta'])) {

                    // TRAEMOS TODOS LOS DATOS DE LOS CURSOS REGISTRADOS
                    $cursos = ModeloCursos::show("cursos", "clientes", $id);

                    // RECORREMOS LOS CURSOS
                    foreach ($cursos as $key => $valueCursos) {

                        // VALIDAMOS EL ID DEL CREADOR
                        if ($valueCursos->id_creador == $valueCliente['id']) {


                            //ENVIAMOS LOS DATOS AL MODELO 
                            $delete = ModeloCursos::delete("cursos", $id);

                            if($delete == "ok"){

                                $json = array(
                                    "status" => 200,
                                    "detalle" => "Was successfully deleted"
                                );

                                echo json_encode($json, true);

                                return;

                            }else {

                                $json = array(
                                    "status" => 404,
                                    "detalle" => "Error: Failed to delete the record"
                                );

                                echo json_encode($json, true);

                                return;
                            }

                        }else{

                            $json = array(
                                "status" => 404,
                                "detalle" => "Error: You don't have access to this course"
                            );

                            echo json_encode($json, true);

                            return;

                        }

                    }

                }else {

                    $json = array(
                        "status" => 404,
                        "detalle" => "Error: User Not Registered"
                    );

                    echo json_encode($json, true);

                    return;

                }

            }

        }else {

            $json = array(
                "status" => 404,
                "detalle" => "Error: Please enter your credentials"
            );

            echo json_encode($json, true);

            return;

        }

    }

}

?>