<?php
namespace App\Models;

use App\Exceptions\StokFilmHabisException;
use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

class Penyewaan
{
    public const STATUS_DISEWA = 'Disewa';
    public const STATUS_TERLAMBAT = 'Terlambat';
    public const STATUS_DIKEMBALIKAN = 'Dikembalikan';

    private string $idPenyewaan;
    private DateTimeImmutable $tanggalSewa;
    private DateTimeImmutable $tanggalKembali;
    private string $statusPenyewaan;
    private Pelanggan $pelanggan;
    private array $daftarFilm = [];

    public function __construct(
        string $idPenyewaan,
        Pelanggan $pelanggan,
        Film $film,
        DateTimeImmutable $tanggalSewa,
        DateTimeImmutable $tanggalKembali
    ) {
        $this->idPenyewaan = $idPenyewaan;
        $this->pelanggan = $pelanggan;
        $this->tanggalSewa = $tanggalSewa;
        $this->tanggalKembali = $tanggalKembali;
        $this->statusPenyewaan = self::STATUS_DISEWA;
        // Multiplisitas 1..*: setiap penyewaan wajib memuat minimal satu film
        $this->tambahFilm($film);
    }

    public function tambahFilm(Film $film): void
    {
        if ($this->statusPenyewaan === self::STATUS_DIKEMBALIKAN) {
            throw new LogicException('Penyewaan sudah selesai, film tidak dapat ditambahkan.');
        }
        if (!$film->kurangiStok(1)) {
            throw new StokFilmHabisException("Stok film '{$film->getJudul()}' habis.");
        }
        $this->daftarFilm[] = $film;
    }

    public function ubahStatus(string $statusBaru): void
    {
        $statusValid = [self::STATUS_DISEWA, self::STATUS_TERLAMBAT, self::STATUS_DIKEMBALIKAN];
        if (!in_array($statusBaru, $statusValid, true)) {
            throw new InvalidArgumentException("Status '{$statusBaru}' tidak dikenal.");
        }
        if ($this->statusPenyewaan === self::STATUS_DIKEMBALIKAN) {
            throw new LogicException('Penyewaan sudah dikembalikan, status tidak dapat diubah.');
        }
        if ($statusBaru === self::STATUS_DIKEMBALIKAN) {
            foreach ($this->daftarFilm as $film) {
                $film->tambahStok(1);
            }
        }
        $this->statusPenyewaan = $statusBaru;
    }

    public function getIdPenyewaan(): string
    {
        return $this->idPenyewaan;
    }

    public function getTanggalSewa(): DateTimeImmutable
    {
        return $this->tanggalSewa;
    }

    public function getTanggalKembali(): DateTimeImmutable
    {
        return $this->tanggalKembali;
    }

    public function getStatusPenyewaan(): string
    {
        return $this->statusPenyewaan;
    }

    public function getPelanggan(): Pelanggan
    {
        return $this->pelanggan;
    }

    public function getDaftarFilm(): array
    {
        return $this->daftarFilm;
    }
}
