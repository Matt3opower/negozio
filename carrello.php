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
        // if (!isset ($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
        $email = $_SESSION['email'];
        //echo $email;
        $sql_cart = "SELECT lista_carrello FROM utente WHERE email = '$email'";
        $result_cart = $db_connection->query($sql_cart);
        $row_cart = $result_cart->fetch_assoc();

        if ($row_cart['lista_carrello'] != "") {
            $stringa_json = $row_cart['lista_carrello'];
            $_SESSION['carrello'] = json_decode($stringa_json, true);

        }
        // }






        $car = $_SESSION['carrello'];

        foreach ($car as $id_prodotto => $dettagliProdotto) {
            $sql_check_present = "SELECT id_prodotto FROM prodotto WHERE id_prodotto = $id_prodotto";
            $result_check_present = $db_connection->query($sql_check_present);

            if ($result_check_present && $result_check_present->num_rows == 0) {
                // Rimuovi il prodotto specificato dal carrello
                unset($car[$id_prodotto]);
            }
        }

        // Re-encode l'array in formato JSON e sovrascrivi $_SESSION['carrello']
        $_SESSION['carrello'] = $car;




        // se non ci sono prodotti nel database in automatico l'array carrello diventa nullo
        $sql_check_present = "SELECT COUNT(*) AS count FROM prodotto";
        $result_check_present = $db_connection->query($sql_check_present);
        $row_db = $result_check_present->fetch_assoc();

        if ($row_db['count'] == 0) {
            $_SESSION['carrello'] = array();
            saveCart();
        }



    }
}

//SVUOTA CARRELLO
if (isset($_POST['empty'])) {
    emptyCart();
}

//COMPRA
$check_empty_cart = true;
$check_qnt = true;
if (isset($_POST['buy'])) {
    $check_qnt = true;
    $check_qnt = buy($check_qnt);
    //echo $check_qnt;
}

//RIMUOVI ELEMENTO INTERO
if (isset($_POST['rimuovi'])) {
    itemRemove();
    header("Location: carrello.php");
}

//TOGLI -1 PRODOTTO
if (isset($_POST['less'])) {
    itemLessOne();
    header("Location: carrello.php");
}

//AGGIUNGI +1 PRODOTTO
if (isset($_POST['more'])) {
    $check_qnt = itemMoreOne($check_qnt);

    header("Location: carrello.php");
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
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
</head>

<body class="bg-[#f8f8f8]">
    <?php
    include "navbar.php";
    ?>
    <div class="container">
        <?php
        // Controlla se l'array $_SESSION['carrello'] è vuoto
        if (!empty($_SESSION['carrello'])) {
            ?>
            <div class="lg:grid lg:grid-cols-10 mb-20 min-[1280px]:mx-36 mt-14">
                <div class=" lg:col-span-7 p-3">
                    <div>
                        <div class="grid gap-4">
                            <?php foreach ($_SESSION['carrello'] as $dettagliProdotto): ?>
                                <form action="#" method="post">
                                    <!-- effettiva riga -->
                                    <div class="bg-white shadow-sm rounded-[20px] grid sm:grid-rows-1 sm:grid-cols-5 p-1">
                                        <div class="py-4 px-6 flex justify-center max-[640px]:col-span-2 sm:col-span-0">
                                            <div class="h-36 w-36 md:h-24 md:w-24">
                                                <img src="<?php echo $dettagliProdotto['img_path']; ?>"
                                                    class="w-full h-full object-contain">
                                            </div>

                                        </div>
                                        <div
                                            class="bg-[#e5e7eb] h-0 max-[640px]:h-[1px] mx-10 my-3 max-[640px]:col-span-2 sm:hidden">
                                        </div>
                                        <div class="py-4 px-6 sm:flex text-center sm:justify-center sm:items-center col-span-1">
                                            <?php echo $dettagliProdotto['nome']; ?>
                                        </div>
                                        <div class="py-4 px-6 sm:flex text-center sm:justify-center sm:items-center col-span-1">
                                            <?php
                                            echo $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'] . " €";
                                            $totale_carrello += $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'];
                                            ?>
                                        </div>

                                        <div class="py-4 px-6 flex justify-center items-center">
                                            <button
                                                class='p-2 mx-2 rounded-lg text-white font-bold w-8 h-8 flex-col justify-center items-center'
                                                id="more" name="more" type="submit"
                                                value="<?php echo $dettagliProdotto['id']; ?>" title="Aggiungi 1 prodotto">
                                                <svg width="24" height="24"
                                                    class="fill-gray-500 hover:fill-blue-500 duration-300">
                                                    <path
                                                        d="M11.883 3.007L12 3a1 1 0 0 1 .993.883L13 4v7h7a1 1 0 0 1 .993.883L21 12a1 1 0 0 1-.883.993L20 13h-7v7a1 1 0 0 1-.883.993L12 21a1 1 0 0 1-.993-.883L11 20v-7H4a1 1 0 0 1-.993-.883L3 12a1 1 0 0 1 .883-.993L4 11h7V4a1 1 0 0 1 .883-.993L12 3l-.117.007z" />
                                                </svg>
                                            </button>
                                            <span class="py-2"><?php echo $dettagliProdotto['quantita']; ?></span>
                                            <button
                                                class='p-2 mx-2 rounded-lg text-white font-bold w-8 h-8 flex-col justify-center items-center'
                                                id="less" name="less" type="submit"
                                                value="<?php echo $dettagliProdotto['id']; ?>" title="Togli 1 prodotto">
                                                <svg width="24" height="24"
                                                    class="fill-gray-500 hover:fill-blue-500 duration-300">
                                                    <path
                                                        d="M18 13H6c-.55 0-1-.45-1-1s.45-1 1-1h12c.55 0 1 .45 1 1s-.45 1-1 1z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="py-4 px-6 flex justify-center items-center">

                                            <div class="flex-col">
                                                <button
                                                    class='p-2 rounded-lg text-white font-bold w-8 h-8  flex-col justify-center items-center'
                                                    id="rimuovi" name="rimuovi" type="submit"
                                                    value="<?php echo $dettagliProdotto['id']; ?>" title="Rimuovi dal carrello">
                                                    <svg width="24" height="24"
                                                        class="stroke-gray-500 hover:stroke-red-500 duration-300">
                                                        <g fill="none" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M4 7h16"></path>
                                                            <path d="M10 11v6"></path>
                                                            <path d="M14 11v6"></path>
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12"></path>
                                                            <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3"></path>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            <?php endforeach; ?>
                        </div>
                        <?php
        } else {
            // Stampa un messaggio che indica che il carrello è vuoto
            $check_empty_cart = false;
        }
        ?>
                </div>

            </div>
            <?php
            if (!empty($_SESSION['carrello'])) {
                ?>
                <div class="lg:col-span-3 p-3">
                    <div class="shadow-md rounded-[20px] bg-white p-3">
                        <form action='#' method='POST'>
                            <div class="grid grid-cols-2 grid-rows-1">
                                <div>
                                    Totale carrello:
                                </div>
                                <div class="text-right">
                                    <?php echo $totale_carrello . '€'; ?>
                                </div>
                            </div>
                            <div class="mt-10">
                                <div class="">
                                    <button
                                        class='w-full p-2 rounded-lg w-full mt-3 text-white font-bold bg-red-500 hover:bg-red-700 hover:scale-105 duration-300'
                                        id='empty' name='empty'>
                                        Svuota carrello
                                    </button>
                                </div>
                                <div class="h-[1px] bg-gray-300 my-3"></div>
                                <div class="">
                                    <button
                                        class='w-full p-2 rounded-lg w-full text-white font-bold bg-blue-500 hover:bg-blue-700 hover:scale-105 duration-300'
                                        id='buy' name='buy'>
                                        Acquista
                                    </button>
                                </div>
                            </div>
                            <?php if (!$check_qnt) { ?>
                                <!-- <div class="alert alert-danger mt-4 w-full " role="alert">
                                Quantità selezionate troppo elevate
                            </div> -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header ">
                                                <h5 class="modal-title" id="exampleModalLabel">Attenzione!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Quantità selezionate troppo elevate
                                            </div>
                                            <div class="modal-footer">
                                                <a href="carrello.php"><button type="button"
                                                        class="bg-sky-500 hover:bg-sky-600 p-2 rounded-lg text-white font-bold">Chiudi</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                                    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
                                    crossorigin="anonymous"></script>

                                <script>
                                    var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
                                    myModal.show();
                                </script>
                            <?php } ?>
                        </form>
                    </div>

                    <?php
            }
            ?>
            </div>
        </div>


        <?php if (!$check_empty_cart) { ?>
            <div class='text-gray-600 font-bold w-fit sm:mt-32 mt-12 mx-auto text-3xl'>
                Carrello vuoto
            </div>
            <div class="flex items-center justify-center mt-16">
                <a href="homepage.php">
                    <button
                        class="border-2 border-gray-300 p-2 rounded-md hover:border-blue-600 hover:bg-blue-100 duration-300 ">
                        Continua gli acquisti
                    </button>
                </a>
            </div>
            <div class="flex items-center justify-center mt-16">
                <!-- <img src="img/empty-cart.png" class="w-32 h-32 "> -->
                <svg class="h-48 fill-gray-400" aria-hidden="true" focusable="false" data-prefix="far"
                    data-icon="shopping-cart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                    class="svg-inline--fa fa-shopping-cart fa-w-18 fa-7x">
                    <path
                        d="M551.991 64H144.28l-8.726-44.608C133.35 8.128 123.478 0 112 0H12C5.373 0 0 5.373 0 12v24c0 6.627 5.373 12 12 12h80.24l69.594 355.701C150.796 415.201 144 430.802 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-18.136-7.556-34.496-19.676-46.142l1.035-4.757c3.254-14.96-8.142-29.101-23.452-29.101H203.76l-9.39-48h312.405c11.29 0 21.054-7.869 23.452-18.902l45.216-208C578.695 78.139 567.299 64 551.991 64zM208 472c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm256 0c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm23.438-200H184.98l-31.31-160h368.548l-34.78 160z"
                        class=""></path>
                </svg>
            </div>

        <?php } ?>
    </div>









    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>