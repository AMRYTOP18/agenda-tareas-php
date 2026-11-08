<?php
declare(strict_types=1);
function validarDescripcion(string $texto): string { return trim($texto); }
function leerTareas(string $archivo): array {
    if (!file_exists($archivo)) return [];
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido === false ? '' : $contenido, true);
    return is_array($datos) ? $datos : [];
}
function guardarTareas(string $archivo, array $tareas): bool {
    return file_put_contents($archivo, json_encode(array_values($tareas), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) !== false;
}
function agregarTarea(array $tareas, string $descripcion): array {
    $descripcion = validarDescripcion($descripcion);
    if ($descripcion === '' || strlen($descripcion) > 300) return $tareas;
    $tareas[] = ['id' => bin2hex(random_bytes(8)), 'descripcion' => $descripcion, 'completada' => false];
    return $tareas;
}
function completarTarea(array $tareas, string $id): array {
    foreach ($tareas as &$tarea) if (isset($tarea['id']) && $tarea['id'] === $id) $tarea['completada'] = true;
    unset($tarea);
    return $tareas;
}
function eliminarTarea(array $tareas, string $id): array {
    return array_values(array_filter($tareas, fn($tarea) => ($tarea['id'] ?? '') !== $id));
}
