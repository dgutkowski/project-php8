function check_pass_len()
{
	var len = document.getElementById("pass1").value.lenght;
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

function check_login()
{
	var len = document.getElementById("login").value.length;
	if(len>0 && len<4) document.getElementById("js-msg").innerHTML="<div class=\"alert alert-info\">Login musi mieć co najmniej 4 znaki.</div>";
	else document.getElementById("js-msg").innerHTML="";
}