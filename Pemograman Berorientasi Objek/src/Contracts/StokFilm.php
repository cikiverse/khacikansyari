<?php
namespace App\Contracts;

interface StokFilm
{
    public function kurangiStok(int $jumlah): bool;

    public function tambahStok(int $jumlah): void;
}
