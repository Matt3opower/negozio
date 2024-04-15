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

        foreach ($_SESSION['carrello'] as $dettagliProdotto):
            $sql_check_present = "SELECT id_prodotto FROM prodotto";
            $result_check_present = $db_connection->query($sql_check_present);
            $rows_check_present = $result_check_present->num_rows;
            while ($row_check_present = $result_check_present->fetch_assoc()) {
                if ($row_check_present['id_prodotto'] != $dettagliProdotto['id']) {
                    $car = $_SESSION['carrello'];
                    if (isset($car[$dettagliProdotto['id']])) {
                        // Rimuove il prodotto specificato dal carrello
                        unset($car[$dettagliProdotto['id']]);
                        // Aggiorna il carrello nella sessione
                        $_SESSION['carrello'] = $car;
                    }
                    saveCart();
                }
            }
        endforeach;

        $sql_check_present = "SELECT COUNT(*) AS count FROM prodotto";
        $result_check_present = $db_connection->query($sql_check_present);
        $row_db = $result_check_present->fetch_assoc();
        
        if($row_db['count'] == 0){
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
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
</head>

<body class="bg-[#f0f3f8]">
    <?php
    include "navbar.php";
    ?>
    <div class="container">
        <?php
        // Controlla se l'array $_SESSION['carrello'] è vuoto
        if (!empty($_SESSION['carrello'])) {
            ?>
            <div class="lg:grid lg:grid-cols-12 mb-20">
                <div class=" lg:col-span-9 p-3 mt-14">
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">

                            <table class="w-full text-left text-gray-700 text-md">
                                <thead class="text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr class="">
                                        <th scope="col" class="py-3 px-6">Immagine prodotto</th>
                                        <th scope="col" class="py-3 px-6">Nome prodotto</th>
                                        <th scope="col" class="py-3 px-6">Prezzo totale</th>
                                        <th scope="col" class="py-3 px-6">Quantità</th>
                                        <th scope="col" class="py-3 px-6">Modifica</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['carrello'] as $dettagliProdotto): ?>
                                        <form action="#" method="post">
                                            <tr class="bg-white border-t dark:bg-gray-800 dark:border-gray-700">
                                                <td class="py-4 px-6">
                                                    <div class="h-24 w-24">
                                                        <img src="<?php echo $dettagliProdotto['img_path']; ?>"
                                                            class="w-full h-full object-contain">
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 ">
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
                                                <td class="py-4 px-6">

                                                    <div class="flex-col">
                                                        <button
                                                            class='p-2 rounded-lg mt-3 text-white font-bold bg-sky-500 hover:bg-sky-700 w-8 h-8  flex-col justify-center items-center'
                                                            id="more" name="more" type="submit"
                                                            value="<?php echo $dettagliProdotto['id']; ?>"
                                                            title="Aggiungi 1 prodotto">
                                                            <svg width="24" height="24">
                                                                <path
                                                                    d="M11.883 3.007L12 3a1 1 0 0 1 .993.883L13 4v7h7a1 1 0 0 1 .993.883L21 12a1 1 0 0 1-.883.993L20 13h-7v7a1 1 0 0 1-.883.993L12 21a1 1 0 0 1-.993-.883L11 20v-7H4a1 1 0 0 1-.993-.883L3 12a1 1 0 0 1 .883-.993L4 11h7V4a1 1 0 0 1 .883-.993L12 3l-.117.007z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            class='p-2 rounded-lg mt-3 text-white font-bold bg-sky-500 hover:bg-sky-700 w-8 h-8  flex-col justify-center items-center'
                                                            id="less" name="less" type="submit"
                                                            value="<?php echo $dettagliProdotto['id']; ?>"
                                                            title="Togli 1 prodotto">
                                                            <svg width="24" height="24">
                                                                <path
                                                                    d="M18 13H6c-.55 0-1-.45-1-1s.45-1 1-1h12c.55 0 1 .45 1 1s-.45 1-1 1z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            class='p-2 rounded-lg mt-3 text-white font-bold bg-red-500 hover:bg-red-700 w-8 h-8  flex-col justify-center items-center'
                                                            id="rimuovi" name="rimuovi" type="submit"
                                                            value="<?php echo $dettagliProdotto['id']; ?>"
                                                            title="Rimuovi dal carrello">
                                                            <svg width="24" height="24">
                                                                <g fill="none" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M4 7h16"></path>
                                                                    <path d="M10 11v6"></path>
                                                                    <path d="M14 11v6"></path>
                                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12">
                                                                    </path>
                                                                    <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3"></path>
                                                                </g>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                </td>
                                            </tr>
                                        </form>
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
            <?php
            if (!empty($_SESSION['carrello'])) {
                ?>
                <div class=" lg:col-span-3 p-3">
                    <form action='#' method='POST'>
                        <div class='text-white font-bold bg-sky-500 p-2 rounded-lg w-full mt-14 lg:mb-[22px]'>
                            <?php echo 'Totale Carrello: ' . $totale_carrello . '€'; ?>
                        </div>

                        <div class="lg:grid lg:grid-cols-2 gap-3 ">
                            <div class="">
                                <button
                                    class='w-full p-2 rounded-lg w-full mt-3 text-white font-bold bg-sky-500 hover:bg-sky-600 hover:scale-105 duration-300'
                                    id='empty' name='empty'>
                                    Svuota carrello
                                </button>
                            </div>
                            <div class="">
                                <button
                                    class='w-full p-2 rounded-lg w-full mt-3 text-white font-bold bg-sky-500 hover:bg-sky-600 hover:scale-105 duration-300'
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
                    <?php
            }
            ?>
            </div>
        </div>


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