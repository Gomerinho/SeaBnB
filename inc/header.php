<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>

<html lang="fr">

<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>SeaBnb</title>
    <link rel="icon" type="image/png" href="favicon.png" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <script type="text/javascript" src="/bower_components/semantic-ui-calendar/dist/calendar.min.js"></script>
    <link rel="stylesheet" href="/bower_components/semantic-ui-calendar/dist/calendar.min.css" />
    <style type="text/css">
        .hidden.menu {
            display: none;
        }

        .masthead.segment {
            padding: 1em 0em;
        }

        .masthead .logo.item img {
            margin-right: 1em;
        }

        .masthead .ui.menu .ui.button {
            margin-left: 0.5em;
        }

        .masthead h1.ui.header {
            margin-top: 3em;
            margin-bottom: 0;
            font-size: 4em;
            font-weight: normal;
        }

        .masthead h2 {
            font-size: 1.7em;
            font-weight: normal;
        }

        .ui.vertical.stripe {
            padding: 8em 0;
        }

        .ui.vertical.stripe h3 {
            font-size: 2em;
        }

        .ui.vertical.stripe .button+h3,
        .ui.vertical.stripe p+h3 {
            margin-top: 3em;
        }

        .ui.vertical.stripe .floated.image {
            clear: both;
        }

        .ui.vertical.stripe p {
            font-size: 1.33em;
        }

        .ui.vertical.stripe .horizontal.divider {
            margin: 3em 0;
        }

        .quote.stripe.segment {
            padding: 0;
        }

        .quote.stripe.segment .grid .column {
            padding-top: 5em;
            padding-bottom: 5em;
        }

        .footer.segment {
            padding: 5em 0;
        }

        .secondary.pointing.menu .toc.item {
            display: none;
        }

        .ui.form {
            font-size: 1rem;
            margin: 5rem;
        }

        .ui.message.center {
            margin: 3% auto 0;
            width: 30%;
        }

        .label-file {
            cursor: pointer;
            color: #00b1ca;
            font-weight: bold;
        }

        .label-file:hover {
            color: #25a5c4;
        }

        .pp {
            display: none;
        }

        @media only screen and (max-width: 700px) {
            .ui.fixed.menu {
                display: none !important;
            }

            .secondary.pointing.menu .item,
            .secondary.pointing.menu .menu {
                display: none;
            }

            .secondary.pointing.menu .toc.item {
                display: block;
            }

            .masthead.segment {
                min-height: 350px;
            }

            .masthead h1.ui.header {
                font-size: 2em;
                margin-top: 1.5em;
            }

            .masthead h2 {
                margin-top: 0.5em;
                font-size: 1.5em;
            }
        }
    </style>
    <script>
        $(document)
            .ready(function() {

                // fix menu when passed
                $('.masthead')
                    .visibility({
                        once: false,
                        onBottomPassed: function() {
                            $('.fixed.menu').transition('fade in');
                        },
                        onBottomPassedReverse: function() {
                            $('.fixed.menu').transition('fade out');
                        }
                    });

                // create sidebar and attach to menu open
                $('.ui.sidebar')
                    .sidebar('attach events', '.toc.item');

                $('.message .close')
                    .on('click', function() {
                        $(this)
                            .closest('.message')
                            .transition('fade');
                    })

            });
    </script>
</head>

<body>

    <!-- Following Menu -->
    <div class="ui large top fixed hidden menu">
        <div class="ui container">
            <a class="active item" href="index.php">Acceuil</a>

            <div class="right menu">
                <div class="right item">
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <a href="logout.php" class="ui inverted primary button">Se déconnecter</a>
                    <?php else : ?>
                        <div class="item">
                            <a class="ui primary button" href="login.php">Se connecter</a>
                        </div>
                        <div class="item">
                            <a class="ui inverted primary button" href="register.php">S'inscrire</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="ui vertical inverted sidebar menu">
        <a class="active item" href="index.php">Acceuil</a>
        <a class="item" href="adverts.php">Reserver</a>
        <a class="item" href="login.php">Se connecter</a>
        <a class="item" href="register.php">S'inscrire</a>
    </div>


    <!-- Page Contents -->
    <div class="pusher">
        <div class="ui inverted vertical masthead center aligned segment">
            <div class="ui container">
                <div class="ui large secondary inverted pointing menu">
                    <a class="toc item">
                        <i class="sidebar icon"></i>
                    </a>
                    <a class="active item" href="index.php">Acceuil</a>

                    <?php if (isset($_SESSION['auth'])) :  ?>
                        <?php if ($_SESSION['auth']->admin == 1) : ?>
                            <a href="reservation.php" class="item">Réservation Client</a>
                        <?php endif; ?>
                    <?php endif ?>
                    <div class="right item">
                        <?php if (isset($_SESSION['auth'])) : ?>
                            <a href="logout.php" class="ui inverted primary button">Se déconnecter</a>
                        <?php else : ?>
                            <a class="ui primary button" href="login.php">Se connecter</a>
                            <a class="ui inverted primary button" href="register.php">S'inscrire</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($errors)) : ?>
            <div class="ui error message">
                <i class="close icon"></i>
                <div class="header">
                    Vous n'avez pas remplis le formulaire correctement
                </div>
                <ul class="list">
                    <?php foreach ($errors as $error) : ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php endif; ?>