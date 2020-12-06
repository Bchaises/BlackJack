<?php
require_once('../Model/DAO_Player.php');
require_once('../Model/DAO_Game.php');

session_start();

$module = 'connection';
$messageErreur = '';
$message = '';

$daoPlayer = new DAO_Player;
$daoGame = new DAO_Game;

// Connexion du joueur
if (isset($_POST['btnConnection'])) {

	if (isset($_POST['pseudo']) && $_POST['pseudo'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
		
		$res = $daoPlayer->connectPlayer($_POST['pseudo'], $_POST['password']);

		if ($res != true) {

			$messageErreur = $res;
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

// inscription du joueur
if (isset($_GET['inscription'])) {
	$module = 'inscription';
}

// inscription de l'utilisateur
if (isset($_POST['btnInscription'])) {

	if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['money']) && $_POST['pseudo'] != '' && $_POST['password'] != '' && $_POST['money'] != '' && $_POST['money'] >= 0) {

		if ($daoPlayer->addPlayer($_POST['pseudo'],$_POST['password'],$_POST['money'])) {

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

// Mise
if (isset($_POST['btnMise'])) {
	if (isset($_POST['mise']) && $_POST['mise'] > 0) {

		$_SESSION['mise'] = $_POST['mise'];
		$_SESSION['money'] = ($_SESSION['money'] - $_SESSION['mise']);
		$daoPlayer->updatePlayer($_SESSION['pseudo'],$_SESSION['money']);

		$module = 'distribCartes';
	}
	else
	{
		$messageErreur = "La mise n'est pas correcte !";
	}
}

// Choix du joueur
if (isset($btnSubmitChoice)) {

	if ($_POST['radioChoice']) {

		if ($_POST['radioChoice'] == "draw") {

			$nbrRand = rand(1,52);
			while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
				$nbrRand = rand(1,52);
			}

			$_SESSION['valueP'] = $_SESSION['valueP'] + ($daoGame->valueCard($nbrRand));
			$_SESSION['carteP3'] = $nbrRand;
		}
	}
}
else{
	$messageErreur="Veuillez faire un choix parmis les quatres proposés pour continuer.";
}

// distribution des cartes pour le croupier et le joueur
if ($module == 'distribCartes') {
	$cartes = array(
		1 => 'As_Trèfle',
		2 => 'As_Pique',
		3 => 'As_Coeur',
		4 => 'As_Carreau',
		5 => 'Roi_Trèfle',
		6 => 'Roi_Pique',
		7 => 'Roi_Coeur',
		8 => 'Roi_Carreau',
		9 => 'Dame_Trèfle',
		10 => 'Dame_Pique',
		11 => 'Dame_Coeur',
		12 => 'Dame_Carreau',
		13 => 'Valet_Trèfle',
		14 => 'Valet_Pique',
		15 => 'Valet_Coeur',
		16 => 'Valet_Carreau',
		17 => '10_Trèfle',
		18 => '10_Pique',
		19 => '10_Coeur',
		20 => '10_Carreau',
		21 => '9_Trèfle',
		22 => '9_Pique',
		23 => '9_Coeur',
		24 => '9_Carreau',
		25 => '8_Trèfle',
		26 => '8_Pique',
		27 => '8_Coeur',
		28 => '8_Carreau',
		29 => '7_Trèfle',
		30 => '7_Pique',
		31 => '7_Coeur',
		32 => '7_Carreau',
		33 => '6_Trèfle',
		34 => '6_Pique',
		35 => '6_Coeur',
		36 => '6_Carreau',
		37 => '5_Trèfle',
		38 => '5_Pique',
		39 => '5_Coeur',
		40 => '5_Carreau',
		41 => '4_Trèfle',
		42 => '4_Pique',
		43 => '4_Coeur',
		44 => '4_Carreau',
		45 => '3_Trèfle',
		46 => '3_Pique',
		47 => '3_Coeur',
		48 => '3_Carreau',
		49 => '2_Trèfle',
		50 => '2_Pique',
		51 => '2_Coeur',
		52 => '2_Carreau',
		53 => 'dos',
	);

	$carteP = '';
	$carteC = '';
	$_SESSION['nbrP'] = 0;
	$_SESSION['nbrC'] = 0;
	$_SESSION['useCards'] = array();


	// carte du joueur
	$nbrRand = rand(1,52);
	$_SESSION['carteP1'] = $nbrRand;
	$_SESSION['valueP'] = $_SESSION['valueP'] + ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrP'] = $_SESSION['nbrP'] + 1;
	array_push($_SESSION['useCards'], $nbrRand);

	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}
	$_SESSION['carteP2'] = $nbrRand;
	$_SESSION['valueP'] = $_SESSION['valueP'] + ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrP'] = $_SESSION['nbrP'] + 1;
	array_push($_SESSION['useCards'], $nbrRand);

	// carte du croupier
	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}
	$_SESSION['carteC1'] = 53;
	$_SESSION['nbrP'] = $_SESSION['nbrC'] + 1;
	$_SESSION['carteC2'] = $nbrRand;
	$_SESSION['valueC'] = $_SESSION['valueC'] + ($daoGame->valueCard($nbrRand));
	$_SESSION['nbrP'] = $_SESSION['nbrC'] + 1;
	array_push($_SESSION['useCards'], $nbrRand);
}

// affichage des cartes
$affichageP = '';
for ($i=1; $i <= $_SESSION['nbrP']; $i++) {
	$affichageP .= '<img src="../Images/'.$_SESSION['carteP'.$i].'.png">';
}

$affichageC = '';
for ($i=1; $i <= $_SESSION['nbrC']; $i++) { 
	$affichageC .= '<img src="../Images/'.$_SESSION['carteC'.$i].'.png">';
}

include('../Vue/top_page.php');
include('../Vue/header.php');

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
	include('../Vue/distribCartes.php');
	include('../Vue/choix.php');
}
else if ($module == 'draw') {
	include('../Vue/info.php');
	include('../Vue/transition.php');
	include('../Vue/draw.php');
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