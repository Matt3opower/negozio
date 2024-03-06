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




include "connessione.php";
function buyCart($db_connection){
    foreach ($_SESSION['carrello'] as $prodotto):
        $id_prod = $prodotto['id'];
        $qnt_prod = $prodotto['quantita'];

        $sql = "SELECT quantita_disponibile FROM prodotto WHERE id_prodotto = '$id_prod'";
        $result = $db_connection->query($sql);
        $resultt = $result->fetch_assoc();

        $qnt_disponibile = $resultt['quantita_disponibile'];

        if ($qnt_prod > $qnt_disponibile) {
            //ERRORE QUANTITÀ TROPPO ELEVATA
            $check_qnt = false;
        } else {
            //VIA LIBERA
            $qnt_disponibile -= $qnt_prod;
            $sql = "UPDATE prodotto SET quantita_disponibile = '$qnt_disponibile' WHERE id_prodotto = '$id_prod'";
            $result = $db_connection->query($sql);
            emptyCart();
        }
    endforeach;
    header("Location:carrello.php"); 
}