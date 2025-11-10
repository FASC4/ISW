<?php
require __DIR__ . '/includes/init.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        // Eliminar el instrumento de la base de datos
        $sql = "DELETE FROM instrumentos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Redirigir de vuelta a la lista de instrumentos
        header('Location: ed_inst.php');
        exit();
    } catch (PDOException $e) {
        // Manejo de errores
        $alerta = 'Error al eliminar el instrumento: ' . $e->getMessage();
        $alertaTipo = 'danger';
    }
} else {
    // Si no se recibe un ID válido
    $alerta = 'Instrumento no encontrado o ID inválido.';
    $alertaTipo = 'danger';
}
?>
