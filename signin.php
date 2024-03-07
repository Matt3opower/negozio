<?php
include "connessione.php";
include "functions.php";
session_start();



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


    $check1 = true;
    $check2 = true;

    if ($nome == null or $cognome == null or $email == null or $password == null or $indirizzo == null or $citta == null or $provincia == null) {
        $check1 = false;
    }
    if($num_rows_duplicate > 0){
        $check2 = false;
    }

    if ($check1 AND $check2) {
        $sql = "INSERT INTO utente (email, password, cognome, nome, indirizzo, citta, provincia) VALUES ('$email', '$password' ,'$cognome', '$nome', '$indirizzo', '$citta', '$provincia')";
        $db_connection->query($sql);
        $db_connection->close();
        echo "<script>window.location.href = 'login.php';</script>";
    }
    
    if (!$check1) {
        echo "
        <div class='alert alert-danger mt-4 w-[1000px] mx-auto' role='alert'>
            Tutti i campi devono essere riempiti
        </div>
        ";
    }
    if (!$check2) {
        echo "
        <div class='alert alert-danger mt-4 w-[1000px] mx-auto' role='alert'>
            L'email inserita è già presente
        </div>
        ";
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

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

        .form-login {
            width: 1000px;
            height: 450px;
            background-color: white;
            border-radius: 25px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* padding: 15px; */
        }
    </style>
</head>

<body class="bg-[#f0f3f8]">
    <form action="#" method="POST">
        <div class="form-login p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1 h-full ">
                <div class="flex justify-center items-center">
                    <img src="img/logo.png" alt="" class="max-w-full h-auto">
                </div>
                <div class="flex flex-col justify-center ">
                    <div class="row">
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Email</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="email" name="email" id="email"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Password</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="password" name="password" id="password"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Password">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Cognome</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="cognome" id="cognome"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Cognome">
                            </div>
                        </div>
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Nome</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="nome" id="nome"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Nome">
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Indirizzo</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="indirizzo" id="indirizzo"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Indirizzo">
                            </div>
                        </div>
                        <div class="my-3 col">
                            <label class="block text-md font-medium leading-6 text-gray-900">Città</label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <input type="text" name="citta" id="citta"
                                    class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                    placeholder="Città">
                            </div>
                        </div>
                        <div class="my-3 col">
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
                            <button name="login" id="login"
                                class="mt-12 w-32 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto">Login</button>
                        </div>
                        <div class="flex flex-col justify-center ">
                            <button name="signin" id="signin"
                                class="mt-12 w-32 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mx-auto">Registrati</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>





    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>