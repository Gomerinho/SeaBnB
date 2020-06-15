<?php

function debug($variable)
{
    echo '<div class="ui text container">' . print_r($variable, true) . '</div>';
}

function str_random($length)
{
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['auth'])) {
        $_SESSION['flash']['negative'] = "Vous devez vous connecter pour accéder a cette page";
        header('Location: login.php');
        exit();
    }
}

function recconnect_from_cookie()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
        require_once 'inc/db.php';
        if (!isset($pdo)) {
            global $pdo;
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        if ($user) {
            $expected = $user_id . '==' . $user->remember_token . sha1($user->id . 'pieuvre');
            if ($expected == $remember_token) {
                session_start();
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                $_SESSION['flash']['positive'] = "Vous êtes vonnecté";
            } else {
                setcookie('remember', NULL, -1);
            }
        } else {
            setcookie('remember', NULL, -1);
        }
    }
}

function format($datetime)
{
    $now = time();
    $created = strtotime($datetime);
    // La différence est en seconde
    $diff = $now - $created;
    $m = ($diff) / (60); // on obtient des minutes
    $h = ($diff) / (60 * 60); // ici des heures
    $j = ($diff) / (60 * 60 * 24); // jours
    $s = ($diff) / (60 * 60 * 24 * 7); // et semaines
    if ($diff < 60) { // "il y a x secondes"
        return 'Il y a ' . $diff . ' secondes';
    } elseif ($m < 60) { // "il y a x minutes"
        $minute = (floor($m) == 1) ? 'minute' : 'minutes';
        return 'Il y a ' . floor($m) . ' ' . $minute;
    } elseif ($h < 24) { // " il y a x heures"
        $heure = (floor($h) <= 1) ? 'heure' : 'heures';
        $dateFormated = 'Il y a ' . floor($h) . ' ' . $heure;
        return $dateFormated;
    } elseif ($j < 7) { // " il y a x jours"
        $jour = (floor($j) <= 1) ? 'jour' : 'jours';
        $dateFormated = 'Il y a ' . floor($j) . ' ' . $jour;
        return $dateFormated;
    } elseif ($s < 52) { // " il y a x semaines"
        $semaine = (floor($s) <= 1) ? 'semaine' : 'semaines';
        $dateFormated = 'Il y a ' . floor($s) . ' ' . $semaine;
        return $dateFormated;
    } else { // on affiche la date normalement
        return strftime("%d %B %Y à %H:%M", $created);
    }
}
