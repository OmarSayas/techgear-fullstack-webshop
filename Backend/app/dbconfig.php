<?php

if (!function_exists("readEnvValue")) {
    function readEnvValue(string $key, string $default): string
    {
        $value = getenv($key);
        if ($value === false || $value === "") {
            return $default;
        }

        return trim($value, " \t\n\r\0\x0B\"'");
    }
}

$type = readEnvValue("DB_TYPE", "mysql");
$servername = readEnvValue("DB_HOST", readEnvValue("MYSQLHOST", "mysql"));
$port = readEnvValue("DB_PORT", readEnvValue("MYSQLPORT", "3306"));
$username = readEnvValue("DB_USERNAME", readEnvValue("MYSQLUSER", "root"));
$password = readEnvValue("DB_PASSWORD", readEnvValue("MYSQLPASSWORD", "secret123"));
$database = readEnvValue("DB_DATABASE", readEnvValue("MYSQLDATABASE", "developmentdb"));
