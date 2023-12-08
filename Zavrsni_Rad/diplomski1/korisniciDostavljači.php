<?php 
    session_start();
    include 'konekcija.php';
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    $row2=mysqli_fetch_assoc($query);
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
                <li>Profil: <?php echo $row2['IME'] .' '. $row2['PREZIME']; ?></li>
                <li><a href="adminProfil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
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
    <div class="uvidKontejner">
        <div class="uvidKorisnici">
            <h2>Korisnici</h2>
            <table class="tabela">
                <thead style="width:100%">
                    <tr style="display:flex;justify-content:space-evenly">
                        <th>ID_korisnika</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>E-mail</th>
                        <th>Broj telefona</th>
                        <th>Aktivnost</th>
                        <th style="border:none"></th>
                    </tr>
                </thead>
                <tbody style="width:100%">
                    <?php
                    $query1=mysqli_query($conn,"SELECT * from korisnik where ID_KATEGORIJE='2'");
                    while($row2=mysqli_fetch_assoc($query1)){
                        echo '<tr style="display:flex;justify-content:space-evenly">';
                        echo '<form action="dodajukloniD.php" method="post">';
                        echo '<td>'.$row2['ID_KORISNIKA'].'</td>';
                        echo '<td>'.$row2['IME'].'</td>';
                        echo '<td>'.$row2['PREZIME'].'</td>';
                        echo '<td>'.$row2['E_MAIL'].'</td>';
                        echo '<td>'.$row2['BROJ_TELEFONA'].'</td>';
                        echo '<td>'.$row2['AKTIVNOST'].'</td>';
                        echo '<input type="hidden" name="idkorisnika" value="'.$row2['ID_KORISNIKA'].'">';
                        echo '<input type="hidden" name="aktivnost" value="'.$row2['AKTIVNOST'].'">';
                        echo '<td style="border:none"><button name="aktivnostKorisnika">Promeni aktivnost</button></td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="uvidDostavljači">
            <h2>Dostavljači</h2>
            <table class="tabela">
            <thead style="width:100%">
                <tr style="display:flex;justify-content:space-evenly">
                    <th>ID_dostavljača</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>E-mail</th>
                    <th>Broj telefona</th>
                    <th>Aktivnost</th>
                    <th style="border:none"></th>
                </tr>
            </thead>
            <tbody style="width:100%">
                <?php
                $query1=mysqli_query($conn,"SELECT * from dostavljac");
                while($row2=mysqli_fetch_assoc($query1)){
                    echo '<tr style="display:flex;justify-content:space-evenly">';
                    echo '<form action="dodajukloniD.php" method="post">';
                    echo '<td>'.$row2['ID_DOSTAVLJACA'].'</td>';
                    echo '<td>'.$row2['IME_D'].'</td>';
                    echo '<td>'.$row2['PREZIME_D'].'</td>';
                    echo '<td>'.$row2['E_MAIL_D'].'</td>';
                    echo '<td>'.$row2['BROJ_TELEFONA_D'].'</td>';
                    echo '<td>'.$row2['AKTIVNOST'].'</td>';
                    echo '<input type="hidden" name="iddostavljaca" value="'.$row2['ID_DOSTAVLJACA'].'">';
                    echo '<input type="hidden" name="aktivnost" value="'.$row2['AKTIVNOST'].'">';
                    echo '<td style="border:none"><button name="aktivnostDostavljaca">Promeni aktivnost</button></td>';
                    echo '</form>';
                    echo '</tr>';
                }
                ?>
            </tbody>
            </table>
            <div class="korisnicidiv">
                <form action="dodajukloniD.php" method="post">
                    <span class="spanovi"><input type="text" name="imeD" placeholder="Ime" required></span>
                    <span class="spanovi"><input type="text" name="prezimeD" placeholder="Prezime" required></span>
                    <span class="spanovi"><input type="email" name="e-mailD" placeholder="E-mail" required></span>
                    <span class="spanovi"><input type="password" name="lozinkaD" placeholder="Lozinka" required></span>
                    <span class="spanovi"><input type="text" name="brojD" placeholder="Broj telefona" required></span>
                    <button  type="submit" name="dodajD">Dodaj dostavljača</button>
                </form>
            </div>
        </div>
        <div class="uvidPorudzbine" style="overflow: scroll; height: 500px">
            <h2>Porudžbine</h2>
            <table class="tabela">
                <thead style="width:100%">
                    <tr style="display:flex;;justify-content:space-evenly">
                        <th>ID_porudžbine</th>
                        <th>ID_korisnika</th>
                        <th>ID_dostavljača</th>
                        <th>Vreme_porudžbine</th>
                        <th>Ukupna_cena</th>
                        <th>Status_porudžbine</th>
                    </tr>
                </thead>
                <tbody style="width:100%">
                <?php
                    $query3=mysqli_query($conn,"SELECT * from porudzbine");
                    while($row2=mysqli_fetch_assoc($query3)){
                        echo '<tr style="display:flex;;justify-content:space-evenly">';
                        echo '<td>'.$row2['ID_PORUDZBINE'].'</td>';
                        echo '<td>'.$row2['ID_KORISNIKA'].'</td>';
                        echo '<td>'.$row2['ID_DOSTAVLJACA'].'</td>';
                        echo '<td>'.$row2['VREME_PORUDZBINE'].'</td>';
                        echo '<td>'.$row2['UKUPNA_CENA'].'</td>';
                        echo '<td>'.$row2['STATUS_PORUDZBINE'].'</td>';
                        echo '</tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        window.addEventListener("scroll",function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0);
        })
    </script>
</body>
</html>