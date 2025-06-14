Berikut adalah **README** untuk proyek UAP berbasis sistem penitipan hewan *PawLand*, ditulis dengan gaya dan struktur serupa dengan `pdtbank`:

---

# 🐾 PawLand (Contoh Proyek UAP)

**PawLand** adalah sistem penitipan hewan berbasis web (PHP & MySQL) yang mendukung fitur transaksi penitipan hewan seperti kucing dan anjing secara aman dan konsisten. Proyek ini mengimplementasikan stored procedure, trigger, transaction, *stored function*, serta backup otomatis menggunakan `mysqldump`. Sistem ini juga dirancang agar selaras dengan prinsip dasar **Pemrosesan Data Terdistribusi**.

![image](https://github.com/user-attachments/assets/535c75ca-9b46-4c59-a76a-9dbf0fbe4afe)


### 📌 Detail Konsep

Sistem PawLand dibangun untuk mengelola layanan penitipan hewan secara efisien. Fokusnya adalah menjamin validitas data dan konsistensi transaksi antara pengguna dan manajemen sistem penitipan.

---

## 🧠 Stored Procedure

Stored procedure di PawLand menyimpan logika utama sistem langsung di database. Hal ini memungkinkan alur proses penitipan dijalankan secara aman dan efisien tanpa tergantung pada kode PHP saja.

![image](https://github.com/user-attachments/assets/665fbbb6-e2b0-4dfd-a591-6ae482cfe8f0)


### ✅ Contoh Procedure:

* **`tambah_penitipan`**
  Menambahkan data penitipan lengkap dengan perhitungan biaya total berdasarkan tanggal masuk, tanggal keluar, dan jenis hewan.

$stmt = $conn->prepare("INSERT INTO penitipan (user_id, nama_hewan, jenis, durasi_hari, tanggal_mulai, status, biaya_total) VALUES (?, ?, ?, ?, ?, 'Diproses', ?)");
    $stmt->bind_param("issisi", $user_id, $nama, $jenis, $durasi, $tanggalMulai, $biaya_total);
    $stmt->execute();

* **`update_status_penitipan`**
  Memperbarui status penitipan menjadi *Selesai* dan menyimpan status baru.

$stmt = $conn->prepare("SELECT nama_hewan, jenis, durasi_hari, status, biaya_total FROM penitipan WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

---

## 🚨 Trigger

Trigger di PawLand berperan sebagai pelindung integritas data otomatis. Contoh:

![image](https://github.com/user-attachments/assets/b6000500-86cb-49b7-b10c-b43da4443d85)

* **`after_update_status_selesai`**
  Otomatis mencetak log jika status berubah menjadi *Selesai*.
  
INSERT INTO log_penitipan (penitipan_id, user_id, aksi, status_lama)
        VALUES (NEW.id, NEW.user_id, CONCAT('Update status ke "', NEW.status, '"'), OLD.status);
        
Trigger ini memastikan proses penting seperti validasi tanggal dan status tetap berjalan meskipun dilakukan dari luar aplikasi.

---

## 🔄 Transaction (Transaksi)

Setiap data penitipan dilakukan dalam 1 unit transaksi:

```php
$conn->begin_transaction();
try {
    // insert penitipan
    $stmt = $conn->prepare(...);
    $stmt->execute();

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
}
```

Hal ini mencegah data setengah jadi (seperti hewan dititipkan tapi tanpa biaya dihitung) agar tidak masuk ke sistem.

---

## 🧮 Stored Function

Stored function digunakan untuk menghitung biaya penitipan berdasarkan jumlah hari dan tarif harian:

```sql
CREATE FUNCTION hitung_biaya(masuk DATE, keluar DATE, tarif_harian INT)
RETURNS INT
BEGIN
    DECLARE durasi INT;
    SET durasi = DATEDIFF(keluar, masuk);
    RETURN durasi * tarif_harian;
END;
```

### Pemanggilan:

```sql
SELECT hitung_biaya('2025-06-10', '2025-06-13', 30000);
-- Output: 90000
```

---

## 💾 Backup Otomatis

PawLand mendukung fitur backup otomatis menggunakan `mysqldump`:

```php
$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = "mysqldump --user=$user --host=$host $dbname > $backup_file";
system($command);
```

Backup dapat dijadwalkan menggunakan Task Scheduler agar data rutin diamankan.

---

## 🧩 Relevansi dengan Pemrosesan Data Terdistribusi

* **Konsistensi**: Logika utama seperti penghitungan biaya dan validasi tanggal ditanam di database, bukan di aplikasi.
* **Reliabilitas**: Dengan trigger dan transaksi, sistem tetap valid walaupun ada bug di aplikasi.
* **Integritas**: Bahkan jika PawLand digunakan dari berbagai client (web, API mobile, dsb), semua proses berjalan sama karena logika utamanya tersimpan di database.

---

## 📁 Struktur Folder

```
pawland/
├── index.php
├── login.php
├── register.php
├── tambah_penitipan.php
├── list_penitipan.php
├── proses_keluar.php
├── backup.php
└── db.php

