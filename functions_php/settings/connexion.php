<?php

	// a faire eventuellement : tracker debogage avec singleton
	// Singleton: gere les connexions plus proprement.
	final class myPDO{
		// Singleton
		private static $mypdo 		= NULL;
		// Connexion bd
		private  	   $pdo 		= NULL;
		private static $user 		= NULL;
		private static $dsn 		= NULL;
		private static $password 	= NULL;

		private function __construct(){
			if(	is_null(self::$user) ||
				is_null(self::$dsn)  ||
				is_null(self::$password))
				throw new Exception("Construction mypdo impossible: parametres connexions absents");
			//connexion avec la bd
			$this->pdo = new PDO(self::$dsn, self::$user, self::$password);
			//mise en place du mode exception pour les erreurs de type PDO
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function __destruct(){
			// si connexion deja établie ... il faut se deconnecter
			if(!is_null($this->pdo)){
				$this->pdo = NULL;
				self::$mypdo = NULL;
			}
			//deconnexion faite
		}

		//recup le singleton
		public static function getSingletonPDO(){
			if(is_null(self::$mypdo)){
				self::$mypdo = new myPDO();
			}
			return self::$mypdo->pdo;
		}

		public static function setOptionsDataBase($_dsn,$_user,$_password){
			self::$dsn = $_dsn;
			self::$user = $_user;
			self::$password=$_password;
		}

		public function __clone(){
			throw new Exception("Clonage de myPDO interdit!");
		}
	}

	myPDO::setOptionsDataBase('mysql: host=localhost;dbname=agence_immo','root','');

	// requet a faire comme cela
	// myPDO::getSingletonPDO()->query("SELECT * FROM appartement");



