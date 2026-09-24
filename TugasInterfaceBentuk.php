<?php
// File: TugasInterfaceBentuk.php
 
// 1. Interface Bentuk dengan method hitungLuas()
interface Bentuk {
    public function hitungLuas();
}
 
// 2. Class Persegi mengimplementasikan interface Bentuk
class Persegi implements Bentuk {
    private $sisi;
 
    public function __construct($sisi) {
        $this->sisi = $sisi;
    }
 
    public function hitungLuas() {
        return $this->sisi * $this->sisi;
    }
 
    public function getSisi() {
        return $this->sisi;
    }
}
 
// 3. Class Lingkaran mengimplementasikan interface Bentuk
class Lingkaran implements Bentuk {
    private $radius;
    const PHI = 3.14;
 
    public function __construct($radius) {
        $this->radius = $radius;
    }
 
    public function hitungLuas() {
        return self::PHI * $this->radius * $this->radius;
    }
 
    public function getRadius() {
        return $this->radius;
    }
}
 
// 4. Skrip: menampung objek Persegi dan Lingkaran dalam satu array,
//    lalu melakukan loop untuk mencetak luas setiap bentuk
$bentukArray = [
    new Persegi(5),
    new Lingkaran(7)
];
 
foreach ($bentukArray as $bentuk) {
    if ($bentuk instanceof Persegi) {
        echo "Luas Persegi (sisi=" . $bentuk->getSisi() . "): "
           . $bentuk->hitungLuas() . "<br>";
    } elseif ($bentuk instanceof Lingkaran) {
        echo "Luas Lingkaran (radius=" . $bentuk->getRadius() . "): "
           . $bentuk->hitungLuas() . "<br>";
    }
}
?>
 