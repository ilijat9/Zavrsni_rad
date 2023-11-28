<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from dostavljac where ID_DOSTAVLJACA='".$_SESSION['id']."'");
    if($query->num_rows>0){
        $row=mysqli_fetch_assoc($query);
        $ime=$row["IME_D"];
        $prezime=$row["PREZIME_D"];
        $email=$row["E_MAIL_D"];
        $lozinka=$row["LOZINKA_D"];
        $telefon=$row["BROJ_TELEFONA_D"];
        $status=$row["STATUS_DOSTAVLJACA"];
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
    <title>Dostavljac</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="dostavljac.php"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li><a href="dostavljacProfil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <h2>Profil dostavljača</h2>
    <div class="profil_divovi">
        <div class="podaci">
            <form action="izmenaProfila.php" method="post">
                <ul class="podaci_lista">
                    <li>Ime:</li>
                    <li>Prezime:</li>
                    <li>E-mail:</li>
                    <li>Lozinka:</li>
                    <li>Broj telefona:</li>
                    <li>Status dostavljača:</li>
                    <li><?php echo $ime ?></li>
                    <li><?php echo $prezime ?></li>
                    <li><?php echo $email ?></li>
                    <li><input type="password" id="nova_lozinka" name="nova_lozinka"  placeholder="Nova lozinka"></li>
                    <li><input type="text" id="novi_broj" name="novi_broj" placeholder="Novi broj telefona" value="<?php echo $telefon ?>"></li>
                    <li><?php echo $status ?></li>
                </ul>
                <label class="izmena"><input type="checkbox" onclick="myFunction()" id="vlozinke">Prikaži lozinku</label>
                <br>
                <br>
                <button name="promena1" id="promena1" class="izmena">Promeni</button>
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