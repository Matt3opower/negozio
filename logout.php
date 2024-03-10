<?php
// Avvia la sessione
session_start();
include "connessione.php";

//SALVATAGGIO DEL CARRELLO DELL'UTENTE
$email = $_SESSION['email'];
$carrello = $_SESSION['carrello'];
$json_carrello = json_encode($carrello);
$sql_cart_save = "UPDATE utente SET lista_carrello = '$json_carrello' WHERE email = '$email'";
$result_cart_save = $db_connection->query($sql_cart_save);

// Eliminazione di tutte le variabili di sessione
session_unset();

// Distruzione della sessione
session_destroy();

// Redirect dell'utente alla pagina di login
header("Location: login.php");

