<?php
session_start();
$totale_carrello = 0;
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
} else {
    if (!isset($_SESSION['carrello'])) {
        $_SESSION['carrello'] = array();
    }
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
</head>

<body>
    <?php
    include "navbar.php";
    ?>
    <div class="container">
        <form action="#" method="POST">

            <div class="row my-3">
                <?php
                include "Connessione.php";
                $conn = $db_connection;
                $sql = "SELECT * FROM prodotto";
                $result = $conn->query($sql);
                $rows = $result->num_rows;

                if ($rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                        echo "

                        ";
                    }
                }
                ?>
            </div>
        </form>







        <div class="my-3">
            <table class="table table-striped ">
                <thead >
                    <tr>
                        <th scope="col">Nome prodotto</th>
                        <th scope="col">Prezzo totale</th>
                        <th scope="col">Quantità</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($_SESSION['carrello'] as $dettagliProdotto): ?>
                        <tr>
                            <!-- <td>
                                <?php echo $dettagliProdotto['id']; ?>
                            </td> -->
                            <td>
                                <?php echo $dettagliProdotto['nome']; ?>
                            </td>
                            <td>
                                <?php
                                echo $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'] . " €";
                                $totale_carrello += $dettagliProdotto['prezzo'] * $dettagliProdotto['quantita'];
                                ?>
                            </td>
                            <td>
                                <?php echo $dettagliProdotto['quantita']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="bg-sky-200 p-2 rounded-lg w-fit mt-12">
                <?php echo "Totale Carrello: " . $totale_carrello . "€";?>
            </div>
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