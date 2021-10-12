<?php
require_once("maincore.php");
require_once(STYLE."body-header.php");

echo "		<h2>Polityka prywatności</h2>\n";

echo $core->getSet('policy');

require_once(STYLE."body-footer.php");
?>