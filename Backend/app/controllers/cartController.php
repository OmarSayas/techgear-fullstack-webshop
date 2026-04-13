<?php

namespace Controllers;

use Services\CartService;

class CartController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new CartService();
    }

    public function getCart()
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $cart = $this->service->getCart($token->data->userId);
        $this->respond($cart);
    }

    public function addToCart()
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $body = $this->createObjectFromPostedJson("Models\\CartItem");
        $this->service->addToCart($token->data->userId, $body->product_id, $body->quantity);
        $this->respond(["message" => "Item added to cart"]);
    }

    public function removeFromCart($productId)
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $this->service->removeFromCart($token->data->userId, $productId);
        $this->respond(["message" => "Item removed from cart"]);
    }

    public function clearCart()
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $this->service->clearCart($token->data->userId);
        $this->respond(["message" => "Cart cleared"]);
    }

    public function cleanupZeroStock()
    {
        $this->service->cleanupZeroStockItems();
        $this->respond(["message" => "Removed items with zero stock"]);
    }

    public function checkout()
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $this->service->checkout($token->data->userId);
        $this->respond(["message" => "Checkout successful"]);
    }

    public function updateQuantity()
    {
        $token = $this->checkForJwt();
        if (!$token) return;

        $body = $this->createObjectFromPostedJson("Models\\CartItem");

        $this->service->updateQuantity($token->data->userId, $body->product_id, $body->quantity);
        $this->respond(["message" => "Quantity updated"]);
    }
}
