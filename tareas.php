<?php
session_start();
if (!isset($_SESSION['cedula'])) {
    header("Location: ingreso.php");
    exit;
}
require "tarea.php";

$usuario = $_SESSION['cedula'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['texto'])) {
    guardarTarea($usuario, $_POST['texto']);
    header("Location: tareas.php");
    exit;
}
if (isset($_GET['completar'])) {
    completarTarea($usuario, $_GET['completar']);
    header("Location: tareas.php");
    exit;
}
if (isset($_GET['eliminar'])) {
    eliminarTarea($usuario, $_GET['eliminar']);
    header("Location: tareas.php");
    exit;
}

$tareas = listarTareas($usuario);
$pendientes = $tareas['pendientes'];
$completadas = $tareas['completadas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="contenedor">
    <h1>Mis Tareas</h1>

    <form method="POST" action="tareas.php">
        <label>Nueva tarea:</label>
        <input type="text" name="texto" required>
        <input type="submit" value="Agregar">
    </form>

    <h2>Pendientes</h2>
    <?php if (empty($pendientes)): ?>
        <p>No tienes tareas pendientes.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($pendientes as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['texto']) ?></td>
                <td>
                    <a href="tareas.php?completar=<?= urlencode($t['id']) ?>">Completar</a> |
                    <a href="tareas.php?eliminar=<?= urlencode($t['id']) ?>">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h2>Completadas</h2>
    <?php if (empty($completadas)): ?>
        <p>No tienes tareas completadas.</p>
    <?php else: ?>
        <table>
            <tr><th>Tarea</th><th>Acciones</th></tr>
            <?php foreach ($completadas as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['texto']) ?></td>
                <td><a href="tareas.php?eliminar=<?= urlencode($t['id']) ?>">Eliminar</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p class="ayuda"><a href="logout.php">Cerrar sesión</a></p>
</div>
</body>
</html>