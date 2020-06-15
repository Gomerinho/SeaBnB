<?php

require 'db.php';

$adverts = $pdo->prepare('SELECT * FROM adverts INNER JOIN users ON adverts.id_users=users.id')->fetch();

$result_adverts = $pdo->query("SELECT * FROM adverts INNER JOIN users ON adverts.id_users=users.id ORDER BY date_ad DESC");

$result_reservations = $pdo->query("SELECT * FROM reservation INNER JOIN users ON reservation.id_users=users.id INNER JOIN adverts ON reservation.id_advert=adverts.id_ad ORDER BY date_ad DESC");
