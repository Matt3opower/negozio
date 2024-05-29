<?php
session_start();
include "connessione.php";
include "functions.php";

if (isset($_SESSION['email'])) {
    header("Location: homepage.php");
}

if (isset($_POST['signin'])) {
    echo "<script>window.location.href = 'signin.php';</script>";
}

$errore1 = true;
$errore2 = true;
$errore3 = true;
if (isset($_POST['login'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $email = stripslashes($email);
    $password = stripslashes($password);

    $email = $db_connection->real_escape_string($email);
    $password = $db_connection->real_escape_string($password);

    $check = true;
    $controllo = false;
    $sql = "SELECT * FROM utente WHERE email='$email'";
    $result = $db_connection->query($sql);
    $conta = $result->num_rows;

    if ($password == null or $email == null) {
        $check = false;
    }

    if ($check) {
        if ($conta == 1) {
            $row = $result->fetch_assoc();
            $passc = $row['password'];
            if (password_verify($password, $passc)) {
                $controllo = true;
            }
        }
        if ($conta == 0) {
            $errore1 = false;

        }
        if ($controllo) {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $passc;
            //COOKIE
            if (isset($_POST['ricorda'])) {
                impostaCookieLogin($email, $password);
            }
            if (!isset($_POST['ricorda'])) {
                deleteCookie("email", "password");
            }
            if ($email == "admin" and $password == "admin") {
                echo "<script>window.location.href = 'homepage.php';</script>";
            } else {
                echo "<script>window.location.href = 'homepage.php';</script>";
            }
        } else {
            $errore2 = false;
        }
    } else if (!$check) {
        $errore3 = false;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" /> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/logo_icon.png">
    <style>
        .form-login {
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

<body class="bg-white sm:bg-[#f8f8f8]">
    <?php
    include "navbar.php";
    ?>
    <form action="#" method="POST">
        <div class="form-login p-6 lg:w-[1000px] lg:h-[450px] mt-12 lg:mt-0 bg-white/0 sm:bg-white/100 w-screen">
            <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1 h-full">
                <div class="flex justify-center items-center">
                    <img src="img/logo.png" alt="" class="h-0 w-0 lg:w-full lg:h-auto lg:h-32 lg:w-32">
                </div>
                <div class="flex flex-col justify-center">
                    <div class="my-3">
                        <label class="block text-md font-medium leading-6 text-gray-900">Email</label>
                        <div class=" rounded-md shadow-sm">
                            <input type="email" name="email" id="email"
                                class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                placeholder="Email" value="<?php
                                if (isset($_COOKIE['email'])) {
                                    echo $_COOKIE['email'];
                                }
                                ?>">
                        </div>
                    </div>
                    <div class="my-3">
                        <label class="block text-md font-medium leading-6 text-gray-900">Password</label>
                        <div class=" rounded-md shadow-sm">
                            <input type="password" name="password" id="password"
                                class="block w-full rounded-md border-0 py-1.5 pl-2 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 focus:outline-none"
                                placeholder="Password" value="<?php
                                if (isset($_COOKIE['password'])) {
                                    echo $_COOKIE['password'];
                                }
                                ?>">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 grid-rows-1 ">
                        <div class="flex flex-col justify-center ">
                            <button name="login" id="login"
                                class="mt-12 w-36 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-auto">Login</button>
                        </div>
                        <div class="flex flex-col justify-center ">
                            <button name="signin" id="signin"
                                class="flex mt-12 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-auto items-center justify-center">Registrati
                                <svg class="h-6 ml-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 512 512">
                                    <path
                                        d="M256 48C141.13 48 48 141.13 48 256s93.13 208 208 208s208-93.13 208-208S370.87 48 256 48zm91.36 212.65a16 16 0 0 1-22.63.09L272 208.42V342a16 16 0 0 1-32 0V208.42l-52.73 52.32A16 16 0 1 1 164.73 238l80-79.39a16 16 0 0 1 22.54 0l80 79.39a16 16 0 0 1 .09 22.65z"
                                        fill="currentColor"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mx-auto mt-6">

                        <?php
                        if (isset($_COOKIE['email'])) {
                            echo '<input class="form-check-input" type="checkbox" id="flexCheckChecked" id="ricorda" name="ricorda" checked>';
                        } else {
                            echo '<input class="form-check-input" type="checkbox" id="flexCheckDefault" id="ricorda" name="ricorda">';
                        }

                        ?>

                        <!-- <input class="form-check-input" type="checkbox" id="flexCheckDefault" id="ricorda" name="ricorda"> -->
                        <label class="form-check-label" for="flexCheckDefault">
                            Ricorda i miei dati
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="flex justify-center items-center">
        <?php
        if (!$errore1) { ?>
            <div
                class="mt-4 flex items-center justify-center mx-auto bg-red-300 border-2 border-red-500 rounded-lg p-3 mx-5 max-w-[700px]">
                Non esistono utenti con queste credenziali
            </div>
        <?php } ?>
        <?php
        if (!$errore2) { ?>
            <div
                class="mt-4 flex items-center justify-center mx-auto bg-red-300 border-2 border-red-500 rounded-lg p-3 mx-5 max-w-[700px]">
                Email o Password errati
            </div>

        <?php } ?>
        <?php
        if (!$errore3) { ?>
            <div
                class="mt-4 flex items-center justify-center mx-auto bg-red-300 border-2 border-red-500 rounded-lg p-3 mx-5 max-w-[700px]">
                Cf o Password vuoti
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