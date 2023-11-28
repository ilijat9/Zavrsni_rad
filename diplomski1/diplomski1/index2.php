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
    <title>Prijava zaposlenih</title>
</head>
<body id="login_body">
    <div class="login-container">
        <a href="index.php"><img src="slike/logoBT2.png" alt="Logo" id="logo"></a>
        <h2 style="font-weight:lighter;font-size:30px">Prijavi se</h2>
        <form id="login-form" action="login.php" method="post">
            <div class="form-group">
                <label for="role" style="font-size:25px">Izaberi ulogu:</label>
                <select id="role" name="role" style="color: rgb(33, 33, 92);font-family: 'Mouse Memoirs'; font-size:20px;">
                    <option value="Administrator">Administrator</option>
                    <option value="Dostavljač">Dostavljač</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" id="email" name="email" required placeholder="E-mail">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="Lozinka">
            </div>
            <button type="submit" id="login2" name="login2">Prijava</button>
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

</body>
</html> 