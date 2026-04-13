<?php
namespace Services;

use Repositories\OrderRepository;

class OrderService
{
    private $repository;

    function __construct()
    {
        $this->repository = new OrderRepository();
    }

    public function getOrdersByUserId($userId) {
        return $this->repository->getOrdersByUserId($userId);
    }    
}