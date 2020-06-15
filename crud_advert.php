<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'inc/db.php';


if (isset($_POST['ajouter']) && !empty($_POST)) {
    $req = $pdo->prepare("INSERT INTO adverts SET name_ad = ?, description_ad = ?, price_ad = ?, position_ad = ?, id_users = ?, country=?");
    $req->execute([$_POST['name_ad'], $_POST['description_ad'], $_POST['price_ad'], $_POST['position_ad'], $_POST['id_user'], $_POST['country']]);
    $sql =  'SELECT id_ad FROM adverts ORDER BY date_ad DESC';
    $id = $pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    $dossier = 'img/a/' . $id->id_ad . "/";
    $fichier = basename($_FILES['main_img_ad']['name']);
    $taille_maxi = 100000000;
    $taille = filesize($_FILES['main_img_ad']['tmp_name']);
    $extensions = array('.jpg', '.jpeg', '.png', '.gif');
    $extension = strrchr($_FILES['main_img_ad']['name'], '.');
    //Début des vérifications de sécurité...
    if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $erreur = 'Vous devez uploader une image de type jpg, jpeg, odt ou doc...';
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
        mkdir("img/a/" . $id->id_ad, 0700);
        $rep1 = $dossier . "main_img" . $extension;
        if (move_uploaded_file($_FILES['main_img_ad']['tmp_name'], $rep1)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
            $dossier = 'img/a/' . $id->id_ad . '/';
            $fichier = basename($_FILES['secondary_img_ad']['name']);
            $taille_maxi = 100000000;
            $taille = filesize($_FILES['secondary_img_ad']['tmp_name']);
            $extensions = array('.jpg', '.jpeg', '.png', '.gif');
            $extension = strrchr($_FILES['secondary_img_ad']['name'], '.');
            //Début des vérifications de sécurité...
            if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
            {
                $erreur = 'Vous devez uploader une image de type jpg, jpeg, odt ou doc...';
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
                $rep = $dossier . "secondary_img" . $extension;
                if (move_uploaded_file($_FILES['secondary_img_ad']['tmp_name'], $rep)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {
                    $req = $pdo->prepare('UPDATE adverts SET main_img_ad = ?, secondary_img_ad =? WHERE id_ad = ?');
                    $req->execute([$rep1, $rep, $id->id_ad]);
                    $_SESSION['flash']['positive'] = 'Votre annonce à bien été ajoutée';
                    header('Location: index.php');
                    exit();
                } else //Sinon (la fonction renvoie FALSE).
                {
                    $erreur['upload'] =  "Echec de l'upload !";
                }
            } else {
                $_SESSION['flash']['danger'] = $erreur;
                header('Location: admin.php');
            }
        } else //Sinon (la fonction renvoie FALSE).
        {
            $erreur['upload'] =  "Echec de l'upload !";
        }
    } else {
        $_SESSION['flash']['negative'] = $erreur;
        header('Location: add_adverts.php');
    }
} elseif (isset($_POST['modify'])) {
    $dossier = 'img/a/' . $_POST['id_ad'] . "/";
    $fichier = basename($_FILES['main_img_ad']['name']);
    $taille_maxi = 100000000;
    $taille = filesize($_FILES['main_img_ad']['tmp_name']);
    $extensions = array('.jpg', '.jpeg', '.png', '.gif');
    $extension = strrchr($_FILES['main_img_ad']['name'], '.');
    //Début des vérifications de sécurité...
    if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $erreur = 'Vous devez uploader une image de type jpg, jpeg, odt ou doc...';
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
        $rep1 = $dossier . "main_img" . $extension;
        if (move_uploaded_file($_FILES['main_img_ad']['tmp_name'], $rep1)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
            $dossier = 'img/a/' . $_POST['id_ad'] . '/';
            $fichier = basename($_FILES['secondary_img_ad']['name']);
            $taille_maxi = 100000000;
            $taille = filesize($_FILES['secondary_img_ad']['tmp_name']);
            $extensions = array('.jpg', '.jpeg', '.png', '.gif');
            $extension = strrchr($_FILES['secondary_img_ad']['name'], '.');
            //Début des vérifications de sécurité...
            if (!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
            {
                $erreur = 'Vous devez uploader une image de type jpg, jpeg, odt ou doc...';
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
                $rep = $dossier . "secondary_img" . $extension;
                if (move_uploaded_file($_FILES['secondary_img_ad']['tmp_name'], $rep)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                {
                    $req = $pdo->prepare('UPDATE adverts SET main_img_ad = ?, secondary_img_ad =? WHERE id_ad = ?');
                    $req->execute([$rep1, $rep, $_POST['id_ad']]);
                    $_SESSION['flash']['positive'] = 'Votre annonce à bien été ajoutée';
                    header('Location: index.php');
                    exit();
                } else //Sinon (la fonction renvoie FALSE).
                {
                    $erreur['upload'] =  "Echec de l'upload !";
                }
            } else {
                $_SESSION['flash']['danger'] = $erreur;
                header('Location: admin.php');
            }
        } else //Sinon (la fonction renvoie FALSE).
        {
            $erreur['upload'] =  "Echec de l'upload !";
        }
    } else {
        $_SESSION['flash']['negative'] = $erreur;
        header('Location: add_adverts.php');
    }
    $req = $pdo->prepare("UPDATE adverts SET name_ad = ?, description_ad = ?, price_ad = ?, position_ad = ?, country=? WHERE id_ad=?");
    $req->execute([$_POST['name_ad'], $_POST['description_ad'], $_POST['price_ad'], $_POST['position_ad'], $_POST['country'], $_POST['id_ad']]);
    $_SESSION['flash']['success'] = 'Votre annonce à bien été modifier';
    header('Location: add_adverts.php');
    exit();
} elseif (isset($_POST['supprimer'])) {
    $req = $pdo->prepare("DELETE FROM adverts WHERE id_ad=?");
    $req->execute([$_POST['id_ad']]);
    if (!is_dir('img/a/' . $_POST['id_ad'] . '/')) {
        rmdir('img/a/' . $_POST['id_ad'] . '/');
    }
    $_SESSION['flash']['success'] = 'Votre annonce à bien été supprimée';
    header('Location: index.php');
    exit();
}
