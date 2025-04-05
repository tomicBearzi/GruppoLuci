<?php
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $conn = openConnection();

    $stmt = $conn->prepare("SELECT id FROM users WHERE confirmation_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE users SET is_confirmed = TRUE, confirmation_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        header("Location: login.php?confirmed=1");
        exit();
    } else {
        $stmt->close();
        echo "Invalid token.";
    }
} else {
    echo "No token provided.";
}
?>