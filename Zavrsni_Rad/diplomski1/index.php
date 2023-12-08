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
    <title>Početna strana</title>
    <style>
        label, p{
            font-weight: lighter;
            font-size: 25px;
        }
        h2{
            font-weight: lighter;
            font-size: 35px;
        }
    </style>
</head>
<body id="login_body">
    <div class="login-container">
        <img src="slike/logoBT2.png" alt="Logo" id="logo">
        <h2>Prijavite se i naručite odmah</h2>
        <form id="login-form" action="login.php" method="post">
            <div class="form-group">
                
                <input type="text" value="<?php if (isset($_COOKIE["user"])){echo $_COOKIE["user"];}?>" id="email" name="email" required placeholder="E-mail">
            </div>
            <div class="form-group">
                
                <input type="password" value="<?php if (isset($_COOKIE["pass"])){echo $_COOKIE["pass"];}?>" id="password" name="password" required placeholder="Lozinka">
            </div>
            <div class="form-group" style="text-align:left;">
                <label><input type="checkbox" name="remember" <?php if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){ echo "checked";}?>> Zapamti me </label>
            </div>
            <button type="submit" id="login" name="login">Prijava</button>
            
            <div style="background-color: indianred; font-size: 20px;color:#fff; border-radius: 5px; margin-top:10px">
            <?php
                if (isset($_SESSION['message'])){
                    echo $_SESSION['message'];
                }
                unset($_SESSION['message']);
            ?></div>
        </form>
        <p class="prijava">Nemate nalog? <a href="registracija.php">Registrujte se ovde</a></p>
        <p class="prijava">Da li ste <b>Administrator</b> ili <b>Dostavljač</b>? <a href="index2.php">Prijavite se ovde</a></p>
    </div>


</body>
</html>
