<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Admin stranica</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="admin.php?id=0"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li>Profil: <?php echo $row['IME'] .' '. $row['PREZIME']; ?></li>
                <li><a href="adminProfil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <div class="restorani-containerAdmin">
        
        <div class="restoraniAdmin">
            <h2><a href="korisniciDostavljači.php" style="text-decoration: none;color:rgb(33, 33, 92)">-><u>Uvid u Korisnike i Dostavljače</u><-</a></h2>
            <h2>Izaberite restoran u kom želite da<br> menjate podatke proizvoda:</h2>
            <ul>
                <form action="admin.php?id" method="get">
                    <?php
                        $query1=mysqli_query($conn,"SELECT * from restoran");
                        if(mysqli_num_rows($query1) != 0){
                            while($row1=mysqli_fetch_assoc($query1)){
                                echo '<li onclick="reloadWithDifferentText('.$row1['ID_RESTORANA'].')"><img class="logo_restorana" src="'.$row1['LOGO'].'" alt="LOGO RESTORANA">'.$row1['NAZIV'].'</li>';    
                            }
                        }
                        else{
                            echo 'Nema aktivnih restorana!!!';
                        }
                    ?>
                </form>
            </ul>
        </div>
        <div class="kontejnerProizvoda">
            <?php
                if(isset($_GET['id']) && $_GET['id']==0){
            ?>
        	<div class="unosRestorana">
                <?php
                    if(isset($_SESSION['message'])){
                        echo '<div style="background-color: rgb(33, 33, 92); font-size: 30px;color:#fff; border-radius: 5px; margin-top:10px; padding:10px">';
                        if (isset($_SESSION['message'])){
                            echo $_SESSION['message'];
                        }
                        unset($_SESSION['message']);
                        echo '</div>';
                    }
                ?>
                <h2>Unesite novi restoran</h2>
                <form action="noviProizvod.php" method="post">
                    <input type="text" name="nazivR" placeholder="Naziv Restorana" required>
                    <input type="text" name="adresaR" placeholder="Adresa Restorana" required>
                    <input type="text" name="radnoV" placeholder="Radno Vreme Restorana (00:00 - 00:00)" required>
                    <input type="text" name="kontaktT" placeholder="Kontakt Telefon" required>
                    <input type="text" name="logoR" placeholder="Apsolutna Putanja Logoa">
                    <button type="submit" name="unesiR">Unesi novi restoran</button>
                </form>
            </div>
            <?php
                }elseif(isset($_GET['id']) && $_GET['id']!=0){
                    $id1=$_GET['id'];
                    $naziv11=mysqli_query($conn,"SELECT * from restoran where ID_RESTORANA=$id1");
                    $naziv12=mysqli_fetch_assoc($naziv11);
            ?>
            <div class="unosProizvoda">
                    <h2>Unesite novi proizvod u <?php echo $naziv12['NAZIV']?> restoran:</h2>
                    <form action="noviProizvod.php" method="post">
                        <input class="infoP" type="text" name="nazivP" placeholder="Naziv Proizvoda" required>
                        <select class="infoP" name="kategorijaP" id="kategorijaP"  required>
                            <option value="none" selected disabled hidden>Kategorija</option>
                            <option value="Doručak">Doručak</option>
                            <option value="Sendvič">Sendvič</option>
                            <option value="Roštilj">Roštilj</option>
                            <option value="Burger">Burger</option>
                            <option value="Giros">Giros</option>
                            <option value="Pizza">Pizza</option>
                            <option value="Piletina">Piletina</option>
                            <option value="Pasta">Pasta</option>
                            <option value="Azijska hrana">Azijska hrana</option>
                            <option value="Domaće jelo">Domaće jelo</option>
                            <option value="Riba">Riba</option>
                            <option value="Salata">Salata</option>
                            <option value="Prilozi">Prilozi</option>
                            <option value="Dezert">Dezert</option>
                            <option value="Piće">Piće</option>
                        </select>
                        <input class="infoP" type="text" name="sastojciP" placeholder="Sastojci">
                        <input class="infoP" type="number" name="cenaP" placeholder="Cena"  required min="0">
                        <input class="infoP" type="text" name="popustP" placeholder="Popust u formatu  (broj%)">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <button type="submit" name="unesiP">Unesi novi proizvod</button>
                    </form>
            </div>
            <div class="promenaProizvoda">
                <?php
                    if(isset($_SESSION['message'])){
                        echo '<div style="background-color: rgb(33, 33, 92); font-size: 30px;color:#fff; border-radius: 5px; margin-top:10px; padding:10px">';
                        if (isset($_SESSION['message'])){
                            echo $_SESSION['message'];
                        }
                        unset($_SESSION['message']);
                        echo '</div>';
                    }
                ?>
                <?php
                    if(isset($_GET['id'])){
                        $id=$_GET['id'];
                        $naziv=mysqli_query($conn,"SELECT * from restoran where ID_RESTORANA=$id");
                        if(!$naziv){
                            die('Neuspešan upit!!!'.mysqli_error($conn));
                        }else{
                            $naziv1=mysqli_fetch_assoc($naziv);
                            $query1=mysqli_query($conn,
                            "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.AKTIVNOST
                            FROM STAVKE_MENIJA SM
                            JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                            JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                            JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                            where R.ID_RESTORANA=$id");
                            if($naziv1['AKTIVNOST']==1){
                                $aktivnost='Aktivan';
                                $boja='green';
                            }
                            else{
                                $aktivnost='Neaktivan';
                                $boja='indianred';
                            }
                            echo '<form action="izmenaProizvoda.php" method="post" style:"justify-content:center"><br>';
                            echo '<span style="font-size:25px;">Promenite aktivnost restorana : </span>';
                            echo '<input type="hidden" id="aktivnostRestorana" name="aktivnostRestorana"  value="'.$naziv1['AKTIVNOST'].'">';
                            echo '<button type="submit" id="promeniAktivnostR" name="promeniAktivnostR" style="background-color:'.$boja.'">'.$aktivnost.'</button><br><br>';
                            echo '<input type="hidden" name="id" value="'.$id.'">';
                            echo '</form>';
                            echo '<h2 style=" margin-top:20px">Ažurirajte podatke u '.$naziv1['NAZIV'].' restoranu:</h2><hr>';
                            while($row=mysqli_fetch_assoc($query1)){
                                if($row['AKTIVNOST']==1){
                                    $aktivnost1='Aktivan';
                                    $boja1='green';
                                }else{
                                    $aktivnost1='Neaktivan';
                                    $boja1='indianred';
                                }
                                echo '<form action="izmenaProizvoda.php" method="post">';
                                echo '<div class="restoranProizvod">';
                                echo '<div class="proizvodNaziv">'.$row['NAZIV_STAVKE'].'</div> <div class="proizvodSastojci">'.$row['SASTOJCI'].'</div>';
                                echo '<br><div class="cenaProizvoda">Ažuriraj cenu i popust proizvoda : <input style="width:40px" type="text" id="cenaProizvoda" name="cenaProizvoda" placeholder="Cena Proizvoda" value="'.$row['CENA'].'">rsd</div>';
                                echo '<div class="popustProizvoda"><span>npr.(10%)</span><input style="width:40px" type="text" id="popustProizvoda" name="popustProizvoda" placeholder="Popust" value="'.$row['POPUST'].'"></div>';
                                echo '<input type="hidden" name="proizvodNaziv" value="' . $row['NAZIV_STAVKE'] . '">';
                                echo '<input type="hidden" name="case" value="1">';
                                echo '<input type="hidden" name="id" value="'.$id.'">';
                                echo '<button type="submit" id="ažurirajProizvod" name="ažurirajProizvod">Ažuriraj podatke</button>';
                                echo '</form>';
                                echo '<form action="izmenaProizvoda.php" method="post">';
                                echo '<input type="hidden" name="proizvodNaziv" value="' . $row['NAZIV_STAVKE'] . '">';
                                echo '<input type="hidden" id="aktivnostProizvoda" name="aktivnostProizvoda"  value="'.$row['AKTIVNOST'].'">';
                                echo '<input type="hidden" name="id" value="'.$id.'">';
                                echo '<span style="font-size:25px;display:inline-block;">Promenite aktivnost proizvoda :</span> ';
                                echo '<button type="submit" id="promeniAktivnost" name="promeniAktivnost" style="background-color:'.$boja1.'">'.$aktivnost1.'</button>';
                                echo '</form>';
                                echo '</div>';
                                echo '<hr>';
    
                            }
                        }
                    }else{
                        echo '<script>console.log("form submitted")</script>';
                        echo "Izaberite restoran!";
                    }
                ?>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script>
        // JavaScript to reload the page with a different ID
        function reloadWithDifferentText(id) {
            window.location.href = "admin.php?id=" + id;
        }
    </script>
    <script type="text/javascript">
        window.addEventListener("scroll",function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0);
        })
    </script>
</body>
</html>
