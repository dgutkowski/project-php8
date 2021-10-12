<?php
require_once("maincore.php");
require_once(STYLE."body-header.php");

$itemsPerPage = 1;

if(isset($_GET['start']))	$start = (int) $_GET['start'];
else $start = 0;

if(isset($start) && is_int($start))
{ 
	$sql = "SELECT * FROM news WHERE date_start < ? AND date_archive > ? ORDER BY `date_start` DESC LIMIT ?, ?";

	$stmt = $core->getConnection()->prepare($sql);
	$now = $core->getDateTime()->format("Y-m-d H:i:s");

	$stmt->bind_param("ssii", $now, $now, $start, $itemsPerPage);
	$stmt->execute();
	$result = $stmt->get_result();


	while($row = $result->fetch_assoc())
	{
		$oNews[] = new cNews($row['id'],$row['title'],$row['content'],$row['author'],$row['date_start'],$row['allow_comment'],$row['allow_rating']);
	}
	foreach($oNews as $item)
	{
		$item->print(3);
	}
}
require_once(STYLE."body-footer.php");
?>