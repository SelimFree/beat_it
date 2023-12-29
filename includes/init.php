<?php
session_start();
if (!isset($_SESSION['authorized'])) {
  $_SESSION['authorized'] = false;
}
$BASE_URL = "/webDevelopment/beat_it/";
try {
  error_reporting(E_ERROR | E_PARSE);
  $connection = new PDO('mysql:host=localhost;dbname=beat_it', "root", "");
} catch (PDOException $e) {
  print_r("Couldnt connect to the database");
}
?>