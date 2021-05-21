<?php

setlocale(LC_TIME, 'fra', 'fr_FR');

// On ajoute les deux DAO 
require_once('../Model/DAO_Player.php');
require_once('../Model/DAO_Game.php');

// pour utiliser $_SESSION
session_start();

// la variable module gere les pages affiché
$module = 'accueil';

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
		
		if($res == "connecté"){
			
		$_SESSION['pseudo'] = $_POST['pseudo'];
		$_SESSION['money'] = $daoPlayer->getMoneyByPseudo($_POST['pseudo']);

		$module = 'mise';

		}
		// si res renvoie autre que true alors affichage d'un message d'erreur
		else{

			$messageErreur = $res;
			$module = "connection";
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
	$module = 'accueil';
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

// page profil
if (isset($_GET['profil'])) {
	$module = 'profil';
}

// retour à la page mise
if (isset($_GET['mise'])) {
	$module = 'mise';
}

// retour à la page mise
if (isset($_GET['recommencer'])){
	if ($_SESSION['money'] > 0) {
		$module = 'distribCartes';
	}
	else{
		$messageErreur = 'Vous n\'avez plus de solde.';
	}
}

if (isset($_GET['regles'])) {
	$module = 'regles';
}

if (isset($_GET['accueil'])) {
	$module = 'accueil';
}

// l'utilisateur tente d'accéder à profil sans s'être connecté
if (isset($_GET['profil']) && !isset($_SESSION['pseudo'])) {
	$module = "connection";
	$messageErreur = "Veuillez vous connecter avant d'accéder à l'onglet \"Profil\"";
}

// l'utilisateur tente d'accéder à jouer sans s'être connecté
if (isset($_GET['mise']) && !isset($_SESSION['pseudo'])) {
	$module = "connection";
	$messageErreur = "Veuillez vous connecter avant d'accéder à l'onglet \"Jouer\"";
}


// Mise
if (isset($_POST['btnMise'])) {

	// on vérifie si la mise est correcte
	if (isset($_POST['mise']) && $_POST['mise'] >= 2 && $_POST['mise'] <= 1000 && $_POST['mise'] <= $_SESSION['money']) {

		// on enregistre la mise
		$_SESSION['mise'] = $_POST['mise'];

		// on lance le module distribCartes
		$module = 'distribCartes';

	// message d'erreurs différents
	}else if ($_POST['mise'] < 0) {
		$messageErreur = "Erreur vous avez saisie une valeur négative.";
	}else if ($_POST['mise'] > 1000) {
		$messageErreur = "Votre mise doit être comprise entre 2€ et 1000€.";
	}else if ($_POST['mise'] > $_SESSION['money']) {
		$messageErreur = "Erreur la mise est supérieur à votre solde.";
	}else{
		$messageErreur = "Erreur lors de la saisie de votre mise.";
	}
}

// distribution des cartes pour le croupier et le joueur
if ($module == 'distribCartes') {

	// on modifie l'argent dy joueur
	$_SESSION['money'] = ($_SESSION['money'] - $_SESSION['mise']);
	$daoPlayer->updatePlayer($_SESSION['pseudo'],$_SESSION['money']);

	// on initialise les variables session du nombre de cartes pour le croupier et le joueur
	// ainsi que les variables de valeurs des cartes
	$_SESSION['cartesP'] = array();
	$_SESSION['cartesC'] = array();
	$_SESSION['valueP'] = 0;
	$_SESSION['valueC'] = 0;

	// on initialise le tableau des cartes utilisés
	$_SESSION['useCards'] = array();


	// donne 2 cartes au joueur
	$nbrRand = rand(1,52);
	array_push($_SESSION['cartesP'],$nbrRand);
	$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));

	// on met la carte dans le tableau des cartes utilisés
	array_push($_SESSION['useCards'], $nbrRand);

	// vérification si la carte existe
	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}

	// donne une deuxieme carte
	array_push($_SESSION['cartesP'],$nbrRand);
	$_SESSION['valueP'] += ($daoGame->valueCard($nbrRand));

	// on le met dans le tableau des cartes utilisées
	array_push($_SESSION['useCards'], $nbrRand);

	// carte du croupier
	// on vérifie si la carte existe
	$nbrRand = rand(1,52);
	while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
		$nbrRand = rand(1,52);
	}

	// on met la carte retourné
	array_push($_SESSION['cartesC'],53);

	// on met la deuxieme carte
	array_push($_SESSION['cartesC'],$nbrRand);
	$_SESSION['valueC'] += ($daoGame->valueCard($nbrRand));

	// on met la carte dans le tableau des cartes utilisées
	array_push($_SESSION['useCards'], $nbrRand);

	$module = 'game';
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
			array_push($_SESSION['cartesP'], $nbrRand);

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
		if ($_POST['radioChoice'] == 'past' && $_SESSION['cartesC'][0] == 53) {
			
			// c'est le tour du croupier
			// on vérifie que la carte existe
			$nbrRand = rand(1,52);
			while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
			$nbrRand = rand(1,52);
			}

			// on change la carte retourné
			$_SESSION['cartesC'][0] = $nbrRand;
			$_SESSION['valueC'] += ($daoGame->valueCard($nbrRand)); 
			array_push($_SESSION['useCards'], $nbrRand);
			

			// tant que la valeur des cartes du croupier n'est pas égale ou superieur a 16 alors il pioche
			while ($_SESSION['valueC'] <= 16) {

				// on vérifie si la carte existe
				$nbrRand = rand(1,52);
				while ($daoGame->verifyCard($nbrRand,$_SESSION['useCards']) == true) {
				$nbrRand = rand(1,52);
				}

				// on ajoute dans le nombre de cartes du croupier, on ajoute une session avec la carte et la valeur de celle ci
				array_push($_SESSION['cartesC'],$nbrRand);
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
			array_push($_SESSION['cartesP'],$nbrRand);

			// on l'ajoute dans le tableau usecards
			array_push($_SESSION['useCards'], $nbrRand);

			// on modifie le solde du joueur une seconde fois
			$_SESSION['money'] = ($_SESSION['money'] - $_SESSION['mise']);

			// on modifie la mise en la multipliant pas deux
			$_SESSION['mise'] *= 2;

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

	//variable pour avoir le profit
	$profit = 0;

	// perdu si la valeur du joueur est superieur à 21
	if ($_SESSION['valueP']>21) {

		// on informe le joueur de la fin de partie ainsi que de la somme perdu
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';

		$profit = -$_SESSION['mise'];
	}
	// blackjack si la valeur du joueur est égale à 21
	else if ($_SESSION['valueP'] == 21) {

		// on informe le joueur de la victoire
		$message = 'BlackJack! Vous récupérez 2 fois votre mise.';

		// on modifie le solde du joueur, il a gagné 2 fois la mise
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 3) ));
		$_SESSION['money'] += $_SESSION['mise'] * 3;

		$profit =  2*$_SESSION['mise'];

	// si la valeur du croupier est superieur a 21
	}else if ($_SESSION['valueC']>21) {

		// on informe le joueur de la victoire
		$message = 'Vous avez gagné! Vous récupérez donc votre mise plus 1 fois votre mise.';

		// on modifie le solde 
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 2) ));
		$_SESSION['money'] += $_SESSION['mise'] * 2;

		$profit = $_SESSION['mise'];

	// si le croupier a un blackjack
	}else if ($_SESSION['valueC']==21) {

		// on informe le joueur de la défaite
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';

		$profit = -$_SESSION['mise'];
	}
	// si le joueur a une valeur superieur au croupier
	else if ($_SESSION['valueP'] > $_SESSION['valueC']) {

		// on informe le joueur de la victoire
		$message = 'Vous avez gagné! Vous récupérez donc votre mise plus 1 fois votre mise.';

		// on modifie le solde
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + ($_SESSION['mise'] * 2) ));
		$_SESSION['money'] += $_SESSION['mise'] * 2;

		$profit = $_SESSION['mise']; 
	}
	// si la valeur du croupier est superieur a celle du joueur
	else if ($_SESSION['valueC'] > $_SESSION['valueP']) {

		// on informe le joueur de la défaite
		$message = 'Vous avez perdu. Votre mise de '.$_SESSION['mise'].'€ a été retiré. Merci d\'avoir joué.';

		$profit = -$_SESSION['mise'];
	}
	// si c'est une égalité
	else{

		// on informe le joueur de l'égalité
		$message = "C'est une égalité! vous récupérez votre mise.";

		// la mise est récupéré
		$daoPlayer->updatePlayer($_SESSION['pseudo'], ($_SESSION['money'] + $_SESSION['mise']));
		$_SESSION['money'] += $_SESSION['mise']; 

		$profit = 0;
	}

	$player = $daoPlayer->getByPseudo($_SESSION['pseudo']);
	$daoGame->addGame($player->getId(),$_SESSION['mise'],$profit);

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



// affichage des cartes
if ($module == 'distribCartes' || $module == 'game' || $module == 'finPartie') {

	// affichage des images du joueur
	$affichageP = '';
	for ($i=0; $i < count($_SESSION['cartesP']); $i++) {
		$affichageP .= '<img src="../Images/'.$_SESSION['cartesP'][$i].'.png">';
	}

	// affichage des images du croupier
	$affichageC = '';
	for ($i=0; $i < count($_SESSION['cartesC']); $i++) { 
		$affichageC .= '<img src="../Images/'.$_SESSION['cartesC'][$i].'.png">';
	}
}

// permet de passer d'une page à l'autre dans l'historique
	if (isset($_GET['page'])) {
		if ($_GET['page'] > 0) {
			if ($_GET['page'] <= $_SESSION['pages']) {
				$page = $_GET['page'];
				$module='profil';
			}
			else{
				$page = $_SESSION['pages'];
				$module = 'profil';
			}
		}
		else{
			$page = 1;
			$module = 'profil';
		}
	}
	else{
		$page = 1;
	}

//affichage des parties réalisées
if ($module == 'profil') {


	if (isset($_POST['limit-records'])) {
		$limit = $_POST['limit-records'];
		$_SESSION['limit-records'] = $_POST['limit-records'];
	}
	else if (isset($_SESSION['limit-records'])) {
		$limit = $_SESSION['limit-records'];
	}
	else{
		$limit = 25;
	}
	$start = ($page - 1) * $limit;

	
	$player = $daoPlayer->getByPseudo($_SESSION['pseudo']);
	$games = $daoGame->getByIdLimit($player->getId(),$start,$limit);

	$parties = '';
	$nbrWin = 0;
	$winRate = 0;
	$cpt = $start + 1;

	if ($games == null) {
		$messageErreur = "Aucune données trouvées";
		$winRate = "<span style='color:#eb3b5a;font-weight:bold;'>0%</span>";
	}
	else{

		foreach ($games as $game) { 

			$date = date_create($game->getDate());

			$tabJours = ["", "Lundi", "Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
			$jour = $tabJours[date_format($date, 'N')];

			$tabMois = ["","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Decembre"];
			$mois = $tabMois[date_format($date,'n')];

			$dateFormat = $jour." ".date_format($date,'d')." ".$mois." ".date_format($date, 'à G\hi');

			$parties = $parties. "
			<tr class='row_history'>
				<td>".$cpt."</td>
				<td>".$game->getBet()."€</td>
			";

			if ( ($game->getProfit()) > 0) {
				$parties = $parties."
				<td style='color:#20bf6b;font-weight:bold;'>".$game->getProfit()."€
				</td>
					<td>gagné</td>
					<td>".$dateFormat."</td>
				</tr>
				";
			}
			else if (($game->getProfit()) == 0) {
				$parties = $parties."<td style='color:#3867d6;font-weight:bold;'>".$game->getProfit()."€</td>
					<td>égalité</td>
					<td>".$dateFormat."</td>
				</tr>
				";
			}
			else{
				$parties = $parties."<td style='color:#eb3b5a;font-weight:bold;'>".$game->getProfit()."€</td>
					  <td>perdu</td>
					<td>".$dateFormat."</td>
				</tr>
				";

			}

			$cpt++;
		}


		// Calcule le nombre de victoires au total
		$games = $daoGame->getById($player->getId());
		foreach ($games as $game) {
			if ( ($game->getProfit()) > 0) {
				$nbrWin++;
			}
		}

		$cptWinrate = $daoGame->numberGamesById($player->getId());


		if($nbrWin/$cptWinrate*100 >= 50){
			$winRate = "<span style='color:#20bf6b;font-weight:bold;'>".round($nbrWin/$cptWinrate*100,1)."%</span>";
		}
		else{
			$winRate = "<span style='color:#eb3b5a;font-weight:bold;'>".round($nbrWin/$cptWinrate*100,1)."%</span>";
		}
	}

	// nombre de pages de l'historique
	$total = $daoGame->numberGamesById($player->getId());
	$pages =ceil( $total / $limit );
	$_SESSION['pages'] = $pages;
	$Previous = $page - 1;
	$Next = $page + 1;

	// rang du joueur
	$players = $daoPlayer->bestPlayers();
	$cpt = 1;
	$rang = 0;

	foreach ($players as $player) {

		$id = ($daoPlayer->getByPseudo($player['pseudo']))->getId();
		$cpt++;

		if($_SESSION['pseudo'] != $player['pseudo']){
			$rang = $cpt;
		}
	}
}

if ($module == 'accueil') {
	
	$players = $daoPlayer->bestPlayers();
	$cpt = 1;
	$classement = '';

	foreach ($players as $player) {

		$id = ($daoPlayer->getByPseudo($player['pseudo']))->getId();
		
		$classement = $classement.'<tr>';

		$classement = $classement.'<td>#'.$cpt.'</td>';

		$classement = $classement.'<td>'.$player['pseudo'].'</td>';

		$classement = $classement.'<td>'.$player['money'].'€</td>';

		$classement = $classement.'<td>'.$daoGame->percentageVictory($id).'%</td>';

		$classement = $classement.'<tr>';

		$cpt++;
	}
}

// page html
include('../Vue/top_page.php');
include('../Vue/header.php');

if (!isset($_SESSION['pseudo'])) {
	include('../Vue/boutonConnect.php');
}
else{
	include('../Vue/info.php');
}

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
	include('../Vue/mise.php');
}
else if ($module == 'distribCartes') {
	include('../Vue/showCards.php');
}
else if ($module == 'game') {
	include('../Vue/showCards.php');
	include('../Vue/choix.php');
}
else if ($module == 'finPartie') {
	include('../Vue/showCards.php');
	include('../Vue/lienFin.php');

	$module = 'mise';
}
else if ($module == 'profil'){
	include('../Vue/profil.php');
}
else if ($module == 'regles'){
	include('../Vue/regles.php');
}
else if($module == 'accueil'){
	include('../Vue/accueil.php');
}
else{
	echo "ERROR404";
}

// message d'erreur
if ($messageErreur != '') {
	echo '<br><span style="

	color:red;
	display: flex;
	flex-direction:column;
	align-items: center;

	">'.$messageErreur."</span>";
}

// message d'information
if ($message != '') {
	echo '<br><span style="

	display: flex;
	flex-direction:column;
	align-items: center;
	
	">'.$message."</span>";
}

include ('../Vue/footer.php');
include('../Vue/bottom_page.php');