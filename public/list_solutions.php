<?php
$conn = mysqli_connect("localhost", "root", "", "itech_db");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }
$res = mysqli_query($conn, "SELECT title, slug, image FROM solutions ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['title'] . " | " . $row['slug'] . " | " . ($row['image'] ?: "NONE") . PHP_EOL;
}
mysqli_close($conn);
?>
