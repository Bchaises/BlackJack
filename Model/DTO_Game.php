<?php

class DTO_Game
{
	private $id;
	private $player;
	private $date;
	private $bet;
	private $profit;

	public function __construct($i,$p,$d,$b,$pf)
	{
		$this->id = $i;
		$this->player = $p;
		$this->date = $d;
		$this->bet = $b;
		$this->profit = $pf;
	}

	//getter
	public function getId(){ return $this->id; }
	public function getPlayer(){ return $this->player; }
	public function getDate(){ return $this->date; }
	public function getBet(){ return $this->bet; }
	public function getProfit(){ return $this->profit; }

	//setter
	public function setId($i){ $this->id = $i; }
	public function setPlayer($p){ $this->player = $p; }
	public function setDate($d){ $this->date = $d; }
	public function setBet($b){ $this->bet = $b; }
	public function setProfit($pf){ $this->profit = $pf; }
}