<?php
    
    if(isset($_POST['login'])){
        session_start();
        include('konekcija.php');
        $email=$_POST['email'];
        $password=$_POST['password'];
        $password1=$_POST['password'];
        $query=mysqli_query($conn,"select * from korisnik where E_MAIL='$email' && LOZINKA=PASSWORD('$password')");
        $row=mysqli_fetch_assoc($query);
        if (mysqli_num_rows($query) == 0){
            $_SESSION['message']="Neuspela prijava. Molimo Vas pokušajte ponovo.";
            header('location: index.php');
        }
        else if($row['ID_KATEGORIJE']!='2'){
            $_SESSION['message']="Nemate korisnički nalog. Registrujte se ili se prijavite kao admin ili dostavljač.";
            header('location: index.php');
        }
        else{
            if($row['AKTIVNOST']==0){
                $_SESSION['message']="Neuspela prijava. Trenutno niste aktivan korisnik!!!";
                header('location: index.php');
                exit();
            }
            else{
                if (isset($_POST['remember'])){
                setcookie("user", $email, time() + (86400 * 30)); 
                setcookie("pass", $password1, time() + (86400 * 30)); 
            }
            $_SESSION['id']=$row['ID_KORISNIKA'];
            header('Location: korisnik1.php');
            }   
        }
    }
    else{
        header('Location:index.php');
        $_SESSION['message']="Molimo Vas prijavite se!";
    }

    

    if(isset($_POST['registruj'])){
        session_start();
        include('konekcija.php');
        $ime=$_POST['ime'];
        $prezime=$_POST['prezime'];
        $lozinka=$_POST['password'];
        $email=$_POST['email'];
        $brojT=$_POST['broj'];
        $query=mysqli_query($conn,"SELECT E_MAIL from korisnik where E_MAIL='$email'");
        $query2=mysqli_query($conn,"SELECT BROJ_TELEFONA from korisnik where BROJ_TELEFONA='$brojT'");    
        if(mysqli_num_rows($query)>0){
            $_SESSION['message']="Neuspela registracija. Ovaj e-mail je već iskorišćen. Molimo Vas pokušajte ponovo.";
            header('location: registracija.php');
            exit();
        }
        if(mysqli_num_rows($query2)>0){
            $_SESSION['message']="Neuspela registracija. Ovaj broj telefona je već iskorišćen. Molimo Vas pokušajte ponovo.";
            header('location: registracija.php');
            exit();
        }
        $unos="INSERT into korisnik(ID_KATEGORIJE,IME,PREZIME,E_MAIL,LOZINKA,BROJ_TELEFONA)
                values('2','$ime','$prezime','$email',PASSWORD('$lozinka'),'$brojT')";
        if(mysqli_query($conn,$unos)===true){
            header('Location: index.php');
            exit();
        }
        else{
            echo "Greška pri unosu podataka";
        }
    }

    if(isset($_POST['login2'])){
        session_start();
        include('konekcija.php');
        $email=$_POST['email'];
        $password=$_POST['password'];
        $role=$_POST['role'];
        $query=mysqli_query($conn,"select * from korisnik where E_MAIL='$email' && LOZINKA=PASSWORD('$password')");
        $query1=mysqli_query($conn,"select * from dostavljac where E_MAIL_D='$email' && LOZINKA_D=PASSWORD('$password')");
        $row=mysqli_fetch_assoc($query);
        $row1=mysqli_fetch_assoc($query1);
        if($role=='Administrator'){
            if (mysqli_num_rows($query) == 0){
                $_SESSION['message']='Neuspela prijava. Molimo Vas pokušajte ponovo.'; 
                header('location: index2.php');
                exit();
            }
            else if($row['ID_KATEGORIJE']!='1'){
                $_SESSION['message']="Nemate admin nalog.";
                header('location: index2.php');
                exit();
            }
            else{
                $_SESSION['id']=$row['ID_KORISNIKA'];
                header('Location: admin.php?id=0');
                exit();
            }
        }
        if($role=='Dostavljač'){
            if (mysqli_num_rows($query1) == 0){
                $_SESSION['message']="Neuspela prijava. Molimo Vas pokušajte ponovo.";
                header('location: index2.php');
                exit();
            }
            else{
                if($row1['AKTIVNOST']==0){
                    $_SESSION['message']="Neuspela prijava. Trenutno niste aktivan dostavljač.";
                    header('location: index2.php');
                    exit();
                }
                else{
                    $_SESSION['id']=$row1['ID_DOSTAVLJACA'];
                    header('Location: dostavljac.php');
                    exit();
                }
            }
        }
    }
?>