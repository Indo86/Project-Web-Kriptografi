<?php
session_start();
include("../connect.php");

$file = $_GET['namaFile'];
$filePath = '../Assets/files/' . $file;
$key = 'enkripsiRahasia123enkripsiRahasia123'; // Kunci enkripsi (24 byte untuk 3DES)
$cipher = 'des-ede3-cbc'; // Triple DES dalam mode CBC

if (!isset($_SESSION["loginAgen"])) {
    header("Location: ../loginAgen.php");
    exit;
}

if (file_exists($filePath)) {
    // Membaca file terenkripsi
    $encryptedData = file_get_contents($filePath);

    // Menyusun IV dan data terenkripsi
    $iv = '12345678';
    $data = substr($encryptedData, 8); // Data terenkripsi dimulai setelah IV

    // Dekripsi data menggunakan Triple DES
    $decryptedDataWithMetadata = openssl_decrypt($data, $cipher, $key, 0, $iv);

    if ($decryptedDataWithMetadata === false) {
        echo "<script>alert('Dekripsi file gagal!');</script>";
        exit;
    }

    // Memisahkan metadata (nama file asli) dan konten file
    list($originalFileName, $decryptedData) = explode("::", $decryptedDataWithMetadata, 2);

    // Menyimpan file hasil dekripsi dengan nama asli
    $decryptedFilePath = '../Assets/files/' . $originalFileName;
    file_put_contents($decryptedFilePath, $decryptedData);

    // Mengunduh file hasil dekripsi
    header('Content-Description: File Transfer');
    header('Content-Type: ' . mime_content_type($decryptedFilePath)); // MIME Type sesuai file asli
    header('Content-Disposition: attachment; filename="' . basename($decryptedFilePath) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($decryptedFilePath));
    ob_clean();
    flush();
    readfile($decryptedFilePath);
    unlink($decryptedFilePath); // Hapus file dekripsi setelah diunduh
    exit;
} else {
    echo "
    <script>
        alert('File tidak ditemukan!');
        document.location.href = 'halamanTugasAgen.php';
    </script>
    ";
}
?>
