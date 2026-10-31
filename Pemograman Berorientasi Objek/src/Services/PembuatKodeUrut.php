<?php
namespace App\Services;

use App\Contracts\PembuatKode;

class PembuatKodeUrut implements PembuatKode
{
    private string $awalan;
    private int $urutan = 0;

    public function __construct(string $awalan = 'PNY-')
    {
        $this->awalan = $awalan;
    }

    public function buat(): string
    {
        return sprintf('%s%03d', $this->awalan, ++$this->urutan);
    }
}
