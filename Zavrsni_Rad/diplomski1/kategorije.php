<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);
    if (isset($_POST['add_to_cart']) && isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];
        $popust=mysqli_query($conn,"SELECT POPUST from STAVKE_MENIJA where ID_STAVKE=$item_id");
        $item_query = mysqli_query($conn, "SELECT SM.ID_STAVKE, SM.NAZIV_STAVKE, SM.CENA, R.NAZIV,SM.POPUST
                                           FROM STAVKE_MENIJA SM
                                           JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                           JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                           WHERE SM.ID_STAVKE = $item_id");
        $item = mysqli_fetch_assoc($item_query);
        if($item['POPUST']){
            $novaCenaUpit=mysqli_query($conn,"SELECT CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat from STAVKE_MENIJA where ID_STAVKE=$item_id");
            $novacena=mysqli_fetch_assoc($novaCenaUpit);
            $item['CENA']=$novacena['rezultat'];
        }
        // Add the selected item to the cart session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    
        $_SESSION['cart'][] = $item;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Stranica za naručivanje</title>
</head>
<body class="korisnik_body">
    <header>
        <a href="korisnik1.php"><img src="slike/logoBT2.png" alt="Logo" id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li>Profil: <?php echo $row['IME'] .' '. $row['PREZIME']; ?></li>
                <li><a href="profil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="kolica.php"><img src="slike/cart1.png" alt="Kolica" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <div class="restorani-container">
        <div class="kategorije">
            <h2>Kategorije:</h2>
            <ul>
                <form action="kategorije.php?id" method="get">
                    <li onclick="reloadWithDifferentText(1)"><img class="logo_kategorije" src="slike/dorucak.jpg" alt="Doručak"></a> Doručak</li>
                    <li onclick="reloadWithDifferentText(2)"><img class="logo_kategorije" src="slike/sendvic.jpg" alt="Sendviči"></a> Sendviči</li>
                    <li onclick="reloadWithDifferentText(3)"><img class="logo_kategorije" src="slike/pica.jpg" alt="Pizza"></a> Pizza</li>
                    <li onclick="reloadWithDifferentText(4)"><img class="logo_kategorije" src="slike/burgeri.jpg" alt="Burgeri"></a> Burgeri</li>
                    <li onclick="reloadWithDifferentText(5)"><img class="logo_kategorije" src="slike/rostilj.jpg" alt="Rostilj"></a> Roštilj</li>
                    <li onclick="reloadWithDifferentText(6)"><img class="logo_kategorije" src="slike/piletina.jpg" alt="Piletina"></a> Piletina</li>
                    <li onclick="reloadWithDifferentText(7)"><img class="logo_kategorije" src="slike/gyros.jpg" alt="Gyros"></a> Gyros</li>
                    <li onclick="reloadWithDifferentText(8)"><img class="logo_kategorije" src="slike/domace.jpg" alt="Domaća jela"></a> Domaća jela</li>
                    <li onclick="reloadWithDifferentText(9)"><img class="logo_kategorije" src="slike/azijska.jpg" alt="Azijska hrana"></a> Azijska hrana</li>
                    <li onclick="reloadWithDifferentText(10)"><img class="logo_kategorije" src="slike/riba.jpg" alt="Riba"></a> Riba</li>
                    <li onclick="reloadWithDifferentText(11)"><img class="logo_kategorije" src="slike/pasta.jpg" alt="Paste"></a> Paste</li>
                    <li onclick="reloadWithDifferentText(12)"><img class="logo_kategorije" src="slike/salata.jpg" alt="Salate"></a> Salate</li>
                    <li onclick="reloadWithDifferentText(13)"><img class="logo_kategorije" src="slike/prilog.jpg" alt="Prilozi"></a> Prilozi</li>
                    <li onclick="reloadWithDifferentText(14)"><img class="logo_kategorije" src="slike/dezert.jpg" alt="Dezerti"></a> Dezerti</li>
                    <li onclick="reloadWithDifferentText(15)"><img class="logo_kategorije" src="slike/pića.jpg" alt="Piće"></a> Piće</li>
                </form>
            </ul>
        </div>
        <div id="content">
            <?php
                if(isset($_GET['id'])){
                    $id=$_GET['id'];
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Doručak'");
                    $query2=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Sendvič'");
                    $query3=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Pizza'");
                    $query4=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Burger'");
                    $query5=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Roštilj'");
                    $query6=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Piletina'");
                    $query7=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Giros'");
                    $query8=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Domaće jelo'");
                    $query9=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Azijska hrana'");
                    $query10=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Riba'");
                    $query11=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Pasta'");
                    $query12=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Salata'");
                    $query13=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Prilozi'
                    GROUP BY SM.NAZIV_STAVKE");
                    $query14=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV ,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Dezert'");
                    $query15=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV,SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Piće'
                    GROUP BY SM.NAZIV_STAVKE");
                    
                    switch ($id){
                        case 1:
                            echo '<h2>Doručak:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query1)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Doručak'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 2:
                            echo '<h2>Sendviči:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query2)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Sendvič'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 3:
                            echo '<h2>Pizza:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query3)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Pizza'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 4:
                            echo '<h2>Burgeri:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query4)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Burger'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 5:
                            echo '<h2>Roštilj:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query5)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Roštilj'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 6:
                            echo '<h2>Piletina:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query6)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Piletina'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 7:
                            echo '<h2>Gyros:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query7)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Giros'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 8:
                            echo '<h2>Domaća jela:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query8)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Domaće jelo'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 9:
                            echo '<h2>Azijska hrana:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query9)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Azijska hrana'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 10:
                            echo '<h2>Riba:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query10)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Riba'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 11:
                            echo '<h2>Paste:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query11)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Pasta'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 12:
                            echo '<h2>Salate:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query12)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Salata'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 13:
                            echo '<h2>Prilozi:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query13)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Prilozi'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 14:
                            echo '<h2>Dezerti:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query14)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Dezert'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        case 15:
                            echo '<h2>Piće:</h2><hr>';
                            while($row1=mysqli_fetch_assoc($query15)){
                                if($row1['POPUST']){
                                    $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                    from stavke_menija SM 
                                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                    where P.KATEGORIJA='Piće'
                                    AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    $novacena=mysqli_fetch_assoc($upitcena);
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }else{
                                    echo '<form action="" method="post">';
                                    echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                    echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span>---<span class="naziv_r">'.$row1['NAZIV'].'</span></div><br>
                                    <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                    echo '</form>';
                                }
                            }
                            break;
                        default:
                            echo "Neispravan id";
                    }
                }else{
                    echo "Nema id";
                }
            ?>
            
            <script>
                var isLocalStorageSupported = (typeof Storage !== "undefined");
                // function dodajUKolica(id, nazivP, cena, nazivR) {
                //     // Check if localStorage is available in the browser
                //     if (isLocalStorageSupported) {
                //         // Initialize the cart array or retrieve it if it already exists
                //         var cart = JSON.parse(localStorage.getItem("cart")) || [];

                //         // Check if the item is already in the cart based on its ID
                //         var itemExists = false;
                //         for (var i = 0; i < cart.length; i++) {
                //             if (cart[i].id === id) {
                //                 // If the item is already in the cart, update the quantity
                //                 cart[i].quantity++;
                //                 itemExists = true;
                //                 break;
                //             }
                //         }

                //         // If the item is not in the cart, add it with quantity 1
                //         if (!itemExists) {
                //             cart.push({ id: id, name: nazivP, price: cena, quantity: 1 });
                //         }

                //         // Store the updated cart in localStorage
                //         localStorage.setItem("cart", JSON.stringify(cart));

                //         // Update the cart count on the page (you can do this dynamically)
                //         var cartCount = document.querySelector(".brojacArtikala");
                //         var totalCount = 0;
                //         for (var i = 0; i < cart.length; i++) {
                //             totalCount += cart[i].quantity;
                //         }
                //         cartCount.innerText = totalCount;
                //     } else {
                //         alert("Local storage is not supported in your browser.");
                //     }
                // }
                // JavaScript to reload the page with a different ID
                function reloadWithDifferentText(id) {
                    window.location.href = "kategorije.php?id=" + id;
                }
            </script>
        </div>
    </div>
    <script type="text/javascript">
        window.addEventListener("scroll",function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0);
        })
    </script>
</body>
</html>