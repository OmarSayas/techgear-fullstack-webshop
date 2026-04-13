<?php

namespace Models;

class OrderItem {
    public int $id;
    public int $order_id;
    public int $product_id;
    public int $quantity;
    public float $price_each;

    public object $product;
}
