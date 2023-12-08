<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);
    if(isset($_POST['dodajD'])){
        $ime=$_POST['imeD'];
        $prezime=$_POST['prezimeD'];
        $email=$_POST['e-mailD'];
        $lozinka=$_POST['lozinkaD'];
        $broj=$_POST['brojD'];
        $status='Dostupan';
        $query=mysqli_query($conn,"SELECT E_MAIL_D from dostavljac where E_MAIL_D='$email'");
        $query2=mysqli_query($conn,"SELECT BROJ_TELEFONA_D from dostavljac where BROJ_TELEFONA_D='$broj'");
        if(mysqli_num_rows($query)>0){
            $_SESSION['message']="Neuspela registracija. Ovaj e-mail je već iskorišćen. Molimo Vas pokušajte ponovo.";
            header('location: korisniciDostavljači.php');
            exit();
        }
        if(mysqli_num_rows($query2)>0){
            $_SESSION['message']="Neuspela registracija. Ovaj broj telefona je već iskorišćen. Molimo Vas pokušajte ponovo.";
            header('location: korisniciDostavljači.php');
            exit();
        }
        $unos="INSERT into dostavljac(IME_D,PREZIME_D,E_MAIL_D,LOZINKA_D,BROJ_TELEFONA_D,STATUS_DOSTAVLJACA)
                values('$ime','$prezime','$email',PASSWORD('$lozinka'),'$broj', '$status')";
        if(mysqli_query($conn,$unos)===true){
            $_SESSION['message']="Uspešno ste registrovali novog dostavljača!";
            header('Location: korisniciDostavljači.php');
            exit();
        }
        else{
            $_SESSION['message']="Greška pri unosu podataka!";
            header('Location: korisniciDostavljači.php');
            exit();
        }
    }
    if(isset($_POST['aktivnostKorisnika'])){
        $aktivnost=$_POST['aktivnost'];
        $id=$_POST['idkorisnika'];
        if($aktivnost==0){
            $upit="UPDATE korisnik set AKTIVNOST='1' where ID_KORISNIKA=$id ";
            if(mysqli_query($conn, $upit)===TRUE){
                $_SESSION['message']="Uspešna promena aktivnosti korisnika!";
                header('Location: korisniciDostavljači.php');
                exit();
            }
            else{
                $_SESSION['message']="Neuspešna promena aktivnosti korisnika!";
                    header('Location: korisniciDostavljači.php');
                    exit();
            }
        }
        elseif($aktivnost==1){
            $upit="UPDATE korisnik set AKTIVNOST='0' where ID_KORISNIKA=$id ";
            if(mysqli_query($conn, $upit)===TRUE){
                $_SESSION['message']="Uspešna promena aktivnosti korisnika!";
                header('Location: korisniciDostavljači.php');
                exit();
            }
            else{
                $_SESSION['message']="Neuspešna promena aktivnosti korisnika!";
                    header('Location: korisniciDostavljači.php');
                    exit();
            }
        }
    }
    if(isset($_POST['aktivnostDostavljaca'])){
        $aktivnost=$_POST['aktivnost'];
        $id=$_POST['iddostavljaca'];
        if($aktivnost==0){
            $upit="UPDATE dostavljac set AKTIVNOST='1', STATUS_DOSTAVLJACA='Dostupan' where ID_DOSTAVLJACA=$id ";
            if(mysqli_query($conn, $upit)===TRUE){
                $_SESSION['message']="Uspešna promena aktivnosti dostavljaca!";
                header('Location: korisniciDostavljači.php');
                exit();
            }
            else{
                $_SESSION['message']="Neuspešna promena aktivnosti dostavljaca!";
                    header('Location: korisniciDostavljači.php');
                    exit();
            }
        }
        elseif($aktivnost==1){
            $upit="UPDATE dostavljac set AKTIVNOST='0', STATUS_DOSTAVLJACA='Ne radi' where ID_DOSTAVLJACA=$id ";
            if(mysqli_query($conn, $upit)===TRUE){
                $_SESSION['message']="Uspešna promena aktivnosti dostavljaca!";
                header('Location: korisniciDostavljači.php');
                exit();
            }
            else{
                $_SESSION['message']="Neuspešna promena aktivnosti dostavljaca!";
                    header('Location: korisniciDostavljači.php');
                    exit();
            }
        }
    }
?>