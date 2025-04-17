<?php
session_start();
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Errore: Metodo di richiesta non valido.");
    echo json_encode(['success' => false, 'message' => 'Metodo di richiesta non valido.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Log dei parametri GET
error_log("Parametri GET: " . json_encode($_GET));

// Forza la lettura dei parametri GET
$token = $_GET['token'] ?? $data['token'] ?? null;
$visitor_number = $_GET['visitor'] ?? $data['visitor'] ?? null;

// Log dei valori ricevuti
error_log("Token: $token, Visitor: $visitor_number");

if (!$token || !$visitor_number) {
    error_log("Errore: Token o numero visitatore non forniti.");
    echo json_encode(['success' => false, 'message' => 'Token o numero visitatore non forniti.']);
    exit();
}

// Debug: stampa il token e il numero del visitatore ricevuti
error_log("Token ricevuto: $token, Numero visitatore: $visitor_number");

$visit_code = $token;
$visitor_number = (int) $visitor_number;

$conn = openConnection();

// Controlla se il visitatore è già registrato come "Inside"
$stmt = $conn->prepare("SELECT status FROM access WHERE visit_code = ? AND visitor_number = ?");
if (!$stmt) {
    error_log("Errore nella preparazione della query: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
    exit();
}

$stmt->bind_param("si", $visit_code, $visitor_number);
if (!$stmt->execute()) {
    error_log("Errore nell'esecuzione della query: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'esecuzione della query.']);
    exit();
}

$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

if ($status === 'Inside') {
    error_log("Il visitatore $visitor_number è già all'interno.");
    echo json_encode(['success' => false, 'message' => 'Il visitatore è già all\'interno.']);
    exit();
}

// Aggiorna o inserisci lo stato del visitatore nella tabella `access`
$stmt = $conn->prepare("INSERT INTO access (visit_code, visitor_number, status) VALUES (?, ?, 'Inside')
                        ON DUPLICATE KEY UPDATE status = 'Inside', timestamp = CURRENT_TIMESTAMP");
if (!$stmt) {
    error_log("Errore nella preparazione della query di inserimento: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Errore interno del server.']);
    exit();
}

$stmt->bind_param("si", $visit_code, $visitor_number);
if (!$stmt->execute()) {
    error_log("Errore nell'esecuzione della query di inserimento: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento del database.']);
    exit();
}
$stmt->close();

$conn->close();

echo json_encode(['success' => true, 'message' => 'Ingresso registrato con successo.']);
?>