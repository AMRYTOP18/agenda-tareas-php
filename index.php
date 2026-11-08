<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/functions.php';
$archivo = __DIR__ . '/data/tareas.json';
if (!is_dir(dirname($archivo))) mkdir(dirname($archivo), 0775, true);
if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) {
        http_response_code(403);
        exit('Solicitud no autorizada.');
    }
    $accion = (string)($_POST['accion'] ?? '');
    $tareas = leerTareas($archivo);
    if ($accion === 'agregar') {
        $descripcion = (string)($_POST['descripcion'] ?? '');
        if (validarDescripcion($descripcion) === '' || strlen(trim($descripcion)) > 300) $error = 'Escribe una tarea de entre 1 y 300 caracteres.';
        else $tareas = agregarTarea($tareas, $descripcion);
    } elseif ($accion === 'completar') {
        $tareas = completarTarea($tareas, (string)($_POST['id'] ?? ''));
    } elseif ($accion === 'eliminar') {
        $tareas = eliminarTarea($tareas, (string)($_POST['id'] ?? ''));
    } else $error = 'Acción desconocida.';
    if ($error === '') {
        if (!guardarTareas($archivo, $tareas)) $error = 'No se pudieron guardar los cambios.';
        else { header('Location: index.php', true, 303); exit; }
    }
}
$tareas = leerTareas($archivo);
$total = count($tareas);
$completadas = count(array_filter($tareas, fn($t) => !empty($t['completada'])));
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Agenda de tareas</title><link rel="stylesheet" href="style.css"></head>
<body><main class="app"><header><div class="eyebrow">MINIPROYECTO · CALIDAD DE SOFTWARE</div><h1>Mi agenda de tareas</h1><p>Organiza tus pendientes de forma fácil y rápida.</p></header>
<section class="stats"><div><strong><?= $total ?></strong><span>Total de tareas</span></div><div><strong><?= $total - $completadas ?></strong><span>Pendientes</span></div><div><strong><?= $completadas ?></strong><span>Completadas</span></div></section>
<section class="panel"><h2>Agregar nueva tarea</h2><?php if ($error !== ''): ?><p class="error" role="alert"><?= e($error) ?></p><?php endif; ?><form method="post" class="add"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="accion" value="agregar"><input type="text" name="descripcion" placeholder="Ej. Terminar informe de calidad" maxlength="300" required aria-label="Descripción de la tarea"><button type="submit">+ Agregar</button></form></section>
<section class="panel"><div class="heading"><h2>Mis tareas</h2><span class="count"><?= $total ?> registradas</span></div><?php if (!$tareas): ?><p class="empty">Aún no tienes tareas. ¡Agrega la primera!</p><?php else: ?><ul class="tasks"><?php foreach ($tareas as $t): ?><li><div class="task-content"><span class="dot <?= !empty($t['completada']) ? 'done' : '' ?>"></span><span class="task-text <?= !empty($t['completada']) ? 'finished' : '' ?>"><?= e((string)$t['descripcion']) ?></span></div><div class="actions"><?php if (empty($t['completada'])): ?><form method="post"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="accion" value="completar"><input type="hidden" name="id" value="<?= e((string)$t['id']) ?>"><button class="complete" type="submit">✓ Completar</button></form><?php else: ?><span class="status">Completada</span><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>"><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= e((string)$t['id']) ?>"><button class="delete" type="submit" aria-label="Eliminar tarea">Eliminar</button></form></div></li><?php endforeach; ?></ul><?php endif; ?></section><footer>Proyecto académico · PHP + HTML + CSS · Validaciones y pruebas básicas</footer></main></body></html>
