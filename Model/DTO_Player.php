<?php

class DTO_Player
{
	private $id;
	private $pseudo;
	private $password;
	private $money;

	public function __construct($i,$p,$pss,$m)
	{
		$this->id = $i;
		$this->pseudo = $p;
		$this->password = $pss;
		$this->money = $m;
	}

	//getter
	public function getId(){ return $this->id; }
	public function getPseudo(){ return $this->pseudo; }
	public function getPassword(){ return $this->password; }
	public function getMoney(){ return $this->money; }

	//setter
	public function setId($i){ $this->id = $i; }
	public function setPseudo($p){ $this->pseudo = $p; }
	public function setPassword($pss){ $this->password = $pss; }
	public function setMoney($m){ $this->money = $money; }
}