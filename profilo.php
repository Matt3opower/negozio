<?php

session_start();

include "connessione.php";
include "functions.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
} else {
    if (!isset($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
    }
}
$email = $_SESSION['email'];

$sql = "SELECT * FROM utente WHERE email = '$email'";
$result = $db_connection->query($sql);
$row = $result->fetch_assoc();




$check_input_prod = 0;
if (isset($_POST['addProduct'])) {
    $nome_p = $_POST['nome'];
    $prezzo_p = $_POST['prezzo'];
    $quantita_disponibile_p = $_POST['quantita_disponibile'];

    if ($nome_p=="" || $prezzo_p=="" || $quantita_disponibile_p=="" || ($_FILES['fileToUpload'])=="" ) {
        $check_input_prod = 2;
    }



    if ($check_input_prod == 0) {
        $target_dir = "img/products/";

        $imgname = $nome_p . "." . pathinfo($_FILES["fileToUpload"]["name"])["extension"];
        $img_path = $target_dir . $nome_p . "." . pathinfo($_FILES["fileToUpload"]["name"])["extension"];

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


    <div class="container flex items-center justify-center">
        <?php if ($row['email'] != 'admin@admin') { ?>
            <div class="bg-white p-4 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg w-[475px] mt-20">
                <div class="row mb-3">
                    <div class="col">Nome</div>
                    <div class="col">
                        <?php echo $row['nome'] ?>
                    </div>
                </div>
                <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                <div class="row mb-3">
                    <div class="col">Cognome</div>
                    <div class="col">
                        <?php echo $row['cognome'] ?>
                    </div>
                </div>
                <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                <div class="row mb-3">
                    <div class="col">Email</div>
                    <div class="col">
                        <?php echo $row['email'] ?>
                    </div>
                </div>
                <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                <div class="row mb-3">
                    <div class="col">Indirizzo</div>
                    <div class="col">
                        <?php echo $row['indirizzo'] ?>
                    </div>
                </div>
                <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                <div class="row mb-3">
                    <div class="col">Città</div>
                    <div class="col">
                        <?php echo $row['citta'] ?>
                    </div>
                </div>
                <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                <div class="row">
                    <div class="col">Provincia</div>
                    <div class="col">
                        <?php echo $row['provincia'] ?>
                    </div>
                </div>
            </div>
        <?php } ?>


        <div class="flex flex-col items-center justify-center p-4">
            <?php if ($row['email'] == 'admin@admin') { ?>
                <div
                    class="bg-white p-4 shadow-[0_3px_10px_rgb(0,0,0,0.2)] rounded-lg w-[475px] mt-20 flex items-center justify-center font-bold text-2xl">
                    <div>Benvenuto admin</div>
                </div>
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