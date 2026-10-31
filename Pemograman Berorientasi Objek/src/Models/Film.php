<?php
namespace App\Models;

use App\Contracts\StokFilm;
use InvalidArgumentException;

class Film implements StokFilm
{
    private string $kodeFilm;
    private string $judul;
    private string $genre;
    private int $tahunRilis;
    private int $jumlahStok;

    public function __construct(string $kodeFilm, string $judul, string $genre, int $tahunRilis, int $jumlahStok)
    {
        $this->kodeFilm = $kodeFilm;
        $this->judul = $judul;
        $this->genre = $genre;
        $this->tahunRilis = $tahunRilis;
        $this->jumlahStok = $jumlahStok;
    }

    public function getFilmInfo(): void
    {
        echo "[{$this->kodeFilm}] {$this->judul} | {$this->genre} | {$this->tahunRilis} | stok: {$this->jumlahStok}" . PHP_EOL;
    }

    public function kurangiStok(int $jumlah): bool
    {
        if ($jumlah <= 0 || $this->jumlahStok < $jumlah) {
            return false;
        }
        $this->jumlahStok -= $jumlah;
        return true;
    }

    public function tambahStok(int $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah stok yang ditambahkan harus lebih dari 0.');
        }
        $this->jumlahStok += $jumlah;
    }

    public function getKodeFilm(): string
    {
        return $this->kodeFilm;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function getJumlahStok(): int
    {
        return $this->jumlahStok;
    }
}
