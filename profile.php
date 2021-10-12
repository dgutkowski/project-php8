<?php
require_once("maincore.php");

if(!$core->checkSession())
{
	$core->redirect();
}

$form = new genForm("Dane użytkownika",2);

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$name		= trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
	$location	= trim(filter_var($_POST['location'], FILTER_SANITIZE_STRING));
	$timezone	= trim(filter_var($_POST['timezone'], FILTER_SANITIZE_NUMBER_INT));
	$userid		= $core->checkSession();
	
	$sql = "SELECT `code` FROM timezones WHERE `id` = ? LIMIT 1";
	$stmt = $core->getConnection()->prepare($sql);
	$stmt->bind_param("i", $timezone);
	$stmt->execute();
	
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	
	$sTimezone	= $row['code'];
	
	$sql = "UPDATE `users` SET ";
	$sql.= "`name` = ?,";
	$sql.= "`location` = ?,";
	$sql.= "`timezone` = ? ";
	$sql.= "WHERE `id` = ? LIMIT 1";
	
	$var1 = "imie";
	$var2 = "nazw";
	$var3 = "loka";
	$id = 1;
	
	$stmt = $core->getConnection()->prepare($sql);
	$stmt->bind_param("sssi", $name, $location, $sTimezone, $userid);
	$stmt->execute();
	if($stmt->affected_rows == 1)
	{
		$form->setState(1);
		$form->setMsg("Dane zostały zaaktualizowane");
	}
}

require_once(STYLE."body-header.php");

$sql = "SELECT * FROM timezones ORDER BY name ASC";
$result = $core->getConnection()->query($sql);
while($row = $result->fetch_assoc())
{
	$names[] = $row['name'];
	$values[] = $row['id'];
	if($row['code'] == $core->getUserVar($core->checkSession(),"timezone"))
	{
		$selected = $row['id'];
	}
}

echo "<div class=\"row\">\n";
echo "<div class=\"col-3\"></div>\n";
echo "<div class=\"col-6\">\n";

$form->addInput("Imię", "name", $core->getUserVar($core->CheckSession(),"name"));
$form->addInput("Miejscowość", "location", $core->getUserVar($core->CheckSession(),"location"));
$form->addSelect("Strefa czasowa", "timezone", $names, $values, $selected);
$form->addSubmit("Aktualizuj");

$form->printForm();

echo "</div>\n";
echo "<div class=\"col-3\"></div>\n";
echo "</div>\n";

require_once(STYLE."body-footer.php");
?>