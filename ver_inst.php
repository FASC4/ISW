<?php
require __DIR__ . '/includes/init.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Obtener detalles del instrumento
        $sql = "SELECT * FROM instrumentos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $instrumento = $stmt->fetch();

        if ($instrumento) {
            // Devolver los detalles en formato JSON
            echo json_encode($instrumento);
        } else {
            echo json_encode(['error' => 'Instrumento no encontrado.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener los detalles: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID inválido.']);
}
