/*
function check_pass_len()
{
	var len = document.getElementById("pass1").value.length;
	if(len < 8)
	{
		document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Hasło musi mieć co najmniej 8 znaków. Powinno też zawierać przynajmniej jedną cyfrę i znak specjalny.</div>";
	}
}

function check_pass()
{
	var pass1 = document.getElementById("pass1").value;
	var pass2 = document.getElementById("pass2").value;
	if(pass1 != pass2)
	{
		document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Hasła nie są zgodne.</div>";
	}
}
*/
function check_login()
{
	var len = document.getElementById("login").value.length;
	if(len>0 && len<4) document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Login musi mieć co najmniej 4 znaki.</div>";
	else document.getElementById("js-msg").innerHTML="";
}

function check_pass()
{
	// return true jesli weryfikacja ciagu jest poprawna
	// obecnie sprawdza tylko liczbe znakow w zmiennej
	
	var pass1 = document.getElementById("pass1").value;
	var pass2 = document.getElementById("pass2").value;
	var len1 = pass1.length;
	var len2 = pass2.length;
	
	if((len1>0 && len1<8) || (len2>0 && len2<8))
	{
		// pole z haslem zostalo wypelnione ciagiem znakow
		if(len1>=8 && len2>=8)
		{
			// hasla maja odpowiednia dlugosc
			if(pass1 == pass2)
			{
				// hasla zgodne - brak komunikatu
				document.getElementById("js-msg").innerHTML="";
				return true;
			}
			else
			{
				// hasla nie sa zgodne
				document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Hasła nie są zgodne.</div>";
				return false;
			}
		}
		else
		{
			// haslo jest za krotkie
			document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Hasło musi mieć co najmniej 8 znaków. Powinno też zawierać przynajmniej jedną cyfrę i znak specjalny.</div>";
			return false;
		}
	}
	else
	{
		document.getElementById("js-msg").innerHTML="";
		return false;
	}
}
