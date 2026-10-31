<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Exceptions\StokFilmHabisException;
use App\Models\Film;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use App\Services\PembuatKodeTanggal;
use App\Services\PembuatKodeUrut;

function tgl(DateTimeImmutable $tanggal): string
{
    return $tanggal->format('Y-m-d');
}

function judulFilm(Penyewaan $penyewaan): string
{
    return implode(', ', array_map(fn (Film $film) => $film->getJudul(), $penyewaan->getDaftarFilm()));
}

try {
    echo "=== 1. Data Film ===" . PHP_EOL;
    $film1 = new Film('F001', 'Interstellar', 'Sci-Fi', 2014, 2);
    $film2 = new Film('F002', 'Parasite', 'Thriller', 2019, 1);
    $film3 = new Film('F003', 'Your Name', 'Animasi', 2016, 3);
    foreach ([$film1, $film2, $film3] as $film) {
        $film->getFilmInfo();
    }

    echo PHP_EOL . "=== 2. Data Pelanggan ===" . PHP_EOL;
    // Dependency Injection: pelanggan hanya mengenal interface PembuatKode
    $budi = new Pelanggan('P001', 'Budi Santoso', '081234567890', 'Pontianak', new PembuatKodeUrut());
    $sari = new Pelanggan('P002', 'Sari Dewi', '085298765432', 'Kubu Raya', new PembuatKodeTanggal(new DateTimeImmutable('2026-10-05')));
    $budi->getProfil();
    $sari->getProfil();

    echo PHP_EOL . "=== 3. Penyewaan Film ===" . PHP_EOL;
    $budi->lakukanPenyewaan($film1, new DateTimeImmutable('2026-10-01'));
    $pny1 = $budi->getPenyewaanTerakhir();
    echo $budi->getNama() . ' menyewa ' . $film1->getJudul() . PHP_EOL;
    echo 'ID penyewaan   : ' . $pny1->getIdPenyewaan() . PHP_EOL;
    echo 'Tanggal sewa   : ' . tgl($pny1->getTanggalSewa()) . PHP_EOL;
    echo 'Tanggal kembali: ' . tgl($pny1->getTanggalKembali()) . PHP_EOL;
    echo 'Status         : ' . $pny1->getStatusPenyewaan() . PHP_EOL;
    echo 'Stok Interstellar tersisa: ' . $film1->getJumlahStok() . PHP_EOL;

    echo PHP_EOL . "=== 4. Satu Penyewaan Memuat Banyak Film (1..*) ===" . PHP_EOL;
    $pny1->tambahFilm($film3);
    echo 'Film pada ' . $pny1->getIdPenyewaan() . ' : ' . judulFilm($pny1) . PHP_EOL;
    echo 'Jumlah film             : ' . count($pny1->getDaftarFilm()) . PHP_EOL;

    echo PHP_EOL . "=== 5. Satu Pelanggan Banyak Penyewaan (1..*) ===" . PHP_EOL;
    $budi->lakukanPenyewaan($film1, new DateTimeImmutable('2026-10-02'));
    $sari->lakukanPenyewaan($film2, new DateTimeImmutable('2026-10-05'));
    $pnySari = $sari->getPenyewaanTerakhir();
    echo $budi->getNama() . ' memiliki ' . count($budi->getDaftarPenyewaan()) . ' penyewaan: ';
    echo implode(', ', array_map(fn (Penyewaan $p) => $p->getIdPenyewaan(), $budi->getDaftarPenyewaan())) . PHP_EOL;
    echo $sari->getNama() . ' memiliki ' . count($sari->getDaftarPenyewaan()) . ' penyewaan: ' . $pnySari->getIdPenyewaan() . PHP_EOL;

    echo PHP_EOL . "=== 6. Penanganan Error ===" . PHP_EOL;
    try {
        $budi->lakukanPenyewaan($film2, new DateTimeImmutable('2026-10-06'));
    } catch (StokFilmHabisException $e) {
        echo 'Penyewaan ditolak : ' . $e->getMessage() . PHP_EOL;
    }
    try {
        $pny1->ubahStatus('Hilang');
    } catch (InvalidArgumentException $e) {
        echo 'Status ditolak    : ' . $e->getMessage() . PHP_EOL;
    }

    echo PHP_EOL . "=== 7. Pengembalian Film ===" . PHP_EOL;
    echo 'Stok sebelum kembali -> Interstellar: ' . $film1->getJumlahStok() . ', Your Name: ' . $film3->getJumlahStok() . PHP_EOL;
    $pny1->ubahStatus(Penyewaan::STATUS_DIKEMBALIKAN);
    echo $pny1->getIdPenyewaan() . ' status: ' . $pny1->getStatusPenyewaan() . PHP_EOL;
    echo 'Stok sesudah kembali -> Interstellar: ' . $film1->getJumlahStok() . ', Your Name: ' . $film3->getJumlahStok() . PHP_EOL;
    try {
        $pny1->ubahStatus(Penyewaan::STATUS_DISEWA);
    } catch (LogicException $e) {
        echo 'Ubah ulang ditolak: ' . $e->getMessage() . PHP_EOL;
    }

    echo PHP_EOL . "=== 8. Keterlambatan ===" . PHP_EOL;
    $pnySari->ubahStatus(Penyewaan::STATUS_TERLAMBAT);
    echo $pnySari->getIdPenyewaan() . ' status: ' . $pnySari->getStatusPenyewaan() . ' (stok Parasite: ' . $film2->getJumlahStok() . ')' . PHP_EOL;
    $pnySari->ubahStatus(Penyewaan::STATUS_DIKEMBALIKAN);
    echo $pnySari->getIdPenyewaan() . ' status: ' . $pnySari->getStatusPenyewaan() . ' (stok Parasite: ' . $film2->getJumlahStok() . ')' . PHP_EOL;

    echo PHP_EOL . "=== 9. Ringkasan Akhir ===" . PHP_EOL;
    foreach ([$budi, $sari] as $pelanggan) {
        foreach ($pelanggan->getDaftarPenyewaan() as $p) {
            echo str_pad($p->getIdPenyewaan(), 18) . '| ' . str_pad($pelanggan->getNama(), 13)
                . '| ' . str_pad($p->getStatusPenyewaan(), 13) . '| ' . judulFilm($p) . PHP_EOL;
        }
    }
    foreach ([$film1, $film2, $film3] as $film) {
        $film->getFilmInfo();
    }
} catch (Throwable $e) {
    echo 'Terjadi Error: ' . $e->getMessage();
}
