 <?php 
session_start();

include "env.php";

include "lib/enginetpl.php";

$section = !empty($_GET["section"]) ? file_exists("controllers/".$_GET["section"]."Controller.php") ? $_GET["section"] : "error404" : "landing";

include "controllers/{$section}Controller.php";


$tpl->printToScreen();

?>