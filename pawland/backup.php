<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "pawland"; // ganti dengan nama database kamu

$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = "mysqldump --user=$user --host=$host $dbname > $backup_file";

// Jalankan perintah
system($command, $output);

// Feedback
if ($output === 0) {
    echo "Backup berhasil! File: $backup_file";
} else {
    echo "Gagal backup!";
}
?>
