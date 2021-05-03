<?php
require_once('../Model/DTO_Player.php');

class DAO_Player
{
	private $bdd;

	public function __construct()
	{
		try{
			$this->bdd = new PDO('mysql:host=localhost;dbname=blackjack','root','');
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	// fonction pour obtenir les infos d'un player par son id
	public function getById($id)
	{
		$sql = 'SELECT * FROM player WHERE id = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute([$id]);

		$data = $req->fetch();
		if ($user != null) {

			$id = $data['id'];
			$pseudo = $data['pseudo'];
			$password = $data['password'];
			$money = $data['money'];

			$player = new DTO_Player($id, $pseudo, $password, $money);
			return player;
		}
		else 
		{
			return null;
		}
	}

	// fonction pour obtenir les infos d'un player par son pseudo
	public function getByPseudo($p)
	{
		$sql = 'SELECT * FROM player WHERE pseudo = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute(array($p));

		$data = $req->fetch();
		if ($data != null) {
			
			$id = $data['id'];
			$pseudo = $data['pseudo'];
			$password = $data['password'];
			$money = $data['money'];

			$player = new DTO_Player($id, $pseudo, $password, $money);
			return $player;
		}
		else
		{
			return null;
		}
	}

	// Connexion de l'utilisateur
	public function connectPlayer($pseudo, $password)
	{
		$p = $this->getByPseudo($pseudo);

		if ($p != null) {

			if (password_verify($password, $p->getPassword())) {
				
				$_SESSION['id'] = $p->getId();
				$_SESSION['pseudo'] = $p->getPseudo();
				$_SESSION['money'] = $p->getMoney();

				return "connecté";
			}
			else
			{
				return "Mot de passe incorrect !";
			}
		}
		else
		{
			return "Joueur inconnu";
		}
	}

	// ajout d'un utilisateur
	public function addPlayer($pseudo,$password,$money)
	{
		$p = $this->getByPseudo($pseudo);

		if ($p == null) {
			
			// on hache le mot de passe
			$passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

			$sql = 'INSERT INTO player(pseudo,password,money) VALUES (?,?,?)';
			$req = $this->bdd->prepare($sql);
			$req->execute(array($pseudo,$passwordHash,$money));

			return true;
		}
		else
		{
			return false;
		}
	}

	// modification du joueur
	public function updatePlayer($pseudo,$money)
	{
		$sql = 'UPDATE player SET money = ? WHERE pseudo = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute(array($money,$pseudo));
	}
}