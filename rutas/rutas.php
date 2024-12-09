<?php

// MENSAJE PREPARADO PARA VER QUE INFORMACION TRAEMOS
// echo "<pre>"; print_r($datos); echo "<pre>";
// return;

// DETECTAMOS LA URL Y CAPTURAMOS LA RUTA
$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

if (isset($_GET['pagina_desde']) && is_numeric($_GET['pagina_desde']) && isset($_GET['pagina_cantidad']) && is_numeric($_GET['pagina_cantidad'])) {

    // INSTANCIAMOS LA CLASE
    $cursos = new ControladorCursos();

    // CREAMOS EL OBJETO PARA INSTANCIAR EL METODO
    $cursos->index($_GET['pagina_desde'], $_GET['pagina_cantidad']);

} else {

    // CUENTA LOS INDICES DEL ARRAY RUTAS
    if (count(array_filter($arrayRutas)) == 1) {

        // CUANDO NO SE HACE UNA PETICION A LA API
        $json = array(
            "detalle" => "no encontrado"
        );

        echo json_encode($json, true);
    } else {

        // CUANDO PASA UN INDICE A LA URL
        if (count(array_filter($arrayRutas)) == 2) {

            // CUANDO HAGO PETICION DESDE CURSOS
            if (array_filter($arrayRutas)[2] == "cursos") {

                // PETICION POST
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {

                    // CAPTURAMOS LOS DATOS
                    $datos = array(
                        "titulo" => $_POST['titulo'],
                        "descripcion" => $_POST['descripcion'],
                        "instructor" => $_POST['instructor'],
                        "imagen" => $_POST['imagen'],
                        "precio" => $_POST['precio']
                    );

                    // INSTANCIAMOS LA CLASE
                    $cursos = new ControladorCursos();

                    // CREAMOS EL OBJETO PARA INSTANCIAR EL METODO
                    $cursos->create($datos);
                } else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {

                    // INSTANCIAMOS LA CLASE
                    $cursos = new ControladorCursos();

                    // CREAMOS EL OBJETO PARA INSTANCIAR EL METODO
                    $cursos->index(null, null);
                }
            }
        }

        // CUANDO HAGO PETICION DESDE REGISTRO
        if (array_filter($arrayRutas)[2] == "registro") {

            // PETICION POST
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {

                // CAPTURAMOS LOS DATOS POR EL METODO POST
                $datos = array(
                    "name" => $_POST['name'],
                    "surname" => $_POST['surname'],
                    "email" => $_POST['email']
                );

                // INSTANCIAMOS LA CLASE
                $clientes = new ControladorClientes();

                // CREAMOS EL OBJETO PARA INSTANCIA EL METODO
                $clientes->create($datos);
            }
        } else {

            // PETISIONES CON PARAMETROS
            if (array_filter($arrayRutas)[2] == "cursos" && isset($_GET['id_course']) && is_numeric($_GET['id_course'])) {

                // PETICION DE TIPO GET
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {

                    // INSTANCIAMOS LA CLASE
                    $cursos = new ControladorCursos();

                    // CREAMOS EL OBJETO PARA INSTANCIA EL METODO Y PASAMOS POR PARAMETROS EL ID A LA FUNSION SHOW
                    $cursos->show($_GET['id_course']);
                }

                // PETICION DE TIPO PUT
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {

                    // CAPTURAMOS LOS DATOS
                    $datos = array();

                    // PREPARAMOS LOS DATOS QUE CAPTURAMOS DEL FORMULARIO
                    parse_str(file_get_contents('php://input'), $datos);

                    // INSTANCIAMOS LA CLASE
                    $editar_cursos = new ControladorCursos();

                    // CREAMOS EL OBJETO PARA INSTANCIA EL METODO Y PASAMOS POR PARAMETROS EL ID A LA FUNSION SHOW
                    $editar_cursos->update($_GET['id_course'], $datos);
                }

                // PETICION DE TIPO DELETE
                if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {

                    // INSTANCIAMOS LA CLASE
                    $delete_cursos = new ControladorCursos();

                    // CREAMOS EL OBJETO PARA INSTANCIA EL METODO Y PASAMOS POR PARAMETROS EL ID A LA FUNSION SHOW
                    $delete_cursos->delete($_GET['id_course']);
                }
            }
        }
    }
}
