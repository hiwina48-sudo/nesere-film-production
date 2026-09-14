<?php
require_once __DIR__ . "/config/database.php";

if (isset($_GET["lang"]) && in_array($_GET["lang"], ["en", "am"])) {
    $_SESSION["lang"] = $_GET["lang"];
}

$redirect = $_SERVER["HTTP_REFERER"] ?? "index.php";
header("Location: " . $redirect);
exit;
?>