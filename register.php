<?php
require_once("maincore.php");
require_once(STYLE."body-header.php");

echo "<div class=\"row\">\n";
echo "<div class=\"col-3\"></div>\n";
echo "<div class=\"col-6\">\n";

$oRegister = new Register();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if($oRegister->newUserValidate($core->getConnection(), $_POST['login'], $_POST['email'], $_POST['pass1'], $_POST['pass2'], $_POST['rules']))
	{
		$oRegister->newUserAdd($core->getConnection(), $core->getSet("timezone"));
	}
}
$oRegister->formPrint();

echo "</div>\n";
echo "<div class=\"col-3\"></div>\n";
echo "</div>\n";

require_once(STYLE."body-footer.php");
?>