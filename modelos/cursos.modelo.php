<?php

// REQUERIMOS LA CONEXION
require_once "conexion.php";

class ModeloCursos
{

    static public function index($tabla, $tabla2, $desde, $cantidad)
    {

        if (isset($desde) && isset($cantidad)) {

            // CONSULTA CON PAGINACION
            $sql = "SELECT 
                    $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, $tabla.imagen, $tabla.precio, $tabla.id_creador,
                    $tabla2.nombre, $tabla2.apellido
                FROM $tabla INNER JOIN $tabla2
                ON $tabla.id_creador = $tabla2.id
                LIMIT $desde, $cantidad";

        } else {

            // CONSULTA SIN PAGINACION
            $sql = "SELECT 
                    $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, $tabla.imagen, $tabla.precio, $tabla.id_creador,
                    $tabla2.nombre, $tabla2.apellido
                FROM $tabla INNER JOIN $tabla2
                ON $tabla.id_creador = $tabla2.id";

        }

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);

        $stmt->close();

        $stmt = null;
    }

    static public function create($tabla, $datos)
    {

        $sql = "INSERT INTO 
                    $tabla (	
                            titulo,	
                            descripcion,
                            instructor,
                            imagen,
                            precio,
                            id_creador,
                            created_at,
                            updated_at)
                    VALUES (	
                            :titulo,	
                            :descripcion,
                            :instructor,
                            :imagen,
                            :precio,
                            :id_creador,
                            :created_at,
                            :updated_at)
        ";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->bindParam(":titulo", $datos['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos['instructor'], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos['precio'], PDO::PARAM_STR);
        $stmt->bindParam(":id_creador", $datos['id_creador'], PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $datos['created_at'], PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos['updated_at'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            print_r(Conexion::conectar()->errorInfo());
        }
    }

    static public function show($tabla, $tabla2, $id)
    {

        $sql = "SELECT 
                    $tabla.id, $tabla.titulo, $tabla.descripcion, $tabla.instructor, $tabla.imagen, $tabla.precio, $tabla.id_creador,
                    $tabla2.nombre, $tabla2.apellido
                FROM $tabla INNER JOIN $tabla2
                ON $tabla.id_creador = $tabla2.id
                WHERE $tabla.id = :id";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            return $stmt->fetchAll(PDO::FETCH_CLASS);

            $stmt->close();

            $stmt = null;
        } else {

            print_r(Conexion::conectar()->errorInfo());
        }
    }

    static public function update($tabla, $datos)
    {

        $sql = "UPDATE $tabla
                SET     titulo = :titulo,	
                        descripcion = :descripcion,
                        instructor= :instructor,
                        imagen= :imagen,
                        precio= :precio,
                        updated_at= :updated_at
                WHERE id = :id";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->bindParam(":titulo", $datos['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos['instructor'], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos['imagen'], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos['precio'], PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos['updated_at'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_STR);

        if ($stmt->execute()) {

            return "ok";
        } else {

            print_r(Conexion::conectar()->errorInfo());
        }
    }

    static public function delete($tabla, $id)
    {

        $sql = "DELETE FROM $tabla
                WHERE id = :id";

        $stmt = Conexion::conectar()->prepare($sql);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            return "ok";
        } else {

            print_r(Conexion::conectar()->errorInfo());
        }
    }
}
