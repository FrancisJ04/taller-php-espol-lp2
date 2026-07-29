<?php

function rutaArchivo($usuario) {
    return "tareas_" . $usuario . ".csv";
}

function siguienteId($usuario) {
    $archivo = rutaArchivo($usuario);
    if (!file_exists($archivo)) return 1;
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $maxId = 0;
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        $id = (int) $campos[0];
        if ($id > $maxId) $maxId = $id;
    }
    return $maxId + 1;
}

function guardarTarea($usuario, $texto) {
    $texto = trim(str_replace(",", " ", $texto));
    if ($texto === "") return;
    $archivo = rutaArchivo($usuario);
    $id = siguienteId($usuario);
    $linea = "$id,$texto,pendiente\n";
    file_put_contents($archivo, $linea, FILE_APPEND);
}

function listarTareas($usuario) {
    $archivo = rutaArchivo($usuario);
    $pendientes = [];
    $completadas = [];
    if (!file_exists($archivo)) {
        return ['pendientes' => $pendientes, 'completadas' => $completadas];
    }
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        $tarea = ['id' => $campos[0], 'texto' => $campos[1], 'estado' => $campos[2]];
        if ($campos[2] === 'completada') {
            $completadas[] = $tarea;
        } else {
            $pendientes[] = $tarea;
        }
    }
    return ['pendientes' => $pendientes, 'completadas' => $completadas];
}

function completarTarea($usuario, $id) {
    $archivo = rutaArchivo($usuario);
    if (!file_exists($archivo)) return;
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $contenido = "";
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        if ($campos[0] == $id) {
            $contenido .= $campos[0] . "," . $campos[1] . ",completada\n";
        } else {
            $contenido .= $linea . "\n";
        }
    }
    file_put_contents($archivo, $contenido);
}

function eliminarTarea($usuario, $id) {
    $archivo = rutaArchivo($usuario);
    if (!file_exists($archivo)) return;
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $contenido = "";
    foreach ($lineas as $linea) {
        $campos = explode(",", $linea);
        if ($campos[0] != $id) {
            $contenido .= $linea . "\n";
        }
    }
    file_put_contents($archivo, $contenido);
}
?>