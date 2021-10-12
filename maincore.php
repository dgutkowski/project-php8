<?php

session_start();

if(preg_match("/maincore.php/i",$_SERVER["PHP_SELF"])) die("500");
foreach(glob("class/c.*.php") as $file)
{
	require_once $file;
}

class Core
{
	private $bState;
	private $tSettings;
	private $sDir;
	private $oDateTime;
	private $oConnection;
	
	private static $iInstance = 0;
	
	public function __construct()
	{		
		if(self::$iInstance === 0) self::$iInstance = 1;
		else die("Core init error");
		
		define("STYLE","style/");
		define("LVL_USR", 1);
		define("LVL_ADM", 2);
		
		$this->sDir = "";
		$i = 0;
		while(!file_exists($this->sDir."config.php"))
		{
			$this->sDir.="../"; $i++;
			if($i == 3) exit("000");
		}
		
		require_once($this->sDir."config.php");		
		
		$this->oConnection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		
		$this->setSet();
		
		$this->oDateTime = new DateTime("now", new DateTimeZone($this->getSet("timezone")));
	}
	
	public function setSet()
	{
		$q = "SELECT * FROM ".DBPREFIX."settings WHERE id = 1 LIMIT 1";
		$result = $this->oConnection->query($q);
		if($result)
		{
			if($result->num_rows == 1)
			{
				$this->tSettings = $result->fetch_assoc();
			}
		}
	}
	
	// Zarządzanie sesją
	
	public function setSession($userId)
	{
		$sql = "SELECT `id`, `login`, `email` FROM users WHERE id = ?";
		$stmt = $this->oConnection->prepare($sql);
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		if($result->num_rows == 1)
		{
			$row = $result->fetch_assoc();
			$_SESSION['active'] = true;
			$_SESSION['userid'] = $row['id'];
			$_SESSION['login'] = $row['login'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			
			return $row['id'];
		}
		else return false;
	}
	public function endSession()
	{
		$_SESSION = array();
		session_unset();
		session_destroy();
	}
	public function checkSession()
	{
		if(!empty($_SESSION))
		{
			if($_SESSION['active'])
			{
				return $_SESSION['userid'];
			}
		}
		else return false;
	}
	public function validateSession()
	{
		if(!empty($_SESSION))
		{
			if($_SESSION['active'] && is_int($_SESSION['userid']))
			{
				// Aktualizacja danych na temat ostatniego polaczenia
				
				$sql = "UPDATE `users` SET `last_date` = NOW(), `last_ip` = ? WHERE `id` = ? LIMIT 1";
				$stmt = $this->oConnection->prepare($sql);
				$stmt->bind_param("si", $_SERVER['REMOTE_ADDR'], $_SESSION['userid']);
				$stmt->execute();
			}
		}
		else return false;
	}
	public function getUserVar($userId, $var)
	{
		if(!empty($var) && is_int($userId))
		{
			$sql = "SELECT * FROM users WHERE id = ?";
			$stmt = $this->oConnection->prepare($sql);
			$stmt->bind_param("i", $userId);
			$stmt->execute();
			
			$result = $stmt->get_result();
			if($result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				return $row[$var];
			}
			else return false;
		}
		else return false;
	}
	
	// Gettery
	
	public function getSet($string)
	{
		if(!empty($this->tSettings[$string])) return $this->tSettings[$string];
		else return false;
	}
	public function getDateTime()
	{
		return $this->oDateTime;
	}
	public function getConnection()
	{
		return $this->oConnection;
	}
	
	// Inne
	
	public function dbQuery($sql)
	{
		return $this->oConnection->query($sql);
	}
	public function redirect($location = false)
	{
		$location = $location ? str_replace("&amp;", "&", $location) : $this->getSet('mainpage');
		
		header("location: ".$location);
	}
}

$core = new Core();
$core->validateSession();

?>