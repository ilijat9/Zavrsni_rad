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
    <title>Izbor Restorana</title>
    <style>
        body{
            background: url("slike/pozadina5.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Mouse Memoirs', sans-serif;
        }
        header{
            background: rgba(204,204,204,0.8);
            border-radius: 5px;
            border: 2px solid #ccc;
        }
        .restaurant-list-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            white-space: nowrap;
            
        }

    </style>
</head>
<body>
    <header>
        <a href="korisnik1.php"><img src="slike/logoBT2.png" alt="Logo"  id="logo_"></a>
        <nav class="navbar">
            <ul class="navbar_lista">
                <li>Profil: <?php echo $row['IME'] .' '. $row['PREZIME']; ?></li>
                <li><a href="profil.php"><img src="slike/profil.png" alt="Nalog" class="ikonice"></a></li>
                <li><a href="kolica.php"><div class="kolica"><img src="slike/cart1.png" alt="Kolica" class="ikonice"></a></li>
                <li><a href="odjava.php"><img src="slike/odjava.png" alt="Odjava" class="ikonice"></a></li>
            </ul>
        </nav>
    </header>
    <div class="restaurant-list-container">
        <?php
            $query1=mysqli_query($conn,"SELECT * from restoran where AKTIVNOST='1' order by OCENA desc ");
            if(mysqli_num_rows($query1) != 0){
                while($row1=mysqli_fetch_assoc($query1)){
                    if($row1['AKTIVNOST']=='1'){
                        echo '<a href="izborRestorana.php?id='.$row1['ID_RESTORANA'].'"><input style="color:rgb(33, 33, 92);margin:20px 5px 10px 5px;width:150px;font-size:20px;font-family: \'Mouse Memoirs\', sans-serif;"  type="submit" name="restorani" value="'.$row1['NAZIV'].'"></a>';
                    }
                }
            }
            else{
                echo 'Nema aktivnih restorana!!!';
            }
        ?>
    </div>
    <div class="restorani-container">   
        <?php
        if(isset($_GET['id'])){
            $upitID=$_GET['id'];
        }
        ?>
        <div class="infoRestorana">
            <?php
                $query1=mysqli_query($conn,"SELECT * from restoran where ID_RESTORANA=$upitID");
                $row1=mysqli_fetch_assoc($query1);
                echo '<h2>'.$row1['NAZIV'].'</h2><br>';
                echo '<label class="infoR">Adresa:<br>'.$row1['ADRESA'].'</label><br>';
                echo '<label class="infoR">Radno vreme:<br>'.$row1['RADNO_VREME'].'</label><br>';
                echo '<label class="infoR">Kontakt telefon:<br>'.$row1['KONTAKT_TELEFON'].'</label><br>';
                if($row1['OCENA']){
                    echo '<label class="infoR">Ocena:<br>'.$row1['OCENA'].'</label>';
                }
                else{
                    echo '<label class="infoR">Ocena:<br>Neocenjen</label>';
                }
            ?>
        </div>
        <div class="restoraniMeni">
            
            <?php
                    $query1=mysqli_query($conn,
                    "SELECT SM.ID_STAVKE, SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV 
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Doručak' and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divDorucak">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Doručak</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA='Doručak' and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';                            
                                echo '</form>';
                            }else{
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.ID_STAVKE 
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA in('Sendvič','Pizza','Burger','Roštilj','Piletina','Giros','Domaće jelo','Azijska hrana','Riba','Pasta') and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divGlavnoJelo">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Glavna jela</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA in('Sendvič','Pizza','Burger','Roštilj','Piletina','Giros','Domaće jelo','Azijska hrana','Riba','Pasta') and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }else{
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Salata' and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divSalate">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Salate</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA='Salata' and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }else{
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.ID_STAVKE 
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Prilozi' and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divPrilozi">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Prilozi</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA='Prilozi' and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }else{
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br> 
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.ID_STAVKE
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Dezert' and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divDezerti">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Dezerti</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA='Dezert' and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }else{
                                echo '<form action="" method="post">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    $query1=mysqli_query($conn,
                    "SELECT SM.NAZIV_STAVKE, SM.SASTOJCI, SM.CENA, SM.POPUST, R.NAZIV, SM.ID_STAVKE 
                    FROM STAVKE_MENIJA SM
                    JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                    JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                    JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                    where P.KATEGORIJA='Piće' and R.ID_RESTORANA=$upitID and SM.AKTIVNOST='1'");
                    if(mysqli_num_rows($query1) != 0){
                        echo '<div class="divPiće">';
                        echo '<h1 style="font-size:40px;font-weight:lighter">Pića</h1>';
                        while($row1=mysqli_fetch_assoc($query1)){
                            if($row1['POPUST']){
                                $upitcena=mysqli_query($conn,"select NAZIV_STAVKE, CENA-(CAST(REPLACE(POPUST,'%','') AS DECIMAL(10,2))/100)*CENA as rezultat  
                                from stavke_menija SM 
                                JOIN MENIJI M ON SM.ID_MENIJA = M.ID_MENIJA
                                JOIN RESTORAN R ON M.ID_RESTORANA = R.ID_RESTORANA
                                JOIN PROIZVODI P ON SM.ID_PROIZVODA = P.ID_PROIZVODA
                                where P.KATEGORIJA='Piće' and SM.AKTIVNOST='1'
                                AND SM.NAZIV_STAVKE = '" . mysqli_real_escape_string($conn, $row1['NAZIV_STAVKE']) . "'");
                                echo '<form method="post" action="">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                $novacena=mysqli_fetch_assoc($upitcena);
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span><div class="popustTag"><div class="brojpopusta">-'.$row1['POPUST'].'</div></div></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="stara_cena">'.$row1['CENA'].'rsd</span><span class="cena" style="color:red;">'.$novacena['rezultat'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }else{
                                echo '<form method="post" action="">';
                                echo '<input type="hidden" name="item_id" value="'.$row1['ID_STAVKE'].'">';
                                echo '<div class="kontejner"><span class="naziv">'.$row1['NAZIV_STAVKE'].'</span></div><br>
                                <span class="sastojci">'.$row1['SASTOJCI'].'</span><div class="staraNovaCena"><span class="cena">'.$row1['CENA'].'rsd</span><input class="plus" type="submit" name="add_to_cart" value="" style="background-image: url(\'slike/dodaj.png\'); border:none;border-radius:50%; background-repeat:no-repeat;background-size:100% 100%;height: 40px;width: 40px;margin-right: 30px;"></div><hr>';
                                echo '</form>';
                            }
                        }
                        echo '</div>';
                    }
                    ?>       
        </div>
    </div>
    <div></div>  
    <script>
        var isLocalStorageSupported = (typeof Storage !== "undefined");
        function dodajUKolica(id, nazivP, cena, nazivR) {
            // Check if localStorage is available in the browser
            if (isLocalStorageSupported) {
                // Initialize the cart array or retrieve it if it already exists
                var cart = JSON.parse(localStorage.getItem("cart")) || [];

                // Check if the item is already in the cart based on its ID
                var itemExists = false;
                for (var i = 0; i < cart.length; i++) {
                    if (cart[i].id === id) {
                        // If the item is already in the cart, update the quantity
                        cart[i].quantity++;
                        itemExists = true;
                        break;
                    }
                }

                // If the item is not in the cart, add it with quantity 1
                if (!itemExists) {
                    cart.push({ id: id, name: nazivP, price: cena, quantity: 1 });
                }

                // Store the updated cart in localStorage
                localStorage.setItem("cart", JSON.stringify(cart));

                // Update the cart count on the page (you can do this dynamically)
                var cartCount = document.querySelector(".brojacArtikala");
                var totalCount = 0;
                for (var i = 0; i < cart.length; i++) {
                    totalCount += cart[i].quantity;
                }
                cartCount.innerText = totalCount;
            } else {
                alert("Local storage is not supported in your browser.");
            }
        }
        const restaurantList = document.querySelector('.restaurant-list-container');
        const scrollDistance = 220; // Adjust the scroll distance as needed

        function scrollLeft() {
            restaurantList.scrollLeft -= scrollDistance;
        }

        function scrollRight() {
            restaurantList.scrollLeft += scrollDistance;
        }
    </script>

    <!-- <script src="kolica.js"></script> -->
    <!-- <script type="text/javascript">
        let kolica=document.querySelectorAll('.add-cart');
        console.log(kolica);
        let selectedIDs=[];

        for(let i=0; i< kolica.length; i++){    
            console.log(kolica[i].id);
            kolica[i].addEventListener('click', () =>{
                const id=kolica[i].id;
                selectedIDs.push(id);
                brojProizvoda(id);
            });
        }

        function brojProizvoda(id){
            let brojP=localStorage.getItem('brojProizvoda');
            console.log(brojP);
            brojP=parseInt(brojP);
            if(brojP){
                localStorage.setItem('brojProizvoda',brojP+1);
                document.querySelector('.kolica span').textContent=brojP+1;
            }
            else{
                localStorage.setItem('brojProizvoda', 1);
                document.querySelector('.kolica span').textContent=1;
            }
            function sendSelectedIDsToKolica(){
                const selectedIDsString=selectedIDs.join(',');
                window.location.href=`kolica.php?selectedIDs=${selectedIDsString}`;
            }
        }
    </script> -->
</body>
</html>