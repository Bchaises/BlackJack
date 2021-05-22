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
			$this->bdd = new PDO('mysql:host=systemeblackjack.mysql.db;dbname=systemeblackjack','systemeblackjack','SR7ienacfNGfM34');
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}