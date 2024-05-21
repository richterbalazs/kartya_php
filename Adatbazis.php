<?php
class Adatbazis {
    //adattagok
    private $host = "localhost";
    private $felhasznaloNev = "root";
    private $jelszo = "";
    private $adatbazis = "kartya";
    private $kapcsolat;
    //konstruktor
    public function __construct()
    {
        $this->kapcsolat = new mysqli(
            $this->host,
            $this->felhasznaloNev,
            $this->jelszo,
            $this->adatbazis
        );
        $siker = 0;
        if ($this->kapcsolat->connect_errno) {
            $siker = "Nem sikerült a kapcsolat";
        }
        else
            $siker = "Sikerült a kapcsolat";
        //echo $siker;
        $this->kapcsolat->query("SET NAMES UTF8");
    }
    //metódusok
    public function adatLeker($oszlop, $tabla){
        $sql = "SELECT $oszlop FROM $tabla";
        return $this->kapcsolat->query($sql);
    }
    public function megjelenit($matrix){
        while ($sor = $matrix->fetch_row()) {
            echo "<img src=\"forras/$sor[0]\" alt=\"forras/$sor[0]\">";
        }
    }

    public function adatLeker2($oszlop1, $oszlop2, $tabla){
        $sql = "SELECT $oszlop1, $oszlop2 FROM $tabla";
        return $this->kapcsolat->query($sql);
    }
    public function megjelenit2($matrix){
        echo "<table>";
        echo "<tr><th>NÉV</th><th>KÉP</th></tr>";
        while ($sor = $matrix->fetch_row()) {
            echo "<tr><td>$sor[0]</td><td><img src=\"forras/$sor[1]\" alt=\"forras/$sor[0]\"></td></tr>";
        }
        echo "</table>";
    }

    public function modosit($tabla, $oszlop, $regiErtek, $ujErtek){
        $sql = "UPDATE $tabla SET $oszlop='$ujErtek' WHERE $oszlop = '$regiErtek'";
        return $this->kapcsolat->query($sql);
    }

    public function torles($tabla, $oszlop, $ertek){
        $sql = "DELETE FROM $tabla WHERE $oszlop = '$ertek'";
        return $this->kapcsolat->query($sql);
    }

    public function rekordokSzama($tabla){
        $sql = "SELECT * FROM $tabla";
        return $this->kapcsolat->query($sql)->num_rows;
    }
    public function kartyaFeltolt(){
        $szinOsszeg = $this->rekordokSzama("szin") + 1;
        $formaOsszeg = $this->rekordokSzama("forma") + 1;
        for ($szinIndex = 1; $szinIndex < $szinOsszeg; $szinIndex++) { 
            for ($formaIndex = 1; $formaIndex < $formaOsszeg; $formaIndex++){
                $sql = "INSERT INTO kartyak(szinAzon, formaAzon) VALUES ('$szinIndex','$formaIndex')";
                $this->kapcsolat->query($sql);
            }
        }
    }

    public function torlesForma($forma){
        $sql = "DELETE FROM kartyak
        WHERE formaAzon IN
        (SELECT formaAzon FROM forma
        WHERE szoveg = '$forma')";
        return $this->kapcsolat->query($sql);
    }

    public function beszurForma($szam){
        $meret = $this->rekordokSzama("szin");
        for ($i = 1; $i <= $meret; $i++) {
            $sql = "INSERT INTO kartyak(szinAzon, formaAzon) VALUES ('$i','$szam')";
            $this->kapcsolat->query($sql);
        }
    }

    public function formaSzam($forma){
        $sql = "SELECT formaAzon FROM forma WHERE szoveg = '$forma'";
        $matrix = $this->kapcsolat->query($sql);
        $sor = $matrix->fetch_array();
        return $sor[0];
    }

    public function kapcsoltBezar(){
        $this->kapcsolat->close();
    }
}
?>