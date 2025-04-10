<?php
session_start();
require 'vendor/autoload.php'; // Assicurati che FPDF e Endroid\QrCode siano installati
include 'db_connection.php';

require_once 'vendor/setasign/fpdf/fpdf.php'; // Importa manualmente FPDF

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['visit_code'])) {
    $visit_code = $_POST['visit_code'];

    $conn = openConnection();

    // Recupera i dettagli della visita
    $stmt = $conn->prepare("SELECT date, start_time, visitor_count FROM visit WHERE code = ?");
    $stmt->bind_param("s", $visit_code);
    $stmt->execute();
    $stmt->bind_result($date, $start_time, $visitor_count);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if (!$date || !$start_time || !$visitor_count) {
        die("Dettagli della visita non trovati.");
    }

    // Inizializza FPDF
    $pdf = new FPDF(); // Usa la classe FPDF senza namespace
    $pdf->SetAutoPageBreak(false);

    // Genera i badge
    for ($i = 1; $i <= $visitor_count; $i++) {
        if (($i - 1) % 2 == 0) {
            $pdf->AddPage(); // Aggiungi una nuova pagina ogni 2 badge
        }

        // Posizione del badge (alto o basso)
        $y_offset = (($i - 1) % 2) * 140;

        // Disegna il rettangolo del badge
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Rect(10, 10 + $y_offset, 190, 120, 'F');

        // Aggiungi il titolo
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetXY(20, 20 + $y_offset);
        $pdf->Cell(0, 10, "Badge Visita", 0, 1, 'L');

        // Aggiungi il numero del badge
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(20, 35 + $y_offset);
        $pdf->Cell(0, 10, "Badge $i/$visitor_count", 0, 1, 'L');

        // Aggiungi la data e l'ora di inizio
        $pdf->SetXY(20, 45 + $y_offset);
        $pdf->Cell(0, 10, "Data: $date", 0, 1, 'L');
        $pdf->SetXY(20, 55 + $y_offset);
        $pdf->Cell(0, 10, "Ora Inizio: $start_time", 0, 1, 'L');

        // Genera il QR code
        $qr_code_url = "http://tomic.bearzi.info/luci/register_access.php?token=$visit_code&visitor=$i";
        $qr_code_file = tempnam(sys_get_temp_dir(), 'qrcode') . '.png';
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qr_code_url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->size(300)
            ->margin(10)
            ->build();

        // Salva il QR code in un file temporaneo
        file_put_contents($qr_code_file, $qrCode->getString());

        // Aggiungi il QR code al PDF
        $pdf->Image($qr_code_file, 150, 20 + $y_offset, 40, 40);
        unlink($qr_code_file); // Elimina il file temporaneo
    }

    // Output del PDF
    $pdf->Output("D", "Badge_Visita_$visit_code.pdf");
    exit();
} else {
    echo "Richiesta non valida.";
}
?>