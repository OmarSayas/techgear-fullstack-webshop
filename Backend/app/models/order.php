<?php

namespace Models;

class Order {
    public int $id;
    public int $user_id;
    public float $total_price;
    public string $created_at;
    public array $items = [];
}
