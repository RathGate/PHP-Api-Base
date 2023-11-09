<?php
require_once "autoload.php";
use security\Credentials;
use database\Database;
$db = new \database\Connection();
$statement = $db->dbh->prepare("INSERT INTO users (username) VALUES (:name)");
echo($statement->execute(["name"=>"Poeut"]));
var_dump($db->dbh->errorInfo());