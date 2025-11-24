<?php
// export.php - Excel İndirme Dosyası
include 'baglanti.php';

// Hata raporlamayı açalım ki sorun varsa görelim (Canlıda kapatılabilir)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Dosyanın Excel (CSV) olduğunu tarayıcıya bildir
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=basvuru_listesi.csv');

// Çıktı kanalını aç
$output = fopen('php://output', 'w');

// Türkçe karakterler Excel'de bozulmasın diye BOM (Byte Order Mark) ekle
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// 1. Satır: Sütun Başlıklarını Yaz
fputcsv($output, array('ID', 'TC Kimlik', 'Ad Soyad', 'Email', 'Egitim', 'Motivasyon', 'Durum', 'Tarih'));

// 2. Veritabanından Verileri Çek
$sql = "SELECT * FROM basvurular ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Motivasyon metnindeki alt satıra geçme (enter) karakterlerini temizle
        // Yoksa Excel tablosu bozulur
        $temiz_motivasyon = str_replace(array("\r", "\n"), " ", $row['motivasyon_metni']);
        
        // Satırı CSV dosyasına yaz
        fputcsv($output, array(
            $row['id'],
            $row['tc_kimlik'],
            $row['ad_soyad'],
            $row['email'],
            $row['egitim_durumu'],
            $temiz_motivasyon,
            $row['durum'],
            $row['created_at']
        ));
    }
}

// Dosyayı kapat
fclose($output);
exit();
?>