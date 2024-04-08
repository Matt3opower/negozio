<?php

session_start();

include "connessione.php";
include "functions.php";

if (!isset ($_SESSION['email'])) {
    header("Location: login.php");
} else {
    if (!isset ($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
    }
}
$email = $_SESSION['email'];
$lista_acquisti = "";
$totale_carrello = 0;

$sql = "SELECT * FROM utente WHERE email = '$email'";
$result = $db_connection->query($sql);
$row = $result->fetch_assoc();



$check_input_prod = 0;
if (isset ($_POST['addProduct'])) {
    $nome_p = $_POST['nome'];
    $prezzo_p = $_POST['prezzo'];
    $quantita_disponibile_p = $_POST['quantita_disponibile'];

    //cerco ultimo id inserito nella tabella prodotto
    $sql_lastid = "SELECT MAX(id_prodotto) as last_id FROM prodotto";
    $result_lastid = $db_connection->query($sql_lastid);

    if ($result_lastid->num_rows > 0) {
        // Estrai l'ultimo ID autoincrementale
        $row_lastid = $result_lastid->fetch_assoc();
        $last_id_prodotto = $row_lastid["last_id"];
        $last_id_prodotto += 1;
    }


    if ($nome_p == "" || $prezzo_p == "" || $quantita_disponibile_p == "" || ($_FILES['fileToUpload']) == "") {
        $check_input_prod = 2;
    }



    if ($check_input_prod == 0) {
        $target_dir = "img/products/";

        $imgname = $nome_p . "." . pathinfo($_FILES["fileToUpload"]["name"])["extension"];
        $img_path = $target_dir . $last_id_prodotto . $nome_p . "." . pathinfo($_FILES["fileToUpload"]["name"])["extension"];

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $img_path)) {

            $sql_upload = "INSERT INTO prodotto (nome, prezzo, quantita_disponibile, img_path) VALUES ('$nome_p', '$prezzo_p', '$quantita_disponibile_p', '$img_path');";
            $result_upload = $db_connection->query($sql_upload);
            $check_input_prod = 1;

        } else {
            echo "Errore durante il caricamento dell'immagine";
            $check_input_prod = 2;
        }
    }
}

?>



<!doctype html>
<html lang="en">

<head>
    <title>Profilo</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
    <style>
        ::-webkit-file-upload-button {
            display: none;
        }

        input[type="file"] {
            color: #9ca3af;
        }
    </style>
</head>

<body class="bg-[#f0f3f8]">
    <?php
    include "navbar.php";
    ?>

    <!-- tolti dal prossimo div: flex flex-col items-center justify-center -->
    <div <?php if ($row['email'] != 'admin@admin') {
        echo "class='container sm:grid sm:grid-cols-2 gap-20'";
    } ?>>
        <!-- riga 1 colonna 1 -->

        <!-- COLONNA DATI UTENTE -->
        <div class="flex flex-col items-center mt-20">
            <?php if ($row['email'] != 'admin@admin') { ?>
                <div class="bg-white p-5 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg w-full h-fit">
                    <div class="row mb-4">
                        <div class="col">Nome</div>
                        <div class="col">
                            <?php echo $row['nome'] ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row mb-4">
                        <div class="col">Cognome</div>
                        <div class="col">
                            <?php echo $row['cognome'] ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row mb-4">
                        <div class="col">Email</div>
                        <div class="col">
                            <?php echo $row['email'] ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row mb-4">
                        <div class="col">Indirizzo</div>
                        <div class="col">
                            <?php echo $row['indirizzo'] ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row mb-4">
                        <div class="col">Città</div>
                        <div class="col">
                            <?php echo $row['citta'] ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row">
                        <div class="col">Provincia</div>
                        <div class="col">
                            <?php echo $row['provincia'] ?>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg w-full mt-10 h-full">
                    <div class="row mb-4">
                        <div class="col">Quantità ordini effettuati</div>
                        <div class="col">
                            <?php
                            //echo $row['nome'];
                            $sql11 = "SELECT * FROM acquisti WHERE email = '$email'";
                            $result11 = $db_connection->query($sql11);
                            $num_rows11 = $result11->num_rows;
                            echo $num_rows11;
                            ?>
                        </div>
                    </div>
                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-4"></div>
                    <div class="row">
                        <div class="col">Totale (€) ordini effettuati</div>
                        <div class="col">
                            <?php
                            $totale_ordini = 0;
                            $totale_singolo = 0;
                            $sql_temp = "SELECT * FROM acquisti WHERE email = '$email' ORDER BY id DESC";
                            $result_temp = $db_connection->query($sql_temp);
                            $num_rows_temp = $result_temp->num_rows;

                            if ($num_rows_temp > 0) {
                                while ($row_temp = $result_temp->fetch_assoc()) {

                                    $stringa_json = $row_temp['lista_acquisto'];
                                    $lista_acquisti = json_decode($stringa_json, true);

                                    $totale_singolo = 0;
                                    foreach ($lista_acquisti as $dettagliProdotto):
                                        $totale_singolo += $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'];
                                    endforeach;
                                    $totale_ordini += $totale_singolo;
                                }
                            }
                            echo "€ " . $totale_ordini;
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLONNA RECORD ACQUISTI -->

            <div class="mt-20 h-[720px] scroll-auto overflow-y-auto flex flex-col items-center rounded-lg">

                <div class="w-full">
                    <?php
                    $sql_temp = "SELECT * FROM acquisti WHERE email = '$email' ORDER BY id DESC";
                    $result_temp = $db_connection->query($sql_temp);
                    $num_rows_temp = $result_temp->num_rows;

                    if ($num_rows_temp > 0) {
                        while ($row_temp = $result_temp->fetch_assoc()) {

                            $stringa_json = $row_temp['lista_acquisto'];
                            $lista_acquisti = json_decode($stringa_json, true);
                            ?>
                            <div class="overflow-x-auto shadow-md sm:rounded-lg mb-10">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6"></th>
                                            <th scope="col" class="py-3 px-6 ">
                                                <?php echo "ID Acquisto: " . $row_temp['id']; ?>
                                            </th>
                                            <th scope="col" class="py-3 px-6"></th>
                                        </tr>
                                    </thead>
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Nome prodotto</th>
                                            <th scope="col" class="py-3 px-6">Prezzo totale</th>
                                            <th scope="col" class="py-3 px-6">Quantità</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lista_acquisti as $dettagliProdotto): ?>
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
                            </div>
                            <?php
                        }
                    } else {
                        // Stampa un messaggio che indica che il carrello è vuoto
                        $check_empty_cart = false;
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>



    <div class="flex flex-col items-center mt-20">
        <?php if ($row['email'] == 'admin@admin') { ?>
            <div
                class="bg-white p-5 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg lg:w-full h-fit items-center justify-center flex text-2xl font-bold">
                <div>Benvenuto Admin</div>
            </div>



            <div
                class="bg-white p-5 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg lg:w-full mt-10 flex flex-col items-center justify-center font-bold text-2xl">
                <div>Inserisci Prodotto</div>
                <div class="mt-10 w-72">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="items-center justify-center">

                            <input type="text" name="nome" id="nome"
                                class="mx-auto mt-2 block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                placeholder="Nome" value="">

                            <input type="number" name="prezzo" id="prezzo" step="0.01"
                                class="mx-auto mt-2 block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                placeholder="Prezzo" value="">

                            <input type="number" name="quantita_disponibile" id="quantita_disponibile"
                                class="mx-auto mt-2 block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                placeholder="Quantità disponibile" value="">

                            <input type="file" name="fileToUpload" id="fileToUpload"
                                class="bg-white mx-auto mt-2 block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                style="width:100%; height:100%;">

                            <input type="submit" name="addProduct" id="addProduct"
                                class="hover:bg-sky-600 bg-sky-500 mx-auto mt-5 p-2 font-bold text-white block w-56 rounded-md text-gray-900 placeholder:text-gray-400 sm:text-sm sm:leading-6 focus:outline-none"
                                style="width:100%; height:100%;">
                            <!-- Aggiungi prodotto
                            </button> -->
                        </div>
                    </form>
                    <?php if ($check_input_prod == 1) { ?>
                        <div class="alert alert-success mt-4 mx-auto w-full" role="alert">
                            Prodotto inserito con successo
                        </div>
                    <?php } ?>
                    <?php if ($check_input_prod == 2) { ?>
                        <div class="alert alert-danger mt-4 mx-auto w-full" role="alert">
                            Errore nell'inserimento del prodotto
                        </div>
                    <?php } ?>
                </div>
            </div>

        <?php } ?>
    </div>
    </div>








    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>