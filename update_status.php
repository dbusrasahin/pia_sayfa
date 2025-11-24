<?php

header('Content-Type: application/json');
include 'baglanti.php';


$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);


if (isset($data['tc_kimlik']) && isset($data['yeni_durum'])) {
    
    $tc = $data['tc_kimlik'];
    $durum = $data['yeni_durum'];

    // Güvenlik temizliği
    $tc = $conn->real_escape_string($tc);
    $durum = $conn->real_escape_string($durum);

    // Güncelleme Sorgusu (TC Kimlik Numarasına göre)
    $sql = "UPDATE basvurular SET durum = '$durum' WHERE tc_kimlik = '$tc'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Eksik veri gönderildi.']);
}
?>