<?php

class Register
{
	private $msg;
	private $state;
	
	private $login;
	private $email;
	private $hpass;
	
	public function __construct()
	{
		$this->state = 0;
		$this->msg = array();
	}
	
	public function newUserValidate($dbConnection, $sLogin, $sMail, $sPass1, $sPass2, $bCheck)
	{
		// status rejestracji 0 - sukces, 1 - notka, 2 - błąd
		
		if(!empty($sLogin) && !empty($sMail) && !empty($sPass1) && !empty($sPass2) && $bCheck)
		{
			$login = filter_var(trim($sLogin), FILTER_SANITIZE_STRING);
			$email = filter_var(trim($sMail), FILTER_SANITIZE_EMAIL);
			$pass1 = filter_var(trim($sPass1), FILTER_SANITIZE_STRING);
			$pass2 = filter_var(trim($sPass2), FILTER_SANITIZE_STRING);
			
			if(strlen($login) < 4) $this->msg[] = "Login musi mieć co najmniej 4 znaki.";
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->msg[] = "Adres email musi mieć odpowiedni format.";
			if(strlen($pass1) < 8) $this->msg[] = "Hasło musi mieć co najmniej 8 znaków.";
			if($pass1 != $pass2) $this->msg[] = "Podane hasło musi zostać potwierdzone.";
			
			if(!empty($this->msg)) { $this->state = 1; return false;	}
			else
			{
				$this->login = $login;
				$this->email = $email;
				$this->hpass = password_hash($pass1, PASSWORD_DEFAULT);
			}
		}
		else
		{
			$this->msg[] = "Uzupełnij wymagane pola!";
			$this->state = 2;

			if(empty($_POST['rules'])) $this->msg[] = "Akceptacja regulaminu i polityki prywatności jest obowiązkowa!";
		}
		
		$sql = "SELECT id FROM users WHERE login LIKE ? LIMIT 1";
		$stmt = $dbConnection->prepare($sql);	$stmt->bind_param("s", $this->login);	$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows > 0)
		{
			$this->msg[] = "Podany login jest już zajęty."; $this->state = 1;
		}
		$sql = "SELECT id FROM users WHERE email LIKE ? LIMIT 1";
		$stmt = $dbConnection->prepare($sql);	$stmt->bind_param("s", $this->email);	$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows > 0)
		{
			$this->msg[] = "Podany adres e-mail jest już zajęty."; $this->state = 1;
		}
		
		if(empty($this->msg))
		{
			// Wiadomość przykryta w metodzie newUserAdd
			
			$this->msg[] = "Dane zostały wprowadzone poprawnie.";
			return true;
		}
		else return false;
	}
	
	public function newUserAdd($dbConnection = null, $timeZone = null)
	{

		// Sprawdzenie zmiennych
		
		if($dbConnection == null) { $this->msg[] = "Wystąpił nieznany błąd. Skontaktuj się z administratorem"; $this->state=2; return false; }
		if($timeZone == null) { $timeZone = "Europe/London"; }
		
		// Sprawdzenie stanu obiektu
		
		if($this->state != 0) return false;
		
		// Inicjalizacja
		
		$now = new DateTime(null, new DateTimeZone($timeZone));
		$date = $now->format("Y-m-d H:i:s");
		
		// Instrukcja SQL
		
		$sql = "INSERT INTO users (`login`, `email`, `pass`, `level`, `rights`, `reg_date`, `reg_ip`, `timezone`) VALUES ";
		$sql.= "(?, ?, ?, 0, '', ?, ?, ?)";
		$stmt = $dbConnection->prepare($sql);
	
		$stmt->bind_param("ssssss", $this->login, $this->email, $this->hpass, $date, $_SERVER['REMOTE_ADDR'], $timezone);
		$stmt->execute();
		
		// Komunikat
		$this->msg = array();
		$this->msg[] = "Zostałeś zarejestrowany. Teraz możesz się zalogować";
	}

	public function formPrint()
	{
		echo "	<h2>Rejestracja</h2>\n";
		
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
		echo "	<div id=\"js-msg\"></div>\n";
		echo "	<form class=\"row g-3\"action=\"\" method=\"post\">\n";
		echo "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"login\">Login</label><input id=\"login\" onfocusout=\"check_login()\" class=\"form-control\" name=\"login\" type=\"text\" value=\"".@$_POST['login']."\" placeholder=\"login\" aria-required=\"true\"></div>\n";
		echo "		<div class=\"col-md-12\"><label class=\"form-label\" for=\"email\">Adres e-mail</label><input id=\"email\" onfocusout=\"check_mail()\" class=\"form-control\" name=\"email\" type=\"text\" value=\"".@$_POST['email']."\" placeholder=\"user@domain\" aria-required=\"true\"></div>\n";
		echo "		<div class=\"col-md-6\"><label class=\"form-label\" for=\"pass1\">Hasło</label><input id=\"pass1\" onfocusout=\"check_pass_len()\" class=\"form-control\" name=\"pass1\" type=\"password\" value=\"\" placeholder=\"\" aria-required=\"true\"></div>\n";
		echo "		<div class=\"col-md-6\"><label class=\"form-label\" for=\"pass2\">Powtórz hasło</label><input id=\"pass2\" onfocusout=\"check_pass()\" class=\"form-control\" name=\"pass2\" type='password' value='' placeholder=\"\" aria-required=\"true\"></div>\n";
		echo "		<div class=\"col-md-12\"><div class=\"form-check\"><input class=\"form-check-input\" type=\"checkbox\" name=\"rules\" value=\"1\"><label class=\"form-check-label\">Akceptuję <a class=\"text-muted\" href=\"rules.php\">regulamin serwisu</a> oraz <a class=\"text-muted\" href=\"policy.php\">politykę prywatności</a></label></div></div>\n";
		echo "		<div class=\"col-md-12\"><center><input class=\"btn btn-sm btn-light btn-outline-primary w-25 mt-2\" type=\"submit\" value=\"Wyślij\"></center></div>\n";
		echo "	</form>\n";
	}
}

?>