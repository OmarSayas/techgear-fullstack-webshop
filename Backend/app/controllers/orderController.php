<?php

namespace Controllers;

use Exception;
use Services\OrderService;

class OrderController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new OrderService();
    }

    public function getUserOrders()
    {
        $token = $this->checkForJwt();
        if (!$token) return;
    
        try {
            $orders = $this->service->getOrdersByUserId($token->data->userId);
            $this->respond($orders);
        } catch (Exception $e) {
            $this->respondWithError(500, "Unable to fetch orders: " . $e->getMessage());
        }
    }
    
}
