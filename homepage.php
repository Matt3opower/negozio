<?php

session_start();


include "connessione.php";
include "functions.php";

$email = "";
$_SESSION['carrello'] = array();

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql_cart = "SELECT lista_carrello FROM utente WHERE email = '$email'";
    $result_cart = $db_connection->query($sql_cart);
    $row_cart = $result_cart->fetch_assoc();
    if ($row_cart['lista_carrello'] != "") {
        $stringa_json = $row_cart['lista_carrello'];
        $_SESSION['carrello'] = json_decode($stringa_json, true);
    }
}






$sql = "SELECT * FROM prodotto";
$result = $db_connection->query($sql);
$rows = $result->num_rows;
while ($riga = $result->fetch_assoc()) {
    $prodotti[] = $riga;
}



$check_qnt = true;
if (isset($_POST['aggiungi'])) {

    $id = $_POST['id_prodotto'];
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
    $quantita = $_POST['quantita'];
    $img_path = $_POST['img_path'];

    $sql_2 = "SELECT * FROM prodotto WHERE id_prodotto = '$id'";
    $result_2 = $db_connection->query($sql_2);
    $row_2 = $result_2->fetch_assoc();

    if ($quantita > $row_2['quantita_disponibile']) {
        $check_qnt = false;
    } else {
        addToCart($id, $nome, $prezzo, $quantita, $img_path);
        //header("Location:carrello.php");
        header("Location:homepage.php");
    }
}


//RIMOZIONE 1 ITEM DA HOMEPAGE
if (isset($_POST['rimuovi_listino'])) {
    $id_rimuovi_listino = $_POST['rimuovi_listino'];

    echo $id_rimuovi_listino;

    $sql_rimuovi_listino = "SELECT img_path FROM prodotto WHERE id_prodotto = '$id_rimuovi_listino'";
    $result_rimuovi_listino = $db_connection->query($sql_rimuovi_listino);

    // Estrazione risultato come stringa
    if ($result_rimuovi_listino && $result_rimuovi_listino->num_rows > 0) {
        $row = $result_rimuovi_listino->fetch_assoc();
        $img_path_stringa = $row['img_path'];
        echo "Risultato della query come stringa: " . $img_path_stringa;
        unlink($img_path_stringa);
    }


    $sql_rimuovi_listino = "DELETE FROM prodotto WHERE id_prodotto = '$id_rimuovi_listino'";
    $result_rimuovi_listino = $db_connection->query($sql_rimuovi_listino);


    header("Location: homepage.php");
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Homepage</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">

    <style>
        body.modal-open {
            overflow: auto !important;
        }
    </style>
</head>

<body class="bg-[#f8f8f8]">
    <?php
    include "navbar.php";
    ?>
    <div class="container">
        <?php if (empty($prodotti)) { ?>
            <div class='text-gray-600 font-bold w-fit sm:mt-32 mt-12 mx-auto text-3xl'>
                Non sono presenti articoli
            </div>
            <?php if(!$_SESSION['email']) {?>
            <div class="flex items-center justify-center mt-16">
                <a href="profilo.php">
                    <button
                        class="border-2 border-gray-300 p-2 rounded-md hover:border-blue-600 hover:bg-blue-100 duration-300 ">
                        Vai al tuo profilo
                    </button>
                </a>
            </div>
            <?php } ?>
            <div class="flex items-center justify-center mt-16">                
                <svg class="h-48" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24">
                    <g fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                    </g>
                </svg>
            </div>
        <?php } ?>



        <?php if (!$check_qnt) { ?>
            <!-- <div class="alert alert-danger mt-4 w-fit mx-auto" role="alert">
                Quantità selezionate troppo elevate
            </div> -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h5 class="modal-title" id="exampleModalLabel">Attenzione!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Quantità selezionate troppo elevate
                        </div>
                        <div class="modal-footer">
                            <a href="homepage.php"><button type="button"
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
        <div class="grid lg:grid-cols-3 mb-20 mt-20">
            <?php if (!empty($prodotti)) {
                foreach ($prodotti as $prodotto): ?>
                    <div class="col-span-1">
                        <form action="#" method="POST" autocomplete="off">
                            <div
                                class="rounded-lg overflow-hidden lg:mx-6 my-6 shadow-[0_3px_8px_rgb(0.2,0.2,0.2,0.2)] bg-white">
                                <div class="h-80 min-w-40 mx-auto p-3 ">
                                    <img src="<?php
                                    //echo 'img/products/' . $prodotto['nome'] . '.webp'; 
                                    echo $prodotto['img_path'];
                                    //$sql_img = "SELECT img_path FROM prodotto WHERE ";
                                    ?>" class="w-full h-full object-contain">
                                </div>
                                <div class="rounded-lg p-3">
                                    <p class="font-size text-2xl mb-3 truncate max-[640px]:max-w-[150px] max-w-[500px]"
                                        title="<?php echo $prodotto['nome'] ?>">
                                        <?php echo $prodotto['nome'] ?>
                                    </p>
                                    <div class="h-[1px] bg-[#e5e7eb] w-full mb-3"></div>
                                    <div class="row my-3">
                                        <div class="col text-lg my-auto">
                                            <p>
                                                Disponibilità:
                                            </p>
                                        </div>
                                        <div class="col my-auto">
                                            <?php echo $prodotto['quantita_disponibile'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-lg font-bold my-auto">
                                            <p>
                                                <?php echo "€ " . $prodotto['prezzo'] ?>
                                            </p>
                                        </div>
                                        <div class="col">
                                            <?php if (isset($_SESSION['email'])) {
                                                if ($_SESSION['email'] != "admin@admin") { ?>
                                                    <input type="number" class="form-control bg-zinc-100" id="quantita" name="quantita"
                                                        value="1">
                                                <?php }
                                            } ?>
                                            <?php if (isset($_SESSION['email'])) {
                                                if ($_SESSION['email'] == "admin@admin") { ?>
                                                    <button
                                                        class='p-2 rounded-lg mt-3 text-white font-bold bg-red-500 hover:bg-red-700 flex-col justify-center items-center'
                                                        id="rimuovi_listino" name="rimuovi_listino" type="submit"
                                                        value="<?php echo $prodotto['id_prodotto']; ?>" title="Rimuovi dal listino">
                                                        Elimina
                                                    </button>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <?php
                                    if (isset($_SESSION['email'])) {
                                        $id_stock = $prodotto['id_prodotto'];
                                        $sql_stock = "SELECT quantita_disponibile FROM prodotto WHERE id_prodotto = '$id_stock'";
                                        $result_stock = $db_connection->query($sql_stock);
                                        $row_stock = $result_stock->fetch_assoc();
                                        $stock = $row_stock['quantita_disponibile'];

                                        if ($_SESSION['email'] != "admin@admin") {
                                            ?>
                                            <?php if ($stock > 0) { ?>
                                                <div class="flex">
                                                    <button type="submit" name="aggiungi" id="aggiungi"
                                                        class="text-white font-bold w-full h-12 bg-blue-500 hover:bg-blue-700 mt-3  duration-300">
                                                        Aggiungi al carrello
                                                    </button>
                                                </div>
                                            <?php } ?>
                                            <?php if ($stock == 0) { ?>
                                                <div class="flex">
                                                    <button type="" name="" id="" disabled
                                                        class="text-white font-bold w-full h-12 bg-red-500 mt-3">
                                                        Prodotto esaurito
                                                    </button>
                                                </div>
                                            <?php } ?>
                                        <?php }
                                    } ?>

                                    <input type="hidden" class="form-control" id="id_prodotto" name="id_prodotto"
                                        value="<?php echo $prodotto['id_prodotto'] ?>">
                                    <input type="hidden" class="form-control" id="nome" name="nome"
                                        value="<?php echo $prodotto['nome'] ?>">
                                    <input type="hidden" class="form-control" id="prezzo" name="prezzo"
                                        value="<?php echo $prodotto['prezzo'] ?>">
                                    <input type="hidden" class="form-control" id="img_path" name="img_path"
                                        value="<?php echo $prodotto['img_path'] ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endforeach;
            } ?>
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