<?php

ob_start(); 

include 'baglanti.php';


if (!$conn) {
    die("Veritabanı bağlantı hatası!");
}


$sql = "SELECT * FROM basvurular ORDER BY created_at DESC";
$result = $conn->query($sql);


ob_end_clean();


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=basvurular_listesi.csv');


$output = fopen('php://output', 'w');


fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));


fputcsv($output, array('TC Kimlik', 'Ad Soyad', 'Email', 'Egitim', 'Motivasyon', 'Durum', 'Tarih'));


if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
        $temiz_motivasyon = preg_replace("/[\r\n]+/", " ", $row['motivasyon_metni']);
        
        
        fputcsv($output, array(
            $row['tc_kimlik'],   
            $row['ad_soyad'],
            $row['email'],
            $row['egitim_durumu'],
            $temiz_motivasyon,
            $row['durum'],
            $row['created_at']
        ));
    }
} else {
    // Veri yoksa
    fputcsv($output, array('Kayıtlı başvuru bulunamadı.'));
}

fclose($output);
exit();
?>
