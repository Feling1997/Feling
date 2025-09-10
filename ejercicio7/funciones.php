<?php

function calcularPromedio($notas) {
    $promedio = 0;

    $cantidad = count($notas);
    if ($cantidad > 0) {
        $suma = array_sum($notas);
        $promedio = $suma / $cantidad;
    }

    return $promedio;
}

function agregarEstudiante($conn, $nombre, $edad, $carrera_id, $notas) {
    $resultado = true;

    $stmt = $conn->prepare("INSERT INTO alumnos (nombre, edad, carrera_id) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sii", $nombre, $edad, $carrera_id);
        if (!$stmt->execute()) {
            $resultado = "Error al insertar alumno: " . $stmt->error;
        } else {
            $alumno_id = $stmt->insert_id;

            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO notas (alumno_id, materia_id, nota) VALUES (?, ?, ?)");
            if ($stmt) {
                foreach ($notas as $materia_id => $listaNotas) {
                    foreach ($listaNotas as $nota) {
                        $stmt->bind_param("iid", $alumno_id, $materia_id, $nota);
                        if (!$stmt->execute()) {
                            $resultado = "Error al insertar nota: " . $stmt->error;
                            break 2; // salir de ambos foreach
        }
    }
}

                $stmt->close();
            } else {
                $resultado = "Error en la preparación de consulta para notas: " . $conn->error;
            }
        }
    } else {
        $resultado = "Error en la preparación de consulta para alumno: " . $conn->error;
    }

    return $resultado;
}

function modificarEstudiante($conn, $id, $nombre, $edad, $carrera_id) {
    $resultado = true;

    $stmt = $conn->prepare("UPDATE alumnos SET nombre = ?, edad = ?, carrera_id = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("siii", $nombre, $edad, $carrera_id, $id);
        if (!$stmt->execute()) {
            $resultado = "Error al modificar alumno: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $resultado = "Error en la preparación de consulta para modificar alumno: " . $conn->error;
    }

    return $resultado;
}

function eliminarEstudiante($conn, $id) {
    $resultado = true;

    $stmt = $conn->prepare("DELETE FROM alumnos WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $resultado = "Error al eliminar alumno: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $resultado = "Error en la preparación de consulta para eliminar alumno: " . $conn->error;
    }

    return $resultado;
}