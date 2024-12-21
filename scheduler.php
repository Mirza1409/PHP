<?php

require_once 'includes/db.php';

$query = "SELECT * FROM member WHERE DATEDIFF(berlaku_s_d, NOW()) <= 7";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $phone = $row['no_hp'];
    $message = "Halo, " . $row['username'] . ". Member anda akan berakhir kurang dari 7 hari. Segera perpanjang member anda.";
    notify($phone, $message);
}
