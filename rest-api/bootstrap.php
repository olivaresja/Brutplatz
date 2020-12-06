<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

$dbConnection = (new \Src\System\DatabaseConnector())->getConnection()
?>