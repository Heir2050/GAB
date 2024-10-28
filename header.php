<?php
    session_start(); // Démarrer la session
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAB | Burundi</title>
    <link rel="stylesheet" type="text/css" href="assets/css/index.css?v=1.0">
    <style>
        th {
            background: #000f23;
            color: #fff;
        }
        body {
            min-height: 93vh;
        }
        select{
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .ds {
            display: flex;
            justify-content: space-around;
        }

        .bts{
            padding: .5rem;
            background-color: #ff0000;
            border-radius: .3rem;
        }
        .no_data {
            padding: 1rem;
            background-color: #0570ff36;
            color: #000;
            font-weight: bold;
            border-radius: .5rem;
        }

        .conta {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .danger{
            background-color: #dc3545;
            color: #fff;
        }
        .warning {
            background-color: #ffc107;
            color: #000;
        }

        .info {
            background-color: #17a2b8;
            color: #fff;
        }

        .message_text {
            color: #ff0000;
        }

        .dec {
            border: 1px solid #fff;
            padding: .5rem 1rem;
            border-radius: .5rem;
            transition: .3s;
        }

        .dec:hover {
            background-color: #fff;
            color: #000f23;
        }

        .hed_list {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
        }

        .hed_list .btn {
            width: initial;
            font-size: 1rem;
            padding: .5rem 1rem;
            background: #fff;
        }

        .flex_d {
            display: flex;
            gap: 1rem;
        }

    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="index.php" style="color: #fff;"><h1>
                        <img src="assets/images/gab.png" alt="logo" style="width: 30%;">
                    </h1></a>
                </div>
                <ul>
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <li>
                            <a href="guichet.php">Guichet</a>
                        </li>
                        <li>
                            <a href="banque.php">Banque</a>
                        </li>
                        <li>
                            <a href="province.php">Province</a>
                        </li>
                        <li>
                            <a href="commune.php">Commune</a>
                        </li>
                        <li>
                            <a href="zone.php">Zone</a>
                        </li>
                        <li>
                            <a href="quartier.php">Quartier</a>
                        </li>
                        <li>
                            <a href="users.php">Users</a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="contacts.php">Contacts</a>
                    </li>
                    <?php if (isset($_SESSION['id_user'])): ?>
                    <li>
                        <a href="logout.php" class="dec">Logout</a>
                    </li>
                    <li>
                    <a href="#"><?= htmlspecialchars($_SESSION['username']) ?></a>
                    </li>
                    <?php else: ?>
                        <li>
                            <a href="login.php" class="dec">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>