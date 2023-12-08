<?php
    session_start();
    include ("konekcija.php");
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index2.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from dostavljac where ID_DOSTAVLJACA='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);

    if(isset($_GET['preuzimanje'])){
        if($row['STATUS_DOSTAVLJACA']=='Dostupan'){
            $idP=$_GET['idP'];
            $id=$_GET['id'];
            $status="Dostava u toku";
            $query1=mysqli_query($conn,"UPDATE porudzbine set ID_DOSTAVLJACA=$id, STATUS_PORUDZBINE='$status' where ID_PORUDZBINE=$idP");
            if($query1){
                $query2=mysqli_query($conn,"UPDATE dostavljac set STATUS_DOSTAVLJACA='Zauzet' where ID_DOSTAVLJACA='".$_SESSION['id']."'");
                if($query2){
                    $_SESSION['message']='Uspešno ste preuzeli porudžbinu!';
                    header('Location: dostavljac.php');
                    exit();
                }else{
                    $_SESSION['message']='Greška pri ažuriranju statusa dostavljača!';
                    header('Location: dostavljac.php');
                    exit();
                } 
            }
            else{
                $_SESSION['message']='Neuspešno preuzimanje porudžbine!';
                header('Location: dostavljac.php');
                exit();
            }
        }else{
            $_SESSION['message']='Ne možete preuzeti novu porudžbinu dok ne dostavite prethodnu!';
            header('Location: dostavljac.php');
            exit();
        }
        
    }
    if(isset($_GET['isporučeno'])){
        $idP=$_GET['idP'];
        $id=$_GET['id'];
        $status="Isporučena";
        $query1=mysqli_query($conn,"UPDATE porudzbine set STATUS_PORUDZBINE='$status' where ID_PORUDZBINE=$idP");
        if($query1){
            $query2=mysqli_query($conn,"UPDATE dostavljac set STATUS_DOSTAVLJACA='Dostupan' where ID_DOSTAVLJACA='".$_SESSION['id']."'");
            if($query2){
                $_SESSION['message']='Uspešno ste dostavili porudžbinu!';
                header('Location: dostavljac.php');
                exit();
            }else{
                $_SESSION['message']='Greška pri ažuriranju statusa dostavljača!';
                header('Location: dostavljac.php');
                exit();
            }
        }
        else{
            $_SESSION['message']='Greška!!!';
            header('Location: dostavljac.php');
            exit();
        }
    }

?>