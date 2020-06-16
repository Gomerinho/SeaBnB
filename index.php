<?php
require 'inc/header_index.php';
?>
<div class="ui horizontal divider">
    <div class="ui container">
        <div class="ui centered">
            <form action="adverts.php" method="GET" class="ui form">
                <div class="fields" style="justify-content: center;">
                    <div class="three wide field">
                        <label>Date de début</label>
                        <input type="date" name="date_start" id="">
                    </div>
                    <div class="three wide field">
                        <label>Date de fin</label>
                        <input type="date" name="date_end" id="">
                    </div>
                    <div class="three wide field">
                        <label>Nombres de personnes</label>
                        <input type="number" name="nb_person" id="" placeholder="Nombre de personnes" style="line-height: 1.5;">
                    </div>
                    <div class="field">
                        <button type="submit" class="ui primary flex button" style="
    position: absolute;
    top: 1.5rem;
    height: 3rem;"><i class="search icon"></i></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="ui container">
    <div class="ui horizontal divider" id="menu">
        <h1 class="ui center aligned header" id="menu">NOTRE SITE</h1>
    </div>
    <div class="ui header" style="text-align: center;">
        <p>Notre site vous permet de réserver rapidement une location de véhicules marins.</p>
        <p>Chaque utilisateur peut poster ou louer un bateau quand il le souhaite.</p>
        <p>
            En espérant que vous allez trouver ce que vous souhaiter ! Bonne navigation !
        </p>
        <p>L'équipe SeaBnB</p>
    </div>

</div>

<?php require 'inc/footer.php'; ?>