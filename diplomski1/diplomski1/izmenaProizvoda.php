<?php
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: admin.php');
        exit();
    }
    if(isset($_POST['ažurirajProizvod'])){
        $idR=$_POST['id'];
        $nazivProizvoda=$_POST['proizvodNaziv'];
        $novaCena=$_POST['cenaProizvoda'];
        $noviPopust=$_POST['popustProizvoda'];
        $case=$_POST['case'];
        $update= "UPDATE stavke_menija set CENA='$novaCena', POPUST='$noviPopust' where NAZIV_STAVKE='$nazivProizvoda'";
        if(mysqli_query($conn, $update)===TRUE){
            $_SESSION['message']="Uspešna promena podataka proizvoda!";
            header('Location: admin.php?id='.$idR.'');
            exit();
        }
        else{
            $_SESSION['message']="Neuspešna promena podataka proizvoda!";
                header('Location: admin.php?id='.$idR.'');
                exit();
        }
    }
    
    if(isset($_POST['promeniAktivnost'])){
        $idR=$_POST['id'];
        $nazivProizvoda=$_POST['proizvodNaziv'];
        $aktivnost=$_POST['aktivnostProizvoda'];
        if($aktivnost==0){
            $update="UPDATE stavke_menija set AKTIVNOST='1' where NAZIV_STAVKE='$nazivProizvoda'";
            if($conn->query($update)===true){
                $_SESSION['message']="Uspešno promenjena aktivnost proizvoda!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }else{
                $_SESSION['message']="Neuspešna promena aktivnosti proizvoda!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }
        }
        if($aktivnost==1){
            $update="UPDATE stavke_menija set AKTIVNOST='0' where NAZIV_STAVKE='$nazivProizvoda'";
            if($conn->query($update)===true){
                $_SESSION['message']="Uspešno promenjena aktivnost proizvoda!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }else{
                $_SESSION['message']="Neuspešna promena aktivnosti proizvoda!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }
        }
    }
    if(isset($_POST['promeniAktivnostR'])){
        $idR=$_POST['id'];
        $aktivnost=$_POST['aktivnostRestorana'];
        if($aktivnost==0){
            $update="UPDATE restoran set AKTIVNOST='1' where ID_RESTORANA='$idR'";
            if($conn->query($update)===true){
                $_SESSION['message']="Uspešno promenjena aktivnost restorana!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }else{
                $_SESSION['message']="Neuspešna promena aktivnosti restorana!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }
        }
        if($aktivnost==1){
            $update="UPDATE restoran set AKTIVNOST='0' where ID_RESTORANA='$idR'";
            if($conn->query($update)===true){
                $_SESSION['message']="Uspešno promenjena aktivnost restorana!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }else{
                $_SESSION['message']="Neuspešna promena aktivnosti restorana!";
                header('Location: admin.php?id='.$idR.'');
                exit();
            }
        }
    }
?>