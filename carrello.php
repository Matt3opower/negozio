<?php
session_start();
include "functions.php";
include "connessione.php";
$totale_carrello = 0;
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
} else {
    if ($_SESSION['email'] == "admin@admin") {
        header("Location: homepage.php");
    } else {
        if (!isset($_SESSION['carrello'])) {
            $_SESSION['carrello'] = array();
            $email = $_SESSION['email'];
            //echo $email;
            $sql_cart = "SELECT lista_carrello FROM utente WHERE email = '$email'";
            $result_cart = $db_connection->query($sql_cart);
            $row_cart = $result_cart->fetch_assoc();
            //echo "ciao" . $row_cart['lista_carrello'];
            if ($row_cart['lista_carrello'] != "") {
                $stringa_json = $row_cart['lista_carrello'];
                $_SESSION['carrello'] = json_decode($stringa_json, true);
                //var_dump($_SESSION['carrello']);
            }
        }
    }
}
if (isset($_POST['empty'])) {
    emptyCart();
}
$check_empty_cart = true;
$check_qnt = true;
if (isset($_POST['buy'])) {



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
        } else {
            //VIA LIBERA
            
            //AGGIUNTA ACQUISTO AL RECORD
            $email = $_SESSION['email'];
            $carrello = $_SESSION['carrello'];
            $json_carrello = json_encode($carrello);
            $sql_cart_buy = "INSERT INTO acquisti (email, lista_acquisto) VALUES ('$email', '$json_carrello')";
            $result_cart_buy = $db_connection->query($sql_cart_buy);

            //UPDATE QUANTITÀ DISPONIBILE
            $qnt_disponibile -= $qnt_prod;
            $sql = "UPDATE prodotto SET quantita_disponibile = '$qnt_disponibile' WHERE id_prodotto = '$id_prod'";
            $result = $db_connection->query($sql);
            emptyCart();
        }
    endforeach;
    header("Location:carrello.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Carrello</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
</head>

<body class="bg-[#f0f3f8]">
    <?php
    include "navbar.php";
    ?>
    <div class="container">
        <div class="lg:grid lg:grid-cols-12">
            <div class=" lg:col-span-9 p-3 mt-14">
                <div class="overflow-x-auto  shadow-md sm:rounded-lg">
                    <div class="overflow-x-auto  shadow-md sm:rounded-lg">
                        <?php
                        // Controlla se l'array $_SESSION['carrello'] è vuoto
                        if (!empty($_SESSION['carrello'])) {
                            ?>
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Nome prodotto</th>
                                        <th scope="col" class="py-3 px-6">Prezzo totale</th>
                                        <th scope="col" class="py-3 px-6">Quantità</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr class="bg-white dark:bg-gray-800">
                            <td class="py-4 px-6">Ethan Davis</td>
                            <td class="py-4 px-6">64738290</td>
                            <td class="py-4 px-6">$865.00</td>
                        </tr> -->
                                    <?php foreach ($_SESSION['carrello'] as $dettagliProdotto): ?>
                                        <tr class="bg-white border-t dark:bg-gray-800 dark:border-gray-700">
                                            <td class="py-4 px-6">
                                                <?php echo $dettagliProdotto['nome']; ?>
                                            </td>
                                            <td class="py-4 px-6">
                                                <?php
                                                echo $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'] . " €";
                                                $totale_carrello += $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'];
                                                ?>
                                            </td>
                                            <td class="py-4 px-6">
                                                <?php echo $dettagliProdotto['quantita']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            // Stampa un messaggio che indica che il carrello è vuoto
                            $check_empty_cart = false;
                        }
                        ?>
                    </div>
                </div>

            </div>
            <div class=" lg:col-span-3 p-3">
                <?php
                if (!empty($_SESSION['carrello'])) {
                    ?>
                    <form action='#' method='POST'>
                        <div class='text-white font-bold bg-sky-500 p-2 rounded-lg w-full mt-14 lg:mb-[22px]'>
                            <?php echo 'Totale Carrello: ' . $totale_carrello . '€'; ?>
                        </div>

                        <div class="lg:grid lg:grid-cols-2 gap-3 ">
                            <div class="">
                                <button
                                    class='w-full p-2 rounded-lg w-fit mt-3 text-white font-bold bg-sky-500 hover:bg-sky-600 hover:scale-105 duration-300'
                                    id='empty' name='empty'>
                                    Svuota carrello
                                </button>
                            </div>
                            <div class="">
                                <button
                                    class='w-full p-2 rounded-lg w-fit mt-3 text-white font-bold bg-sky-500 hover:bg-sky-600 hover:scale-105 duration-300'
                                    id='buy' name='buy'>
                                    Acquista
                                </button>
                            </div>
                        </div>

                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php if (!$check_qnt) { ?>
            <div class="alert alert-danger mt-4 w-fit mx-auto" role="alert">
                Quantità selezionate troppo elevate
            </div>
        <?php } ?>

        <?php
        if (!$check_empty_cart) {
            echo "
                    <div class='text-white font-bold bg-sky-500 p-2 rounded-lg w-fit mt-12 mx-auto text-2xl font-bold'>
                        Carrello vuoto
                    </div>
                ";
        }
        ?>
    </div>









    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>