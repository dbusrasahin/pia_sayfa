<?php
include 'baglanti.php';

if ($_POST) {
    $tc = $_POST['tc_kimlik'];
    $ad = $_POST['ad_soyad'];
    $mail = $_POST['email'];
    $egitim = $_POST['egitim_durumu'];
    $motivasyon = $_POST['motivasyon_metni'];
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $durum = 'beklemede';

    $hedef_klasor = "dosyalar/";
    
    // --- GÜVENLİ DOSYA YÜKLEME FONKSİYONU ---
    function dosyaYukle($dosya, $klasor, $on_ek) {
        if(isset($dosya["name"]) && $dosya["name"] != "") {
            // Dosyanın uzantısını al (pdf, jpg vs.)
            $uzanti = strtolower(pathinfo($dosya["name"], PATHINFO_EXTENSION));
            
            // Rastgele benzersiz bir isim oluştur (örn: cv_654a12b.pdf)
            // Bu sayede Türkçe karakter ve boşluk sorunu ASLA yaşanmaz.
            $yeni_ad = $on_ek . "_" . uniqid() . "." . $uzanti;
            
            $hedef_yol = $klasor . $yeni_ad;
            
            if(move_uploaded_file($dosya["tmp_name"], $hedef_yol)) {
                return $hedef_yol; // Başarılıysa yeni yolu döndür
            }
        }
        return ""; // Başarısızsa veya dosya yoksa boş dön
    }
    // -------------------------------------------

    // CV'yi Yükle (Başına 'cv' ekle)
    $ozgecmis_yolu = dosyaYukle($_FILES["ozgecmis"], $hedef_klasor, "cv");

    // Belgeyi Yükle (Başına 'belge' ekle)
    $belge_yolu = dosyaYukle($_FILES["ogrenci_belgesi"], $hedef_klasor, "belge");

    // Veritabanına Yaz
    $sql = "INSERT INTO basvurular (tc_kimlik, ad_soyad, email, egitim_durumu, motivasyon_metni, ozgecmis_path, ogrenci_belgesi_path, ip_adresi, durum) 
            VALUES ('$tc', '$ad', '$mail', '$egitim', '$motivasyon', '$ozgecmis_yolu', '$belge_yolu', '$ip', '$durum')";

    if ($conn->query($sql) === TRUE) {
        echo "<div style='text-align: center; margin-top: 50px; font-family: Arial, sans-serif;'>";
        echo "<h1 style='color: green;'>✅ Başvuru Başarıyla Alındı.</h1>";
        echo "<p>Dosyalar güvenli bir şekilde yüklendi.</p>";
        echo "</div>";
    } else {
        echo "Hata: " . $conn->error;
    }
}
?>