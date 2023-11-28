<?php
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    if(isset($_POST['promena'])){
        $novaLozinka=$_POST['nova_lozinka'];
        $noviBroj=$_POST['novi_broj'];
        $update= "update korisnik set LOZINKA=PASSWORD('$novaLozinka'), BROJ_TELEFONA='$noviBroj' where ID_KORISNIKA='".$_SESSION['id']."'";
        if(mysqli_query($conn, $update)===TRUE){
            header('Location: profil.php');
        }
        else{
            echo "Greška pri promeni podataka!";
        }
    }
    if(isset($_POST['promena1'])){
        $novaLozinka=$_POST['nova_lozinka'];
        $noviBroj=$_POST['novi_broj'];
        $update= "update dostavljac set LOZINKA_D=('$novaLozinka'), BROJ_TELEFONA_D='$noviBroj' where ID_DOSTAVLJACA='".$_SESSION['id']."'";
        if(mysqli_query($conn, $update)===TRUE){
            header('Location: dostavljacProfil.php');
        }
        else{
            echo "Greška pri promeni podataka!";
        }
    }
    
?>