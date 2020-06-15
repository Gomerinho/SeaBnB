<?php
require_once 'inc/function.php';
require_once 'inc/db.php';
require 'inc/request.php';
session_start();
logged_only();
$result_reservations = $pdo->query("SELECT * FROM reservation INNER JOIN users ON reservation.id_users=users.id INNER JOIN adverts ON reservation.id_advert=adverts.id_ad WHERE users.id = " . $_SESSION['auth']->id . " AND reservation.date_end > NOW()");
?>

<?php require 'inc/header_reservation.php' ?>


<h1 class="ui header" style="
    text-align: center;
    text-transform: uppercase;
">
    vos réservations :
</h1>
<div class="ui container " style="    text-align: -webkit-center;">
    <?php
    while ($reservation = $result_reservations->fetch(PDO::FETCH_OBJ)) { ?>
        <div class="ui segment" style="margin : 2rem;width: 40rem;">
            <div class="ui medium header">
                <?= $reservation->name_ad ?>
            </div>
            <div class="ui small header">
                Prix : <?= $reservation->price_ad * $reservation->nb_person ?> €
            </div>
            <p><i class="ui circular calendar icon"></i> Début : <?= $reservation->date_start ?> <i class="ui circular calendar icon"></i>Fin : <?= $reservation->date_end ?> <i class="circular users icon"></i> : <?= $reservation->nb_person ?>
            </p>
        </div>
    <?php } ?>
</div>


<?php
require 'inc/footer.php';
?>