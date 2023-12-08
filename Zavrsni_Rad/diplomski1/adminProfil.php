<?php
    session_start();
    include("konekcija.php");
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
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
        <a href="admin.php?id=0"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li><a href="adminProfil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <h2>Profil administratora</h2>
    <div class="profil_divovi">
        <div class="podaci">
            <form action="izmenaProfila.php" method="post">
                <ul class="podaci_lista">
                    <li>Ime:</li>
                    <li>Prezime:</li>
                    <li>E-mail:</li>
                    <li>Lozinka:</li>
                    <li><?php echo $ime ?></li>
                    <li><?php echo $prezime ?></li>
                    <li><?php echo $email ?></li>
                    <li><input type="password" id="nova_lozinka" name="nova_lozinka"  placeholder="Nova lozinka"></li>
                </ul>
                <label class="izmena"><input type="checkbox" onclick="myFunction()" id="vlozinke">Prikaži lozinku</label>
                <br>
                <br>
                <button name="promena" id="promena" class="izmena">Promeni</button>
            </form>
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
</body>
</html>