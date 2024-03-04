<?php

session_start();
if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = array();
}

include "connessione.php";
include "addtocart.php";
$conn = $db_connection;
$sql = "SELECT * FROM prodotto";
$result = $conn->query($sql);
$rows = $result->num_rows;
while ($riga = $result->fetch_assoc()) {
    $prodotti[] = $riga;
}



if (isset($_POST['aggiungi'])) {
    $id = $_POST['id_prodotto'];
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
    $quantita = $_POST['quantita'];

    addToCart($id, $nome, $prezzo, $quantita);

    /*
    foreach ($_SESSION['carrello'] as $dettagliProdotto): {
            echo $dettagliProdotto['nome'];
            echo $dettagliProdotto['quantita'];
        }
    endforeach;
    */
    header("Location: carrello.php");
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
</head>

<body>
    <?php
    include "navbar.php";
    ?>
    <div class="container">

        <div class="row my-3">
            <?php foreach ($prodotti as $prodotto): ?>
                <div class="col-4 mb-3">
                    <form action="#" method="POST">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">
                                    <?php echo $prodotto['nome'] ?>
                                </h4>
                                <p class="card-text">Prezzo:
                                    <?php echo $prodotto['prezzo'] ?>
                                </p>
                                <p class="card-text">Quantità:</p>
                                <input type="number" class="form-control" id="quantita" name="quantita"
                                    value="<?php echo $prodotto['quantita'] ?>">
                                <button type="submit" name="aggiungi" id="aggiungi" class="btn btn-primary my-3">
                                    Aggiungi al carrello
                                </button>

                                <input type="hidden" class="form-control" id="id_prodotto" name="id_prodotto"
                                    value="<?php echo $prodotto['id_prodotto'] ?>">
                                <input type="hidden" class="form-control" id="nome" name="nome"
                                    value="<?php echo $prodotto['nome'] ?>">
                                <input type="hidden" class="form-control" id="prezzo" name="prezzo"
                                    value="<?php echo $prodotto['prezzo'] ?>">
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach ?>
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