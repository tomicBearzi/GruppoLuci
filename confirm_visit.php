<?php
include 'db_connection.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $conn = openConnection();

    // Verifica che la visita esista e sia in stato "Pending"
    $stmt = $conn->prepare("SELECT code FROM visit WHERE code = ? AND visit_status = 'Pending'");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        // Aggiorna lo stato della visita a "Accepted"
        $stmt = $conn->prepare("UPDATE visit SET visit_status = 'Accepted' WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->close();

        echo "✅ Visita confermata con successo.";
    } else {
        $stmt->close();
        echo "❌ Codice visita non valido o già confermato.";
    }

    $conn->close();
} else {
    echo "❌ Nessun codice fornito.";
}
?>