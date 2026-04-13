<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Order;
use Models\OrderItem;

class OrderRepository extends Repository
{
    public function getOrdersByUserId($userId)
    {
        $stmt = $this->connection->prepare("
            SELECT o.id AS order_id, o.total_price, o.created_at,
                   oi.quantity, oi.price_each,
                   p.name AS product_name, p.image AS product_image
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN product p ON oi.product_id = p.id
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];

        foreach ($rows as $row) {
            $orderId = $row['order_id'];

            if (!isset($orders[$orderId])) {
                $order = new Order();
                $order->id = $orderId;
                $order->user_id = $userId;
                $order->total_price = $row['total_price'];
                $order->created_at = $row['created_at'];
                $order->items = [];
                $orders[$orderId] = $order;
            }

            $item = new OrderItem();
            $item->quantity = $row['quantity'];
            $item->price_each = $row['price_each'];
            $item->product = (object)[
                'name' => $row['product_name'],
                'image' => $row['product_image']
            ];

            $orders[$orderId]->items[] = $item;
        }

        return array_values($orders);
    }
}
