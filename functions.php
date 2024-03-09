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
// function impostaCookieColor($colore_sfondo)
// {
//     $cookie_name = "colore_sfondo";
//     $cookie_value = $colore_sfondo;
//     setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
// }





function deleteCookie($email, $password)
{
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
}





function addToCart($id, $nome, $prezzo, $quantita)
{
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
            'quantita' => $quantita
        );
        $_SESSION['carrello'][$id] = $item;
        //print_r($_SESSION);
        //echo "Aggiunto con successo";
    }
}





// function buy()
// {

// }





function emptyCart()
{
    $_SESSION['carrello'] = array();
    //unset($_SESSION['carrello']);
    header("Location:carrello.php");
}


