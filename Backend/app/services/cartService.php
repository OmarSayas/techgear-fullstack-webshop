<?php

namespace Services;

use Exception;
use Repositories\CartRepository;
use Repositories\ProductRepository;

class CartService
{
    private $repository;
    private $productRepository;

    public function __construct()
    {
        $this->repository = new CartRepository();
        $this->productRepository = new ProductRepository();
    }

    public function getCart($userId)
    {
        return $this->repository->getCartByUserId($userId);
    }

    public function addToCart($userId, $productId, $quantity)
    {
        $this->repository->addItem($userId, $productId, $quantity);
    }

    public function removeFromCart($userId, $productId)
    {
        $this->repository->removeItem($userId, $productId);
    }

    public function clearCart($userId)
    {
        $this->repository->clearCart($userId);
    }

    public function cleanupZeroStockItems()
    {
        $this->repository->removeItemsWithZeroStock();
    }

    public function updateQuantity($userId, $productId, $newQuantity) {
        $currentItem = $this->repository->getItem($userId, $productId);
        $product = $this->productRepository->getOne($productId); 
    
        if (!$product || !$currentItem) {
            throw new Exception("Item or product not found");
        }
    
        $stockChange = $currentItem['quantity'] - $newQuantity;
        $newStock = $product->stock + $stockChange;
    
        if ($newStock < 0) {
            throw new Exception("Not enough stock");
        }
    
        $this->repository->updateQuantity($userId, $productId, $newQuantity);
        $this->productRepository->updateStock($productId, $newStock);
    }

    public function checkout($userId) {
        $cartItems = $this->repository->getCartByUserId($userId);
    
        if (empty($cartItems)) return;
    
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['quantity'] * $item['product']['price'];
        }
    
        $this->repository->finalizeCheckout($userId, $total, $cartItems);
    }    
    
}
