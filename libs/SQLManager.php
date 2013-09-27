<?php
/*********************************************************************************************************************************************************

Nom du fichier : SQLManager.php
Auteur : Flauder Vincent
Version : 1.0
Description :Fichier de la classe SQLManager permettant de cr�e un objet pour �tre en relation avec une base de donn�es
		MySQL. Poss�de diverses fonctions facilitant la programmation SQL et la gestion de requ�tes. 

*********************************************************************************************************************************************************/

// Classe SQLManager
class SQLManager
{
	private $user; //Variable stockant le nom d'utilisateur pour l'acces a la base de donn�es.
	private $password; //Variable stockant le mot de passe pour l'acces a la base de donn�es.
	private $server; //Variable stockant le nom du serveur auquel se connecter.
	private $db; //Identifiant de la BDD
	private $dbname; //Nom de la BDD � laquelle se connecter.	

/*********************************************************************************************************************************************************
METHODES PUBLIQUES
*********************************************************************************************************************************************************/
	
	//Constructeur de la classe avec 4 parametre ( nom du serveur, nom d'utilisateur, mot de passe, base de donn�es)
	public function __construct( $serverarg, $userarg, $passwordarg, $dbnamearg )
	{
		$this->user = $userarg;
		$this->password = $passwordarg;
		$this->server = $serverarg;		
		$this->dbname = $dbnamearg;

		$this->connect( $this->dbname );
	}
	
	//Destructeur qui lors de son appel fermera la conection � la BDD.
	public function __destruct()
	{
		@mysql_close( $this->db );
	}
		
	//M�thode permettant d'interpreter une requete et de renvoyer un tableau associatif avec les r�sultats.
	public function query( $query )
	{
		$result = mysql_query( $query, $this->db );
		
		if ( !isset( $result ) )
		{
			die('Probleme Interne, contactez le webmaster');
		}
		
		$i=0;
		while ( $row = mysql_fetch_array( $result ) )
		{
			$tabend[$i++] = $row;
		}
		
		if ( isset($tabend) )//S�curit� avant le renvoi pour �viter les erreurs.
		{
			return $tabend;
		}
	}
	
	/** 
	 * M�thode pour effectuer une requ�te SELECT , prenant 7 arguments
	 * (1) column : champs � extraire
	 * (2) table : table s�lectionn�e
	 * (3) condition : 0 ou 1 pour inclure une clause WHERE dans la requ�te
	 * (4) champ � comparer
	 * (5) op�rateur de comparaison
	 * (6) donn�e � comparer
	 * (7) options pour ajouter une instruction en fin de requ�te
	 *Exemple : selectQuery( 'Login', 'user', 1, 'Password', '=', $_POST['password'] )
	 *Exemple : selectQuery('Name', 'user', 0, '', '', '', 'ORDER BY ASC') Selectionne tous les noms et les range dans l'ordre croissant 
	 **/
	public function selectQuery( $colum, $table, $condition, $columnToCompare, $operator, $comparator, $option)
	{
		$querymaker = 'SELECT '.$colum.' FROM '.$table.' ';
		
		if ( $condition != 0 )
		{
			$querymaker .= 'WHERE '.$columnToCompare.' '.$operator.' \''.$comparator.'\' ';
		}
		
		//Rajout de l'option
		$querymaker .= $option;
		
		return ( $this->query( $querymaker, $this->db ) );
	}
	
	/* M�thode pour ins�rer une nouvelle ligne dans la base de donn�es, prend 3 arguments :
	(1)table : table ou l'on ins�re la ligne.
	(2)tabcol : tableau dans lequel les noms des champs sont rang�s.
	(3)tabval : tableau dans lequel les donn�es des champs sont rang�s.
	Tabcol et Tabval doivent poss�der le m�me index de rangement pour les donn�es associ�es aux champs.
	Exemple : 
	$fields[0] = 'Login';
	$values[0] = 'loginTest';
	$fields[1] = 'Password';
	$values[1] = $_POST['password'];
	$sql->insertQuery( 'user', $fields, $values ); 
	Ins�rera un nouvel utilisateur dans la base de donn�es.*/	
	public function insertQuery( $table, $tabcol, $tabval )
	{
		if ( count($tabcol) != count($tabval) )
		{
			die('Probleme d\'insertion, contactez le webmaster.');
		}
		$queryinsert = 'INSERT INTO `'.$table.'`  ( ';
		$queryinsert .= $this->chainmaker( $tabcol , 1 );
		$queryinsert .= ' ) VALUES ( ';
		$queryinsert .= $this->chainmaker( $tabval , 0 );
		$queryinsert .= ' )';
		
		if (  !( mysql_query( $queryinsert, $this->db ) ) )
		{
			die('Probleme interne insert, veuillez contactez le webmaster.');
		}		
	}
	
	/* M�thode permettant de mettre � jour une ligne dans une table, prend 6 arguments :
	(1)table : table s�lectionn�e.
	(2)fields : tableau contenant les nom des champs.
	(3)values : tableau respectif � (2)fields pour les donn�es des champs.
	(4)colomnToCompare : champs ou l'on cherche � modifier la ligne.
	(5)operator : op�rateur de comparaison.
	(6)comparator : donn�e � comparer pour la recherche.
	Exemple :
	$fields[0] = 'Password';
	$values[0] = $_POST['password'];
	$sql->updateQuery( 'user', $fields, $values, 'Login', '=', 'loginTest');
	Ceci change le mot de passe de l'utilisateur qui � le Login "loginTest" */
	public function updateQuery( $table, $fields, $values, $colomnToCompare, $operator, $comparator )
	{
		if ( count($fields) != count($values) )
		{
			die('Probleme d\'insertion, contactez le webmaster.');
		}
		
		$queryupdate = 'UPDATE `'.$table.'` SET ';
		
		for ( $i=0; $i<count($fields); $i++ )
		{
			if ( $i==(count($fields)-1) )
			{
				$queryupdate .= '`'.$fields[$i].'`='.( (strpos($values[$i], '()')===FALSE) ? "'$values[$i]'" : $values[$i] ); //Fin de cha�ne...
			}
			else
			{
				$queryupdate .= '`'.$fields[$i].'`='.( (strpos($values[$i], '()')===FALSE) ? "'$values[$i]'" : $values[$i] ).',';//...sinon on rajoute une virgule.
			}
		}
		
		$queryupdate .= ' WHERE `'.$colomnToCompare.'`'.$operator.'\''.$comparator.'\'';		
		
		if ( !( mysql_query( $queryupdate, $this->db ) ) )
		{
			die('Probl�me interne, veuillez contactez le webmaster.');
		}
	}
	
	/*M�thode pour supprimer un enregistrement dans la base de donn�e, prend 4 arguments
	(1) : le nom de la table ou l'on va supprimer
	(2) : le nom du champ pour la condition
	(3) : op�rateur de comparaison
	(4) : valeur du champ de comparaison
	Exemple : deleteQuery( 'user', 'login', '=', $login );*/
	public function deleteQuery( $table, $columnToCompare, $operator, $comparator )
	{
		$deletequery = 'DELETE FROM `'.$table.'` WHERE '.$columnToCompare.$operator.'\''.$comparator.'\'';
		
		if ( !( mysql_query( $deletequery, $this->db ) ) )
		{
			die('Probl�me interne de requ�te, veuillez contactez le webmaster.');
		}		
	}
	
	/* M�thode pour changer la base de donn�es sur laquelle on doit travailler. */
	public function changeDB( $newdb )
	{
		$this->dbname = $newdb;
		// Selection de la BDD.
		if ( !mysql_select_db( $this->dbname, $this->db ) )
		{
			die('Impossible de se connecter � la base de donn�es.');
		}			
	}
	
/*******************************************************************************************************************************************************************
METHODES PRIVEE
******************************************************************************************************************************************************************/
	
	//M�thode pour se connecter au serveur ainsi qu'a la BDD dont le nom est pass� en param�tre.
	private function connect( $dbnamearg )
	{
		// Connection
		if ( !( $this->db = mysql_connect( $this->server, $this->user, $this->password ) ) )
		{
			die('Impossible de se connecter au serveur.');
		}
		else
		{
			// Selection de la BDD.
			if ( !mysql_select_db( $this->dbname, $this->db ) )
			{
				die('Impossible de se connecter � la base de donn�es.');
			}			
		}
	}
	
	// M�thode servant � cr�e une cha�ne de caractere � partir d'un tableau de valeurs, renvoi la cha�ne ainsi cr�e
	//L'option est � 1 lorsque l'ont souhaite rajouter des guillemets pour les variables avant insertion, sinon � 0.
	private function chainmaker( $tabval , $option)
	{
		$chain = ''; //Initialisation
		
		for ( $i=0; $i < count($tabval); $i++ )
		{										
			if ( $option == 0 )
			{
				if ( strpos($tabval[$i], '()') === FALSE ) //On test l'existence de parenthese car en cas de fonction SQL il ne faut pas rajouter de guillemet.
				{
					$chain .= '\''.$tabval[$i].'\''; //On rajoute les guillemets n�cessaires dans une requ�te SQL.
				}
				else
				{
					$chain .= $tabval[$i]; //Pas de guillemet.
				}
			}
			else
			{
				$chain .= $tabval[$i];//Rajout simple dans la chaine, pour les nom de tables et champs.
			}
			
			if ( $i < ( count($tabval) - 1 ) ) //Si c'est la derni�re valeur � ajout�e, on ne met pas de virgule.
			{
				$chain .= ', ';
			}
		}
		return $chain;		
	}
		
		
	
}
?>