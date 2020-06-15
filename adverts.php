<?php

include 'inc/header_advert.php';
require 'inc/function.php';
require 'inc/request.php';
require 'inc/db.php';

?>

<?php if (isset($_SESSION['flash'])) : ?>
    <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
        <div class="ui <?= $type ?> message center">
            <?= $message; ?>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="ui container">
    <div class="ui three stackable cards">
        <?php
        $adverts = $result_adverts->fetchAll(PDO::FETCH_OBJ);
        $date_debut = $_GET['date_start'];
        $date_fin = $_GET['date_end'];
        $result_reservations = $pdo->query("SELECT * FROM reservation INNER JOIN users ON reservation.id_users=users.id INNER JOIN adverts ON reservation.id_advert=adverts.id_ad WHERE ('" . $date_debut . "' <= reservation.date_start AND '" . $date_fin . "' >= reservation.date_start) OR ('" . $date_debut . "' <= reservation.date_end AND '" . $date_fin . "' >= reservation.date_end) OR ('" . $date_debut . "' >= reservation.date_start AND '" . $date_fin . "' <= reservation.date_end) OR ('" . $date_debut . "' >= reservation.date_start AND '" . $date_fin . "' <= reservation.date_end) OR ('" . $date_debut . "' <= reservation.date_start AND '" . $date_fin . "' >= reservation.date_end) ");
        $reservations = $result_reservations->fetchAll(PDO::FETCH_OBJ);
        foreach ($adverts as $key => $advert) {
            foreach ($reservations as $key_reserve => $reservation) {
                if ($reservation->id_ad == $advert->id_ad) {
                    unset($adverts[$key]);
                }
            }
        }
        foreach ($adverts as $key => $advert) { ?>
            <div class="card">
                <div class="content">
                    <div class="right floated meta"><?php echo format($advert->date_ad) ?></div>
                    <h3 class="header floated"><img class="ui avatar image" src="<?php echo $advert->profil_pic ?>"><?php echo $advert->username ?></h3>
                    <div class="left blue"><i class="<?php echo $advert->country ?> flag"></i><?php echo $advert->position_ad ?></div>
                </div>
                <div class="ui slide masked reveal image">
                    <img src="<?php echo $advert->main_img_ad ?>" class="visible content">
                    <img src="<?php echo $advert->secondary_img_ad ?>" class="hidden content">
                </div>
                <div class="content">
                    <span class="right floated">
                        <?php echo $advert->price_ad ?>€/personnes
                    </span>
                    <h5 class="header floated"><?php echo $advert->name_ad ?></h5>
                </div>

                <a href=<?= "advert.php?id_ad=" . $advert->id_ad . "&date_start=" . $_GET['date_start'] . "&date_end=" . $_GET['date_end'] . "&nb_person=" . $_GET['nb_person'] ?>>
                    <div class="ui primary bottom attached button"> <i class="calendar alternate outline icon"></i>Réserver</div>
                </a>
            </div>


        <?php } ?>
    </div>
</div>

<?php

include 'inc/footer.php';
