<?php
// ayar_guncelle.php - DOSYA YÖNTEMİ
header('Content-Type: application/json');

// Panelden gelen veriyi al
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['durum'])) {
    // Eğer durum true (tikli) ise '1', false ise '0' olsun
    $yeni_deger = ($input['durum'] === true) ? '1' : '0';
    
    // Veritabanı yerine direkt senin oluşturduğun 'durum.txt' dosyasına yazıyoruz
    file_put_contents('durum.txt', $yeni_deger);
    
    echo json_encode(['success' => true]);
}
?>