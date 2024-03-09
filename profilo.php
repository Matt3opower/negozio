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
                <!-- <div class="mt-10">
                    <form action="#" method="POST">
                        <input type="text" name="nome" id="nome"
                            class="mt-2 block w-56 rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                            placeholder="Nome" value="">
                        <input type="number" name="prezzo" id="prezzo"
                            class="mt-2 block w-56 rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                            placeholder="Prezzo" value="">
                        <input type="number" name="quantita_disponibile" id="quantita_disponibile"
                            class="mt-2 block w-56 rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                            placeholder="Quantità disponibile" value="">



                        <input type="file" name="fileToUpload" id="fileToUpload" class="btn btn-primary bottone-input"
                            style="width:100%; height:100%;">
                        <input type="submit" value="Upload Image" name="submitFile" id="submitFile" class="btn btn-primary"
                            style="width:100%; height:100%;">



                        <button
                            class='w-full p-2 rounded-lg w-fit mt-20 text-white font-bold bg-sky-500 hover:bg-sky-600 hover:scale-105 duration-300'
                            id='addProduct' name='addProduct'>
                            Aggiungi prodotto
                        </button>
                    </form>
                </div> -->
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