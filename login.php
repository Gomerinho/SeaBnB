<?php
require_once 'inc/function.php';
recconnect_from_cookie();
if (isset($_SESSION['auth'])) {
    header('Location: account.php');
    exit();
}

if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    require_once 'inc/db.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
    $req->execute(['username' => $_POST['username']]);
    $user = $req->fetch();
    if (password_verify($_POST['password'], $user->password)) {
        session_start();
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
    }
    header('Location: account.php');
    exit();
} else {
    $errors['username'] = 'Identifiant ou mot de passe incorrecte';
}

?>
<?php require 'inc/header_non_active.php'; ?>

<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    Se connecter
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
            <label>Pseudo ou Email</label>
            <input type="text" name="username">
        </div>
        <div class="field">
            <label>Mot de passe</label>
            <input type="password" name="password">
            <a href="forget.php">J'ai oublié mon mot de passe</a>
        </div>
        <button class="ui button" type="submit">Se connecter</button>
    </form>
</div>

<?php require 'inc/footer.php'; ?>