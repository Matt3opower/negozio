<?php
include "connessione.php";
include "functions.php";
session_start();


$check1 = true;
$check2 = true;
if (isset($_POST['signin'])) {
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $cognome = $_POST["cognome"];
    $nome = $_POST["nome"];

    $indirizzo = $_POST["indirizzo"];
    $citta = $_POST["citta"];
    $provincia = $_POST["provincia"];



    $sql_duplicate = "SELECT * FROM utente WHERE email = '$email'";
    $result_duplicate = $db_connection->query($sql_duplicate);
    $num_rows_duplicate = $result_duplicate->num_rows;




    if ($nome == null or $cognome == null or $email == null or $password == null or $indirizzo == null or $citta == null or $provincia == null) {
        $check1 = false;
    }
    if ($num_rows_duplicate > 0) {
        $check2 = false;
    }

    if ($check1 and $check2) {
        $sql = "INSERT INTO utente (email, password, cognome, nome, indirizzo, citta, provincia) VALUES ('$email', '$password' ,'$cognome', '$nome', '$indirizzo', '$citta', '$provincia')";
        $db_connection->query($sql);
        $db_connection->close();
        echo "<script>window.location.href = 'login.php';</script>";
    }


}



if (isset($_SESSION['email'])) {
    header("Location: homepage.php");
}

if (isset($_POST['login'])) {
    echo "<script>window.location.href = 'login.php';</script>";
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Registrazione</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
    <style>
        .form-login {
            background-color: white;
            border-radius: 25px;
            position: absolute;
            z-index: 10;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* padding: 15px; */
        }
    </style>
</head>

<body class="bg-white sm:bg-[#f8f8f8]">
    <?php
    include "navbar.php";
    ?>
    <form action="#" method="POST">
        <div class="form-login p-6 lg:w-[1000px] lg:h-[450px] mt-16 lg:mt-0 bg-white/0 sm:bg-white/100 w-screen">
            <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1 h-full">
                <div class="flex justify-center items-center">
                    <img src="img/logo.png" alt="" class="h-0 w-0 md:w-full md:h-auto md:h-32 md:w-32">
                </div>


                <div class="flex flex-col justify-center">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="my-3 ">
                            <label class="block text-md font-medium leading-6 text-gray-900">Email</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="email" name="email" id="email"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="my-3">
                            <label class="block text-md font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="password" name="password" id="password"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Password">
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-2 gap-2">
                        <div class="my-3">
                            <label class="block text-md font-medium leading-6 text-gray-900">Cognome</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="cognome" id="cognome"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Cognome">
                            </div>
                        </div>
                        <div class="my-3">
                            <label class="block text-md font-medium leading-6 text-gray-900">Nome</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="nome" id="nome"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Nome">
                            </div>
                        </div>
                    </div>



                    <div class="grid grid-cols-3 gap-2">
                        <div class="my-3 ">
                            <label class="block text-md font-medium leading-6 text-gray-900">Indirizzo</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="indirizzo" id="indirizzo"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Indirizzo">
                            </div>
                        </div>
                        <div class="my-3 ">
                            <label class="block text-md font-medium leading-6 text-gray-900">Città</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="citta" id="citta"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Città">
                            </div>
                        </div>
                        <div class="my-3 ">
                            <label class="block text-md font-medium leading-6 text-gray-900">Provincia</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="provincia" id="provincia"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Provincia">
                            </div>
                        </div>
                    </div>



                    <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1">
                        <div class="flex flex-col justify-center ">
                            <button name="signin" id="signin"
                                class="mt-12 w-36 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-auto">Registrati</button>
                        </div>
                        <div class="flex flex-col justify-center ">
                            <button name="login" id="login"
                                class="flex mt-12 w-36 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-auto items-center justify-center">Login
                                <svg class="h-6 ml-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512">
                                    <path
                                        d="M256 48C141.13 48 48 141.13 48 256s93.13 208 208 208s208-93.13 208-208S370.87 48 256 48zm91.36 212.65a16 16 0 0 1-22.63.09L272 208.42V342a16 16 0 0 1-32 0V208.42l-52.73 52.32A16 16 0 1 1 164.73 238l80-79.39a16 16 0 0 1 22.54 0l80 79.39a16 16 0 0 1 .09 22.65z"
                                        fill="currentColor"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="flex justify-center items-center">
        <?php
        if (!$check1) { ?>
            <div
                class="mt-4 flex items-center justify-center mx-auto bg-red-300 border-2 border-red-500 rounded-lg p-3 mx-5 max-w-[700px]">
                Tutti i campi devono essere riempiti
            </div>
        <?php } ?>

        <?php
        if (!$check2) { ?>
            <div
                class="mt-4 flex items-center justify-center mx-auto bg-red-300 border-2 border-red-500 rounded-lg p-3 mx-5 max-w-[700px]">
                L'email inserita è già presente
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