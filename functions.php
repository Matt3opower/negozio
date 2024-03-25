<?php

//IMPOSTA COOKIE
function impostaCookieLogin($email, $password)
{
    $cookie_name = "email";
    $cookie_value = $email;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

    $cookie_name = "password";
    $cookie_value = $password;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

}




//CANCELLA COOKIE
function deleteCookie($email, $password)
{
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
}




//AGGIUNGI AL CARRELLO
function addToCart($id, $nome, $prezzo, $quantita, $img_path)
{
    include "connessione.php";
    if (!isset ($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
    }

    //controllo se articolo già presente
    $found = false;
    if (isset ($_SESSION['carrello'][$id])) {
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


//SVUOTA CARRELLO
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


//COMPRA
function buy($check_qnt)
{
    include "connessione.php";
    //CHECK QUANTITÀ
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
        }
    endforeach;



    if ($check_qnt) {
        //VIA LIBERA

        //AGGIUNTA ACQUISTO AL RECORD
        $email = $_SESSION['email'];
        $carrello = $_SESSION['carrello'];
        $json_carrello = json_encode($carrello);
        var_dump($json_carrello);
        $sql_cart_buy = "INSERT INTO acquisti (email, lista_acquisto) VALUES ('$email', '$json_carrello')";
        $result_cart_buy = $db_connection->query($sql_cart_buy);

        foreach ($_SESSION['carrello'] as $prodotto):

            $id_prod = $prodotto['id'];
            $qnt_prod = $prodotto['quantita'];

            $sql = "SELECT quantita_disponibile FROM prodotto WHERE id_prodotto = '$id_prod'";
            $result = $db_connection->query($sql);
            $resultt = $result->fetch_assoc();
            $qnt_disponibile = $resultt['quantita_disponibile'];

            //UPDATE QUANTITÀ DISPONIBILE
            $qnt_disponibile -= $qnt_prod;
            $sql = "UPDATE prodotto SET quantita_disponibile = '$qnt_disponibile' WHERE id_prodotto = '$id_prod'";
            $result = $db_connection->query($sql);
        endforeach;
        emptyCart();
    }
    //header("Location:carrello.php");
    return ($check_qnt);
}



//SALVA STATO CARRELLO
function saveCart()
{
    include "connessione.php";
    $email = $_SESSION['email'];
    $carrello = $_SESSION['carrello'];
    $json_carrello = json_encode($carrello);
    $sql_cart_save = "UPDATE utente SET lista_carrello = '$json_carrello' WHERE email = '$email'";
    $result_cart_save = $db_connection->query($sql_cart_save);
}


function itemRemove(){
    include "connessione.php";
    $car = $_SESSION['carrello'];
    $id_remove = $_POST['rimuovi'];
    if (isset ($car[$id_remove])) {
        // Rimuove il prodotto specificato dal carrello
        unset($car[$id_remove]);
        // Aggiorna il carrello nella sessione
        $_SESSION['carrello'] = $car;
    }
    saveCart();
}

function itemLessOne(){
    include "connessione.php";
    $car = $_SESSION['carrello'];
    $id_less = $_POST['less'];
    if (isset ($car[$id_less])) {
        if ($car[$id_less]['quantita'] <= 1) {
            unset($car[$id_less]);
        } else {
            $car[$id_less]['quantita']--;
        }
        // Aggiorna il carrello nella sessione
        $_SESSION['carrello'] = $car;
    }
    saveCart();
}



function itemMoreOne($check_qnt){
    include "connessione.php";
    $car = $_SESSION['carrello'];
    $id_more = $_POST['more'];
    if (isset ($car[$id_more])) {
        $id_prod = $car[$id_more]['id'];
        $qnt_prod = $car[$id_more]['quantita'];
        $sql = "SELECT quantita_disponibile FROM prodotto WHERE id_prodotto = '$id_prod'";
        $result = $db_connection->query($sql);
        $resultt = $result->fetch_assoc();
        $qnt_disponibile = $resultt['quantita_disponibile'];
        if ($car[$id_more]['quantita'] == $qnt_disponibile) {
            $check_qnt = false;
        } 
        if($check_qnt){
            $car[$id_more]['quantita']++;
        }
        // Aggiorna il carrello nella sessione
        $_SESSION['carrello'] = $car;
    }
    saveCart();
    return($check_qnt);
}


