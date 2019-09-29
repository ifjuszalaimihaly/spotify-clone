<?php
date_default_timezone_set("Europe/Budapest");
$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);
try {
    $db = new PDO('mysql:host=localhost;dbname=slotify;charset=utf8', 'root', 'root1234');
    $db->exec("SET time_zone='$offset';");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}