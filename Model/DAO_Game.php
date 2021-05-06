<?php
require_once('../Model/DTO_Game.php'); 

class DAO_Game
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

	// fonction pour obtenir les infos d'une partie par son id avec limite
	public function getByIdLimit($id,$start,$limit)
	{
		$sql = 'SELECT * FROM game WHERE player = ? LIMIT ?,?';
		$req = $this->bdd->prepare($sql);

		$req->bindParam(1, $id, PDO::PARAM_STR);
		$req->bindParam(2, $start, PDO::PARAM_INT);
		$req->bindParam(3, $limit, PDO::PARAM_INT);

		$req->execute();

		$games = [];

		while ($data = $req->fetch()) {

			$id = $data['id'];
			$player = $data['player'];
			$date = $data['date'];
			$bet = intval($data['bet']);
			$profit = intval($data['profit']);

			$game = new DTO_Game($id,$player,$date,$bet,$profit);

			array_push($games, $game);

		}

		if ($games != null) {
			return $games;
		}
		else
		{
			return null;
		}
	}

	// fonction pour obtenir les infos d'une partie par son id sans limite
	public function getById($id)
	{
		$sql = 'SELECT * FROM game WHERE player = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute(array($id));

		$games = [];

		while ($data = $req->fetch()) {

			$id = $data['id'];
			$player = $data['player'];
			$date = $data['date'];
			$bet = intval($data['bet']);
			$profit = intval($data['profit']);

			$game = new DTO_Game($id,$player,$date,$bet,$profit);

			array_push($games, $game);

		}

		if ($games != null) {
			return $games;
		}
		else
		{
			return null;
		}
	}

	// fonction pour obtenir les infos d'une partie grâce à l'di du player
	public function getByPlayer($pseudo)
	{
		$sql = 'SELECT * FROM player,game WHERE id.player = player.game AND pseudo = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute([$player]);

		$data = $req->fetch();
		if ($data != null) {
			
			$id = $data['id'];
			$player = $data['player'];
			$date = $data['date'];
			$bet = $data['bet'];
			$profit = $data['profit'];

			$game = new DTO_Game($id,$player,$date,$bet,$profit);
			return $game;
		}
		else
		{
			return null;
		}
	}

	public function valueCard($nombre)
	{
		$cartes = array(
			1 => 1,
			2 => 1,
			3 => 1,
			4 => 1,
			5 => 10,
			6 => 10,
			7 => 10,
			8 => 10,
			9 => 10,
			10 => 10,
			11 => 10,
			12 => 10,
			13 => 10,
			14 => 10,
			15 => 10,
			16 => 10,
			17 => 10,
			18 => 10,
			19 => 10,
			20 => 10,
			21 => 9,
			22 => 9,
			23 => 9,
			24 => 9,
			25 => 8,
			26 => 8,
			27 => 8,
			28 => 8,
			29 => 7,
			30 => 7,
			31 => 7,
			32 => 7,
			33 => 6,
			34 => 6,
			35 => 6,
			36 => 6,
			37 => 5,
			38 => 5,
			39 => 5,
			40 => 5,
			41 => 4,
			42 => 4,
			43 => 4,
			44 => 4,
			45 => 3,
			46 => 3,
			47 => 3,
			48 => 3,
			49 => 2,
			50 => 2,
			51 => 2,
			52 => 2,
		);

		$value = $cartes[$nombre];
		return $value;
	}

	public function verifyCard($carte,$useCards){

		for ($i=0; $i < count($useCards); $i++) { 
			if ($carte == $useCards[$i]) {
				return true;
				exit();
			}
		}
		return false;
	}

	// ajout de la partie 
	public function addGame($id,$bet,$profit){

		$requete = 'INSERT INTO game(player,date,bet,profit) VALUE(?,NOW(),?,?)';
		$req = $this->bdd->prepare($requete);
		$req->execute(array($id,$bet,$profit));

		return true;
	}
	
	//renvoie le nombre de parties d'un joueur
	public function numberGamesByIdLimit($id,$start,$limit){
		$sql = 'SELECT COUNT(id) FROM game WHERE player = ? LIMIT ?,?';
		$req = $this->bdd->prepare($sql);
		$req->execute(array($id,$start,$limit));

		$data = intval($req->fetch()[0]);

		return $data;
	}

	//renvoie le nombre de parties d'un joueur
	public function numberGamesById($id){
		$sql = 'SELECT COUNT(id) FROM game WHERE player = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute(array($id));

		$data = intval($req->fetch()[0]);

		return $data;
	}
}