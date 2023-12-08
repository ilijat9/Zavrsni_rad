<?php 
    session_start();
    include('konekcija.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registracija</title>
</head>
<body id="login_body">
    <div class="login-container">
        <a href="index.php"><img src="slike/logoBT2.png" alt="Logo" id="logo"></a>
        <h2 style="font-weight:lighter;font-size:30px">Registruj se</h2>
        <form action="login.php" id="signup-form" method="post">
            <div class="form-group">
                <input type="text" id="ime" name="ime" required placeholder="Ime">
            </div>
            <div class="form-group">
                <input type="text" id="prezime" name="prezime" required placeholder="Prezime">
            </div>
            <div class="form-group">                
                <input type="email" id="email" name="email" required placeholder="E-mail">
            </div>
            <div class="form-group">               
                <input type="password" id="password" name="password" required placeholder="Lozinka">
            </div>
            <div class="form-group">
                <input type="text" id="broj" name="broj" required placeholder="Broj telefona">
            </div>
            <button type="submit" id="registruj" name="registruj">Registracija</button>
        </form>
        <div style="background-color: indianred; font-size: 20px;color:#fff; border-radius: 5px; margin-top:10px">
            <?php
                if (isset($_SESSION['message'])){
                    echo $_SESSION['message'];
                }
                unset($_SESSION['message']);
            ?>
        </div>
    </div>
    <?php
    
    ?>
</body>
</html>