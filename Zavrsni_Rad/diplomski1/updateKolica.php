<?php
    session_start();

    if (isset($_POST['cart_data'])) {
        // Decode the JSON data from the client
        $newCartData = json_decode($_POST['cart_data'], true);

        // Update the session variable with the new data
        $_SESSION['cart'] = $newCartData;

        // Respond with a success message
        echo 'success';
    } else {
        // Handle the case where 'cart_data' is not set in the POST request
        echo 'error';
    }
?>
