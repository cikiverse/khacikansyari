<?php
namespace App\Models;

use App\Contracts\PembuatKode;
use DateTimeImmutable;

class Pelanggan
{
    private string $idPelanggan;
    private string $nama;
    private string $nomorTelepon;
    private string $alamat;
    private PembuatKode $pembuatKode;
    private array $daftarPenyewaan = [];

    public function __construct(
        string $idPelanggan,
        string $nama,
        string $nomorTelepon,
        string $alamat,
        PembuatKode $pembuatKode
    ) {
        $this->idPelanggan = $idPelanggan;
        $this->nama = $nama;
        $this->nomorTelepon = $nomorTelepon;
        $this->alamat = $alamat;
        $this->pembuatKode = $pembuatKode;
    }

    public function getProfil(): void
    {
        echo "[{$this->idPelanggan}] {$this->nama} | {$this->nomorTelepon} | {$this->alamat}" . PHP_EOL;
    }

    public function lakukanPenyewaan(Film $film, ?DateTimeImmutable $tanggalSewa = null): void
    {
        $tanggalSewa = $tanggalSewa ?? new DateTimeImmutable('today');
        $penyewaan = new Penyewaan(
            $this->pembuatKode->buat(),
            $this,
            $film,
            $tanggalSewa,
            $tanggalSewa->modify('+3 days')
        );
        $this->daftarPenyewaan[] = $penyewaan;
    }

    public function getIdPelanggan(): string
    {
        return $this->idPelanggan;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getDaftarPenyewaan(): array
    {
        return $this->daftarPenyewaan;
    }

    public function getPenyewaanTerakhir(): ?Penyewaan
    {
        $kunci = array_key_last($this->daftarPenyewaan);
        return $kunci === null ? null : $this->daftarPenyewaan[$kunci];
    }
}
