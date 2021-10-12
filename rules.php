<?php
require_once("maincore.php");
require_once(STYLE."body-header.php");

echo "		<h2>Regulamin</h2>\n";

echo $core->getSet('rules');

require_once(STYLE."body-footer.php");
?>