<?php

// On ajoute les deux DAO 
require_once('../Model/DAO_Player.php');
require_once('../Model/DAO_Game.php');

// pour utiliser $_SESSION
session_start();

// la variable module gere les pages affiché
$module = 'connection';

// les messages permettent d'informer l'utilisateur, ils sont affiché en fin de page
$messageErreur = '';
$message = '';

// on crée deux objets 
$daoPlayer = new DAO_Player;
$daoGame = new DAO_Game;

// Connexion du joueur
// vérification si le bouton a été utilisé
if (isset($_POST['btnConnection'])) {

	// on vérifie que l'utilisateur a bien rentré les informations correctement
	if (isset($_POST['pseudo']) && $_POST['pseudo'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
		
		// on utilise la fonction connectPlayer pour connecter le joueur
		$res = $daoPlayer->connectPlayer($_POST['pseudo'], $_POST['password']);

		// si res renvoie true alors affichage d'un message d'erreur
		if ($res != true) {

			$messageErreur = $res;
			var_dump($messageErreur);
		}
	}
	else
	{
		// s'affiche si la connection resulte d'une erreur
		$messageErreur = "Le formulaire n'a pas été correctement remplis !";
	}
}

// inscription du joueur
if (isset($_GET['inscription'])) {
	$module = 'inscription';
}

// inscription de l'utilisateur
if (isset($_POST['btnInscription'])) {

	// on vérifie que le formulaire d'inscription est correctement remplie
	if (isset($_POST['pseudo']) && isset($_POST['password']) && $_POST['pseudo'] != '' && $_POST['password'] != '') {

		// on ajoute le joueur avec la fonction addPlayer si l'utilisateur n'existe pas déjà
		if ($daoPlayer->addPlayer($_POST['pseudo'],$_POST['password'],1000)) {

			// on connecte le joueur
			$res =  $daoPlayer->connectPlayer($_POST['pseudo'], $_POST['password']);
		}
		else
		{
			$messageErreur = "L'utilisateur existe déjà !";
		}
	}
	else
	{
		$messageErreur = "Le formulaire n'a pas été correctement remplis !";
	}
}

// le joueur est déjà connecté
if(isset($_SESSION['pseudo'])){
	$module = 'mise';
}

// deconnexion du joueur
if (isset($_GET['deco'])) {
	unset($_SESSION['id'], $_SESSION['pseudo'], $_SESSION['money']);
	$module = 'connection';
}

// bouton continuer
if (isset($_GET['continuer'])) {
	header('location:index.php');
	$module = "continuer";
}

// bouton arrêt
if (isset($_POST['btnStop'])) {
	$module = 'finPartie';
}


// Mise
if (isset($_POST['btnMise'])) {

	// on vérifie si la mise est correcte
	if (isset($_POST['mise']) && $_POST['mise'] >= 2 && $_POST['mise'] <= 100 && $_POST['mise'] <= $_SESSION['money']) {

		// on enregistre la mise et on modifie l'argent du joueur
		$_SESSION['mise'] = $_POST['mise'];
		$_SESSION['money'] = ($_SESSION['money'] - $_SESSION['mise']);
		$daoPlayer->updatePlayer($_SESSION['pseudo'],$_SESSION['money']);

		// on lance le module distribCartes
		$module = 'distribCartes';

	// message d'erreurs différents
	}else if ($_POST['mise'] < 0) {
		$messageErreur = "Erreur vous avez saisie une valeur négative.";
	}else if ($_POST['mise'] > 100) {
		$messageErreur = "Votre mise doit être comprise entre 2€ et 100€.";
	}else if ($_POST['mise'] > $_SESSION['money']) {
		$messageErreur = "Erreur la mise est supérieur à votre solde.";
	}else{
		$messageErreur = "Erreur lors de la saisie de votre mise.";
	}
}

// Choix du joueur
if (isset($_POST['btnChoiceSubmit'])) {

	// on vérifie qu'un bouton a été selectionné
	if (isset($_POST['radioChoice']) && $_SESSION['valueP']<21) {

		// si le bouton draw/tiré une carte a été selectionné
		if ($_POST['radioChoice'] == "draw" ) {

			// on vérifie si la carte existe
			$nbrRand = rand(1,52);
			while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
				$nbrRand = rand(1,52);
			}

			// on donne alors la valeur de la carte, on l'ajoute dans le nombre de carte du joueur et on ajoute une session avec la carte
			$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));
			$_SESSION['nbrP'] += 1;
			$_SESSION['carteP'.$_SESSION['nbrP']] = $nbrRand;

			// on met la carte d'un le tableau usecards 
			array_push($_SESSION['useCards'], $nbrRand);

			// si le joueur a une valeur de plus de 21 avec la carte tiré alors la partie est terminé
			if ($_SESSION['valueP'] > 21 || $_SESSION['valueP'] == 21) {
				$module = 'finPartie';
			//sinon on redirige le joueur dans le module game
			}else{
				$module = 'game';
			}
		}

		// si le bouton past/passé la main a été selectionné
		if ($_POST['radioChoice'] == 'past') {
			
			// c'est le tour du croupier
			// on vérifie que la carte existe
			$nbrRand = rand(1,52);
			while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
			$nbrRand = rand(1,52);
			}

			// on change la carte retourné
			$_SESSION['carteC1'] = $nbrRand;
			array_push($_SESSION['useCards'], $nbrRand);

			// tant que la valeur des cartes du croupier n'est pas égale ou superieur a 16 alors il pioche
			while ($_SESSION['valueC'] < 16) {

				// on vérifie si la carte existe
				$nbrRand = rand(1,52);
				while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
				$nbrRand = rand(1,52);
				}

				// on ajoute dans le nombre de cartes du croupier, on ajoute une session avec la carte et la valeur de celle ci
				$_SESSION['nbrC'] += 1;
				$_SESSION['carteC'.$_SESSION['nbrC']] = $nbrRand;
				$_SESSION['valueC'] += ($daoGame->valueCard($nbrRand));
				// on l'ajoute dans usecards
				array_push($_SESSION['useCards'], $nbrRand);
			}

			// à la fin du tour du croupier c'est la fin du jeu
			$module = 'finPartie';
		}

		// si le bouton double/ double son jeu est selectionné
		if ($_POST['radioChoice'] == 'double') {
			
			// ajout d'une carte puis double la mise
			// on vérifie si la carte existe
			$nbrRand = rand(1,52);
			while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
				$nbrRand = rand(1,52);
			}

			// on ajoute valeur, nombre et carte
			$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));
			$_SESSION['nbrP'] += 1;
			$_SESSION['carteP'.$_SESSION['nbrP']] = $nbrRand;

			// on l'ajoute dans le tableau usecards
			array_push($_SESSION['useCards'], $nbrRand);

			// on modifie la mise en la multipliant pas deux
			$_SESSION['mise'] *= 2;

			// on modifie le solde du joueur
			$_SESSION['money'] = ($_SESSION['money'] - $_SESSION['mise']);

			// le solde du joueur est modifier dans la base de données
			$daoPlayer->updatePlayer($_SESSION['pseudo'],$_SESSION['money']);

			// si la valeur est supérieur ou égale a 21 alors fin de partie
			if ($_SESSION['valueP'] > 21 || $_SESSION['valueP'] == 21) {
				$module = 'finPartie';
			}else{
				$module = 'game';
			}
		}

	// si la valeur est superieur ou inferieur a 21 alors fin de partie
	}else if ($_SESSION['valueP'] == 21 || $_SESSION['valueP'] > 21) {
				$module= 'finPartie';
	// erreur si le joueur n'a pas selectionné de choix
	}else{
		$messageErreur="Veuillez faire un choix parmis les quatres proposés pour continuer.";
		$module = 'game';
	}
}

// module fin de partie
if ($module == 'finPartie') {

	// perdu si la valeur du joueur est superieur à 21
	if ($_SESSION['valueP']>21) {

		// on informe le joueur de la fin de partie ainsi que de la somme perdu
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';
	}
	// blackjack si la valeur du joueur est égale à 21
	else if ($_SESSION['valueP'] == 21) {

		// on informe le joueur de la victoire
		$message = 'BlackJack! Vous récupérez 2 fois votre mise.';

		// on modifie le solde du joueur, il a gagné 2 fois la mise
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 2) ));
		$_SESSION['money'] += $_SESSION['mise'] * 2; 

	// si la valeur du croupier est superieur a 21
	}else if ($_SESSION['valueC']>21) {

		// on informe le joueur de la victoire
		$message = 'Vous avez gagné! Vous récupérez donc votre mise plus 1x votre mise.';

		// on modifie le solde 
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 2) ));
		$_SESSION['money'] += $_SESSION['mise'] * 2;

	// si le croupier a un blackjack
	}else if ($_SESSION['valueC']==21) {

		// on informe le joueur de la défaite
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';
	}
	// si le joueur a une valeur superieur au croupier
	else if ($_SESSION['valueP'] > $_SESSION['valueC']) {

		// on informe le joueur de la victoire
		$message = 'Vous avez gagné! Vous récupérez donc votre mise plus 1x votre mise.';

		// on modifie le solde
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 2) ));
		$_SESSION['money'] += $_SESSION['mise'] * 2; 
	}
	// si la valeur du croupier est superieur a celle du joueur
	else if ($_SESSION['valueC'] > $_SESSION['valueP']) {

		// on informe le joueur de la défaite
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';
	}
	// si c'est une égalité
	else{

		// on informe le joueur de l'égalité
		$message = "C'est une égalité! vous récupérez votre mise.";

		// la mise est récupéré
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + $_SESSION['mise']));
		$_SESSION['money'] += $_SESSION['mise']; 
	}
}

// module continuer
if ($module == 'continuer') {

	// on unset toutes les variables session sauf celle de la connexion
	for ($i=0; $i < $_SESSION['nbrP']; $i++) { 
			unset($_SESSION['carteP'.$i]);
		}
		for ($i=0; $i < $_SESSION['nbrC']; $i++) { 
			unset($_SESSION['carteC'.$i]);
		}
		unset($_SESSION['nbrP'], $_SESSION['nbrC'], $_SESSION['useCards'], $_SESSION['valueP'], $_SESSION['valueC']);

		// on retourne sur le module connection
		$module = 'connection';
}

// distribution des cartes pour le croupier et le joueur
if ($module == 'distribCartes') {

	// on initialise les variables session du nombre de cartes pour le croupier et le joueur
	// ainsi que les variables de valeurs des cartes
	$_SESSION['nbrP'] = 0;
	$_SESSION['nbrC'] = 0;
	$_SESSION['valueP'] = 0;
	$_SESSION['valueC'] = 0;

	// on initialise le tableau des cartes utilisés
	$_SESSION['useCards'] = array();


	// donne 2 cartes au joueur
	$nbrRand = rand(1,52);
	$_SESSION['carteP1'] = $nbrRand;
	$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrP'] += 1;

	// on met la carte dans le tableau des cartes utilisés
	array_push($_SESSION['useCards'], $nbrRand);

	// vérification si la carte existe
	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}

	// donne une deuxieme carte
	$_SESSION['carteP2'] = $nbrRand;
	$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrP'] += 1;

	// on le met dans le tableau des cartes utilisées
	array_push($_SESSION['useCards'], $nbrRand);

	// carte du croupier
	// on vérifie si la carte existe
	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}

	// on met la carte retourné
	$_SESSION['carteC1'] = 53;
	$_SESSION['nbrC'] += 1;

	// on met la deuxieme carte
	$_SESSION['carteC2'] = $nbrRand;
	$_SESSION['valueC'] += ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrC'] += 1;

	// on met la carte dans le tableau des cartes utilisées
	array_push($_SESSION['useCards'], $nbrRand);
}

// affichage des cartes
if ($module == 'distribCartes' || $module == 'game' || $module == 'finPartie') {

	// affichage des images du joueur
	$affichageP = '';
	for ($i=1; $i <= $_SESSION['nbrP']; $i++) {
		$affichageP .= '<img src="../Images/'.$_SESSION['carteP'.$i].'.png">';
	}

	// affichage des images du croupier
	$affichageC = '';
	for ($i=1; $i <= $_SESSION['nbrC']; $i++) { 
		$affichageC .= '<img src="../Images/'.$_SESSION['carteC'.$i].'.png">';
	}
}

// page html
include('../Vue/top_page.php');
include('../Vue/header.php');

// différents modules
if ($module == 'connection') {
	include('../Vue/lien_inscription.php');
	include('../Vue/connection_form.php');
}
else if($module == 'inscription'){
	include('../Vue/lien_connection.php');
	include('../Vue/inscription_form.php');
}
else if($module == 'mise'){
	include('../Vue/info.php');
	include('../Vue/transition.php');
	include('../Vue/mise.php');
}
else if ($module == 'distribCartes') {
	include('../Vue/info.php');
	include('../Vue/transition.php');
	include('../Vue/showCards.php');
	include('../Vue/choix.php');
}
else if ($module == 'game') {
	include('../Vue/info.php');
	include('../Vue/transition.php');
	include('../Vue/showCards.php');
	include('../Vue/choix.php');
}
else if ($module == 'finPartie') {
	include('../Vue/info.php');
	include('../Vue/transition.php');
	include('../Vue/showCards.php');
	include('../Vue/lienFin.php');
}
else{
	echo "ERROR404";
}

// message d'erreur
if ($messageErreur != '') {
	echo '<br>'.$messageErreur;
}

// message d'information
if ($message != '') {
	echo '<br>'.$message;
}

include('../Vue/bottom_page.php');