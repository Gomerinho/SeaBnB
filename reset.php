<?php
if (isset($_GET['id']) && isset($_GET['token'])) {
    require 'inc/db.php';
    require 'inc/function.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE )');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if ($user) {
        if (!empty($_POST)) {
            if (!empty($_POST['password']) && $_POST['password'] == $_POST['password_confirm']) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $pdo->prepare('UPDATE users SET password = ? WHERE id=?')->execute([$password, $_GET['id']]);
                session_start();
                $_SESSION['flash']['positive'] = "Votre mot de passe a bien été modifié";
                $_SESSION['auth'] = $user;
                header('Location: account.php');
                exit();
            }
        }
    } else {
        session_start();
        $_SESSION['flash']['negative'] = "Ce token n'est pas valide";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
<?php require 'inc/header.php'; ?>

<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    Réinitialiser mon mot de passe
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
            <label>Mot de passe</label>
            <input type="password" name="password">
        </div>
        <div class="field">
            <label>Confirmation du mot de passe</label>
            <input type="password" name="password_confirm">
        </div>
        <button class="ui button" type="submit">Réinitialiser mon mot de passe</button>
    </form>
</div>

<?php require 'inc/footer.php'; ?>