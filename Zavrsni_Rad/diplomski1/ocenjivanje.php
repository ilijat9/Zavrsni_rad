<?php
    session_start();
    include("konekcija.php");
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    if(isset($_POST['oceni'])){
        $idK=$_SESSION['id'];
        $nazivR=$_POST['nazivR'];
        $nazivR1=mysqli_real_escape_string($conn,$nazivR);
        $ocena=$_POST['ocena'];
        $upitIDR=mysqli_query($conn,"SELECT ID_RESTORANA from restoran where NAZIV='$nazivR1'");
        if($upitIDR) {
            if(mysqli_num_rows($upitIDR) > 0) {
                $IDR = mysqli_fetch_assoc($upitIDR);
                $idR = $IDR['ID_RESTORANA'];
                
                $unos = mysqli_query($conn, "INSERT INTO ocene(ID_KORISNIKA, ID_RESTORANA, OCENA) VALUES ($idK, $idR, $ocena)");
                
                if($unos){
                    $ocena1=mysqli_query($conn,"SELECT ROUND(AVG(OCENA),2) as prosek from ocene where ID_RESTORANA=$idR");
                    $unosOcene=mysqli_query($conn,"UPDATE restoran set OCENA=".mysqli_fetch_assoc($ocena1)['prosek']." where ID_RESTORANA=$idR");
                    if($unosOcene===true){
                        $_SESSION['message'] = "Uspešno ste ocenili restoran!";
                        header("Location: profil.php");
                        exit();
                    }
                    else{
                        $_SESSION['message'] = "Neuspešno ažuriranje ocene restorana!";
                        header("Location: profil.php");
                        exit();
                    }

                } else {
                    $_SESSION['message'] = "Greška prilikom ocenjivanja restorana!";
                    header("Location: profil.php");
                    exit();
                }
            } else {
                $_SESSION['message'] = "Ne postoji restoran sa datim nazivom!";
                header("Location: profil.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Greška prilikom izvršavanja upita!";
            header("Location: profil.php");
            exit();
        }
    }
?>