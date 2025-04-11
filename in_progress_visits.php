<?php
require 'db_connection.php';
$conn = openConnection();

$result = $conn->query("SELECT * FROM visit WHERE visit_status = 'In_Progress'");
?>

<h2>Visite in corso</h2>
<table border="1">
    <tr>
        <th>Codice</th>
        <th>Email</th>
        <th>Data</th>
        <th>Ora Inizio</th>
        <th>Visitatori</th>
        <th>Descrizione</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['code'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['start_time'] ?></td>
        <td><?= $row['visitor_count'] ?></td>
        <td><?= $row['description'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
