<?php

/**
 * 
 */
abstract class DAO
{
	
	protected $bdd;

	public function __construct()
	{
		try{
			$this->bdd = new PDO('mysql:host=localhost;dbname=blackjack','root','');
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}