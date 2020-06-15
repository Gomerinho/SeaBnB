<?php session_start();
require 'inc/function.php';
logged_only();
if (!empty($_POST) && isset($_POST['password_change'])) {
    if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['flash']['negative'] = "Les mots de passes ne correspondent pas";
    } else {
        $user_id = $_SESSION['auth']->id;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        require_once 'inc/db.php';
        $req = $pdo->prepare('UPDATE users SET password =? WHERE id=?')->execute([$password, $user_id]);
        $_SESSION['flash']['positive'] = "Votre mot de passe a bien été mise à jour";
    }
} elseif (!empty($_POST) && isset($_POST['email_change'])) {
    if (empty($_POST['email']) || $_POST['email'] != $_POST['email_confirm']) {
        $_SESSION['flash']['negative'] = "Les emails ne correspondent pas";
    } else {
        $user_id = $_SESSION['auth']->id;
        require_once 'inc/db.php';
        $req = $pdo->prepare('UPDATE users SET email =? WHERE id=?')->execute([$_POST['email'], $user_id]);
        $_SESSION['flash']['positive'] = "Votre email a bien été mise à jour";
    }
} elseif (!empty($_POST) && isset($_POST['modify'])) {
    $user_id = $_SESSION['auth']->id;
    require_once 'inc/db.php';
    $req = $pdo->prepare('UPDATE users SET name =?,first_name=? WHERE id=?')->execute([$_POST['name'], $_POST['first_name'], $user_id]);
    $_SESSION['flash']['positive'] = "Votre nom/prénom a bien été mise à jour";
} elseif (!empty($_POST) && isset($_POST['pp'])) {
    $user_id = $_SESSION['auth']->id;
    $dossier = 'img/users/' . $user_id . '/';
    $fichier = basename($_FILES['pp']['name']);
    $taille_maxi = 100000000;
    $taille = filesize($_FILES['pp']['tmp_name']);
    $extensions = array('.jpg', '.jpeg', '.png', '.gif');
    $extension = strrchr($_FILES['pp']['name'], '.');
    //Début des vérifications de sécurité...
    if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $erreur = 'Vous devez uploader une image de type jpg, jpeg, gif ou png';
    }
    if ($taille > $taille_maxi) {
        $erreur = 'Le fichier est trop gros...';
    }
    if (!isset($erreur)) //S'il n'y a pas d'erreur, on upload
    {
        //On formate le nom du fichier
        $fichier = strtr(
            $fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
        );
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
        if (file_exists("img/users/" . $user_id) != TRUE) {
            mkdir("img/users/" . $user_id, 0700);
        }
        $rep1 = $dossier . "pp" . $extension;
        var_dump($rep1);
        if (move_uploaded_file($_FILES['pp']['tmp_name'], $rep1)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
            require_once 'inc/db.php';
            $req = $pdo->prepare('UPDATE users SET profil_pic =? WHERE id=?')->execute([$rep1, $user_id]);
            $_SESSION['flash']['positive'] = "Votre image a bien été mise à jour";
            header('Location: account.php');
            exit();
        }
    } else {
        $_SESSION['flash']['negative'] = $erreur;
        echo 'erreur';
    }
}

require_once 'inc/db.php';
$sth = $pdo->prepare('SELECT *
    FROM users
    WHERE id = ?');
$sth->execute([$_SESSION['auth']->id]);
$pp = $sth->fetch(PDO::FETCH_OBJ);

?>

<?php require 'inc/header.php'; ?>

<?php if (isset($_SESSION['flash'])) : ?>
    <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
        <div class="ui <?= $type ?> message center">
            <?= $message; ?>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    Bonjour <?= $_SESSION['auth']->username; ?>
</h1>

<div class="ui container">
    <h2 class="ui heder center aligned" style="margin-left:5rem;"> VOTRE PROFIL : </h2>
    <form action="" method="POST" class="ui form" enctype="multipart/form-data">
        <div style="margin-left:5rem;">
            <div class="field">
                <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
            </div>
            <div class="field">
                <label for="file">Changer d'image de profil</label>
                <img class="ui small circular right floated image" src="<?= $pp->profil_pic ?>">
                <input type="file" name="pp" style="width:300px;">
                <button class="ui primary button" name="pp" type="submit">Modifier</button>
            </div>
            <div class="ui vertical segment" style="width:500px">
                <label for="name">
                    <h4>Nom :</h4>
                </label>
                <input class="field" type="text" name="name" value="<?= $_SESSION['auth']->name ?>"></input>
            </div>
            <div class="ui vertical segment" style="width:500px">
                <label for="first_name">
                    <h4>Prénom :</h4>
                </label>
                <input class="field" type="text" name="first_name" value="<?= $_SESSION['auth']->first_name ?>"></input>
                <button class="ui primary button" type="submit" style="margin-top:1rem;" name="modify">
                    Modifier
                </button>
    </form>
</div>
<div class="ui vertical segment" style="width:500px">
    <h4>Email :</h4><?= $_SESSION['auth']->email ?>
</div>
</div>
<form class="ui form" action="" method="POST">
    <div class="field">
        <input type="password" name="password" placeholder="Changer de mot de passe">
    </div>
    <div class="field">
        <input type="password" name="password_confirm" placeholder="Confirmer le mot de passe">
    </div>
    <button type="submit" class="ui primary button" name="password_change">Changer mon mot de passe</button>
</form>
<form class="ui form" action="" method="POST">
    <div class="field">
        <input type="password" name="password" placeholder="Entrez votre nouvelle email">
    </div>
    <div class="field">
        <input type="password" name="password_confirm" placeholder="Confirmer l'email">
    </div>
    <button name=" password_change" type="submit" class="ui primary button">Changer mon adresse mail</button>
</form>
</div>


<?php require 'inc/footer.php'; ?>