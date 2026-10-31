<?php
namespace App\Services;

use App\Contracts\PembuatKode;
use DateTimeImmutable;

class PembuatKodeTanggal implements PembuatKode
{
    private DateTimeImmutable $tanggal;
    private int $urutan = 0;

    public function __construct(DateTimeImmutable $tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function buat(): string
    {
        return sprintf('PNY-%s-%03d', $this->tanggal->format('Ymd'), ++$this->urutan);
    }
}
