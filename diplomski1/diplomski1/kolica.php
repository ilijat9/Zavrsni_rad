<?php 
    session_start();
    include('konekcija.php');
    if (!isset($_SESSION['id']) || (trim ($_SESSION['id']) == '')) {
        header('Location: index.php');
        exit();
    }
    $query=mysqli_query($conn,"select * from korisnik where id_korisnika='".$_SESSION['id']."'");
    $row=mysqli_fetch_assoc($query);
    $localStorageSupported = isset($_GET['localStorageSupported']) && $_GET['localStorageSupported'] === 'true';
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        $array=$_SESSION['cart'];
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
    <div class="cart-container">
        <h2>Tvoja Kolica</h2>
        <hr style="width:100%">
        <?php
            if(isset($_SESSION['message'])){
                echo '<div style="background-color: rgb(33, 33, 92); font-size: 30px;color:#fff; border-radius: 5px; margin-top:10px; padding:10px">';
                if (isset($_SESSION['message'])){
                    echo $_SESSION['message'];
                }
                unset($_SESSION['message']);
                echo '</div>';
            }
        ?>
        <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                echo'<table class="tabela1" style="width:100%">';
                echo '<tr >';
                    echo'<th>NAZIV PROIZVODA</th>';
                    echo'<th>KOLIČINA</th>';
                    echo'<th>CENA</th>';
                    echo'<th>RESTORAN</th>';
                    echo'<th>NAPOMENA</th>';
                echo'</tr>';
                $i=0;
                echo '<form method="post" action="dodajPorudzbinu.php">';
                $ukupnaCena=0;
                foreach ($_SESSION['cart'] as $itemIndex=>$item) {
                    echo '<tr name="id" width:100%">';
                        echo '<td name="naziv">' . $item['NAZIV_STAVKE'] . '</td>';
                        echo '<td style="text-align:center"><input type="number" name="kolicina[]" id="kolicina-' . $i . '" oninput="azurirajCenu('.$i.')" style="width:50px" required min="1"></td>';
                        echo '<td style="text-align:center"><span name="cena">' . $item['CENA'] . '</span> rsd</td>';
                        $ukupnaCena+=$item['CENA'];
                        echo '<td style="text-align:center">' . $item['NAZIV'] . '</td>';
                        echo '<td style="text-align:center"><textarea name="napomena[]" style="height:40px;width: 160px"></textarea></td>';
                        echo '<td style="text-align:center"><img class="dodaj_stavku" src="slike/oduzmi.png" alt="MINUS" onclick=izbaciProizvod('.$i.')></td>';
                        echo '</tr>';
                        $i++;
                }
                echo '</table>';
                echo '<hr style="width:100%">';
                echo '<div class="infoPorudzbine">';
                echo '<span style="font-size:30px; margin-right:5px">Adresa:</span><input type="text" name="adresa" id="adresa" required>';
                echo '<input type="hidden" name="ukupnaCena" value="">';
                echo '<span class="ukupnaCena" >Ukupan iznos: <span name="ukupno">'.$ukupnaCena.'</span> rsd</span>';
                echo '<input type="hidden" name="vreme" value="'.date('Y-m-d H:i:s', time()).'">';
                echo '<button type="submit" name="poruči" class="poruči">Poruči</button>';
                echo '</div>';
                echo '</form>';
                
            } else {
                echo '<tr><td colspan="3""><strong style="font-size:35px; font-weight:lighter; ">Tvoja kolica su prazna!</strong></td></tr>';
            }
            ?>
    </div>
    <script>
        function azurirajCenu(index) {
            var quantity = document.getElementById('kolicina-' + index).value;
            console.log("index"+index);
            var duzina=<?php echo sizeof($_SESSION['cart'])?>;
            var niz;
            niz=<?php echo json_encode($_SESSION['cart'])?>;
            console.log(<?php echo json_encode($_SESSION['cart'])?>);
            
            var totalPrice = quantity * parseFloat(niz[index]['CENA']);
            document.getElementsByName('cena')[index].innerHTML = totalPrice;
            azurirajUkupnuCenu();
        }
        function azurirajUkupnuCenu(){
            //document.getElementsByName('ukupno').textContent="";
            var suma=0;
            document.getElementsByName('cena').forEach((element) => {
                console.log(element.innerHTML)
                suma+=parseFloat(element.innerHTML);
            });
            document.getElementsByName('ukupno')[0].innerHTML=suma;
            document.getElementsByName('ukupnaCena')[0].value=suma;
        }
        function izbaciProizvod(itemId) {
            var currentCart = <?php echo json_encode($_SESSION['cart']); ?>;
        
            delete currentCart[itemId];
            var currentCartArray = Object.values(currentCart);
        
            // Send the updated cart data to the server
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateKolica.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200 && xhr.responseText === 'success') {
                    // Reload the page (kolica.php) or update the cart display as needed
                    window.location.reload(); // You can also choose to update the cart display without a full page reload
                } else {
                    alert('Failed to update the cart.');
                }
            };

            // Encode the updated cart data as JSON and send it in the POST request
            xhr.send('cart_data=' + JSON.stringify(currentCartArray));
        }   
    </script>
    <!-- <script src="kolica.js"></script> -->
    
    <script type="text/javascript">
        window.addEventListener("scroll",function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0);
        })
    </script>
</body>
</html>
