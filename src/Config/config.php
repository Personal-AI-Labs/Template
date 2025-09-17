<?php

// Require the Composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables from the .env file in the project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();