<?php
require_once __DIR__ . '/../functions.php';
function verificar(bool $condicion, string $nombre): void {
    if (!$condicion) { echo "FALLO: $nombre\n"; exit(1); }
    echo "OK: $nombre\n";
}
$tareas = [];
$tareas = agregarTarea($tareas, 'Estudiar PHP');
verificar(count($tareas) === 1, 'Agregar tarea válida');
verificar(count(agregarTarea($tareas, '   ')) === 1, 'Rechazar tarea vacía');
$id = $tareas[0]['id'];
$tareas = completarTarea($tareas, $id);
verificar($tareas[0]['completada'] === true, 'Completar tarea');
verificar(count(eliminarTarea($tareas, 'inexistente')) === 1, 'ID inválido no elimina tareas');
$tareas = eliminarTarea($tareas, $id);
verificar(count($tareas) === 0, 'Eliminar tarea');
echo "Todas las pruebas pasaron.\n";
