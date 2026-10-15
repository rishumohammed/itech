<?php
require_once __DIR__ . '/config/config.php';

$sql = file_get_contents(__DIR__ . '/itech_v6_migration.sql');

// Fix passwords with real hashes
$adminPass = password_hash('admin123', PASSWORD_DEFAULT);
$subPass   = password_hash('subadmin123', PASSWORD_DEFAULT);

$sql = str_replace(
    "'$2y$10$C8iU0L/1l0bA1qG8k1u1e.v7G9Y7E8z1h8P6/E1o8i5r4v3w2y1q0'",
    "'$adminPass'",
    $sql
);
$sql = str_replace(
    "'$2y$10$S9V1P6U6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6.'",
    "'$subPass'",
    $sql
);

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($res = mysqli_store_result($conn)) {
            mysqli_free_result($res);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
    echo "Migration successful!";
} else {
    echo "Migration failed: " . mysqli_error($conn);
}
?>
