<?php
// sifre_degistir.php
header('Content-Type: application/json');
include 'baglanti.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['username']) && isset($input['old_pass']) && isset($input['new_pass'])) {
    
    $user = $conn->real_escape_string($input['username']);
    $old = $input['old_pass'];
    $new = $input['new_pass'];

    // 1. Önce eski şifre doğru mu diye kontrol et
    $sql = "SELECT * FROM yoneticiler WHERE kullanici_adi = '$user' AND sifre = '$old'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 2. Doğruysa yeni şifreyle güncelle
        $sql_update = "UPDATE yoneticiler SET sifre = '$new' WHERE kullanici_adi = '$user'";
        if ($conn->query($sql_update) === TRUE) {
            echo json_encode(['message' => '✅ Şifreniz başarıyla değiştirildi!']);
        } else {
            echo json_encode(['message' => 'Hata oluştu.']);
        }
    } else {
        echo json_encode(['message' => '❌ Hata: Kullanıcı adı veya eski şifre yanlış.']);
    }

} else {
    echo json_encode(['message' => 'Eksik bilgi.']);
}
?>