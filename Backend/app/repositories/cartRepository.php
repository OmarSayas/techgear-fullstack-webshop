<?php

namespace Repositories;

use Exception;
use PDO;
use Repositories\Repository;

class CartRepository extends Repository
{
    public function getCartByUserId($userId)
    {
        $stmt = $this->connection->prepare("
        SELECT sci.quantity,
               p.id as product_id,
               p.name, p.price, p.description, p.image, p.stock,
               c.id as category_id, c.name as category_name
        FROM cart sci
        JOIN product p ON sci.product_id = p.id
        JOIN category c ON p.category_id = c.id
        WHERE sci.user_id = :user_id
    ");
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        $cartItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cartItems[] = [
                "product" => [
                    "id" => $row["product_id"],
                    "name" => $row["name"],
                    "price" => $row["price"],
                    "description" => $row["description"],
                    "image" => $row["image"],
                    "stock" => $row["stock"],
                    "category" => [
                        "id" => $row["category_id"],
                        "name" => $row["category_name"],
                    ],
                ],
                "quantity" => $row["quantity"],
            ];
        }

        return $cartItems;
    }

    public function addItem($userId, $productId, $quantity)
    {
        $this->connection->beginTransaction();

        try {
            $stmt = $this->connection->prepare("UPDATE product SET stock = stock - :quantity WHERE id = :productId AND stock >= :quantity");
            $stmt->execute(["quantity" => $quantity, "productId" => $productId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Not enough stock");
            }

            $stmt = $this->connection->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(["user_id" => $userId, "product_id" => $productId]);
            $existing = $stmt->fetch();

            if ($existing) {
                $stmt = $this->connection->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
                $stmt->execute([
                    "user_id" => $userId,
                    "product_id" => $productId,
                    "quantity" => $quantity,
                ]);
            } else {
                $stmt = $this->connection->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
                $stmt->execute([
                    "user_id" => $userId,
                    "product_id" => $productId,
                    "quantity" => $quantity,
                ]);
            }

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function removeItem($userId, $productId)
    {
        $stmt = $this->connection->prepare("SELECT quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(["user_id" => $userId, "product_id" => $productId]);
        $item = $stmt->fetch();

        if ($item) {
            $quantity = $item["quantity"];

            $stmt = $this->connection->prepare("UPDATE product SET stock = stock + :quantity WHERE id = :productId");
            $stmt->execute(["quantity" => $quantity, "productId" => $productId]);

            $stmt = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->execute(["user_id" => $userId, "product_id" => $productId]);
        }
    }

    public function clearCart($userId)
    {
        $this->connection->beginTransaction();

        try {
            $stmt = $this->connection->prepare("SELECT product_id, quantity FROM cart WHERE user_id = :user_id");
            $stmt->execute(["user_id" => $userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $item) {
                $restore = $this->connection->prepare("UPDATE product SET stock = stock + :quantity WHERE id = :product_id");
                $restore->execute([
                    "quantity" => $item["quantity"],
                    "product_id" => $item["product_id"],
                ]);
            }

            $delete = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $delete->execute(["user_id" => $userId]);

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function removeItemsWithZeroStock()
    {
        $stmt = $this->connection->prepare("
            DELETE sci FROM cart sci
            JOIN product p ON sci.product_id = p.id
            WHERE p.stock = 0
        ");
        $stmt->execute();
    }

    public function finalizeCheckout($userId, $total, array $cartItems)
    {
        $this->connection->beginTransaction();

        try {
            $orderId = $this->createOrder($userId, $total);

            foreach ($cartItems as $item) {
                $this->insertOrderItem(
                    $orderId,
                    $item["product"]["id"],
                    $item["quantity"],
                    $item["product"]["price"]
                );
            }

            $stmt = $this->connection->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt->execute(["user_id" => $userId]);

            $this->connection->commit();

            return $orderId;
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function updateQuantity($userId, $productId, $quantity)
    {
        $stmt = $this->connection->prepare("
            UPDATE cart
            SET quantity = :quantity
            WHERE user_id = :user_id AND product_id = :product_id
        ");
        $stmt->execute([
            "user_id" => $userId,
            "product_id" => $productId,
            "quantity" => $quantity,
        ]);
    }

    public function getItem($userId, $productId)
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM cart
            WHERE user_id = :user_id AND product_id = :product_id
        ");
        $stmt->execute(["user_id" => $userId, "product_id" => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrder($userId, $total)
    {
        $stmt = $this->connection->prepare("
            INSERT INTO orders (user_id, total_price)
            VALUES (:user_id, :total)
        ");
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":total", $total);
        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    public function insertOrderItem($orderId, $productId, $quantity, $price)
    {
        $stmt = $this->connection->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price_each)
            VALUES (:order_id, :product_id, :quantity, :price_each)
        ");
        return $stmt->execute([
            "order_id" => $orderId,
            "product_id" => $productId,
            "quantity" => $quantity,
            "price_each" => $price,
        ]);
    }
}
