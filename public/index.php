<?php
session_start();
require '../config/database.php';
require '../app/controllers/AuthController.php';
require '../app/models/Usuario.php';

$controller = new AuthController();
$controller->login();
