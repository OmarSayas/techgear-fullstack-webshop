<?php

namespace Controllers;

use Exception;
use Services\ProductService;

class ProductController extends Controller
{
    public function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        try {
            $service = new ProductService();
            $products = $service->getAll($offset, $limit);
            $this->respond($products);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $service = new ProductService();
            $product = $service->getOne($id);

            if (!$product) {
                $this->respondWithError(404, "Product not found");
                return;
            }

            $this->respond($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function create()
    {
        $this->requireRole('admin');
        try {
            $service = new ProductService();
            $product = $this->createObjectFromPostedJson("Models\\Product");

            if ($product->price < 0 || $product->stock < 0) {
                $this->respondWithError(400, "Price and stock cannot be negative.");
                return;
            }

            $product = $service->insert($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($product);
    }

    public function update($id)
    {
        $this->requireRole('admin');
        try {
            $service = new ProductService();
            $product = $this->createObjectFromPostedJson("Models\\Product");

            if ($product->price < 0 || $product->stock < 0) {
                $this->respondWithError(400, "Price and stock cannot be negative.");
                return;
            }

            $product = $service->update($product, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
            return;
        }

        $this->respond($product);
    }

    public function delete($id)
    {
        $this->requireRole('admin');
        try {
            $service = new ProductService();
            $service->delete($id);
            $this->respond(true);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
