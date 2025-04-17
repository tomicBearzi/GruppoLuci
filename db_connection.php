<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('/home/tomic.bearzi.info/');
$dotenv->load();

$encryptionKey = \Defuse\Crypto\Key::loadFromAsciiSafeString(file_get_contents('/home/tomic.bearzi.info/encryption.key'));

function decryptEnvVar($encryptedVar, $encryptionKey)
{
    return \Defuse\Crypto\Crypto::decrypt($encryptedVar, $encryptionKey);
}

function openConnection()
{
    global $encryptionKey;

    $servername = decryptEnvVar($_ENV['DB_SERVERNAME'], $encryptionKey);
    $username = decryptEnvVar($_ENV['DB_USERNAME'], $encryptionKey);
    $password = decryptEnvVar($_ENV['DB_PASSWORD'], $encryptionKey);
    $dbname = decryptEnvVar($_ENV['DB_NAME'], $encryptionKey);

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>