<?php
class LogSystem
{
	private $state;
	private $msg;
	
	public function __construct()
	{
		$this->state = 0;
		$this->msg = array();
	}

	public function userValidate($dbConnection, $sMail, $sPass)
	{
	if(!$dbConnection) { $this->state = 2; $this->msg[] = "Fatal Error"; return false; }
		if(!empty($sMail) && !empty($sPass))
		{
			// Wstepne filtrowanie danych

			$email = filter_var(trim($sMail), FILTER_SANITIZE_EMAIL);
			$pass = filter_var(trim($sPass), FILTER_SANITIZE_STRING);
			
			// Pobieranie rekordu z bazy na podstawie maila
			
			$sql = "SELECT `id`, `pass` FROM users WHERE email LIKE ? LIMIT 1";
			$stmt = $dbConnection->prepare($sql);
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$result = $stmt->get_result();
			if(@$result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				
				if(password_verify($pass, $row['pass']))
				{
					// Weryfikacja zakonczona sukcesem
					
					$this->msg[] = "Zalogowany";
					$this->state = 0;
					return $row['id'];
				}
				else
				{
					// Instrukcje gdy hasla nie sa zgodne
					
					$this->msg[] = "Wprowadzono błędne dane";
					$this->state = 2;
					return false;
				}
			}
		}
		else
		{
			$this->msg[] = "Wprowadz dane w celu zalogowania do systemu";
			$this->state = 1;
			return false;
			
			// Prosba o uzupelnienie danych
		}
	}
	
	public function userLogout()
	{
		
	}

	public function formPrint()
	{
		echo "	<h2>Logowanie</h2>\n";
		
		if(is_int($this->state) && !empty($this->msg))
		{
			switch($this->state){
			case 0:
				$sDivClass = "alert alert-success";
				break;
			case 1:
				$sDivClass = "alert alert-info";
				break;
			case 2:
				$sDivClass = "alert alert-danger";
				break;
			}
			if(!empty($this->msg))
			{
				echo "	<div class=\"".$sDivClass."\">";
				foreach($this->msg as $msg)
				{
					echo "".$msg."<br>";
				}
				echo "</div>\n";
			}
		}
		
		echo "	<form class=\"row g-3\"action=\"\" method=\"post\">\n";
		echo "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"email\">Adres e-mail</label><input class=\"form-control\" name=\"email\" type=\"text\" value=\"".@$_POST['email']."\" placeholder=\"user@domain\"></div>\n";
		echo "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"pass1\">Hasło</label><input class=\"form-control\" name=\"pass\" type=\"password\" value=\"\" placeholder=\"password\"></div>\n";
		echo "		<div class=\"col-md-12 small\">Nie masz konta? <a class=\"text-muted\" href=\"register.php\">Zarejestruj się!</a></div>\n";
		echo "		<div class=\"col-md-12\"><center><input class=\"btn btn-sm btn-light btn-outline-primary w-25 mt-2\" type=\"submit\" value=\"Zaloguj\"></center></div>\n";
		echo "	</form>\n";
		
	}
}
?>