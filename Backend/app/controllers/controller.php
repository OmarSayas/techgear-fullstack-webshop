<?php

namespace Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Controller
{
    protected function getJwtSecret(): string
    {
        $value = getenv("JWT_SECRET");
        if ($value === false || $value === "") {
            return "banaan";
        }

        return trim($value, " \t\n\r\0\x0B\"'");
    }

    protected function getAuthorizationHeader(): ?string
    {
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            return $_SERVER["HTTP_AUTHORIZATION"];
        }

        if (function_exists("apache_request_headers")) {
            foreach (apache_request_headers() as $header => $value) {
                if (strtolower($header) === "authorization") {
                    return $value;
                }
            }
        }

        return null;
    }

    function checkForJwt()
    {
        $authHeader = $this->getAuthorizationHeader();

        if (!$authHeader) {
            $this->respondWithError(401, "No token provided");
            return null;
        }

        $arr = explode(" ", $authHeader);
        $jwt = $arr[1] ?? "";

        try {
            return JWT::decode($jwt, new Key($this->getJwtSecret(), "HS256"));
        } catch (Exception $e) {
            $this->respondWithError(401, "Invalid or expired token");
            return null;
        }
    }

    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array("errorMessage" => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code($httpcode);
        $json = json_encode($data);
        if ($json === false) {
            echo json_encode(["errorMessage" => "JSON encoding error: " . json_last_error_msg()]);
        } else {
            echo $json;
        }
    }

    function createObjectFromPostedJson($className)
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        $object = new $className();
        if (!is_object($data)) {
            return $object;
        }

        foreach ($data as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            $object->{$key} = $value;
        }

        return $object;
    }

    protected function requireRole(string $requiredRole)
    {
        $decoded = $this->checkForJwt();
        if (!$decoded) {
            exit;
        }

        $userRole = $decoded->data->role ?? null;
        if ($userRole !== $requiredRole) {
            $this->respondWithError(403, "Forbidden: Insufficient permissions");
            exit;
        }
    }
}
