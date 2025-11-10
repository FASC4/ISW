<?php
require __DIR__ . '/includes/init.php';

// Verificar que se recibe un ID y que sea válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Eliminar el cliente de la base de datos
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Redirigir después de la eliminación con un mensaje de éxito
        header('Location: ed_clientes.php?message=Cliente eliminado con éxito');
    } catch (PDOException $e) {
        // En caso de error, redirigir con mensaje de error
        header('Location: ed_clientes.php?error=Error al eliminar el cliente: ' . $e->getMessage());
    }
} else {
    // Si no se recibe un ID válido
    header('Location: ed_clientes.php?error=ID inválido');
}
