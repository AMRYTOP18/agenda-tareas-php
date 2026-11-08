# Agenda de tareas PHP
Miniproyecto académico de calidad de software. Permite agregar, consultar, completar y eliminar tareas.

## Requisitos
PHP 8.0+ y navegador web. No requiere base de datos.

## Ejecutar
En la carpeta del proyecto:
```
php -S localhost:8000
```
Abrir http://localhost:8000

Con XAMPP, colocar la carpeta en `htdocs` y visitar `http://localhost/agenda_php/` con Apache iniciado.

## Pruebas
```
php tests/test_agenda.php
```

## Calidad
Validación de descripciones, escape HTML, token CSRF, escritura JSON con bloqueo y pruebas básicas.

## Limitaciones
Prototipo para uso local y un solo usuario. No incluye autenticación ni sincronización concurrente de operaciones; no publicarlo en Internet sin reforzar seguridad y persistencia.
