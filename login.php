<?php
require_once("maincore.php");

$oLogin = new logSystem();

if($_SERVER['REQUEST_METHOD'] == 'GET' && @$_GET['logout'])
{
	$core->endSession();
	$core->redirect("login.php");
}
elseif($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$core->setSession(($oLogin->userValidate($core->getConnection(), $_POST['email'], $_POST['pass'])));
}
if($core->checkSession())
{
	$core->redirect();
}

require_once(STYLE."body-header.php");

echo "<div class=\"row\">\n";
echo "<div class=\"col-4\"></div>\n";
echo "<div class=\"col-4\">\n";

$oLogin->formPrint();

echo "</div>\n";
echo "<div class=\"col-4\"></div>\n";
echo "</div>\n";

require_once(STYLE."body-footer.php");
?>