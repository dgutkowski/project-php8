<!doctype html>
<html>
<head>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="style/style.css" rel="stylesheet">
	<title><?php echo $core->getSet("title"); ?></title>
	<meta name="author" content="<?php echo $core->getSet("auth"); ?>">
	<meta name="keywords" content="<?php echo $core->getSet("keys"); ?>">
	<meta name="description" content="<?php echo $core->getSet("desc"); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="js/check.js"></script>
	<!--<script type="text/javascript" src="js/slider.js"></script>-->
</head>
<body onload="changeImg()">
	<div class='container'>
		<div class='row pt-2 pb-1'>
			<header class='col-12 bg-pr text-dark text-center'>
				<h1 class='title-header'><?php echo $core->getSet("header_text_main"); ?></h1>
				<p><?php echo $core->getSet("header_text_sub"); ?></p>
			</header>
		</div>
		<div class='row'>
			<nav class='navbar navbar-expand-sm navbar-light p-0 justify-content-md-center'>
<?php

$sql = "SELECT `name`, `url`, `special` FROM `nav` WHERE `order` > 0 ORDER BY `order` ASC";
$result = $core->getConnection()->query($sql);

if($result)
{
	if($result->num_rows > 0)
	{
		echo "				<ul class='navbar-nav'>\n";
		while($row = $result->fetch_assoc())
		{
			echo "					<li class='nav-item'><a class='nav-link' href='".$row['url']."'>".$row['name']."</a></li>\n";
		}
		// link for log system
		
		if($core->getSet("log_system"))
		{
			if(is_int($core->checkSession()))
			{
				if($core->getUserVar($core->checkSession(),"level") === LVL_ADM) echo "					<li class='nav-item'><a class='nav-link' href='administration.php'>Administracja</a></li>\n";
				echo "					<li class='nav-item'><a class='nav-link' href='profile.php'>Profil</a></li>\n";
				echo "					<li class='nav-item'><a class='nav-link' href='login.php?logout=1'>Wyloguj</a></li>\n";
			}
			else
			{
				echo "					<li class='nav-item'><a class='nav-link' href='login.php'>Zaloguj</a></li>\n";
			}
		}
		else
		{
			// system off
		}
		
		echo "				</ul>\n";
	}
}
else 
{
	// Dodaj log do bazy danych o bledzie
}

?>
			</nav>
		</div>
	</div>
	<main class='container mt-4 pt-2 pb-2'>
