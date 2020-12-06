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

	// fonction pour obtenir les infos d'une partie par son id
	public function getById($id)
	{
		$sql = 'SELECT * FROM game WHERE id = ?';
		$req = $this->bdd->prepare($sql);
		$req->execute([$id]);

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

	// fonction pour obtenir les infos d'une partie grâce à l'di du player
	public function getByPlayer($player)
	{
		$sql = 'SELECT * FROM game WHERE player = ?';
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
}