<?php
        session_start();
        include('konekcija.php');
    if(isset($_POST['unesiP'])){ 
        var_dump($_POST);     
        $idR=$_POST['id'];
        $nazivP=$_POST['nazivP'];
        $kategorija=$_POST['kategorijaP'];
        $sastojci=$_POST['sastojciP'];
        $cena=$_POST['cenaP'];
        $popust=$_POST['popustP'];
        $unosProizvoda=mysqli_query($conn,"INSERT INTO proizvodi(NAZIV_PROIZVODA,KATEGORIJA) values('$nazivP','$kategorija')");
        if($unosProizvoda===true){
            $poslednji=mysqli_query($conn,"SELECT MAX(ID_PROIZVODA) as rezultat from proizvodi");
            $poslednji1=mysqli_fetch_assoc($poslednji);
            $IDP=$poslednji1['rezultat'];
            if($kategorija=='Doručak'){
                $nazivMenija='Doručak';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }
            elseif($kategorija=='Roštilj' || $kategorija=='Pizza' || $kategorija=='Sendvič' || $kategorija=='Burger' || $kategorija=='Piletina' || $kategorija=='Azijska hrana'
                || $kategorija=='Pasta' || $kategorija=='Riba' || $kategorija=='Domaće jelo' || $kategorija=='Giros'){
                $nazivMenija='Glavno jelo';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }elseif($kategorija=='Salate'){
                $nazivMenija='Salate';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }elseif($kategorija=='Prilozi'){
                $nazivMenija='Prilozi';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }elseif($kategorija=='Dezert'){
                $nazivMenija='Dezerti';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }elseif($kategorija=='Piće'){
                $nazivMenija='Pića';
                $meni=mysqli_query($conn,"SELECT * from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                if(mysqli_num_rows($meni)==0){
                    $unosMenija=mysqli_query($conn,"INSERT INTO meniji(ID_RESTORANA,NAZIV_MENIJA) VALUES($idR,'$nazivMenija')");
                    if($unosMenija===true){
                        $meni1=mysqli_query($conn,"SELECT ID_MENIJA from meniji where ID_RESTORANA=$idR and NAZIV_MENIJA='$nazivMenija'");
                        $meniNIZ=mysqli_fetch_assoc($meni1);
                        $IDM=$meniNIZ['ID_MENIJA'];
                        if(mysqli_num_rows($meni1)!=0){
                            $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                                    VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                            if($unosStavkiMenija===true){
                                $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }else{
                                $_SESSION['message']="Došlo je do greške prilikom unosa novog proizvoda u stavke menija!!!";
                                header('Location: admin.php?id='.$idR);
                                exit();
                            }
                        }else{
                            $_SESSION['message']="Došlo je do greške prilikom odabira menija!!!";
                            header('Location: admin.php?id='.$idR);
                            exit();
                        }
                    }else{
                        $_SESSION['message']="Došlo je gdo greške prilikom unosa menija!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }else{
                    $meniNIZ=mysqli_fetch_assoc($meni);
                    $IDM=$meniNIZ['ID_MENIJA'];
                    $unosStavkiMenija=mysqli_query($conn,"INSERT INTO stavke_menija(ID_PROIZVODA,ID_MENIJA,NAZIV_STAVKE,SASTOJCI,CENA,POPUST) 
                                            VALUES($IDP,$IDM,'$nazivP','$sastojci',$cena,'$popust')");
                    if($unosStavkiMenija===true){
                        $_SESSION['message']="Uspešno ste uneli novi proizvod!!!";
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }else{
                        $_SESSION['message']=mysqli_error($conn).'    '.$IDP;
                        header('Location: admin.php?id='.$idR);
                        exit();
                    }
                }
            }
                
        }else{
            $_SESSION['message']="Došlo je gdo greške prilikom unosa proizvoda!!!";
            header('Location: admin.php?id='.$idR);
            exit();
        }
    }
    if(isset($_POST['unesiR'])){
        $nazivR=$_POST['nazivR'];
        $adresaR=$_POST['adresaR'];
        $radnoV=$_POST['radnoV'];
        $kontaktT=$_POST['kontaktT'];
        $logoR=$_POST['logoR'];
        $query=mysqli_query($conn,"INSERT INTO restoran(NAZIV,ADRESA,RADNO_VREME,KONTAKT_TELEFON,AKTIVNOST,OCENA,LOGO)
                                VALUES('$nazivR','$adresaR','$radnoV','$kontaktT',1,0,'$logoR')");
        if($query===true){
            $_SESSION['message']="Uspešno ste uneli novi restoran!!!";
            header('Location: admin.php?id=0');
            exit();
        }else{
            $_SESSION['message']="Došlo je do greške prilikom unosa novog restorana!!!";
            header('Location: admin.php?id=0');
            exit();
        }
    }
?>