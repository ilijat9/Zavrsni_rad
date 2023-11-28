<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
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
    <title>Stranica za naručivanje</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="korisnik1.php"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li>Profil: <?php echo $row['IME'] .' '. $row['PREZIME']; ?></li>
                <li><a href="profil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="kolica.php"><img src="slike/cart1.png" alt="Kolica" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <div class="restorani-container">
        <div class="kategorije">
            <h2>Kategorije:</h2>
            <ul>
                <li><a href="kategorije.php?id=1"><img class="logo_kategorije" src="slike/dorucak.jpg" alt="Doručak"></a> Doručak</li>
                <li><a href="kategorije.php?id=2"><img class="logo_kategorije" src="slike/sendvic.jpg" alt="Sendviči"></a> Sendviči</li>
                <li><a href="kategorije.php?id=3"><img class="logo_kategorije" src="slike/pica.jpg" alt="Pizza"></a> Pizza</li>
                <li><a href="kategorije.php?id=4"><img class="logo_kategorije" src="slike/burgeri.jpg" alt="Burgeri"></a> Burgeri</li>
                <li><a href="kategorije.php?id=5"><img class="logo_kategorije" src="slike/rostilj.jpg" alt="Rostilj"></a> Roštilj</li>
                <li><a href="kategorije.php?id=6"><img class="logo_kategorije" src="slike/piletina.jpg" alt="Piletina"></a> Piletina</li>
                <li><a href="kategorije.php?id=7"><img class="logo_kategorije" src="slike/gyros.jpg" alt="Gyros"></a> Gyros</li>
                <li><a href="kategorije.php?id=8"><img class="logo_kategorije" src="slike/domace.jpg" alt="Domaća jela"></a> Domaća jela</li>
                <li><a href="kategorije.php?id=9"><img class="logo_kategorije" src="slike/azijska.jpg" alt="Azijska hrana"></a> Azijska hrana</li>
                <li><a href="kategorije.php?id=10"><img class="logo_kategorije" src="slike/riba.jpg" alt="Riba"></a> Riba</li>
                <li><a href="kategorije.php?id=11"><img class="logo_kategorije" src="slike/pasta.jpg" alt="Paste"></a> Paste</li>
                <li><a href="kategorije.php?id=12"><img class="logo_kategorije" src="slike/salata.jpg" alt="Salate"></a> Salate</li>
                <li><a href="kategorije.php?id=13"><img class="logo_kategorije" src="slike/prilog.jpg" alt="Prilozi"></a> Prilozi</li>
                <li><a href="kategorije.php?id=14"><img class="logo_kategorije" src="slike/dezert.jpg" alt="Dezerti"></a> Dezerti</li>
                <li><a href="kategorije.php?id=15"><img class="logo_kategorije" src="slike/pića.jpg" alt="Piće"></a> Piće</li>
            </ul>
        </div>
        <div class="restorani">
            <h2>&middot; D O B R O D O Š L I &middot;</h2>
            <h3><u>Izaberite restoran:</u></h3>
            <ul>
                <?php
                    $query1=mysqli_query($conn,"SELECT * from restoran order by OCENA desc");
                    if(mysqli_num_rows($query1) != 0){
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['AKTIVNOST']=='1'){
                                echo '<div class="divLogo">';
                                $ocena=$row1['OCENA']?:"/";
                                echo '<form action="izborResorana.php" method="get">';
                                echo '<li><a href="izborRestorana.php?id='.$row1['ID_RESTORANA'].'"><img class="logo_restorana" src="'.$row1["LOGO"].'" alt="LOGO RESTORANA"></a>
                                '.$row1["NAZIV"].'<br>Ocena : '.$ocena.'</li>';
                                echo '</form>';
                                echo '</div>';
                            }    
                        }
                    }
                    else{
                        echo 'Nema aktivnih restorana!!!';
                    }
                ?>
            </ul>
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
