<?php
    session_start();
    include("konekcija.php");
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    if($query->num_rows>0){
        $row=mysqli_fetch_assoc($query);
        $ime=$row["IME"];
        $prezime=$row["PREZIME"];
        $email=$row["E_MAIL"];
        $lozinka=$row["LOZINKA"];
        $telefon=$row["BROJ_TELEFONA"];
    }
    else{
        echo "Korisnik nije pronadjen";
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profil korisnika</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="korisnik1.php"><img src="slike/logoBT2.png" alt="Logo"  id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li><a href="profil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="kolica.php"><img src="slike/cart1.png" alt="Kolica" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <h2>Profil korisnika</h2>
    <div class="profil_divovi">
        <div class="podaciOcene">
            <div class="podaciKorisnik">
                <form action="izmenaProfila.php" method="post">
                    <ul class="podaci_lista">
                        <li>Ime:</li>
                        <li>Prezime:</li>
                        <li>E-mail:</li>
                        <li>Lozinka:</li>
                        <li>Broj telefona:</li>
                        <li><?php echo $ime ?></li>
                        <li><?php echo $prezime ?></li>
                        <li><?php echo $email ?></li>
                        <li><input type="password" id="nova_lozinka" name="nova_lozinka"  placeholder="Nova lozinka" required></li>
                        <li><input type="text" id="novi_broj" name="novi_broj" placeholder="Novi broj telefona" value="<?php echo $telefon ?>"></li>
                    </ul>
                    <label class="izmena"><input type="checkbox" onclick="myFunction()" id="vlozinke">Prikaži lozinku</label>
                    <br>
                    <br>
                    <button name="promena" id="promena" class="izmena">Promeni</button>
                </form>
            </div>
            <h2>Restorani iz kojih ste naručivali hranu:</h2>
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
            <div class="ocene" style="overflow:scroll;height:400px">
                <?php
                    $upitRestorana=mysqli_query($conn,"SELECT sp.NAZIV_RESTORANA from stavke_porudzbine sp 
                    JOIN porudzbine p on p.ID_PORUDZBINE=sp.ID_PORUDZBINE
                    where p.ID_KORISNIKA=".$_SESSION['id']." and p.STATUS_PORUDZBINE='Isporučena'
                    GROUP BY sp.NAZIV_RESTORANA");
                    while($red=mysqli_fetch_assoc($upitRestorana)){
                        $nazivR=$red['NAZIV_RESTORANA'];
                        $nazivR1=mysqli_real_escape_string($conn,$red['NAZIV_RESTORANA']);
                        $idK=$_SESSION['id'];
                        $upitKorisnika=mysqli_query($conn,"SELECT * from ocene where ID_RESTORANA=(SELECT ID_RESTORANA from restoran where NAZIV='".$nazivR1."') and ID_KORISNIKA=$idK");
                        if($upitKorisnika){
                        if(mysqli_num_rows($upitKorisnika)==1){
                            echo '<span style="margin-left:20px">'.$nazivR.'</span>';
                            echo '<span style="float:right">Ovaj restoran ste već ocenili!</span>';
                            echo '<hr>';
                        }else{
                            echo '<form action="ocenjivanje.php" method="post">';
                            echo '<span name="nazivR" style="margin-left:20px">'.$nazivR.'</span>';
                            echo '<input type="hidden" name="nazivR" value="'.$nazivR.'">';
                            echo '<input type="number" name="ocena" style="width:50px;height:35px;float:right;display:inline-block; margin:0 15px 0 10px" min=1 max=5 required>';
                            echo '<button name="oceni" style="display:inline-block;float:right">Oceni</button>';
                            echo '<hr>';
                            echo '</form>';
                        }
                        }else{
                            echo "Error: " . mysqli_error($conn);
                        }
                    }   
                ?>
            </div>
        </div>
        <div class="prethodne_porudzbine" style="overflow: scroll;height:600px">
            <h2>Prethodne porudžbine</h2>
            <div class="prethodne">
            <table class="tabela">
                <thead style="width:84%">
                    <tr style="display:flex;justify-content:space-evenly;border-bottom:2px solid black">
                        <th>ID_porudžbine</th>
                        <th>ID_dostavljača</th>
                        <th>Vreme porudžbine</th>
                        <th>Cena</th>
                        <th>Status porudžbine</th>
                    </tr>
                </thead>
                <tbody style="width:100%">
                <?php
                    $query=mysqli_query($conn,"SELECT * from porudzbine where ID_KORISNIKA='".$_SESSION['id']."'"); 
                    while($row=mysqli_fetch_assoc($query)){
                        echo '<tr style="display:flex;justify-content:space-evenly">';
                        echo '<form action="napraviPDF.php" method="get" target="_blank">';
                        echo '<td>'.$row['ID_PORUDZBINE'].'</td>';
                        echo '<td>'.$row['ID_DOSTAVLJACA'].'</td>';
                        echo '<td>'.$row['VREME_PORUDZBINE'].'</td>';
                        echo '<td>'.$row['UKUPNA_CENA'].' rsd</td>';
                        echo '<td>'.$row['STATUS_PORUDZBINE'].'</td>';
                        echo '<input type="hidden" name="idD" value="'.$row['ID_DOSTAVLJACA'].'">';
                        echo '<input type="hidden" name="idP" value="'.$row['ID_PORUDZBINE'].'">';
                        echo '<td style="border:none"><button name="uvid">Uvid</button></td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                ?>
                </tbody>
            </div>
        </div>
    </div>
    <script>
        function myFunction() {
            var x = document.getElementById("nova_lozinka");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
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