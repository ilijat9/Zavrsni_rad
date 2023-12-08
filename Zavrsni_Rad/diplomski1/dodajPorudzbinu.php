<?php
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    
    if(isset($_POST['poruči'])){
        $id=$_SESSION['id'];
        $vreme=$_POST['vreme'];
        $status="U pripremi";
        if (isset($_POST['ukupnaCena'])) {
            $ukupnaCena1 = floatval($_POST['ukupnaCena']); // Ensure it's a numeric value
        } else {
            // Handle the case when 'ukupnaCena' is not set in $_POST
            $_SESSION['message'] = "Greška: 'ukupnaCena' nije postavljena u POST zahtevu.";
            header('location: kolica.php');
            exit();
        }
        $adresa=$_POST['adresa'];
        
        $dodajP="INSERT INTO porudzbine(ID_KORISNIKA,VREME_PORUDZBINE,UKUPNA_CENA,STATUS_PORUDZBINE,ADRESA_DOSTAVE)
                                    VALUES($id,'$vreme',$ukupnaCena1,'$status','$adresa') ";
        if(mysqli_query($conn,$dodajP)===true){
            
            $upitP=mysqli_query($conn,"SELECT ID_PORUDZBINE from porudzbine where ID_PORUDZBINE=(SELECT LAST_INSERT_ID())");
            $upitPString = json_encode(mysqli_fetch_assoc($upitP));
            if($upitPString){
                $upitPArray = json_decode($upitPString, true);
                if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
                    $brojac=0;
                    foreach($_SESSION['cart'] as $itemIndex => $item){
                        $idP=$upitPArray['ID_PORUDZBINE'];
                        $stavkaID=$item['ID_STAVKE'];                       
                        $nazivStavke=$item['NAZIV_STAVKE'];                        
                        $kolicina=$_POST['kolicina'][$itemIndex];
                        $cena=$item['CENA'];
                        $cena1=(int)$kolicina * (float)$cena;
                        $nazivR=$item['NAZIV'];                       
                        $napomena = $_POST['napomena'][$itemIndex];
                       
                        $dodajStavkuP="INSERT INTO stavke_porudzbine(ID_PORUDZBINE,ID_STAVKE,NAZIV_STAVKE_P,NAPOMENA,CENA_STAVKE,NAZIV_RESTORANA,KOLIČINA) 
                                                        VALUES($idP,$stavkaID,'$nazivStavke','" . mysqli_real_escape_string($conn, $napomena) . "',$cena1,'" . mysqli_real_escape_string($conn, $nazivR) . "',$kolicina)";
                        if(mysqli_query($conn,$dodajStavkuP)===true){
                            $brojac++;
                        }else {
                            // There was an error executing the query
                            echo "Error: " . mysqli_error($conn);
                            $_SESSION['message'] = "Greška prilikom upisa stavke porudžbine: " . mysqli_error($conn);
                            header('location: kolica.php');
                            exit();
                        }
                    }
                    $idP1=$upitPArray['ID_PORUDZBINE'];
                    $stavkeP=mysqli_query($conn,"SELECT * from stavke_porudzbine where ID_PORUDZBINE=$idP1");
                    if(mysqli_num_rows($stavkeP)==$brojac){    
                        $_SESSION['message']="Uspešno ste naručili hranu!!!";
                        unset($_SESSION['cart']);
                        header('location: kolica.php');
                    }
                }else{
                    $_SESSION['message']="Greška pri pristupu niza!!!";
                    header('location: kolica.php');
                    exit();
                }
            }else{

            }
        }else{
            echo "Error: " . mysqli_error($conn);
            $_SESSION['message']=mysqli_error($conn);
            header('location: kolica.php');
            exit();
        }
    }
    else{
        $_SESSION['message']="Greška pri naručivanju hrane!!!";
        header('location: kolica.php');
    }

?>