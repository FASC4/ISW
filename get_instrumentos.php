<?php
require __DIR__ . '/includes/init.php';

// Comprobar que la magnitud se ha enviado
if (isset($_GET['magnitud'])) {
    $magnitud = $_GET['magnitud'];

    // Consultar los instrumentos de acuerdo con la magnitud seleccionada
    try {
        $sql = "SELECT id, nombre FROM instrumentos WHERE magnitud = :magnitud ORDER BY nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':magnitud' => $magnitud]);
        $instrumentos = $stmt->fetchAll();

        // Enviar los instrumentos como respuesta en formato JSON
        echo json_encode($instrumentos);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al cargar los instrumentos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No se recibió la magnitud']);
}
?>
