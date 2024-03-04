<?php
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