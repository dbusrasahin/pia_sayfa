<?php
// export.php - Güzelleştirilmiş Excel Çıktısı
include 'baglanti.php';

// Veritabanı kontrolü
if (!$conn) { die("Veritabanı hatası!"); }

// Dosya adını ve türünü belirle (Excel formatı)
$dosya_adi = "basvurular_" . date('Y-m-d') . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$dosya_adi");
header("Pragma: no-cache");
header("Expires: 0");

// 1. Verileri Çek
$sql = "SELECT * FROM basvurular ORDER BY created_at DESC";
$result = $conn->query($sql);

// Excel'in Türkçe karakterleri tanıması için meta etiketi
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';

// Tabloyu Başlat (Border=1 sayesinde çizgiler görünür)
echo '<table border="1">';

// 2. BAŞLIK SATIRI (Koyu renk ve ortalı)
echo '<tr style="background-color: #f2f2f2; font-weight: bold;">';
echo '<td>TC Kimlik</td>';
echo '<td>Ad Soyad</td>';
echo '<td>Email</td>';
echo '<td>Eğitim</td>';
echo '<td>Motivasyon</td>';
echo '<td>Durum</td>';
echo '<td>Tarih</td>';
echo '</tr>';

// 3. VERİLERİ DÖK
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        // TC (Metin olarak algılansın diye başına style ekledik)
        echo '<td style="mso-number-format:\@">' . $row['tc_kimlik'] . '</td>';
        echo '<td>' . $row['ad_soyad'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['egitim_durumu'] . '</td>';
        echo '<td>' . $row['motivasyon_metni'] . '</td>';
        
        // Duruma göre renklendirme (İsteğe bağlı güzellik)
        $renk = "black";
        if($row['durum'] == 'kabul') $renk = "green";
        if($row['durum'] == 'ret') $renk = "red";
        
        echo '<td style="color:'.$renk.'; font-weight:bold;">' . strtoupper($row['durum']) . '</td>';
        echo '<td>' . $row['created_at'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7">Kayıt bulunamadı.</td></tr>';
}

echo '</table>';
exit();
?>
