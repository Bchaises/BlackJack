<?= "<p>Connecté en tant que <b>".htmlspecialchars($_SESSION['pseudo'])."</b> ";?>
<?= "<a href='index.php?deco' class=deco>Deconnexion</a></p>";?>
<?php if ($module == "mise") {
	echo "<a href='index.php?profil' class='button_connect_profil'>Profil</a>";
}?>
<?php if ($module == "profil") {
	echo "<a href='index.php?mise' class='button_connect_mise'>Jouer</a>";
}?>
