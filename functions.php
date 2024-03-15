<?php

function impostaCookieLogin($email, $password)
{
    $cookie_name = "email";
    $cookie_value = $email;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

    $cookie_name = "password";
    $cookie_value = $password;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

}





function deleteCookie($email, $password)
{
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
}





function addToCart($id, $nome, $prezzo, $quantita, $img_path)
{
    include "connessione.php";
    if (!isset($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
    }

    //controllo se articolo già presente
    $found = false;
    if (isset($_SESSION['carrello'][$id])) {
        $_SESSION['carrello'][$id]['quantita'] += $quantita;
        $found = true;
        //echo "Quantità aggiornata";
    }

    //se non presente lo aggiungo al carrello
    if (!$found) {
        $item = array(
            'id' => $id,
            'nome' => $nome,
            'prezzo' => $prezzo,
            'quantita' => $quantita,
            'img_path' => $img_path
        );
        $_SESSION['carrello'][$id] = $item;
        //print_r($_SESSION);
        //echo "Aggiunto con successo";
    }

    $email = $_SESSION['email'];
    $carrello = $_SESSION['carrello'];
    $json_carrello = json_encode($carrello);
    $sql_cart_save = "UPDATE utente SET lista_carrello = '$json_carrello' WHERE email = '$email'";
    $result_cart_save = $db_connection->query($sql_cart_save);
}



function emptyCart()
{
    include "connessione.php";
    $_SESSION['carrello'] = array();

    $email = $_SESSION['email'];
    $carrello = $_SESSION['carrello'];
    $json_carrello = json_encode($carrello);
    $sql_cart_save = "UPDATE utente SET lista_carrello = '$json_carrello' WHERE email = '$email'";
    $result_cart_save = $db_connection->query($sql_cart_save);

    header("Location:carrello.php");
}


