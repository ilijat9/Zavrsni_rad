<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from dostavljac where ID_DOSTAVLJACA='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Stranica za naručivanje</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="dostavljac.php"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li>Profil: <?php echo $row['IME_D'] .' '. $row['PREZIME_D']; ?></li>
                <li><a href="dostavljacProfil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
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
    <div class="ponudaPorudzbina">
        <div class="zaPreuzimanje">
            <h2>Izaberite poružbinu:</h2>
            <table class="tabela">
            <thead>
                <tr style="display:flex;justify-content:space-evenly">
                    <th>ID_porudžbine</th>
                    <th>ID_korisnika</th>
                    <th>Vreme porudžbine</th>
                    <th>Cena</th>
                    <th>Status porudžbine</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $query=mysqli_query($conn,"SELECT * from porudzbine where STATUS_PORUDZBINE='U pripremi'");
                while($row=mysqli_fetch_assoc($query)){
                    echo '<tr style="display:flex;justify-content:space-evenly">';
                    echo '<form action="preuzimanjeIsporuka.php" method="get">';
                    echo '<td>'.$row['ID_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['ID_KORISNIKA'].'</td>';
                    echo '<td>'.$row['VREME_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['UKUPNA_CENA'].' rsd</td>';
                    echo '<td>'.$row['STATUS_PORUDZBINE'].'</td>';
                    echo '<input type="hidden" name="id" value="'.$_SESSION['id'].'">';
                    echo '<input type="hidden" name="idP" value="'.$row['ID_PORUDZBINE'].'">';
                    echo '<td><button style="background-color:orange" name="preuzimanje">Preuzmi</button></td>';
                    echo '</form>';
                    echo '<form action="napraviPDF.php" method="get">';
                    echo '<input type="hidden" name="id" value="'.$_SESSION['id'].'">';
                    echo '<input type="hidden" name="idP" value="'.$row['ID_PORUDZBINE'].'">';
                    echo '<td><button name="uvid1">Uvid</button></td>';
                    echo '</form>';
                    echo '</tr>';
                }
            ?>
            </tbody>
            </table>
            <hr>
        </div>
        <div class="zaIsporuku">
            <h2>Preuzete porudžbine:</h2>
            <table class="tabela">
            <thead>
                <tr style="display:flex;justify-content:space-evenly">
                    <th>ID_porudžbine</th>
                    <th>ID_korisnika</th>
                    <th>Vreme porudžbine</th>
                    <th>Cena</th>
                    <th>Status porudžbine</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $query=mysqli_query($conn,"SELECT * from porudzbine where STATUS_PORUDZBINE='Dostava u toku' and ID_DOSTAVLJACA='".$_SESSION['id']."'");
                while($row=mysqli_fetch_assoc($query)){
                    echo '<tr style="display:flex;justify-content:space-evenly">';
                    echo '<form action="preuzimanjeIsporuka.php" method="get">';
                    echo '<td>'.$row['ID_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['ID_KORISNIKA'].'</td>';
                    echo '<td>'.$row['VREME_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['UKUPNA_CENA'].' rsd</td>';
                    echo '<td>'.$row['STATUS_PORUDZBINE'].'</td>';
                    echo '<td style="border:none"><button style="background-color:green" name="isporučeno">Isporučeno</button></td>';
                    echo '<input type="hidden" name="id" value="'.$_SESSION['id'].'">';
                    echo '<input type="hidden" name="idP" value="'.$row['ID_PORUDZBINE'].'">';
                    echo '</form>';
                    echo '<form action="napraviPDF.php" method="get">';
                    echo '<input type="hidden" name="id" value="'.$_SESSION['id'].'">';
                    echo '<input type="hidden" name="idP" value="'.$row['ID_PORUDZBINE'].'">';
                    echo '<td><button name="uvid1">Uvid</button></td>';
                    echo '</form>';
                    echo '</tr>';
                }
            ?>
            </tbody>
            </table>
            <hr>
        </div>
        <div class="dostavljene">
            <h2>Dostavljene porudžbine:</h2>
            <table class="tabela">
            <thead>
                <tr style="display:flex;justify-content:space-evenly">
                    <th>ID_porudžbine</th>
                    <th>ID_korisnika</th>
                    <th>ID_dostavljača</th>
                    <th>Vreme porudžbine</th>
                    <th>Cena</th>
                    <th>Status porudžbine</th>
                    
                </tr>
            </thead>
            <tbody>
            <?php
                $query=mysqli_query($conn,"SELECT * from porudzbine where STATUS_PORUDZBINE='Isporučena' and ID_DOSTAVLJACA=".$_SESSION['id']."");
                while($row=mysqli_fetch_assoc($query)){
                    echo '<tr style="display:flex;justify-content:space-evenly">';
                    echo '<td>'.$row['ID_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['ID_KORISNIKA'].'</td>';
                    echo '<td>'.$row['ID_DOSTAVLJACA'].'</td>';
                    echo '<td>'.$row['VREME_PORUDZBINE'].'</td>';
                    echo '<td>'.$row['UKUPNA_CENA'].' rsd</td>';
                    echo '<td>'.$row['STATUS_PORUDZBINE'].'</td>';
                    echo '</form>';
                    echo '</tr>';
                }
            ?>
            </tbody>
            </table>
            <hr>
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
