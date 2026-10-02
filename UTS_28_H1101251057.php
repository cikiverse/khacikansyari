<?php

//1. Buat Abstract Class Tiket Bioskop dengan property id, nama, hargaDasar dan method abstract hitungTotal() dan getJenis()
abstract class TiketBioskop {
    protected $id;
    protected $nama;
    protected $hargaDasar;

    public function __construct($id, $nama, $hargaDasar) {
        $this->id = $id;
        $this->nama = $nama;
        $this->hargaDasar = $hargaDasar;
    }

    public function getId()         { return $this->id; }
    public function getNama()       { return $this->nama; }
    public function getHargaDasar() { return $this->hargaDasar; }

    abstract public function hitungTotal();
    abstract public function getJenis();
}

//2. Buat 3 Child Class (Regular, Premiere, IMAX)

class Reguler extends TiketBioskop {
    private $kursi;

    public function __construct($id, $nama, $hargaDasar, $kursi) {
        parent::__construct($id, $nama, $hargaDasar);
        $this->kursi = $kursi;
    }

    public function hitungTotal() {
        return $this->hargaDasar + (5000 * $this->kursi);
    }

    public function getJenis() {
        return "Reguler";
    }

}

class Premiere extends TiketBioskop {
    private $kursi;

    public function __construct($id, $nama, $hargaDasar, $kursi) {
        parent::__construct($id, $nama, $hargaDasar);
        $this->kursi = $kursi;
    }

    public function hitungTotal() {
        $total = $this->hargaDasar + (20000 * $this->kursi);
        if ($this->kursi > 3) {
            $total = $total - ($total * 0.15);   // diskon 15%
        }
        return $total;
    }

    public function getJenis() {
        return "Premiere";
    }
}

class IMAX extends TiketBioskop {
    private $kursi;

    public function __construct($id, $nama, $hargaDasar, $kursi) {
        parent::__construct($id, $nama, $hargaDasar);
        $this->kursi = $kursi;
    }

    public function hitungTotal() {
        return $this->hargaDasar + (25000 * $this->kursi);
    }

    public function getJenis() {
        return "IMAX";
    }
}

//3.Override kedua method abstract di semua child


$daftarTiket = [
    new Reguler ("TKT-001", "Khairunisa Ansyari",    40000, 2),
    new Premiere("TKT-002", "Melly", 60000, 4),   // kursi > 3 -> diskon 15%
    new IMAX    ("TKT-003", "Listy", 75000, 3),
    new Reguler ("TKT-004", "Aiffy",         40000, 5),
    new Premiere("TKT-005", "Keyza",         60000, 2),
];

echo "<h2>Daftar Tiket Bioskop</h2>";
 
foreach ($daftarTiket as $tiket) {
    echo $tiket->getId() . " | "
       . $tiket->getNama() . " | "
       . $tiket->getJenis() . " | Harga Dasar: Rp "
       . number_format($tiket->getHargaDasar(), 0, ',', '.') . " | Total: Rp "
       . number_format($tiket->hitungTotal(), 0, ',', '.')
       . "<br>";
}


