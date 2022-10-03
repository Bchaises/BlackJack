<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Blackjack</title>
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/bootstrap-5.2.2/css/bootstrap.min.css">
</head>
<body>
<div class="page">
    <header>
        <p class="Titre fw-bold">Bienvenue dans le jeu du BlackJack !</p>
        <div class="menu">
            <ul>
                <li id="menu_item_1"><a href="/accueil">Accueil</a></li>
                <li id="menu_item_2"><a href="/profil">Profil</a></li>
                <li id="menu_item_3"><a href="/mise">Jouer</a></li>
                <li id="menu_item_4"><a href="/regles">Les règles</a></li>
            </ul>
        </div>
    </header>
    <?=
        isset($PAGE_CONTENT) ? view($PAGE_CONTENT) : null
    ?>
</div>
<div id="footer">

</div>

<script src="<?= base_url() ?>/assets/css/main.js" ></script>
<script src="<?= base_url() ?>/assets/plugins/bootstrap-5.2.2/js/bootstrap.min.js" ></script>
</body>
</html>