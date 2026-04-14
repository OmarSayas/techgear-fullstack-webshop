<?php
$type = getenv("DB_TYPE") ?: "mysql";
$servername = getenv("DB_HOST") ?: "mysql";
$port = getenv("DB_PORT") ?: "3306";
$username = getenv("DB_USERNAME") ?: "root";
$password = getenv("DB_PASSWORD") ?: "secret123";
$database = getenv("DB_DATABASE") ?: "developmentdb";
