<?php
if (!empty($_POST) && !empty($_POST['email'])) {
    require_once 'inc/db.php';
    require_once 'inc/function.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email =  ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if ($user) {
        session_start();
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        $_SESSION['flash']['success'] = 'Les instructions du rappel du mot de passe vous ont été envoyées par email';
        mail($_POST['email'], "Réinitialisation de votre mot de passe SeaBnb", "Afin de réinitialiser votre mot de passe : \n Merci de cliquer sur ce lien \n\n http://localhost:8888/SEABNB/SeaBnB/reset.php?id={$user->id}&token=$reset_token");
        header('Location: login.php');
        exit();
    } else {
        $errors['username'] = 'Aucun compte ne correspond a cet adresse';
    }
}

?>
<?php require 'inc/header.php'; ?>

<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    Mot de Passe oublié
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
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <button class="ui button" type="submit">Récupérer mon mot de passe</button>
    </form>
</div>

<?php require 'inc/footer.php'; ?>