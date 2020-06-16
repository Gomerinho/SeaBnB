<?php
require 'inc/db.php';
session_start();
$user = $pdo->query("SELECT * FROM users WHERE id =" . $_POST['user_id'])->fetch(PDO::FETCH_OBJ);
$advert = $pdo->query("SELECT * FROM adverts INNER JOIN users ON adverts.id_users=users.id WHERE id_ad =" . $_POST['id_ad'])->fetch(PDO::FETCH_OBJ);
if (intval($user->wallet) >= (intval($_POST['price_ad']) * intval($_POST['nb_person']))) {
    $req = $pdo->prepare('INSERT INTO reservation SET date_start = ?, date_end =? ,nb_person =?, id_users = ?, id_advert=?');
    $req->execute([$_POST['date_start'], $_POST['date_end'], $_POST['nb_person'], $_POST['user_id'], $_POST['id_ad']]);
    $req = $pdo->prepare('UPDATE users SET users.wallet = users.wallet - ? WHERE id= ' . $user->id);
    $req->execute([(intval($_POST['price_ad']) * intval($_POST['nb_person']))]);
    $req = $pdo->prepare('UPDATE users SET users.wallet = users.wallet + ? WHERE id= ' . $advert->id);
    $req->execute([intval($_POST['price_ad' * intval($_POST['nb_person']])]);
    $From  = "From:SeaBnb@service.com\n";
    $From .= "MIME-version: 1.0\n";
    $From .= "Content-type: text/html; charset= iso-8859-1\n";
    mail($_SESSION['auth']->email, "Confirmation de votre réservation SeaBnB", '<h2><strong>Bonjour ' . $_SESSION['auth']->username . ',</strong></h2>
        <p>Merci de la confiance que vous nous accorder, Nous vous informons que votre réservation de ' . $_POST['name_ad'] . ' a bien été prise en compte. </p>
        <p>Les dates sont du ' . $_POST['date_start'] . ' au ' . $_POST['date_end'] . '</p>
        <p>Cordialement,</p>
        <p></p>
        <p>L"&eacute;quipe SeaBnB.</p>
        <p></p>
        <p><img src="https://nsa40.casimages.com/img/2020/06/07/200607052858346002.png" alt="" /></p>', $From);
    mail($advert->email, "Nouvelle réservation SeaBnB", '<h2><strong>Bonjour ' . $advert->username . ',</strong></h2>
        <p>Nous vous informons d une nouvelle réservation de ' . $_POST['name_ad'] . ' </p>
        <p>Les dates sont du ' . $_POST['date_start'] . ' au ' . $_POST['date_end'] . '</p>
        <p>Cordialement,</p>
        <p></p>
        <p>L"&eacute;quipe SeaBnB.</p>
        <p></p>
        <p><img src="https://nsa40.casimages.com/img/2020/06/07/200607052858346002.png" alt="" /></p>', $From);
    $_SESSION['flash']['positive'] = "Félicitaions vous venez de réserver un bien , un mail récapitulatif vous a été envoyer !";
    header('Location: index.php');
    exit();
} else {
    $_SESSION['flash']['negative'] = "Vous n'avez pas assez d'argent pour réserver ce bien";
    header('Location: index.php');
    exit();
}
