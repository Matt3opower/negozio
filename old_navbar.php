
<header
    class="header sticky top-0 bg-white shadow-[0_3px_10px_rgb(0,0,0,0.2)] flex items-center justify-between px-8 h-16">
    <!-- logo -->
    <h1 class="w-3/12 lg:w-1/12">
        <a href="homepage.php">
            <img src="img/logo.png" class="h-16 w-auto">
        </a>
    </h1>



    <!-- navigation -->
    <nav class="nav font-semibold text-lg">
        <ul class="flex items-center">
            <a href="homepage.php">
                <li
                    class="h-16 p-4 flex items-center border-b-2 border-sky-500 border-opacity-0 hover:border-opacity-100 hover:text-sky-500 duration-200 cursor-pointer active">
                    Homepage
                </li>
            </a>
            <!-- <li class="h-24 p-4 flex items-center border-b-2 border-green-500 border-opacity-0 hover:border-opacity-100 hover:text-green-500 duration-200 cursor-pointer active">
              <a href="carrello.php">Carrello</a>
            </li> -->
            <?php if (isset ($_SESSION['email'])) { ?>
                <a href="profilo.php">
                    <li
                        class="h-16 p-4 flex items-center border-b-2 border-sky-500 border-opacity-0 hover:border-opacity-100 hover:text-sky-500 duration-200 cursor-pointer active">
                        Profilo
                    </li>
                </a>
            <?php } ?>
            <?php if (!isset ($_SESSION['email'])) { ?>
                <a href="login.php">
                    <li
                        class="h-16 p-4 flex items-center border-b-2 border-sky-500 border-opacity-0 hover:border-opacity-100 hover:text-sky-500 duration-200 cursor-pointer active">
                        Login
                    </li>
                </a>
            <?php } ?>
            <?php if (isset ($_SESSION['email'])) { ?>
                <a href="logout.php">
                    <li
                        class="h-16 p-4 flex items-center border-b-2 border-red-600 border-opacity-0 hover:border-opacity-100 hover:text-red-600 duration-200 cursor-pointer active">
                        Logout
                    </li>
                </a>
            <?php } ?>
        </ul>
    </nav>



    <!-- buttons --->
    <div class="w-3/12 lg:w-1/12 flex justify-end">
        <a href="carrello.php">
            <svg class="h-8 p-1 hover:text-sky-500 duration-200" aria-hidden="true" focusable="false" data-prefix="far"
                data-icon="shopping-cart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                class="svg-inline--fa fa-shopping-cart fa-w-18 fa-7x">
                <path fill="currentColor"
                    d="M551.991 64H144.28l-8.726-44.608C133.35 8.128 123.478 0 112 0H12C5.373 0 0 5.373 0 12v24c0 6.627 5.373 12 12 12h80.24l69.594 355.701C150.796 415.201 144 430.802 144 448c0 35.346 28.654 64 64 64s64-28.654 64-64a63.681 63.681 0 0 0-8.583-32h145.167a63.681 63.681 0 0 0-8.583 32c0 35.346 28.654 64 64 64 35.346 0 64-28.654 64-64 0-18.136-7.556-34.496-19.676-46.142l1.035-4.757c3.254-14.96-8.142-29.101-23.452-29.101H203.76l-9.39-48h312.405c11.29 0 21.054-7.869 23.452-18.902l45.216-208C578.695 78.139 567.299 64 551.991 64zM208 472c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm256 0c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm23.438-200H184.98l-31.31-160h368.548l-34.78 160z"
                    class=""></path>
            </svg>
        </a>
    </div>
</header>