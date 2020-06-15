<?php

require_once 'inc/function.php';
session_start();

if (!empty($_POST)) {

    $errors = array();
    require_once 'inc/db.php';

    if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
        $errors['username'] = "Votre pseudo n'est pas valide";
    } else {
        $req = $pdo->prepare('SELECT id FROM users WHERE username = ? ');
        $req->execute([$_POST['username']]);
        $user = $req->fetch();
        if ($user) {
            $errors['username'] = 'Ce pseudo est déjà pris';
        }
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Votre email n'est pas valide";
    } else {
        $req = $pdo->prepare('SELECT id FROM users WHERE email = ? ');
        $req->execute([$_POST['email']]);
        $user = $req->fetch();
        if ($user) {
            $errors['email'] = 'Cet email est déjà utilisé pour un autre compte';
        }
    }

    if (empty($_POST['password']) || $_POST['password'] != $_POST['password-confirm']) {
        $errors['password'] = "Vous devez rentrer un mot de passe valide";
    }

    if (empty($errors)) {
        $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = str_random(60);
        $req->execute([$_POST['username'], $password, $_POST['email'], $token]);
        $user_id = $pdo->lastInsertId();
        $From  = "From:SeaBnb@service.com\n";
        $From .= "MIME-version: 1.0\n";
        $From .= "Content-type: text/html; charset= iso-8859-1\n";
        $link = "http://localhost:8888/SEABNB/SeaBnB/confirm.php?id=$user_id&amp;token=$token";
        mail($_POST['email'], "Confirmation de votre compte SeaBnB", '<h2><strong>Bonjour ,</strong></h2>
        <p>Merci de la confiance que vous nous accorder, pour valider la cr&eacute;ation de votre compte merci de cliquer sur le liens ci-dessous :</p>
        <p><span>&nbsp;</span><a href=' . $link . '>Activer mon compte SeaBnB</a></p>
        <p></p>
        <p>Cordialement,</p>
        <p></p>
        <p>L"&eacute;quipe SeaBnB.</p>
        <p></p>
        <p><img src="https://nsa40.casimages.com/img/2020/06/07/200607052858346002.png" alt="" /></p>', $From);
        $_SESSION['flash']['positive'] = "Un email vous a était envoyé pour enregistré votre compte";
        header('Location: login.php');
        exit();
    }
}
?>

<?php

require 'inc/header.php';

?>

<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    S'INSCRIRE
</h1>
<div class="ui container ">
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

    <form class="ui form" action="" method="POST">
        <div class="field">
            <label>Pseudo</label>
            <input type="text" name="username">
        </div>
        <div class="field">
            <label>Email</label>
            <input type="text" name="email">
        </div>
        <div class="field">
            <label>Mot de passe</label>
            <input type="password" name="password">
        </div>
        <div class="field">
            <label>Confirmer votre mot de passe</label>
            <input type="password" name="password-confirm">
        </div>
        <button class="ui button" type="submit">M'inscrire</button>
    </form>
</div>

<?php require 'inc/footer.php'; ?>