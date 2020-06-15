<?php

include 'inc/header_advert.php';
require_once 'inc/function.php';
require_once 'inc/db.php';
logged_only();
$advert = $pdo->query("SELECT * FROM adverts INNER JOIN users ON adverts.id_users=users.id WHERE id_ad =" . $_GET['id_ad'])->fetch(PDO::FETCH_OBJ);
$date_debut = $_GET['date_start'];
$date_fin = $_GET['date_end'];
$nb_person = $_GET['nb_person'];
$id_user = $_SESSION['auth']->id;
$id_ad = $_GET['id_ad'];
$user = $_SESSION['auth'];
?>
<div class="ui container">
    <div class="ui grid" style="margin-top: 3rem;">
        <div class="eight wide column">
            <img class="ui rounded image" src="<?= $advert->main_img_ad ?>" alt="" srcset="" width="100%">
            <div class="ui divider"></div>
            <img class="ui rounded image" src="<?= $advert->secondary_img_ad ?>" alt="" srcset="" width="100%">
            <div class="ui horizontal divider header">
                <h2 class="ui centered header">VENDEUR </h2>
            </div>
            <div class="ui centered card">
                <div class="image">
                    <img src="img/users/1/pp.jpg">
                </div>
                <div class="content">
                    <a class="header"><?= $advert->name ?> <?= $advert->first_name ?></a>
                    <div class="meta">
                        <span class="date"><?= $advert->username ?></span>
                    </div>
                    <div class="description">
                        <?= $advert->bio ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="eight wide column">
            <div class="ui attached segment">
                <h2 class="ui centered huge header"><?= $advert->name_ad ?></h2>
                <div class="ui centered header">
                    <i class="<?= $advert->country ?> flag"></i><?= $advert->position_ad ?>
                </div>
                <div class="ui divider"></div>
                <p><?= str_replace("\n", "<br>", $advert->description_ad) ?></p>
            </div>
            <form action="add_reservation.php" method="POST">
                <input type="hidden" name="date_start" value="<?= $date_debut ?>">
                <input type="hidden" name="date_end" value="<?= $date_fin ?>">
                <input type="hidden" name="nb_person" value="<?= $nb_person ?>">
                <input type="hidden" name="id_ad" value="<?= $advert->id_ad ?>">
                <input type="hidden" name="price_ad" value="<?= $advert->price_ad ?>">
                <input type="hidden" name="name_ad" value="<?= $advert->name_ad ?>">
                <input type="hidden" name="user_id" value="<?= $_SESSION['auth']->id ?>">
                <button class="ui animated fade primary bottom attached fluid button" type="submit" name="reserver">
                    <div class="visible content">Réserver l'annonce</div>
                    <div class="hidden content">
                        <?= $advert->price_ad * $_GET['nb_person'] ?> € pour <?= $_GET['nb_person'] ?> personnes
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>